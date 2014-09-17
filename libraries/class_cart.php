<?php

class CartClass {

	public $token;

	public function __construct($cookie_token)
	{
	
		$this->token=sha1($cookie_token);
	
	}
	
	public function show_cart()
	{
	
		
	
	}
	
	public function modify_attributes_cart()
	{
	
		
	
	}
	
	public function add_to_cart($idproduct, $arr_details=array(), $price=0, $special_offer=0, $redirect=1)
	{
	
		
	
	}
	
	public function payment_address()
	{
	
		
	
	}
	
	public function execute_plugins_payment()
	{
	
		
	
	}
	
	public function payment_gateway()
	{
	
		
	
	}

}

?>