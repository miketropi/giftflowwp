<?php return array(
    'root' => array(
        'name' => 'giftflowwp/giftflowwp',
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'reference' => null,
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'giftflowwp/giftflowwp' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'reference' => null,
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'paypal/paypal-checkout-sdk' => array(
            'pretty_version' => '1.0.2',
            'version' => '1.0.2.0',
            'reference' => '19992ce7051ff9e47e643f28abb8cc1b3e5f1812',
            'type' => 'library',
            'install_path' => __DIR__ . '/../paypal/paypal-checkout-sdk',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'paypal/paypalhttp' => array(
            'pretty_version' => '1.0.1',
            'version' => '1.0.1.0',
            'reference' => '7b09c89c80828e842c79230e7f156b61fbb68d25',
            'type' => 'library',
            'install_path' => __DIR__ . '/../paypal/paypalhttp',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'stripe/stripe-php' => array(
            'pretty_version' => 'v17.1.0',
            'version' => '17.1.0.0',
            'reference' => 'b4ce1fdd4ec607e4e8906419c346846f19363f10',
            'type' => 'library',
            'install_path' => __DIR__ . '/../stripe/stripe-php',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
