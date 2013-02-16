<?php

namespace SclZfCartPayment\Method;

use SclZfCartPayment\PaymentMethodInterface;

/**
 * Interface for class which stores the selected method.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface MethodSelectorInterface
{
    const NO_METHODS_AVAILABLE = -1;
    const NO_METHOD_SELECTED   = -2;

    /**
     * Saves the selected payment method
     *
     * @param string $methodName
     */
    public function setSelectedMethod($methodName);

    /**
     * Returns the selected payment method.
     *
     * @return PaymentMethodInterface
     */
    public function getSelectedMethod();
}
