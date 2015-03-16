<?php

function MailCartView($arr_address, $arr_address_transport, $arr_order_shop, $cart, $no_show_button_checkout=0)
{

	$portal_name=html_entity_decode(PhangoVar::$portal_name);
	
	?>
	<style>
	td { padding: 4px; border: solid #000 1px; }
	</style>
	<?php
	
	echo '<h3>'.PhangoVar::$lang['shop']['your_orders'].'</h3>';
		
	echo '<p>'.PhangoVar::$lang['shop']['num_order'].': '.$arr_order_shop['IdOrder_shop'].'</p>';
	
	echo PhangoVar::$lang['shop']['explain_petition'].'<p>'.PhangoVar::$lang['shop']['if_error_send_email_to'].': '.PhangoVar::$portal_email.'</p>';

	echo load_view(array($arr_address, $arr_address_transport, $cart, $no_show_button_checkout), 'shop/checkoutcart');
	
}

?>