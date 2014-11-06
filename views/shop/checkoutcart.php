<?php

function CheckOutCartView($arr_address, $arr_address_transport, $cart, $yes_button_checkout=1)
{

	global $lang, $config_shop, $model, $base_url;
	
	load_libraries(array('forms/textplainform'));
				
	$cart->yes_update=0;
	
	?>
	<h1><?php echo $lang['shop']['final_order']; ?></h1>
	<h2><?php echo $lang['shop']['shopping_list']; ?></h2>
	<?php
	
	list($arr_product_cart, $arr_price_base, $arr_price_base_total, $arr_price_filter, $arr_weight_product)=$cart->show_cart();

	$total_price_product=array_sum($arr_price_filter);
	
	if(isset($_SESSION['idtransport']))
	{
	?>
	<h2><?php echo $lang['shop']['transport_price']; ?></h2>
	<?php
	
		$total_weight_product=array_sum($arr_weight_product);
	
		list($price_transport, $num_packages, $name_transport)=$cart->obtain_transport_price($total_weight_product, $total_price_product, $_SESSION['idtransport']);
		
		?>
		
			<strong><?php echo $name_transport; ?>: <?php echo MoneyField::currency_format($price_transport); ?></strong>
		
		<?php
		
		$total_price_product+=$price_transport;
	
	}
	
	?>
	<p style="font-size:28px;"><?php echo $lang['shop']['total_price']; ?>: <?php echo MoneyField::currency_format($total_price_product); ?></p>
	<?php
	?>
	<h2><?php echo $lang['shop']['address_billing']; ?></h2>
	<?php
	
	foreach(ConfigShop::$arr_fields_address as $field)
	{
	
		$model['user_shop']->forms[$field]->form='TextPlainForm';
	
	}
	
	ModelForm:: SetValuesForm($arr_address, $model['user_shop']->forms, $show_error=1);
	
	echo load_view(array($model['user_shop']->forms, ConfigShop::$arr_fields_address), 'common/forms/modelform');
	?>
	<br />
	<h2><?php echo $lang['shop']['address_transport']; ?></h2>
	<?php
	
	foreach(ConfigShop::$arr_fields_transport as $field)
	{
	
		$model['address_transport']->forms[$field]->form='TextPlainForm';
	
	}
	
	ModelForm:: SetValuesForm($arr_address_transport, $model['address_transport']->forms, $show_error=1);
	
	echo load_view(array($model['address_transport']->forms, ConfigShop::$arr_fields_transport), 'common/forms/modelform');
	
	if($yes_button_checkout==1)
	{
	?>
	<br />
	<form method="get" action="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'checkout', array('action' => 'finish_checkout')); ?>">
	<p><input type="submit" value="<?php echo $lang['shop']['send_order_and_checkout']; ?>" /></p>
	</form>
	<?php
	}
}

?>