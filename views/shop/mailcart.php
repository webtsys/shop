<?php

function MailCartView($arr_address, $arr_address_transport, $arr_order_shop, $cart, $no_show_button_checkout=0)
{

	$portal_name=html_entity_decode(PhangoVar::$portal_name);
	
	?>
	<style>
	td { padding: 4px; border: solid #000 1px; }
	</style>
	<?php
	
	echo '<h3>'.i18n_lang('shop', 'your_orders', 'Su pedido').'</h3>';
		
	echo '<p>'.i18n_lang('shop', 'num_order', 'Número de pedido').': '.$arr_order_shop['IdOrder_shop'].'</p>';
	
	echo i18n_lang('shop', 'explain_petition', 'En este email, le adjuntamos los datos de su pedido. Por favor, guárdelo por si necesita hacer algún tipo de reclamación sobre este.').'<p>'.i18n_lang('shop', 'if_error_send_email_to', 'Si hubo algún error, por favor, envíenos un email a esta dirección').': '.PhangoVar::$portal_email.'</p>';

	echo load_view(array($arr_address, $arr_address_transport, $cart, $no_show_button_checkout), 'shop/checkoutcart');
	
}

?>