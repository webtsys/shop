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
	
		load_libraries(array('table_config'));
		
		$plugins=new PreparePluginClass('cart');
		
		//Prepare config for this plugins..
		
		$plugins->load_all_plugins();
	
		$plugin_price=array();
	
		echo '<p>'.$lang['shop']['explain_cart_options'].'</p>';
	
		//Add plugins for cart that added money to price, for example, taxes or discounts. You can configure taxes or discounts on its plugins admin.
	
		$arr_id=array(0);
		$arr_price=array();

		//$query=webtsys_query('select idproduct,price_product from cart_shop where token="'.$this->token.'"');
		
		$quert=$model['cart_shop']->select(array('idproduct', 'price_product'), 'where token="'.$this->token.'"');
		
		while(list($idproduct, $price_in_cart)=webtsys_fetch_row($query))
		{
			
			settype($arr_id[$idproduct], 'integer');

			$arr_id[$idproduct]++;
			$arr_price[$idproduct]=$price_in_cart;
			
			//Plugins for add money to value.
			
			foreach($plugins->arr_plugins_list as $plugin)
			{
			
				$this->arr_plugins[$plugin]->add_price_to_value($price_in_cart);
			
			}

		}
		
		?>
		<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'deleteproduct', 'deleteproduct', array()); ?>">
		
		<?php
		set_csrf_key();

		$fields=array($lang['shop']['referer'], $lang['common']['name'], $lang['shop']['num_products'], $lang['shop']['price']);

		$fields[]=$lang['shop']['select_product'];

		up_table_config( $fields );
	
		down_table_config();
	
		//Plugins for added values text.
		
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