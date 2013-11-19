<?php
namespace SclZfCartPayment\Listener;

use SclZfCartPayment\Entity\PaymentInterface;
use SclZfCartPayment\Exception\RuntimeException;
use SclZfCartPayment\Mapper\PaymentMapperInterface;
use SclZfCartPayment\Method\MethodSelectorInterface;
use SclZfCartPayment\PaymentMethodInterface;
use SclZfCart\CartEvent;
use SclZfCart\Entity\OrderInterface;
use SclZfUtilities\Model\Route;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\Form\Form;

/**
 * Provides the methods which are attach to the cart's event manager.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class CartListener implements SharedListenerAggregateInterface
{
    const CHECKOUT_PRIORITY = 100;
    const PROCESS_PRIORITY  = 100;

    private $listeners = array();

    private $methodSelector;

    private $paymentMapper;

    public function __construct(
        MethodSelectorInterface $methodSelector,
        PaymentMapperInterface $paymentMapper
    ) {
        $this->methodSelector = $methodSelector;
        $this->paymentMapper  = $paymentMapper;
    }

    /**
     * Attach the listener functions to the event manager.
     *
     * @param  SharedEventManagerInterface $events
     *
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
            throw RuntimeException::invalidObjectType(
                '\SclZfCart\Entity\OrderInterface',
                $order
            );
        }

        $method = $this->getMethodSelector()->getSelectedMethod();

        if (!$method instanceof PaymentMethodInterface) {
            throw RuntimeException::invalidObjectType(
                '\SclZfCartPayment\PaymentMethodInterface',
                $method
            );
        }

        $event->stopPropagation(true);

        $payment = $this->paymentMapper->create();

        $payment->setTransactionId($method->generateTransactionId());

        $payment->setDate(new \DateTime());
        $payment->setOrder($order);
        $payment->setStatus(PaymentInterface::STATUS_PENDING);
        $payment->setType(get_class($method));
        $payment->setAmount($order->getTotal());

        $this->paymentMapper->save($payment);

        $form = $this->createRedirectForm();

        $method->updateCompleteForm($form, $order, $payment);

        return $form;
    }

    /**
     * @return MethodSelectorInterface
     */
    protected function getMethodSelector()
    {
        return $this->methodSelector;
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
}
