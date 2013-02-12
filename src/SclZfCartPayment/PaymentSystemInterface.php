<?php
namespace SclZfCartPayment;

use Zend\Form\Form;

/**
 * The interface that a payment system must implement to integrate with this
 * payment module.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface PaymentSystemInterface
{
    /**
     * The name of the payment system.
     *
     * @return string
     */
    public function name();

    /**
     * The payment system may update the confirm form.
     *
     * @param Form $form
     */
    public function updateCompleteForm(Form $form);

    /**
     * Takes the values returned from the payment system and completes the payment.
     *
     * @param array $data
     * @return boolean Return true if the payment was successful
     */
    public function complete(array $data);
}
