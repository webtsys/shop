<?php

function Paypal_ipn()
{
	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $lang_taxes, $email_paypal_shop, $url_paypal_shop;

	load_model('shop');

	$cookie_shop=sha1($_GET['webtsys_shop']);

	$req = 'cmd=_notify-validate';

	foreach ($_POST as $key => $value)
	{ 
		/*if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1)
		{ 
			$value = urlencode(stripslashes($value));
		} 
		else 
		{*/
			$value = urlencode($value);
		//}
		$req .= "&$key=$value";
	}

	$ch = curl_init();

	$url_paypal=$url_paypal_shop.'?'.$req;

	curl_setopt($ch, CURLOPT_URL, $url_paypal);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_HEADER, FALSE); 

	ob_start();
		
	curl_exec($ch);

	$result=ob_get_contents();

	$result=trim($result);
	
	curl_close($ch);

	$db_res='';

	if($result=='VERIFIED' && ($_POST['payment_status']=='Completed' || $_POST['payment_status']=='Pending') )
	{

		$model['order_shop']->reset_require();
		
		//Set number of invoice of order_shop
		
		$model['invoice_num']->insert(array('token_shop' => $cookie_shop));
		
		$num_order=webtsys_insert_id();

		$model['order_shop']->reset_require();
		
		$query=$model['order_shop']->update(array('make_payment' => 1, 'invoice_num' => $num_order), 'where token="'.$cookie_shop.'"');

		//Send email with result...

		//$db_res=ob_get_contents();		

		$db_res='Orden:'.$num_order;

		//die;
	
	}

	/*ob_end_clean();

	mail('webmaster@web-t-sys.com', "Prueba Paypal", $result." ".$_POST['payment_status']."\n\n".$cookie_shop."\n\n".$db_res );
	die;*/

}
?>
