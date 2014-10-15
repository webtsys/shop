<?php

class CartClass {

	public $token;

	public function __construct()
	{
	
		if(!isset($_COOKIE['webtsys_shop']))
		{
		
			$token=sha1(uniqid(rand(), true));

			setcookie  ( 'webtsys_shop', $token, 0, $cookie_path);
		
		}
		else
		{
		
			$token=$_COOKIE['webtsys_shop'];
		
		}
	
		$this->token=sha1($token);
	
	}
	
	public function show_cart()
	{
	
		global $lang, $model, $base_url, $base_path;
		
		load_lang('shop');
	
		load_libraries(array('table_config'));
		
		$plugins=new PreparePluginClass('cart');
		
		//Prepare config for this plugins..
		
		$plugins->load_all_plugins();
	
		$plugin_price=array();
	
		echo '<p>'.$lang['shop']['explain_cart_options'].'</p>';
	
		//Add plugins for cart that added money to price, for example, taxes or discounts. You can configure taxes or discounts on its plugins admin.
	
		$arr_id=array(0);
		$arr_product_cart=array();
		$arr_price=array();
		$arr_price_filter=array();
		
		//$query=webtsys_query('select idproduct,price_product from cart_shop where token="'.$this->token.'"');
		
		$query=$model['cart_shop']->select('where token="'.$this->token.'"', array('IdCart_shop', 'idproduct', 'price_product'));
		
		while($arr_product=webtsys_fetch_array($query))
		{
			
			settype($arr_id[$arr_product['idproduct']], 'integer');

			$arr_id[$arr_product['idproduct']]++;
			$arr_price[$arr_product['idproduct']][$arr_product['IdCart_shop']]=$arr_product['price_product'];
			$arr_product_cart[$arr_product['idproduct']]=$arr_product;

			//Plugins for add money to value.
			
			//$arr_product_cart[$arr_product['idproduct']]['price_product_last']=$arr_product['price_product'];
			
			$arr_price_filter[$arr_product['idproduct']][$arr_product['IdCart_shop']]=$arr_product['price_product'];
			
			foreach($plugins->arr_plugin_list as $plugin)
			{
			
				//Pass the filter of the plugin
			
				$arr_price_filter[$arr_product['idproduct']][$arr_product['IdCart_shop']]=$this->arr_plugins[$plugin]->add_price_to_value($arr_product['price_product']);
			
			}
			
		}
		
		?>
		<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'deleteproduct', 'deleteproduct', array()); ?>">
		
		<?php
		set_csrf_key();

		$fields=array($lang['shop']['referer'], $lang['common']['name'], $lang['shop']['num_products'], $lang['shop']['price']);

		//$fields[]=$lang['shop']['select_product'];

		up_table_config( $fields );
	
		foreach($arr_product_cart as $arr_product)
		{
		
			$arr_product['product_title']=I18nField::show_formatted($arr_product['product_title']);
		
			$price_last=array_sum($arr_price_filter[$arr_product['idproduct']]);
			
			$arr_product['price_product_last_txt']=MoneyField::currency_format($price_last);
		
			$form_num_products=TextForm('num_products', 'units', $arr_id[$arr_product['idproduct']]);
		
			middle_table_config(array($arr_product['product_referer'], $arr_product['product_title'], $form_num_products, $arr_product['price_product_last_txt']));
		
		}
	
		down_table_config();
	
		//Plugins for added values text.
		
		?>
		<p><input type="submit" value="<?php echo $lang['shop']['delete_products_selected']; ?>"/></p>
		</form>
		<?php
		
	}
	
	public function modify_attributes_cart()
	{
	
		//here modify attributes for example, num units or the chosen unit with special attributes, for example, color.
	
	}
	
	public function add_to_cart($idproduct, $arr_details=array(), $price=0, $special_offer=0, $redirect=1)
	{
	
		//Add product to cart, if the product have attributes, config here. Attributes are plugin. 
		
			global $model, $base_path, $base_url, $arr_block, $cookie_path, $lang;
	
			settype($_POST['IdCart_shop'], 'integer');

			$redirect_url=make_fancy_url($base_url, 'shop', 'cart', 'add_cart', array() );

			$query=$model['cart_shop']->select('where token="'.$this->token.'"');

			$arr_cart=webtsys_fetch_array($query);

			settype($arr_cart['IdCart_shop'], 'integer');
			
			if($arr_cart['IdCart_shop']==0)
			{

				$this->token=sha1(uniqid(rand(), true));

				setcookie  ( 'webtsys_shop', $this->token, 0, $cookie_path);

			}

			if($special_offer>0)
			{

				$price=$special_offer;
			
			}
			
			if($_POST['IdCart_shop']>0 && $model['cart_shop']->select_count('where cart_shop.IdCart_shop='.$_POST['IdCart_shop'], 'IdCart_shop'))
			{
				
				if(!$model['cart_shop']->update( array('details' => $arr_details, 'time' => time(), 'price_product' => $price) , 'where token = "'.sha1($this->token).'" and IdCart_shop='.$_POST['IdCart_shop'].'  and idproduct ='. $_GET['IdProduct']))
				{

					return 0;

				}
				

			}
			else
			{
				
				if(!$model['cart_shop']->insert( array('token' => $this->token, 'idproduct' => $_GET['IdProduct'], 'details' => $arr_details, 'time' => time(), 'price_product' => $price) ))
				{

					return 0;

				}

			}
			
			if($redirect==1)
			{

				ob_end_clean();
				
				load_libraries(array('redirect'));
				die( redirect_webtsys( $redirect_url, $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );

			}
			else
			{

				return 1;

			}
	
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