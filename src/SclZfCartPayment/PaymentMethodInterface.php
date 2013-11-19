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
     * Returns the transaction ID to be used for the payment.
     *
     * @return string
     */
    public function generateTransactionId();

    /**
     * The payment method may update the confirm form.
     *
     * @param  Form             $form
     * @param  OrderInteface    $order
     * @param  PaymentInterface $payment
     * @return void
     */
    public function updateCompleteForm(
        Form $form,
        OrderInterface $order,
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
