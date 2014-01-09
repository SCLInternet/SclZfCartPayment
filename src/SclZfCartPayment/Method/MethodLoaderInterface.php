<?php

namespace SclZfCartPayment\Method;

use SclZfCartPayment\Exception\InvalidArgumentException;
use SclZfCartPayment\PaymentMethodInterface;

/**
 * Interface for a class which loads a method by name.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface MethodLoaderInterface
{
    /**
     * Returns a instance of a payment method object by name.
     *
     * @param string $methodName
     * @return PaymentMethodInterface
     * @throws InvalidArgumentException
     */
    public function getMethod($methodName);
}
