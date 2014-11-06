<?php

function MailAdminCartView($arr_address, $arr_address_transport, $arr_order_shop, $cart, $idmodule, $no_show_button_checkout=0)
{

	global $config_data, $lang;

	$portal_name=html_entity_decode($config_data['portal_name']);
	
	echo '<h1>'.$lang['shop']['new_order'].'</h1><p>'.$lang['shop']['explain_new_order'].'</p>'; 
	
	echo load_view(array($arr_address, $arr_address_transport, $cart, $no_show_button_checkout), 'shop/checkoutcart');
	
	echo '<h3>'.$lang['shop']['url_bill_for_admin'].'</h3><p><a href="'.set_admin_link( 'obtain_bill', array('IdModule' => $idmodule, 'op' => 16, 'IdOrder_shop' => $arr_order_shop['IdOrder_shop'])).'">'.$lang['shop']['click_here_for_download_bill'].'</a></p>';
}

?>