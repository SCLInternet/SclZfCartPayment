<?php
namespace SclZfCartPayment\Listener;

use SclZfCart\CartEvent;

class Cart
{
    /**
     * Inserts the select payment page into the checkout process.
     *
     * @param CartEvent $event
     */
    public static function checkout(CartEvent $event)
    {
        /* @var $cart \SclZfCart\Cart */
        $cart = $event->getTarget();
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
