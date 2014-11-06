<?php

class CartClass {

	public $token;
	public $url_update;
	public $yes_update;
	public $plugins;
	
	public function __construct($yes_update=1)
	{
	
		global $base_url, $cookie_path;
	
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
	
		$this->plugins=new PreparePluginClass('cart');
	
		//Prepare config for this plugins..
		
		$this->plugins->load_all_plugins();
	
	}
	
	public function show_cart()
	{
	
		global $lang, $model, $base_url, $base_path;
		
		load_lang('shop');
	
		load_libraries(array('table_config'));
	
		$plugin_price=array();
		
		if($this->yes_update==1)
		{
			echo '<p>'.$lang['shop']['explain_cart_options'].'</p>';
		}
		
		//Add plugins for cart that added money to price, for example, taxes or discounts. You can configure taxes or discounts on its plugins admin.
	
		$arr_id=array(0);
		$arr_product_cart=array();
		$arr_price=array();
		//Final price with plugins.
		$arr_price_filter=array();
		$arr_price_explain_plugin=array();
		$arr_price_rest=array();
		
		//Base price without applied plugins how taxes or discounts.
		$arr_price_base=array(); 
		//Total price of all units without applied plugins.
		$arr_price_base_total=array();

		$arr_weight_total=array();
		
		$arr_new_field=array();
		
		//$query=webtsys_query('select idproduct,price_product from cart_shop where token="'.$this->token.'"');
		
		$query=$model['cart_shop']->select('where token="'.$this->token.'"', array('IdCart_shop', 'idproduct', 'price_product', 'units', 'weight'));
		
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
			
			foreach($this->plugins->arr_plugin_list as $plugin)
			{
			
				//Pass the filter of the plugin
				
				list($add_price, $substract_price)=$this->arr_plugins[$plugin]->add_price_to_value($arr_price_filter[$arr_product['IdCart_shop']][$arr_product['idproduct']]);
			
				$arr_price_filter[$arr_product['IdCart_shop']]=$add_price;
			
				$arr_price_base[$arr_product['IdCart_shop']]=$substract_price;
			
				$arr_price_base_total[$arr_product['IdCart_shop']]=$arr_price_base[$arr_product['IdCart_shop']]*$arr_product['units'];
			
				//$arr_price_explain_plugin[$arr_product['IdCart_shop']][$plugin]=array('price_after_plugin' => $add_price, 'price_plugin' => $substract_price);
				
			}
			
			$arr_weight_total[$arr_product['IdCart_shop']]=$arr_product['weight']*$arr_product['units'];
			
		}
		
		$method_text_form='';
		
		$close_form='';
		
		if($this->yes_update==1)
		{
		
			/*function show_text_form($idcart_shop, $units)
			{
			
				return TextForm('num_products['.$idcart_shop.']', 'units', $units);
			
			}*/
			
			$method_text_form='show_text_form';
			
			$close_form='</form>';
		
		?>
		<form method="post" action="<?php echo $this->url_update; ?>">
		
		<?php
		set_csrf_key();
		}
		else
		{
		
			/*function show_text_form($idcart_shop, $units)
			{
			
				return $units;
			
			}*/
			
			$method_text_form='no_show_text_form';
		
		}
		
		//Go to view...
		
		echo load_view(array($this->plugins, $arr_product_cart, $arr_price_base, $arr_price_base_total, $arr_price_filter, $this->yes_update, $method_text_form), 'shop/cartshow');
		
		echo $close_form;
		
		return array( $arr_product_cart, $arr_price_base, $arr_price_base_total, $arr_price_filter, $arr_weight_total);
		
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
			
			//obtain product
			
			$arr_product=$model['product']->select_a_row($idproduct, array('IdProduct', 'price', 'weight'));
			
			settype($arr_product['IdProduct'], 'integer');
			
			if($arr_product['IdProduct']>0)
			{
				$where_sql='where cart_shop.idproduct='.$idproduct.' and token="'.$this->token.'" and details="'.addslashes(serialize($arr_details)).'"';
				
				if($model['cart_shop']->select_count($where_sql)==0)
				{
				
					if($_POST['units']<=0)
					{
					
						$_POST['units']=1;
					
					}
				
					if(!$model['cart_shop']->insert( array('token' => $this->token, 'idproduct' => $idproduct, 'details' => $arr_details, 'time' => time(), 'units' => $_POST['units'], 'price_product' => $price, 'weight' => $arr_product['weight']) ))
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
			
			}
			else
			{
			
				return 0;
			
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
		$total_weight_product=0;
		
		/*$plugins=new PreparePluginClass('cart');
		
		//Prepare config for this plugins..
		
		$plugins->load_all_plugins();*/
	
		$plugin_price=array();
		
		$query=$model['cart_shop']->select('where token="'.$this->token.'"', array('idproduct', 'price_product', 'units', 'weight'));
		
		while(list($idproduct, $price_product, $units, $weight)=webtsys_fetch_row($query))
		{
			
			$num_product+=$units;

			$total_price_product+=$price_product*$units;
			
			$total_weight_product+=$weight*$units;

			foreach($this->plugins->arr_plugin_list as $plugin)
			{
			
				//Pass the filter of the plugin
			
				$arr_price_filter[$arr_product['idproduct']][$arr_product['IdCart_shop']]=$this->arr_plugins[$plugin]->add_price_to_value($total_price_product);
			
			}
			
		}
		
		
		return array($num_product, $total_price_product, $total_weight_product);
	
	}
	
	
	public function execute_plugins_payment()
	{
	
		//Here, plugins that you need configure for add to the price, for example, transport.
	
		
	
	}
	
	public function payment_gateway($idpayment)
	{
	
		global $model, $lang, $base_path, $config_shop;
	
		$cart=new CartClass();
	
		//Here define the payment and notify that the product was paid. Also fill order_shop and delete cart.
		
		$arr_payment=$model['payment_form']->select_a_row($idpayment);
				
		settype($arr_payment['IdPayment_form'], 'integer');
		
		if($arr_payment['IdPayment_form']>0)
		{
	
			if(!include($base_path.'modules/shop/payment/'.basename($arr_payment['code'])))
			{
		
				echo $lang['shop']['error_no_proccess_payment_send_email'].': '.$config_data['portal_email'];

			}
			else
			{
			
				$name_class=str_replace('.php', '', $arr_payment['code']).'PaymentClass';
				
				$payment_class=new $name_class($cart);
				
				if($payment_class->checkout())
				{
					$post['token']=$this->token;
					
					$model['user_shop']->components['country']->name_field_to_field='name';
					$model['user_shop']->components['country']->fields_related_model=array('name');
					
					$model['address_transport']->components['country_transport']->name_field_to_field='name';
					$model['address_transport']->components['country_transport']->fields_related_model=array('name');
					
					ConfigShop::$arr_fields_address[]='email';
					
					$arr_address=$model['user_shop']->select_a_row($_SESSION['IdUser_shop'], ConfigShop::$arr_fields_address);
					
					$arr_address['country']=unserialize($arr_address['country']);
					
					if($config_shop['no_transport']==0)
					{
					
						$arr_address_transport=$model['address_transport']->select_a_row($_SESSION['idaddress'], ConfigShop::$arr_fields_transport);
					
						$arr_address_transport['country_transport']=unserialize($arr_address_transport['country_transport']);
					
						$arr_transport=$model['transport']->select_a_row($_SESSION['idtransport'], array('name'));
						
						$post['transport']=$arr_transport['name'];
				
					}
					
					$post=array_merge($post, $arr_address, $arr_address_transport);
					
					$post['name_payment']=$arr_payment['name'];
					
					$post['make_payment']=1;
					
					$post['date_order']=DateTimeNow::$today;
					
					$post['iduser']=$_SESSION['IdUser_shop'];
					
					list($num_product, $total_price_product, $total_weight_product)=$this->obtain_simple_cart();
					
					$post['total_price']=$total_price_product;
					
					list($total_price_transport, $num_packs, $name)=$this->obtain_transport_price($total_weight_product, $total_price_product, $_SESSION['idtransport']);
					
					$post['price_transport']=$total_price_transport;
					
					$post['price_payment']=$arr_payment['price_payment'];
					
					if($model['order_shop']->insert($post))
					{
					
						foreach($this->plugins->arr_plugin_list as $plugin)
						{
						
							//Pass the filter of the plugin
						
							$this->arr_plugins[$plugin]->insert_price_to_order_shop($this);
						
						}
						
						//Send emails...
						
						$post['IdOrder_shop']=Webmodel::insert_id();
						
						$this->send_mail_order($post, $arr_address, $arr_address_transport);
						
						//$this->clean_cart();
						
						echo $lang['shop']['order_success_cart_clean'];
					
					}
					else
					{
					
						echo '<p>'.$model['order_shop']->std_error.'</p>';
					
						if($payment_class->cancel_checkout())
						{
						
							echo $lang['shop']['cancel_checkout_success'];
						
						}
						else
						{
						
							echo $lang['shop']['no_cancel_checkout_success'];
						
						}
					
					}
				}
				else
				{
				
					
				
				}
			
			}
	
		}
	
	}
	
	public function obtain_transport_price($total_weight, $total_price, $idtransport)
	{

		global $model;

		$query=$model['transport']->select('where IdTransport='.$idtransport, array('name', 'type'));
		
		list($name, $type)=webtsys_fetch_row($query);
		
		if($type==0)
		{

			$query=webtsys_query('select price from price_transport where weight>='.$total_weight.' and idtransport='.$idtransport.' order by price ASC limit 1');
				
			list($price_transport)=webtsys_fetch_row($query);

			settype($price_transport, 'double');
			
			if($price_transport>0)
			{

				return array($price_transport, 1, $name);

			}
			else
			{

				$weight_substract=0;
				$price_transport=0;
				$total_price_transport=0;

				$query=webtsys_query('select weight, price from price_transport order by price DESC limit 1');

				list($max_weight, $max_price)=webtsys_fetch_row($query);

				//Tenemos que ver en cuanto supera los kilos...

				//Dividimos y obtenemos el resto...

				if($max_weight==0)
				{

					$max_weight=1;

				}

				$num_packs=($total_weight/$max_weight)-1;
				
				for($x=0;$x<$num_packs;$x++)
				{

					$total_price_transport+=$max_price;
					$weight_substract+=$max_weight;

				}

				$weight_last=$total_weight-$weight_substract;
			
				$query=webtsys_query('select price from price_transport where weight>='.$weight_last.' and idtransport='.$idtransport.' order by price ASC limit 1');
				
				list($price_transport)=webtsys_fetch_row($query);

				settype($price_transport, 'double');
				
				$total_price_transport+=$price_transport;

				$num_packs=ceil($num_packs+1);

				return array($total_price_transport, $num_packs, $name);
				
			}
			
		}
		else
		{
		
			$query=webtsys_query('select price from price_transport_price where min_price>='.$total_price.' and idtransport='.$idtransport.' order by min_price ASC limit 1');
			
			list($price_transport)=webtsys_fetch_row($query);

			//settype($price_transport, 'double');
			
			if($price_transport!='')
			{

				return array($price_transport, 1, $name);

			}
			else
			{

				$min_price_substract=0;
				$price_transport=0;
				$total_price_transport=0;

				$query=webtsys_query('select min_price, price from price_transport_price order by min_price DESC limit 1');

				list($max_min_price, $max_price)=webtsys_fetch_row($query);
				
				return array($max_price, 1, $name);

				//Tenemos que ver en cuanto supera los kilos...

				//Dividimos y obtenemos el resto...

				
			}
		
		}

	}
	
	public function clean_cart()
	{
	
		global $cookie_path;
	
		setcookie ( "webtsys_shop", FALSE, 0, $cookie_path);
	
	}
	
	public function send_mail_order($arr_order_shop, $arr_address, $arr_address_transport)
	{
	
		global $model, $config_data, $config_shop, $lang;
	
		load_libraries(array('utilities/set_admin_link', 'send_email'));
		
		$arr_address['country']=I18nField::show_formatted(serialize($arr_address['country']));
		
		$arr_address_transport['country_transport']=I18nField::show_formatted(serialize($arr_address_transport['country_transport']));
		
		//Prepare email
		
		$arr_user=$model['user_shop']->select_a_row($_SESSION['IdUser_shop'], array('email'));
		
		//$num_order=order_shop::calculate_num_bill($arr_order_shop['IdOrder_shop']);

		$content_mail_user=load_view(array($arr_address, $arr_address_transport, $arr_order_shop, $this, 0), 'shop/mailcart');
		
		$query=$model['module']->select('where name="shop"', array('IdModule'));

		list($idmodule)=webtsys_fetch_row($query);

		$content_mail_admin=load_view(array($arr_address, $arr_address_transport, $arr_order_shop, $this, $idmodule, 0), 'shop/mailadmincart');
		
		//If no send mail write a message with the reference, for send to mail shop...

		if( !send_mail($arr_user['email'], $lang['shop']['your_orders'], $content_mail_user, 'html') || !send_mail($config_data['portal_email'], $lang['shop']['orders'], $content_mail_admin, 'html') )
		{

			echo '<p>'.$lang['shop']['error_cannot_send_email'].', '.$lang['shop']['use_this_id_for_contact_with_us'].': <strong>'.$arr_order_shop['IdOrder_shop'].'</strong></p>';

		}
	
	}

}

function show_text_form($idcart_shop, $units)
{
	
	return TextForm('num_products['.$idcart_shop.']', 'units', $units);
	
}
	
function no_show_text_form($idcart_shop, $units)
{
	
	return $units;
	
}


?>