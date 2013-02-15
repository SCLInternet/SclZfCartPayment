<?php
namespace SclZfCartPayment\Service;

use SclZfCartPayment\Fetcher\ConfigFetcher;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for creating {@see ConfigFetcher} objects.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class ConfigFetcherFactory implements FactoryInterface
{
    const CONFIG_KEY = 'scl_zf_cart_payment';

    /**
     * Create an instance of {@see ConfigFetcher}.
     *
     * @param ServiceLocatorInterface
     * @return ConfigFetcher
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $session = $serviceLocator->get('SclZfCartPayment\Session');

        $config = $serviceLocator->get('Config');

        $config = $config[self::CONFIG_KEY];

        return new ConfigFetcher($session, $config);
    }
}
