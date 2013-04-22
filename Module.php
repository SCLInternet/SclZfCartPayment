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
        /* @var $cart \SclZfCart\Cart */
        $cart = $serviceLocator->get('SclZfCart\Cart');
        $eventManager = $cart->getEventManager();

        $eventManager->attach(
            CartEvent::EVENT_CHECKOUT,
            function (CartEvent $event) use ($serviceLocator) {
                return \SclZfCartPayment\Listener\CartListener::checkout($event, $serviceLocator);
            },
            self::CHECKOUT_EVENT_PRIORITY
        );

        $eventManager->attach(
            CartEvent::EVENT_PROCESS,
            function (CartEvent $event) use ($serviceLocator) {
                return \SclZfCartPayment\Listener\CartListener::process($event, $serviceLocator);
            },
            self::PROCESS_EVENT_PRIORITY
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
            'aliases' => array(
                'SclZfCartPayment\Method\MethodFetcherInterface' => 'SclZfCartPayment\Method\ConfigFetcher',
                'SclZfCartPayment\Method\MethodLoaderInterface'  => 'SclZfCartPayment\Method\MethodLoader',
                'SclZfCartPayment\Method\MethodSelectorInterface'=> 'SclZfCartPayment\Method\MethodSelector',
                'SclZfCartPayment\Session'                       => 'SclZfCart\Session',
                'SclZfCartPayment\Mapper\PaymentMapperInterface' => 'SclZfCartPayment\Mapper\DoctrinePaymentMapper',
            ),
            'invokables' => array(
                'SclZfCartPayment\Form\PaymentMethods' => 'SclZfCartPayment\Form\PaymentMethods',
                'SclZfCartPayment\Method\MethodLoader' => 'SclZfCartPayment\Method\MethodLoader',
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
