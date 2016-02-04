<?php
Router::parseExtensions('html', 'json');
Router::connect('/', array('controller' => 'User', 'action' => 'login'));
Router::connect( '/user_:id', array('controller' => 'User', 'action' => 'view'), array('pass' => array('id'), 'id' => '[0-9A-Za-z]+'));
Router::connect( '/group_:id', array('controller' => 'Group', 'action' => 'view'), array('pass' => array('id'), 'id' => '[0-9A-Za-z]+'));
Router::connect('/Mytime', array('controller' => 'Timeline', 'action' => 'index'));
Router::connect('/Planet', array('controller' => 'Timeline', 'action' => 'planet'));
//Router::connect('/apiv1/:action', array('plugin'=>'Api','controller' => 'Apiv1', ));
Router::connect('/apiv1/*', array('plugin'=>'Api','controller' => 'Apiv1','action'=>'index' ));

Router::connect('/admin', array('admin' => true, 'controller' => 'Dashboard', 'action' => 'index'));
Router::connect('/admin/login', array('admin' => true, 'controller' => 'user', 'action' => 'login'));
Router::connect('/admin/logout', array('admin' => true, 'controller' => 'user', 'action' => 'logout'));
//Router::connect('/admin', array('admin' => true, 'controller' => 'dashboard', 'action' => 'index'));
Router::connect('/apiv2/*', array('plugin'=>'Apiv2','controller' => 'Apiv2','action'=>'index' ));
CakePlugin::routes();

require CAKE.'Config'.DS.'routes.php';
