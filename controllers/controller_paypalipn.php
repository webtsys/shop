<?php

class PayPalIpnSwitchClass extends ControllerSwitchClass {

	public function index()
	{

		load_model('shop');

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

		if($result=='VERIFIED' && ($_POST['payment_status']=='Completed' || $_POST['payment_status']=='Pending') )
		{

			PhangoVar::$model['paypal_check']->insert(array('cookie_shop' => $cookie_shop, 'check' => 1));

			$db_res='Orden:'.$num_order;

			//die;
		
		}

		mail('webmaster@web-t-sys.com', "Prueba Paypal", $result." ".$_POST['payment_status']."\n\n".$cookie_shop."\n\n".$db_res );
		die;

	}
	
}
?>
