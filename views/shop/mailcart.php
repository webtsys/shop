<?php

function MailCartView($arr_address, $arr_address_transport, $arr_order_shop, $cart, $no_show_button_checkout=0)
{

	global $config_data, $lang;

	$portal_name=html_entity_decode($config_data['portal_name']);
	
	echo '<h3>'.$lang['shop']['your_orders'].'</h3>';
		
	echo '<p>'.$lang['shop']['num_order'].': '.$arr_order_shop['IdOrder_shop'].'</p>';
	
	echo $lang['shop']['explain_petition'].'<p>'.$lang['shop']['if_error_send_email_to'].': '.$config_data['portal_email'].'</p>';

	echo load_view(array($arr_address, $arr_address_transport, $cart, $no_show_button_checkout), 'shop/checkoutcart');
	
}

?>