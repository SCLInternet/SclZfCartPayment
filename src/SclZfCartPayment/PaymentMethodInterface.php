<?php
namespace SclZfCartPayment;

use SclZfCart\Entity\Order;
use SclZfCartPayment\Entity\Payment;
use Zend\Form\Form;

/**
 * The interface that a payment method must implement to integrate with this
 * payment module.
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
     */
    public function updateCompleteForm(
        Form $form,
        Order $order,
        Payment $payment
    );

    /**
     * Takes the values returned from the payment method and completes the payment.
     *
     * @param array $data
     *
     * @return boolean Return true if the payment was successful
     */
    public function complete(array $data);
}
