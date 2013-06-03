<?php
namespace SclZfCartPayment\Listener;

use SclZfCart\CartEvent;
use SclZfUtilities\Model\Route;
use SclZfCartPayment\Method\MethodSelectorInterface;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use SclZfCartPayment\Entity\PaymentInterface;

/**
 * Provides the methods which are attach to the cart's event manager.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class CartListener
{
    /**
     * @param  ServiceLocatorInterface $serviceLocator
     * @return MethodSelectorInterface
     */
    protected static function getMethodSelector(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('SclZfCartPayment\Method\MethodSelectorInterface');
    }

    /**
     * Reset the selected payment method
     *
     * @return void
     */
    /*
    public static function clear(CartEvent $event, ServiceLocatorInterface $serviceLocator)
    {
        self::getMethodSelector($serviceLocator)->reset();
    }
    */

    /**
     * Inserts the select payment page into the checkout process.
     *
     * @todo Implement commented out bits
     * @param  CartEvent               $event
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Route|null
     */
    public static function checkout(CartEvent $event, ServiceLocatorInterface $serviceLocator)
    {
        /*
        $cart = $event->getCart();

        if (0 == $cart->getTotal()) {
            return null;
        }
        */

        $method = self::getMethodSelector($serviceLocator)->getSelectedMethod();

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
    protected static function createRedirectForm()
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
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Form
     */
    public static function process(CartEvent $event, ServiceLocatorInterface $serviceLocator)
    {
        /* @var $order \SclZfCart\Entity\OrderInterface */
        $order = $event->getTarget();

        $method = self::getMethodSelector($serviceLocator)->getSelectedMethod();

        /*
        if (!$method instanceof PaymentMethodInterface) {
            // BLAH
        }
        */

        $event->stopPropagation(true);

        $mapper = $serviceLocator->get('SclZfCartPayment\Mapper\PaymentMapperInterface');
        $payment = $mapper->create();
        $payment->setDate(new \DateTime());
        $payment->setOrder($order);
        $payment->setStatus(PaymentInterface::STATUS_PENDING);
        $payment->setType(get_class($method));
        // @todo FIXME
        $payment->setAmount(0);

        $mapper->save($payment);

        $form = self::createRedirectForm();

        $method->updateCompleteForm($form, $order, $payment);

        return $form;
    }
}
