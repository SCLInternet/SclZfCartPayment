<?php

namespace SclZfCartPayment\Fetcher;

use Zend\ServiceManager\ServiceLocatorInterface;

use SclZfCartPayment\Exception\InvalidArgumentException;
use SclZfCartPayment\PaymentMethodInterface;

/**
 * Loads the services from the given config.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class ConfigFetcher extends AbstractMethodFetcher
{
    const CONFIG_ROOT     = 'scl_zf_cart_payment';
    const PAYMENT_METHODS = 'payment_methods';

    /**
     * The config for the payment module
     *
     * @var array
     */
    private $config;

    /**
     * Cache the methods to avoid fetching them multiple times.
     *
     * @var array
     */
    private $methods = null;

    /**
     * {@inheritDoc}
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        parent::setServiceLocator($serviceLocator);

        $config = $serviceLocator->get('Config');
        $this->config = $config[self::CONFIG_ROOT];
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function listMethods()
    {
        if (null !== $this->methods) {
            return $this->methods;
        }

        $this->methods = array();

        foreach ($this->config[self::PAYMENT_METHODS] as $name => $methodName) {
            /* @var $method PaymentMethodInterface */
            $method = $this->getServiceLocator()->get($methodName);
            $this->methods[$name] = $method->name();
        }

        return $this->methods;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $methodName
     * @return PaymentMethodInterface
     */
    public function getMethod($methodName)
    {
        $methodName = $this->config[self::PAYMENT_METHODS][$methodName];
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
