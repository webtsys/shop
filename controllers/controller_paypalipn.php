<?php

class PayPalIpnSwitchClass extends ControllerSwitchClass {

	public function index()
	{

		load_model('shop');
		load_libraries(array('config_shop', 'class_cart'), PhangoVar::$base_path.'modules/shop/libraries/');
		
		settype($_GET['webtsys_shop'], 'string');
		
		$_GET['webtsys_shop']=form_text($_GET['webtsys_shop']);
		
		$cart=new CartClass(0, $_GET['webtsys_shop']);

		$cookie_shop=sha1($_GET['webtsys_shop']);

		$req = 'cmd=_notify-validate';

		foreach ($_POST as $key => $value)
		{ 

			$value = urlencode($value);
		
			$req .= "&$key=$value";
		}

		$ch = curl_init();

		$url_paypal=URL_PAYPAL_SHOP.'?'.$req;

		curl_setopt($ch, CURLOPT_URL, $url_paypal);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_HEADER, FALSE); 		
			
		curl_exec($ch);

		$result=ob_get_contents();

		$result=trim($result);
		
		curl_close($ch);

		$db_res='';
		
		settype($_POST['payment_status'], 'string');

		/*if($result=='VERIFIED' && ($_POST['payment_status']=='Completed' || $_POST['payment_status']=='Pending') )
		{

			PhangoVar::$model['order_shop']->reset_require();
		
		//print_r($_SESSION);
		
			if(PhangoVar::$model['order_shop']->update(array('payment_done' => 1, 'finished' => 1), 'where token="'.$cart->token.'"'))
			{
			
				$cart->send_mail_order();
			
			}

			$db_res='Orden:'.$num_order;

			//die;
		
		}*/

		mail('webmaster@web-t-sys.com', "Prueba Paypal", $result." ".$_POST['payment_status']."\n\n".$cookie_shop."\n\n".$db_res );
		die;

	}
	
}
?>
