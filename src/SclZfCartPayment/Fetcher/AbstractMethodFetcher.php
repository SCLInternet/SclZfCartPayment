<?php

namespace SclZfCartPayment\Fetcher;

use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\ServiceManager\ServiceLocatorAwareInterface;

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
