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
	
	echo '<h1>'.i18n_lang('shop', 'new_order', 'Nuevo pedido').'</h1><p>'.i18n_lang('shop', 'explain_new_order', 'Un cliente ha hecho un nuevo pedido. Le enviamos todos los datos de este para verificación y su gestión.').'</p>'; 
	
	echo load_view(array($arr_address, $arr_address_transport, $cart, $no_show_button_checkout), 'shop/checkoutcart');
	
	echo '<h3>'.i18n_lang('shop', 'url_bill_for_admin', 'Enlace de factura para administrador').'</h3><p><a href="'.set_admin_link( 'shop', array('op' => 16, 'IdOrder_shop' => $arr_order_shop['IdOrder_shop'])).'">'.i18n_lang('shop', 'click_here_for_download_bill', 'Pulse aquí para descargar factura').'</a></p>';
}

?>