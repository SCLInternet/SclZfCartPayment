<?php
namespace SclZfCartPayment\Listener;

use SclZfCart\CartEvent;
use SclZfCart\Utility\Route;

/**
 * Provides the methods which are attach to the cart's event manager.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Cart
{
    /**
     * Inserts the select payment page into the checkout process.
     *
     * @todo Implement commented out bits
     * @param CartEvent $event
     */
    public static function checkout(CartEvent $event)
    {
        /* @var $cart \SclZfCart\Cart */
        $cart = $event->getTarget();

        /*
        if (0 == $cart->getTotal()) {
            return null;
        }
        */

        /*
        if (singlePaymentMethod) {
            setPaymentMethod();
            return null;
        }
         */

        $event->stopPropagation(true);

        return new Route('payment/select-payment');
    }

    /**
     * Adjusts the complete checkout button to redirect to the payment page.
     *
     * @param CartEvent $event
     */
    public static function completeForm(CartEvent $event)
    {
        /* @var $form \Zend\Form\Form */
        $form = $event->getTarget();

        $form->setAttribute('action', 'Paypointurl');
        $form->add(
            array(
                'name' => 'amount',
                'type' => 'Zend\Form\Element\Hidden',
                'attributes' => array(
                    'value' => '10.50'
                )
            )
        );

        $form->get('complete')->setValue('Confirm & Pay');
    }
}
