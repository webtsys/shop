<?php

load_libraries(array('config_shop', 'class_cart'));

class PaypalPaymentClass extends PaymentClass
{

	public function checkout($cart)
	{
	
		settype($_GET['op_pay'], 'integer');
		
		if(EMAIL_PAYPAL_SHOP && URL_PAYPAL_SHOP)
		{
			
			switch($_GET['op_pay'])
			{
				default:
				
					?>
					<p><strong><?php echo PhangoVar::$lang['shop']['paypal_explain']; ?></strong></p>
					<?php

					/*$query=PhangoVar::$model['config_shop']->select('', array('yes_transport'));

					list($yes_transport)=webtsys_fetch_row($query);*/
					
					$url_return=make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'finish_checkout', array(), array('op' => 1, 'op_pay' => 1));
					
					?>
					<form action="<?php echo URL_PAYPAL_SHOP; ?>" method="post">
					<input type="hidden" name="cmd" value="_cart">
					<input type="hidden" name="business" value="<?php echo EMAIL_PAYPAL_SHOP; ?>">
					<input type="hidden" name="notify_url" value="<?php echo make_direct_url(PhangoVar::$base_url, 'shop', 'paypalipn', array('webtsys_shop' => $_COOKIE['webtsys_shop'])); ?>">
					<input type="hidden" name="return" value="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_finished', array(), array()); ?>">
					<!--<input type="hidden" name="quantity" value="1">-->
					<?php
					
					$arr_products=PhangoVar::$model['cart_shop']->select_to_array('where token="'.sha1($_COOKIE['webtsys_shop']).'"');
					
					$total_weight=0;
					
					$total_price=0;
					
					$z=1;
					
					foreach($arr_products as $idcart_shop => $arr_cart_shop)
					{
					
					$idproduct=$arr_cart_shop['idproduct'];
					
					$price_total=MoneyField::currency_format($arr_cart_shop['price_product']*$arr_cart_shop['units'], 0);
					
					?>
					
					<input type="hidden" name="item_name_<?php echo $z; ?>" value="<?php echo $arr_cart_shop['units']; ?> x <?php echo PhangoVar::$model['product']->components['title']->show_formatted($arr_cart_shop['product_title']); ?>">
					<input type="hidden" name="amount_<?php echo $z; ?>" value="<?php echo $price_total; ?>">
					<input type="hidden" name="quantity_<?php echo $z; ?>" value="1">
					<?php
					
					$total_weight+=$arr_cart_shop['weight'];
					
					$total_price+=$price_total;
					
					$z++;
					
					}
					
					if(ConfigShop::$config_shop['no_transport']==0)
					{

						list($price_total_transport, $num_packs)=$cart->obtain_transport_price($total_weight, $total_price, $_SESSION['idtransport']);

						?>
						<input type="hidden" name="item_name_<?php echo $z; ?>" value="Transporte">
						<input type="hidden" name="amount_<?php echo $z; ?>" value="<?php echo $price_total_transport; ?>">
						<?php
						$z++;

					}
					
					?>

						<input type="hidden" name="custom" value="merchant_custom_value">
						<!--<input type="hidden" name="invoice" value="merchant_invoice_12345">-->
						<input type="hidden" name="charset" value="utf-8">
						<input type="hidden" name="no_shipping" value="1">
						<input type="hidden" name="cancel_return" value="<?php //echo make_fancy_url($base_url, 'shop', 'cart', 'cancel_payment', array() ); ?>/shop/cart.php">
						<input type="hidden" name="no_note" value="0">
						<input type="hidden" name="upload" value="1">
						<input type="hidden" name="currency_code" value="EUR">
						<input type="submit" value="<?php echo PhangoVar::$lang['shop']['checkout_order']; ?>" />
						</form>

					<?php
					
					return 5;
				
				break;
			
				case 1:
				
					//Here check that the payment was done.
					//Check payment
					
					$c=PhangoVar::$model['paypal_check']->select_count('where cookie_shop="'.sha1($_COOKIE['webtsys_shop']).'"');
					
					if($c>0)
					{
				
						return 'done';
						
					}
					else
					{
					
						return 1;
					
					}
				
				break;
			
			}
			
		}
		else
		{
			echo '<p><strong>'.PhangoVar::$lang['shop']['paypal_email_variable_no_isset'].'</strong></p>';

			return 0;
		}
	}
	
	public function cancel_checkout()
	{
	
		return 1;
	
	}
	
}

/*
global $email_paypal_shop, $url_paypal_shop, $user_data, $prefix_key;

//Boton de ejemplo de paypal

if(!isset($email_paypal_shop) || !isset($url_paypal_shop))
{

	echo '<p><strong>'.$lang['shop']['paypal_email_variable_no_isset'].'</strong></p>';

	return 0;

}

settype($_GET['op_pay'], 'integer');

switch($_GET['op_pay'])
{

default:

	?>
	<p><strong><?php echo $lang['shop']['paypal_explain']; ?></strong></p>
	<?php

	$query=$model['config_shop']->select('', array('yes_transport'));

	list($yes_transport)=webtsys_fetch_row($query);

	?>
	<form action="<?php echo $url_paypal_shop; ?>" method="post">
	<input type="hidden" name="cmd" value="_cart">
	<input type="hidden" name="business" value="<?php echo $email_paypal_shop; ?>">
	<input type="hidden" name="notify_url" value="<?php echo make_fancy_url($base_url, 'shop', 'paypal_ipn', 'paypal_ipn', array('webtsys_shop' => $_COOKIE['webtsys_shop'], 'csrf_token' => $prefix_key)); ?>">
	<input type="hidden" name="return" value="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'return_cart', array()); ?>">
	<input type="hidden" name="quantity" value="1">

	<?php

		//Load discounts
		
		$discounts=$arr_order_shop['discount_percent'];
		$transport_discount=$arr_order_shop['transport_discount_percent'];
		$taxes_discount=$arr_order_shop['tax_discount_percent'];
		$payment_discount=$arr_order_shop['payment_discount_percent'];
		
		$z=1;

		$query=$model['cart_shop']->select('where token="'.sha1($_COOKIE['webtsys_shop']).'"', array('IdCart_shop', 'idproduct', 'details'));

		while(list($idcart, $idproduct, $details)=webtsys_fetch_row($query))
		{
			settype($arr_final[$idproduct], 'integer');
			
			$arr_final[$idproduct]++;

			$arr_data[$idcart]=array($idproduct, $details);

		}

		$total_weight=0;

		$arr_item_name=array();
		$arr_item_amount=array();
		$arr_item_quantity=array();
		

		$query=webtsys_query('select product.IdProduct, product.title, product.price, product.weight, cart_shop.IdCart_shop, cart_shop.details from product, cart_shop where product.IdProduct IN ('.implode(',', array_keys($arr_final) ).') and product.IdProduct=cart_shop.idproduct and token="'.sha1($_COOKIE['webtsys_shop']).'" order by cart_shop.idproduct ASC');
		
		while( list($idproduct, $title, $price, $weight, $idcart_shop, $ser_details)=webtsys_fetch_row($query) )
		{
			
			$total_weight+=$weight;
			
			if($discounts>0)
			{
				
				$division=100/$discounts;
				$discount_product=($price/$division);
				
				//$discount_product=MoneyField::currency_format($discount_product, false);
				
				$price-=$discount_product;
				
			}
			
			$arr_details=unserialize($ser_details);

			settype($arr_details['ident'], 'string');

			$arr_item_name[$idproduct]=$title;
			$arr_item_amount[$idproduct]=$price;
			settype($arr_item_quantity[$idproduct], 'integer');
			$arr_item_quantity[$idproduct]++;


		}
		$z=1;
		
		$total_price=0;

		foreach($arr_item_name as $idproduct => $value)
		{
		
			//$discount_price=obtain_discount($price_discount, $arr_item_amount[$idproduct]);
			
			$tax=calculate_taxes($config_shop['idtax'], $arr_item_amount[$idproduct]);
			
			
			//$arr_item_amount[$idproduct]=$arr_item_amount[$idproduct];
			$discount_tax=obtain_discount($taxes_discount, $tax);
			
			$tax-=$discount_tax;
			
			$arr_item_amount[$idproduct]+=$tax;
			
			$total_price_amount=$arr_item_amount[$idproduct]*$arr_item_quantity[$idproduct];
			
			//echo MoneyField::currency_format($total_price_amount).'<p>';
			?>
			<input type="hidden" name="item_name_<?php echo $z; ?>" value="<?php echo $arr_item_quantity[$idproduct]; ?> x <?php echo $model['product']->components['title']->show_formatted($arr_item_name[$idproduct]); ?>">
			<input type="hidden" name="amount_<?php echo $z; ?>" value="<?php echo MoneyField::currency_format($total_price_amount, 0); ?>">
			<input type="hidden" name="quantity_<?php echo $z; ?>" value="1<?php //echo $arr_item_quantity[$idproduct]; ?>">
			<?php

			$total_price+=$arr_item_amount[$idproduct]*$arr_item_quantity[$idproduct];
			
			$z++;

		}
		
		if($yes_transport==1)
		{

			list($price_total_transport, $num_packs)=obtain_transport_price($total_weight, $total_price, $arr_order_shop['transport']);

			$discount_transport=obtain_discount($transport_discount, $price_total_transport);

			$price_total_transport-=$discount_transport;

			?>
			<input type="hidden" name="item_name_<?php echo $z; ?>" value="Transporte">
			<input type="hidden" name="amount_<?php echo $z; ?>" value="<?php echo $price_total_transport; ?>">
			<?php
			$z++;

		}
		
		//shipping costs

		if($arr_order_shop['price_payment']>0)
		{

			$discount_payment=obtain_discount($payment_discount, $arr_order_shop['price_payment']);

			$price_total_payment=$arr_order_shop['price_payment']-$discount_payment;

			?>
			<input type="hidden" name="item_name_<?php echo $z; ?>" value="Precio de pago">
			<input type="hidden" name="amount_<?php echo $z; ?>" value="<?php echo $price_total_payment; ?>">
			<?php

		}

	?>

	<input type="hidden" name="custom" value="merchant_custom_value">
	<!--<input type="hidden" name="invoice" value="merchant_invoice_12345">-->
	<input type="hidden" name="charset" value="utf-8">
	<input type="hidden" name="no_shipping" value="1">
	<!--<input type="hidden" name="image_url" value="http://www.knight-comics.com/media/comics/images/logo.jpg">-->
	<input type="hidden" name="cancel_return" value="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'cancel_payment', array() ); ?>/shop/cart.php">
	<input type="hidden" name="no_note" value="0">
	<input type="hidden" name="upload" value="1">
	<input type="hidden" name="currency_code" value="EUR">
	<input type="submit" value="<?php echo $lang['shop']['checkout_order']; ?>" />
	</form>

<?php
	break;


}
*/
?>

