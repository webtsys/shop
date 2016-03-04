<?php

use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaView\View;
use PhangoApp\PhaModels\Webmodel;

function CheckOutCartView($arr_address, $arr_address_transport, $cart, $yes_button_checkout=1)
{

	//global PhangoVar::$lang, PhangoVar::$config_shop, PhangoVar::$model, PhangoVar::$base_url;
	
	//load_libraries(array('forms/textplainform'));
				
	$cart->yes_update=0;
	
	?>
	<h1><?php echo I18n::lang('shop','final_order', 'Pedido final'); ?></h1>
	<h2><?php echo I18n::lang('shop','shopping_list', 'Lista de la compra'); ?></h2>
	<?php
	
	list($arr_product_cart, $arr_price_base, $arr_price_base_total, $arr_price_filter, $arr_weight_product)=$cart->show_cart();

	$total_price_product=array_sum($arr_price_filter);
	
	if(isset($_SESSION['idtransport']))
	{
	?>
	<h2><?php echo I18n::lang('shop','transport_price', 'Portes'); ?></h2>
	<?php
	
		$total_weight_product=array_sum($arr_weight_product);
	
		list($price_transport, $num_packages, $name_transport)=$cart->obtain_transport_price($total_weight_product, $total_price_product, $_SESSION['idtransport']);
		
		?>
		
			<p><strong><?php echo $name_transport; ?>: <?php echo ShopMoneyField::currency_format($price_transport); ?></strong></p>
		
		<?php
		
		$total_price_product+=$price_transport;
	
	}
	
	?>
	<p style="font-size:28px;"><?php echo I18n::lang('shop','total_price', 'Precio total'); ?>: <?php echo ShopMoneyField::currency_format($total_price_product); ?></p>
	<?php
	?>
	<h2><?php echo I18n::lang('shop','address_billing', 'Dirección de facturación'); ?></h2>
	<?php
	
	foreach(ConfigShop::$arr_fields_address as $field)
	{
		
		Webmodel::$model['user_shop']->forms[$field]=new PhangoApp\PhaModels\Forms\NoForm($field, '');
	
	}
	
	$arr_address['country']=PhangoApp\PhaModels\CoreFields\I18nField::show_formatted($arr_address['country']);
	
	PhangoApp\PhaModels\ModelForm::set_values_form(Webmodel::$model['user_shop']->forms, $arr_address, $show_error=1);
	
	echo View::load_view(array(Webmodel::$model['user_shop']->forms, ConfigShop::$arr_fields_address), 'forms/modelform');
	?>
	<br />
	<h2><?php echo I18n::lang('shop','address_transport', 'Dirección de envio'); ?></h2>
	<?php
	
	foreach(ConfigShop::$arr_fields_transport as $field)
	{
	
		Webmodel::$model['address_transport']->forms[$field]->form='TextPlainForm';
	
	}
	
	PhangoApp\PhaModels\ModelForm::set_values_form(Webmodel::$model['address_transport']->forms, $arr_address_transport, $show_error=1);
	
	echo View::load_view(array(Webmodel::$model['address_transport']->forms, ConfigShop::$arr_fields_transport), 'forms/modelform');
	
	if($yes_button_checkout==1)
	{
	?>
	<br />
	<form method="get" action="<?php echo Routes::make_simple_url('shop/cart/finish_checkout'); ?>">
	<p><input type="submit" value="<?php echo I18n::lang('shop','send_order_and_checkout', 'Enviar pedido y pagar'); ?>" /></p>
	</form>
	<?php
	}
}

?>