<?php

namespace SclZfCartPayment\Method;

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
     * Gets the session storage container
     *
     * @return \Zend\Session\Container
     */
    protected function getSession()
    {
        return $this->session;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $methodName
     * @todo Throw proper exception class
     */
    public function setSelectedMethod($methodName)
    {
        $methods = $this->fetcher->listMethods();

        if (!isset($methods[$methodName])) {
            throw new \Exception("Invalid payment method name '{$methodName}' given.");
        }

        $session = $this->getSession();
        $session->paymentMethod = $methodName;
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

        $session = $this->getSession();

        if ($numMethods == 1) {
            $session->paymentMethod = key($methods);
        }

        if (!$session->paymentMethod) {
            return self::NO_METHOD_SELECTED;
        }

        return $this->fetcher->getMethod($session->paymentMethod);
    }
}
