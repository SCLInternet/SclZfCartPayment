<?php

namespace SclZfCartPayment\Method;

use SclZfCartPayment\Exception\NonExistentMethodException;
use SclZfCartPayment\PaymentMethodInterface;
use Zend\Session\Container;

/**
 * A convenient abstract class to extend when creating new payment methods.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class MethodSelector implements MethodSelectorInterface
{
    /**
     * The method list fetcher
     *
     * @var MethodFetcherInterface
     */
    private $fetcher;

    /**
     * The session container
     * @var Container
     */
    private $session;

    /**
     * @param Container $session
     */
    public function __construct(Container $session, MethodFetcherInterface $fetcher)
    {
        $this->session = $session;
        $this->fetcher = $fetcher;
    }

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function reset()
    {
        $this->session->paymentMethod = null;
    }

    /**
     * {@inheritDoc}
     *
     * @param  string $methodName
     * @return void
     * @todo Throw proper exception class
     */
    public function setSelectedMethod($methodName)
    {
        $methods = $this->fetcher->listMethods();

        if (!isset($methods[$methodName])) {
            throw new NonExistentMethodException("Invalid payment method name '{$methodName}' given.");
        }

        $this->session->paymentMethod = $methodName;
    }

    /**
     * {@inheritDoc}
     *
     * @return PaymentMethodInterface
     */
    public function getSelectedMethod()
    {
        $methods = $this->fetcher->listMethods();

        $numMethods = count($methods);

        if ($numMethods < 1) {
            return self::NO_METHODS_AVAILABLE;
        }

        if ($numMethods == 1) {
            $this->session->paymentMethod = key($methods);
        }

        if (!$this->session->paymentMethod) {
            return self::NO_METHOD_SELECTED;
        }

        return $this->fetcher->getMethod($this->session->paymentMethod);
    }
}
