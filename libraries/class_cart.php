<?php

class CartClass {

	public $token;

	public function __construct($cookie_token)
	{
	
		$this->token=sha1($cookie_token);
	
	}
	
	public function show_cart()
	{
	
		global $lang;
		
		load_lang('shop');
	
		echo '<p>'.$lang['shop']['explain_cart_options'].'</p>';
	
		//Add plugins for cart that added money to price, for example, taxes or discounts. You can configure taxes or discounts on its plugins admin.
	
		
		
	}
	
	public function modify_attributes_cart()
	{
	
		//here modify attributes for example, num units or the chosen unit with special attributes, for example, color.
	
	}
	
	public function add_to_cart($idproduct, $arr_details=array(), $price=0, $special_offer=0, $redirect=1)
	{
	
		//Add product to cart, if the product have attributes, config here.
	
	}
	
	public function execute_plugins_payment()
	{
	
		//Here, plugins that you need configure for add to the price, for example, transport.
	
		
	
	}
	
	public function payment_gateway()
	{
	
		//Here define the payment and notify that the product was paid.
	
	}

}

?>