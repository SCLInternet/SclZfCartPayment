<?php
namespace SclZfCartPayment\Listener;

use SclZfCart\CartEvent;
use SclZfCart\Entity\OrderInterface;
use SclZfCartPayment\Entity\PaymentInterface;
use SclZfCartPayment\Exception\RuntimeException;
use SclZfCartPayment\Method\MethodSelectorInterface;
use SclZfCartPayment\PaymentMethodInterface;
use SclZfUtilities\Model\Route;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;

/**
 * Provides the methods which are attach to the cart's event manager.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class CartListener implements
    SharedListenerAggregateInterface,
    ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    const CHECKOUT_PRIORITY = 100;
    const PROCESS_PRIORITY  = 100;

    protected $listeners = array();

    /**
     * Attach the listener functions to the event manager.
     *
     * @param  SharedEventManagerInterface $events
     * @return void
     */
    public function attachShared(SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            'SclZfCart\Cart',
            CartEvent::EVENT_CHECKOUT,
            array($this, 'checkout'),
            self::CHECKOUT_PRIORITY
        );

        $this->listeners[] = $events->attach(
            'SclZfCart\Cart',
            CartEvent::EVENT_PROCESS,
            array($this, 'process'),
            self::PROCESS_PRIORITY
        );
    }

    /**
     * Detach all listener.
     *
     * @param  SharedEventManagerInterface $events
     *
     * @return void
     */
    public function detachShared(SharedEventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Reset the selected payment method
     *
     * @return void
     */
    /*
    public static function clear(CartEvent $event)
    {
        $this->getMethodSelector()->reset();
    }
    */

    /**
     * Inserts the select payment page into the checkout process.
     *
     * @todo Implement commented out bits
     * @param  CartEvent               $event
     * @return Route|null
     */
    public function checkout(CartEvent $event)
    {
        /*
        $cart = $event->getCart();

        if (0 == $cart->getTotal()) {
            return null;
        }
        */

        $method = $this->getMethodSelector()->getSelectedMethod();

        if (MethodSelectorInterface::NO_METHOD_SELECTED === $method) {
            $event->stopPropagation(true);
            return new Route('payment/select-payment');
        }

        return null;
    }

    /**
     * Creates a form to redirect to the payment page.
     *
     * @return Form
     */
    protected function createRedirectForm()
    {
        $form = new Form('payment-form');

        $form->add(
            array(
                'name' => 'complete',
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'id' => 'complete',
                    'value' => 'Proceed',
                ),
            )
        );

        return $form;
    }

    /**
     * Adjusts the complete checkout button to redirect to the payment page.
     *
     * @param  CartEvent               $event
     * @return Form
     * @throws RuntimeException        When the event target isn't an instanceof OrderInterface
     * @throws RuntimeException        When the a payment method is not selected
     */
    public function process(CartEvent $event)
    {
        /* @var $order \SclZfCart\Entity\OrderInterface */
        $order = $event->getTarget();

        if (!$order instanceof OrderInterface) {
            throw new RuntimeException(
                sprintf(
                    'Instance of %sexpected; got "%s" in %s on line %d.',
                    '\SclZfCart\Entity\OrderInterface',
                    is_object($order) ? get_class($order) : gettype($order),
                    __FILE__,
                    __LINE__
                )
            );
        }

        $method = $this->getMethodSelector()->getSelectedMethod();

        if (!$method instanceof PaymentMethodInterface) {
            throw new RuntimeException(
                sprintf(
                    'Instance of %sexpected; got "%s" in %s on line %d.',
                    '\SclZfCartPayment\PaymentMethodInterface',
                    is_object($order) ? get_class($order) : gettype($order),
                    __FILE__,
                    __LINE__
                )
            );
        }

        $event->stopPropagation(true);

        $mapper = $this->serviceLocator->get('SclZfCartPayment\Mapper\PaymentMapperInterface');

        $payment = $mapper->create();
        $payment->setDate(new \DateTime());
        $payment->setOrder($order);
        $payment->setStatus(PaymentInterface::STATUS_PENDING);
        $payment->setType(get_class($method));
        // @todo FIXME
        $payment->setAmount(0);

        $mapper->save($payment);

        $form = $this->createRedirectForm();

        $method->updateCompleteForm($form, $order, $payment);

        return $form;
    }

    /**
     * @return MethodSelectorInterface
     */
    protected function getMethodSelector()
    {
        return $this->serviceLocator->get('SclZfCartPayment\Method\MethodSelectorInterface');
    }
}
