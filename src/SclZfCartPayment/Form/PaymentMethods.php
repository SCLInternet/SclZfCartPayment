<?php

namespace SclZfCartPayment\Form;

use Zend\Form\Form;

/**
 * Form for selecting which payment method to use.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class PaymentMethods extends Form
{
    const ELEMENT_SUBMIT = 'submit';
    const ELEMENT_METHOD = 'method';

    /**
     * Sets up the options and adds the submit button.
     */
    public function __construct()
    {
        parent::__construct('payment-form');

        $this->setAttribute('method', 'post');

        $this->add(
            array(
                'name' => self::ELEMENT_SUBMIT,
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' => 'Continue',
                ),
            )
        );
    }

    /**
     * Adds the payment methods radio buttons to the form.
     *
     * @param array $methods
     */
    public function addMethods(array $methods)
    {
        $this->add(
            array(
                'name' => self::ELEMENT_METHOD,
                'type' => 'Zend\Form\Element\Radio',
                'options' => array(
                    'value_options' => $methods,
                ),
            )
        );
    }
}
