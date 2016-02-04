<?php

Router::connect('/User/subscriptions',
	array('admin' => false, 'plugin' => 'billing', 'controller' => 'billing_user', 'action' => 'subscriptions')
);

/*
Router::connect('/User/balance/payment',
	array('admin' => false, 'plugin' => 'billing', 'controller' => 'billing_users', 'action' => 'payment')
);

Router::connect('/User/balance/checkout',
	array('admin' => false, 'plugin' => 'billing', 'controller' => 'billing_users', 'action' => 'checkout')
);
*/

Router::connect('/subscribe/plans/:group',
	array('admin' => false, 'plugin' => 'billing', 'controller' => 'billing_subscriptions', 'action' => 'plans'),
	array('pass' => array('group'), 'group' => '[a-z0-9\-]+')
);

Router::connect('/subscribe/payment',
	array('admin' => false, 'plugin' => 'billing', 'controller' => 'billing_subscriptions', 'action' => 'payment')
);

Router::connect('/subscribe/add_payment',
	array('admin' => false, 'plugin' => 'billing', 'controller' => 'billing_subscriptions', 'action' => 'addPayment')
);

Router::connect('/subscribe/checkout',
	array('admin' => false, 'plugin' => 'billing', 'controller' => 'billing_subscriptions', 'action' => 'checkout')
);

Router::connect('/subscribe/success/:id',
	array('admin' => false, 'plugin' => 'billing', 'controller' => 'billing_subscriptions', 'action' => 'success'),
	array('pass' => array('id'), 'id' => '[0-9]+')
);

Router::connect('/billing/braintree/subscription/callback',
	array('admin' => false, 'plugin' => 'billing', 'controller' => 'billing_callback', 'action' => 'processSubscription')
);
