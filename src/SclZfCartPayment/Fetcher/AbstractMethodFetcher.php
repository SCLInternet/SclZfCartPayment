<?php

namespace SclZfCartPayment\Fetcher;

use SclZfCartPayment\Exception\InvalidArgumentException;
use SclZfCartPayment\PaymentMethodInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Session\Container;

/**
 * A convenient abstract class to extend when creating new payment methods.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
abstract class AbstractMethodFetcher implements
     MethodFetcherInterface,
     ServiceLocatorAwareInterface
{
    /**
     * The service locator
     *
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * The session container
     * @var Container
     */
    private $session;

    /**
     * @param Container $session
     */
    public function __construct(Container $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * {@inheritDoc}
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Gets the session storage container
     *
     * @return \Zend\Session\Container
     */
    protected function getSession()
    {
        return $this->getServiceLocator()->get('SclZfCartPayment\Session');
    }

    /**
     * Returns a instance of a payment method object by name.
     * 
     * @param string $methodName
     * @return PaymentMethodInterface
     * @throws InvalidArgumentException
     */
    protected function getMethodObject($methodName) {
        $method = $this->getServiceLocator()->get($methodName);

        if (!$method instanceof PaymentMethodInterface) {
            throw new InvalidArgumentException(
                'SclZfCartPayment\PaymentMethodInterface',
                $method,
                __METHOD__,
                __LINE__
            );
        }

        return $method;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $methodName
     */
    public function setSelectedMethod($methodName)
    {
        $methods = $this->listMethods();

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
        $methods = $this->listMethods();
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

        return $this->getMethod($session->paymentMethod);
    }
}
