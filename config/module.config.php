<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'SclZfCartPayment\Controller\Payment' => 'SclZfCartPayment\Controller\PaymentController',
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
                                'controller' => 'SclZfCartPayment\Controller\Payment',
                                'action'     => 'selectPayment',
                            ),
                        ),
                    ),
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
            'SclZfCartPayment\Controller' => __DIR__ . '/../view',
        ),
    ),
);
