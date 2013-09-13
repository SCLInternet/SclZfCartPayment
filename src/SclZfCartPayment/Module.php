<?php

namespace SclZfCartPayment;

use SclZfCart\CartEvent;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 * This module contains a payment module for SclZfCart.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Module implements
    BootstrapListenerInterface,
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    const CHECKOUT_EVENT_PRIORITY = 100;
    const PROCESS_EVENT_PRIORITY  = 100;

    /**
     * {@inheritDoc}
     *
     * @param \Zend\EventManager\EventInterface $e
     * @todo Maybe use the shared event manager instead.
     */
    public function onBootstrap(EventInterface $e)
    {
        $serviceLocator = $e->getApplication()->getServiceManager();

        $eventManager = $serviceLocator->get('SharedEventManager');

        $cartListener = $serviceLocator->get('SclZfCartPayment\Listener\CartListener');

        $eventManager->attachAggregate($cartListener);
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                'SclZfCartPayment\Method\MethodFetcherInterface' => 'SclZfCartPayment\Method\ConfigFetcher',
                'SclZfCartPayment\Method\MethodLoaderInterface'  => 'SclZfCartPayment\Method\MethodLoader',
                'SclZfCartPayment\Method\MethodSelectorInterface'=> 'SclZfCartPayment\Method\MethodSelector',
                'SclZfCartPayment\Session'                       => 'SclZfCart\Session',
                'SclZfCartPayment\Mapper\PaymentMapperInterface' => 'SclZfCartPayment\Mapper\DoctrinePaymentMapper',
            ),
            'invokables' => array(
                'SclZfCartPayment\Form\PaymentMethods'   => 'SclZfCartPayment\Form\PaymentMethods',
                'SclZfCartPayment\Method\MethodLoader'   => 'SclZfCartPayment\Method\MethodLoader',
                'SclZfCartPayment\Listener\CartListener' => 'SclZfCartPayment\Listener\CartListener',
            ),
            'factories' => array(
                'SclZfCartPayment\Method\ConfigFetcher'  => 'SclZfCartPayment\Service\ConfigFetcherFactory',
                'SclZfCartPayment\Method\MethodSelector' => 'SclZfCartPayment\Service\MethodSelectorFactory',
                'SclZfCartPayment\Mapper\DoctrinePaymentMapper' => function ($sm) {
                    return new \SclZfCartPayment\Mapper\DoctrinePaymentMapper(
                        $sm->get('doctrine.entitymanager.orm_default'),
                        $sm->get('SclZfUtilities\Doctrine\FlushLock')
                    );
                },
            ),
        );
    }
}
