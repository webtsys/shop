<?php

class CartClass {

	public $token;
	public $url_update;
	public $yes_update;
	
	public function __construct($yes_update=1)
	{
	
		global $base_url;
	
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
	
		$this->url_update=make_fancy_url($base_url, 'shop', 'cart', 'modify_product', array('action' => 'update'));
	
		$this->yes_update=$yes_update;
	
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
		$arr_price_explain_plugin=array();
		$arr_price_rest=array();
		
		$arr_new_field=array();
		
		//$query=webtsys_query('select idproduct,price_product from cart_shop where token="'.$this->token.'"');
		
		$query=$model['cart_shop']->select('where token="'.$this->token.'"', array('IdCart_shop', 'idproduct', 'price_product', 'units'));
		
		while($arr_product=webtsys_fetch_array($query))
		{
			
			settype($arr_id[$arr_product['IdCart_shop']], 'integer');

			$arr_id[$arr_product['IdCart_shop']]++;
			
			//Original price.
			
			$arr_price[$arr_product['IdCart_shop']]=$arr_product['price_product'];
			$arr_product_cart[$arr_product['IdCart_shop']]=$arr_product;

			//Plugins for add money to value.
			
			//$arr_product_cart[$arr_product['idproduct']]['price_product_last']=$arr_product['price_product'];
			
			//Price filter by plugins how taxes or discounts.
			
			$arr_price_filter[$arr_product['IdCart_shop']]=$arr_price[$arr_product['IdCart_shop']]*$arr_product['units'];
			
			$arr_price_base[$arr_product['IdCart_shop']]=$arr_price[$arr_product['IdCart_shop']];
			
			$arr_price_base_total[$arr_product['IdCart_shop']]=$arr_price[$arr_product['IdCart_shop']]*$arr_product['units'];
			
			foreach($plugins->arr_plugin_list as $plugin)
			{
			
				//Pass the filter of the plugin
				
				list($add_price, $substract_price)=$this->arr_plugins[$plugin]->add_price_to_value($arr_price_filter[$arr_product['IdCart_shop']][$arr_product['idproduct']]);
			
				$arr_price_filter[$arr_product['IdCart_shop']]=$add_price;
			
				$arr_price_base[$arr_product['IdCart_shop']]=$substract_price;
			
				$arr_price_base_total[$arr_product['IdCart_shop']]=$arr_price_base[$arr_product['IdCart_shop']]*$arr_product['units'];
			
				//$arr_price_explain_plugin[$arr_product['IdCart_shop']][$plugin]=array('price_after_plugin' => $add_price, 'price_plugin' => $substract_price);
				
			}
			
		}
		
		if($this->yes_update==1)
		{
		
			function show_text_form($idcart_shop, $units)
			{
			
				return TextForm('num_products['.$idcart_shop.']', 'units', $units);
			
			}
		
		?>
		<form method="post" action="<?php echo $this->url_update; ?>">
		
		<?php
		set_csrf_key();
		}
		else
		{
		
			function show_text_form($idcart_shop, $units)
			{
			
				return $units;
			
			}
		
		}
		
		//Go to view...
		
		echo load_view(array($plugins, $arr_product_cart, $arr_price_base, $arr_price_base_total, $arr_price_filter, $this->yes_update), 'shop/cartshow');
		
		
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

			//$query=$model['cart_shop']->select('where token="'.$this->token.'"');

			/*$arr_cart=webtsys_fetch_array($query);

			settype($arr_cart['IdCart_shop'], 'integer');*/
			
			/*if($arr_cart['IdCart_shop']==0)
			{

				$this->token=sha1(uniqid(rand(), true));

				setcookie  ( 'webtsys_shop', $this->token, 0, $cookie_path);

			}*/

			if($special_offer>0)
			{

				$price=$special_offer;
			
			}
			
			$defined_post=1;
			
			if(!isset($_POST['units']))
			{
			
				$_POST['units']=0;
				$defined_post=0;
			
			}
			
			$where_sql='where cart_shop.idproduct='.$idproduct.' and token="'.$this->token.'" and details="'.addslashes(serialize($arr_details)).'"';
			
			if($model['cart_shop']->select_count($where_sql)==0)
			{
			
				if($_POST['units']<=0)
				{
				
					$_POST['units']=1;
				
				}
			
				if(!$model['cart_shop']->insert( array('token' => $this->token, 'idproduct' => $idproduct, 'details' => $arr_details, 'time' => time(), 'units' => $_POST['units'], 'price_product' => $price) ))
				{

					return 0;

				}
			
			}
			else
			{
			
				$arr_cart=$model['cart_shop']->select_a_row_where($where_sql);
				
				if($_POST['units']<=0 && $defined_post==1)
				{
				
					$model['cart_shop']->delete($where_sql);
				
				}
				else
				{
				
					if($defined_post==1)
					{
					
						$arr_cart['units']=$_POST['units'];
				
					}
					else
					{
					
						$arr_cart['units']++;
					
					}
					
					$arr_cart['details']=SerializeField::unserialize($arr_cart['details']);
					
					$model['cart_shop']->update($arr_cart, $where_sql);
				
				}
				
			
			}
			
			/*if($_POST['IdCart_shop']>0 && $model['cart_shop']->select_count('where cart_shop.IdCart_shop='.$_POST['IdCart_shop'], 'IdCart_shop'))
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

			}*/
			
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
	
	public function sum_product_to_cart($idcart_shop, $units)
	{
		global $model;
	
	
		if($units>0)
		{
		
			$model['cart_shop']->reset_require();
		
			return $model['cart_shop']->update(array('units' => $units), 'where IdCart_shop='.$idcart_shop.' and token="'.$this->token.'"');
		
		}
		else
		if($units<=0)
		{
		
			return $model['cart_shop']->delete('where IdCart_shop='.$idcart_shop.' and token="'.$this->token.'"');
		
		}
	
	}
	
	public function obtain_simple_cart()
	{
	
		global $model;
	
		$num_product=0;
		$total_price_product=0;
		
		$plugins=new PreparePluginClass('cart');
		
		//Prepare config for this plugins..
		
		$plugins->load_all_plugins();
	
		$plugin_price=array();
		
		$query=$model['cart_shop']->select('where token="'.$this->token.'"', array('idproduct', 'price_product', 'units'));
		
		while(list($idproduct, $price_product, $units)=webtsys_fetch_row($query))
		{
			
			$num_product+=$units;

			$total_price_product+=$price_product*$units;

			foreach($plugins->arr_plugin_list as $plugin)
			{
			
				//Pass the filter of the plugin
			
				$arr_price_filter[$arr_product['idproduct']][$arr_product['IdCart_shop']]=$this->arr_plugins[$plugin]->add_price_to_value($total_price_product);
			
			}
			
		}
		
		
		return array($num_product, $total_price_product);
	
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