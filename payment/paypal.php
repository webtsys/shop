<?php
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

		$discounts=0;
		$transport_discount=0;
		$taxes_discount=0;
		$payment_discount=0;
		
		$model['group_shop_users']->components['group_shop']->fields_related_model=$model['group_shop_users']->components['group_shop']->get_all_fields();
		
		$query=$model['group_shop_users']->select('where group_shop_users.iduser='.$user_data['IdUser'].' order by group_shop_discount DESC, group_shop_transport_for_group DESC, group_shop_shipping_costs_for_group DESC limit 1');
	
		while($arr_group=webtsys_fetch_array($query))
		{
			
			$discounts+=$arr_group['group_shop_discount'];
			$transport_discount+=$arr_group['group_shop_transport_for_group'];
			$taxes_discount+=$arr_group['group_shop_taxes_for_group'];
			$payment_discount+=$arr_group['shipping_costs_for_group'];

			//echo '<p>'.$arr_group['group_shop_name'].'</p>';
		}
		
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
				$discounts=($price/$division);
				
				$price-=$discounts;

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

			$tax=calculate_taxes($config_shop['idtax'], $arr_item_amount[$idproduct]);

			$discount_tax=obtain_discount($taxes_discount, $tax);

			$tax-=$discount_tax;

			$arr_item_amount[$idproduct]+=$tax;
			
			?>
			<input type="hidden" name="item_name_<?php echo $z; ?>" value="<?php echo $model['product']->components['title']->show_formatted($arr_item_name[$idproduct]); ?>">
			<input type="hidden" name="amount_<?php echo $z; ?>" value="<?php echo MoneyField::currency_format($arr_item_amount[$idproduct], 0); ?>">
			<input type="hidden" name="quantity_<?php echo $z; ?>" value="<?php echo $arr_item_quantity[$idproduct]; ?>">
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

	case 1:

	//Check answered...
	/*
	$url_paypal='www.sandbox.paypal.com';

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
	$model['order_shop']->components['products_list']->required=0;

	$query=$model['order_shop']->update(array('make_payment' => 1), 'where token="'.md5($_GET['webtsys_shop_payment']).'"');

        $arr_mail=array();

	foreach ($_POST as $key => $value)
	{
		$arr_mail[]=$key.'='.$value;
	}
	
	mail($config_data['portal_email'], "Prueba Paypal", implode("\n\n", $arr_mail) );
	ob_end_clean();
	die;*/

	/*
	$req = 'cmd=_notify-validate';
	$header='';

	$header .= "POST /us/cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	
	$fp = fsockopen ($url_paypal, 80, $errno, $errstr, 30);
	// Process validation from PayPal
	if (!$fp) 
	{ 
		// HTTP ERROR

		
	} 
	else 
	{
		// NO HTTP ERROR
		fputs ($fp, $header . $req);
		
		while (!feof($fp)) 
		{	
			$res = fgets ($fp, 1024);
			echo $res.'<p>';
			if (strcmp ($res, "VERIFIED") == 0) 
			{
				
				foreach ($_POST as $key => $value)
				{
					$emailtext .= $key . " = " .$value ."\n\n";
					$arr_mail[$key]=$value;
				}
				
				mail($email, "VALID IPN ".$res, $emailtext . "\n\n" . $req);

				//setcookie ( "webtsys_shop_payment", FALSE, 0, $cookie_path);

				

				//die(header('Location: cart.php?op=1'));

			}
			if (strcmp ($res, "INVALID") == 0) 
			{

				foreach ($_POST as $key => $value)
				{
					$emailtext .= $key . " = " .$value ."\n\n";
				}
		
				mail($email, "Live-INVALID IPN ".$res, $emailtext . "\n\n" . $req);

			}

		}

	}*/

	break;

}

?>

