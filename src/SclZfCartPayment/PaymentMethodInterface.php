<?php
namespace SclZfCartPayment;

use SclZfCart\Entity\OrderInterface;
use SclZfCartPayment\Entity\PaymentInterface;
use Zend\Form\Form;

/**
 * The interface that a payment method must implement to integrate with this
 * payment module.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface PaymentMethodInterface
{
    /**
     * The name of the payment method.
     *
     * @return string
     */
    public function name();

    /**
     * The payment method may update the confirm form.
     *
     * @param  Form             $form
     * @param  OrderInteface    $cart
     * @param  PaymentInterface $cart
     * @return void
     */
    public function updateCompleteForm(
        Form $form,
        OrderInterface $cart,
        PaymentInterface $payment
    );

    /**
     * Takes the values returned from the payment method and completes the payment.
     *
     * @param  array $data
     * @return boolean Return true if the payment was successful
     */
    public function complete(array $data);
}
