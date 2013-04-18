<?php
namespace SclZfCartPayment\Service;

use SclZfCartPayment\Method\MethodSelector;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for creating {@see MethodSelector} objects.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class MethodSelectorFactory implements FactoryInterface
{
    const CONFIG_KEY = 'scl_zf_cart_payment';

    /**
     * Create an instance of {@see MethodSelector}.
     *
     * @param ServiceLocatorInterface
     * @return MethodSelector
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $fetcher = $serviceLocator->get('SclZfCartPayment\Method\MethodFetcherInterface');

        $session = $serviceLocator->get('SclZfCartPayment\Session');

        return new MethodSelector($session, $fetcher);
    }
}
