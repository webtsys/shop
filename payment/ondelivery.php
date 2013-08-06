<?php

$model['order_shop']->components['name']->required=0;	
$model['order_shop']->components['last_name']->required=0;
$model['order_shop']->components['email']->required=0;
$model['order_shop']->components['address']->required=0;
$model['order_shop']->components['zip_code']->required=0;
$model['order_shop']->components['city']->required=0;
$model['order_shop']->components['country']->required=0;
$model['order_shop']->components['phone']->required=0;

$model['order_shop']->components['name_transport']->required=0;	
$model['order_shop']->components['last_name_transport']->required=0;
$model['order_shop']->components['address_transport']->required=0;
$model['order_shop']->components['zip_code_transport']->required=0;
$model['order_shop']->components['city_transport']->required=0;
$model['order_shop']->components['country_transport']->required=0;
$model['order_shop']->components['phone_transport']->required=0;


$model['order_shop']->components['token']->required=0;
$model['order_shop']->components['transport']->required=0;
$model['order_shop']->components['payment_form']->required=0;

$model['order_shop']->reset_require();

$model['invoice_num']->insert(array('token_shop' => sha1($_COOKIE['webtsys_shop'])));

$num_order=webtsys_insert_id();

$query=$model['order_shop']->update(array('make_payment' => 1, 'invoice_num' => $num_order), 'where token="'.sha1($_COOKIE['webtsys_shop']).'"');

die(header('Location: '.make_fancy_url($base_url, 'shop', 'cart', 'payment_done', array('op' => 1))));

?>
