<?php

PhangoVar::$urls['shop']['viewproduct']=array('pattern' => '/^shop\/viewproduct\/([0-9]+)\/(.*)$/', 'url' => '/shop/viewproduct', 'module' => 'shop', 'controller' => 'viewproduct', 'action' => 'index', 'parameters' => array('$1' => 'integer', '$2' => 'string'));

PhangoVar::$urls['shop']['viewcategory']=array('pattern' => '/^shop\/viewcategory\/([0-9]+)\/(.*)$/', 'url' => '/shop/viewcategory', 'module' => 'shop', 'controller' => 'viewcategory', 'action' => 'index', 'parameters' => array('$1' => 'integer', '$2' => 'string'));

PhangoVar::$urls['shop']['viewcategory_id']=array('pattern' => '/^shop\/viewcategory\/([0-9]+)$/', 'url' => '/shop/viewcategory', 'module' => 'shop', 'controller' => 'viewcategory', 'action' => 'index', 'parameters' => array('$1' => 'integer'));

PhangoVar::$urls['shop']['cart_ajax_jscript']=array('pattern' => '/^shop\/cart\/ajax\/cart_ajax_jscript/', 'url' => '/shop/cart/ajax/cart_ajax_jscript', 'module' => 'shop', 'controller' => 'ajax/fjscript', 'action' => 'index', 'parameters' => array());

PhangoVar::$urls['shop']['cart_ajax_buy']=array('pattern' => '/^shop\/cart\/ajax\/buy/', 'url' => '/shop/cart/ajax/buy', 'module' => 'shop', 'controller' => 'ajax/buy', 'action' => 'index', 'parameters' => array());

PhangoVar::$urls['shop']['cart_checkoptionsproduct']=array('pattern' => '/^shop\/cart\/ajax\/checkoptionsproduct/', 'url' => '/shop/cart/ajax/checkoptionsproduct', 'module' => 'shop', 'controller' => 'ajax/checkoptionsproduct', 'action' => 'index', 'parameters' => array());

PhangoVar::$urls['shop']['cart_ajax_obtaincart']=array('pattern' => '/^shop\/cart\/ajax\/obtain_cart\/(.*)$/', 'url' => '/shop/cart/ajax/obtain_cart', 'module' => 'shop', 'controller' => 'ajax/obtaincart', 'action' => 'index', 'parameters' => array('$1' => 'string'));

PhangoVar::$urls['shop']['cart_update']=array('pattern' => '/^shop\/cart\/update/', 'url' => '/shop/cart/update', 'module' => 'shop', 'controller' => 'cart', 'action' => 'update', 'parameters' => array());

PhangoVar::$urls['shop']['cart_delete']=array('pattern' => '/^shop\/cart\/delete/', 'url' => '/shop/cart/delete', 'module' => 'shop', 'controller' => 'cart', 'action' => 'delete', 'parameters' => array());

PhangoVar::$urls['shop']['cart_get_address']=array('pattern' => '/^shop\/cart\/get_address/', 'url' => '/shop/cart/get_address', 'module' => 'shop', 'controller' => 'cart', 'action' => 'get_address', 'parameters' => array());

PhangoVar::$urls['shop']['cart_save_address']=array('pattern' => '/^shop\/cart\/save_address/', 'url' => '/shop/cart/save_address', 'module' => 'shop', 'controller' => 'cart', 'action' => 'save_address', 'parameters' => array());

PhangoVar::$urls['shop']['cart_get_user_save']=array('pattern' => '/^shop\/cart\/get_user_save/', 'url' => '/shop/cart/get_user_save', 'module' => 'shop', 'controller' => 'cart', 'action' => 'get_user_save', 'parameters' => array());

PhangoVar::$urls['shop']['cart_set_transport']=array('pattern' => '/^shop\/cart\/set_transport/', 'url' => '/shop/cart/set_transport', 'module' => 'shop', 'controller' => 'cart', 'action' => 'set_transport', 'parameters' => array());

PhangoVar::$urls['shop']['cart_save_transport_address']=array('pattern' => '/^shop\/cart\/save_transport_address/', 'url' => '/shop/cart/save_transport_address', 'module' => 'shop', 'controller' => 'cart', 'action' => 'save_transport_address', 'parameters' => array());

PhangoVar::$urls['shop']['cart_save_choose_address_transport']=array('pattern' => '/^shop\/cart\/save_choose_address_transport/', 'url' => '/shop/cart/save_choose_address_transport', 'module' => 'shop', 'controller' => 'cart', 'action' => 'save_choose_address_transport', 'parameters' => array());

PhangoVar::$urls['shop']['cart_set_method_transport']=array('pattern' => '/^shop\/cart\/set_method_transport/', 'url' => '/shop/cart/set_method_transport', 'module' => 'shop', 'controller' => 'cart', 'action' => 'set_method_transport', 'parameters' => array());

PhangoVar::$urls['shop']['cart_save_choose_transport']=array('pattern' => '/^shop\/cart\/save_choose_transport/', 'url' => '/shop/cart/save_choose_transport', 'module' => 'shop', 'controller' => 'cart', 'action' => 'save_choose_transport', 'parameters' => array());

PhangoVar::$urls['shop']['cart_checkout']=array('pattern' => '/^shop\/cart\/checkout/', 'url' => '/shop/cart/checkout', 'module' => 'shop', 'controller' => 'cart', 'action' => 'checkout', 'parameters' => array());

PhangoVar::$urls['shop']['cart_finish_checkout']=array('pattern' => '/^shop\/cart\/finish_checkout/', 'url' => '/shop/cart/finish_checkout', 'module' => 'shop', 'controller' => 'cart', 'action' => 'finish_checkout', 'parameters' => array());

PhangoVar::$urls['shop']['cart_finished']=array('pattern' => '/^shop\/cart\/finished/', 'url' => '/shop/cart/finished', 'module' => 'shop', 'controller' => 'cart', 'action' => 'finished', 'parameters' => array());

PhangoVar::$urls['shop']['cart_login']=array('pattern' => '/^shop\/cart\/login/', 'url' => '/shop/cart/login', 'module' => 'shop', 'controller' => 'cart', 'action' => 'login', 'parameters' => array());

PhangoVar::$urls['shop']['cart_logout']=array('pattern' => '/^shop\/cart\/logout/', 'url' => '/shop/cart/logout', 'module' => 'shop', 'controller' => 'cart', 'action' => 'logout', 'parameters' => array());

PhangoVar::$urls['shop']['cart_cancel_order']=array('pattern' => '/^shop\/cart\/cancel/', 'url' => '/shop/cart/cancel', 'module' => 'shop', 'controller' => 'cart', 'action' => 'cancel_order', 'parameters' => array());

PhangoVar::$urls['shop']['cart_recovery_password']=array('pattern' => '/^shop\/cart\/recovery_password/', 'url' => '/shop/cart/recovery_password', 'module' => 'shop', 'controller' => 'cart', 'action' => 'recovery_password', 'parameters' => array());

PhangoVar::$urls['shop']['cart_recovery_password_send']=array('pattern' => '/^shop\/cart\/recovery_password_send/', 'url' => '/shop/cart/recovery_password_send', 'module' => 'shop', 'controller' => 'cart', 'action' => 'recovery_password_send', 'parameters' => array());

PhangoVar::$urls['shop']['cart']=array('pattern' => '/^shop\/cart/', 'url' => '/shop/cart', 'module' => 'shop', 'controller' => 'cart', 'action' => 'index', 'parameters' => array());



?>