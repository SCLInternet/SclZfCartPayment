<?php

namespace SclZfCartPayment;

return array(
    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__ . '\Controller\Payment' => __NAMESPACE__ . '\Controller\PaymentController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'payment' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/payment',
                ),
                'child_routes' => array(
                    'select-payment' => array(
                        'type'   => 'literal',
                        'options' => array(
                            'route'    => '/select-payment',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\Payment',
                                'action'     => 'selectPayment',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    // @todo Move to .dist config file
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/xml/doctrine-entities'
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ => __NAMESPACE__ . '_driver',
                ),
            ),
        ),
    ),

    'scl_zf_cart_payment' => array(
        // Payment method modules add themselves to this array
        'payment_methods' => array(),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __NAMESPACE__ . '\Controller' => __DIR__ . '/../view',
        ),
    ),
);
