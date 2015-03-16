<?php

function MailAdminCartView($arr_address, $arr_address_transport, $arr_order_shop, $cart, $no_show_button_checkout=0)
{

	//global $config_data, PhangoVar::$lang;

	$portal_name=html_entity_decode(PhangoVar::$portal_name);
	
	?>
	<style>
	td { padding: 4px; border: solid #000 1px; }
	</style>
	<?php
	
	echo '<h1>'.PhangoVar::$lang['shop']['new_order'].'</h1><p>'.PhangoVar::$lang['shop']['explain_new_order'].'</p>'; 
	
	echo load_view(array($arr_address, $arr_address_transport, $cart, $no_show_button_checkout), 'shop/checkoutcart');
	
	echo '<h3>'.PhangoVar::$lang['shop']['url_bill_for_admin'].'</h3><p><a href="'.set_admin_link( 'shop', array('op' => 16, 'IdOrder_shop' => $arr_order_shop['IdOrder_shop'])).'">'.PhangoVar::$lang['shop']['click_here_for_download_bill'].'</a></p>';
}

?>