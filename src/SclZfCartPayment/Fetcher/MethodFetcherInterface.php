<?php

namespace SclZfCartPayment\Fetcher;

use SclZfCartPayment\PaymentMethodInterface;

/**
 * Interface for class which find which payment service are available.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface MethodFetcherInterface
{
    const NO_METHODS_AVAILABLE = -1;
    const NO_METHOD_SELECTED   = -2;

    /**
     * Returns a list of payment methods that are available.
     *
     * @return array
     */
    public function listMethods();

    /**
     * Fetches an instance of a payment method by name.
     *
     * @param string $methodName
     * @return PaymentMethodInterface
     */
    public function getMethod($methodName);

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
