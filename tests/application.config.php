<?php

return array(
    'modules' => array(
        'ZfcUser',
        'DoctrineModule',
        'DoctrineORMModule',
        'SCL\ZF2\Currency',
        'SclZfUtilities',
        'SclZfGenericMapper',
        'SclZfCart',
        'SclZfCartPayment',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            __DIR__ . '/config.php',
        ),
        'module_paths' => array(
            __DIR__ . '/../..',
            __DIR__ . '/../vendor',
        ),
    ),
);
