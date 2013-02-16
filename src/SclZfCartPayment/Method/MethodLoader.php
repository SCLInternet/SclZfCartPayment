<?php

namespace SclZfCartPayment\Method;

use SclZfCartPayment\Exception\InvalidArgumentException;
use SclZfCartPayment\PaymentMethodInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * This class loads up a method from the service locator.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class MethodLoader implements
    MethodLoaderInterface,
    ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

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
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Returns a instance of a payment method object by name.
     * 
     * @param string $methodName
     * @return PaymentMethodInterface
     * @throws InvalidArgumentException
     */
    public function getMethod($methodName)
    {
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
}
