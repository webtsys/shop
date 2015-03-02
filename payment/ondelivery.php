<?php
/*
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

$model['order_shop']->reset_require();

/*$model['invoice_num']->insert(array('token_shop' => sha1($_COOKIE['webtsys_shop'])));

$num_order=webtsys_insert_id();*/

/*$query=$model['order_shop']->update(array('make_payment' => 1), 'where token="'.sha1($_COOKIE['webtsys_shop']).'"');

die(header('Location: '.make_fancy_url($base_url, 'shop', 'cart', 'payment_done', array('action' => 'payment_done'))));*/

class OnDeliveryPaymentClass extends PaymentClass
{

	public function checkout($cart)
	{
		//Update payment_done
		
		PhangoVar::$model['order_shop']->reset_require();
		
		//print_r($_SESSION);
		
		if(PhangoVar::$model['order_shop']->update(array('payment_done' => 0, 'finished' => 1), 'where token="'.$cart->token.'"'))
		{
			
			$cart->send_mail_order();
				
			simple_redirect_location(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_finish_checkout'));
			
			
		}
		else
		{
		
			//echo load_view(array('
			
			//simple_redirect_location(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_finish_checkout'));
			echo load_view(array( PhangoVar::$lang['shop']['error_no_proccess_payment_send_email'], PhangoVar::$lang['shop']['error_contact_with_us'] ), 'content');
		
		}
		
	}
	
	public function cancel_checkout()
	{
	
		return 1;
	
	}
	
}

?>
