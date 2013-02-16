<?php

namespace SclZfCartPayment\Method;

use SclZfCartPayment\PaymentMethodInterface;
use Zend\Session\Container;

/**
 * Loads the services from the given config.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class ConfigFetcher implements MethodFetcherInterface
{
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
     * 
     * @var MethodLoaderInterface
     */
    private $loader;

    /**
     * @param MethodLoaderInterface $loader
     * @param array                 $config
     */
    public function __construct(MethodLoaderInterface $loader, array $config)
    {
        $this->loader = $loader;
        $this->config = $config;
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
            $method = $this->loader->getMethod($methodName);
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

        return $this->loader->getMethod($methodName);
    }
}
