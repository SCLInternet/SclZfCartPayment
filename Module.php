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
    const CHECKOUT_EVENT_PRIORITY = 1000;

    /**
     * {@inheritDoc}
     *
     * @param \Zend\EventManager\EventInterface $e
     */
    public function onBootstrap(EventInterface $e)
    {
        $serviceLocator = $e->getApplication()->getServiceManager();
        /* @var $cart \SclZfCart\Cart */
        $cart = $serviceLocator->get('SclZfCart\Cart');
        $eventManager = $cart->getEventManager();

        $eventManager->attach(
            CartEvent::EVENT_CHECKOUT,
            array('SclZfCartPayment\Listener\Cart', 'checkout'),
            self::CHECKOUT_EVENT_PRIORITY
        );

        $eventManager->attach(
            CartEvent::EVENT_COMPLETE_FORM,
            array('SclZfCartPayment\Listener\Cart', 'completeForm')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceConfig()
    {
        return array(
        );
    }
}
