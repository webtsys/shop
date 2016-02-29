<?php

use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaRouter\Routes;

class CartClass {

	public $token;
	public $url_update;
	public $yes_update;
	public $plugins;
	
	public function __construct($yes_update=1, $token_selected='')
	{
	
		if(!isset($_COOKIE['webtsys_shop']))
		{
			
			$token=$this->create_new_token();
		
		}
		else
		{
		
			$token=$_COOKIE['webtsys_shop'];
		
		}
		
		if($token_selected!='')
		{
		
			$token=$token_selected;
		
		}
		
		$this->token=sha1($token);
		
		$this->url_update=Routes::make_url('shop', 'cart_update');
	
		$this->yes_update=$yes_update;
	
		$this->plugins=new PreparePluginClass('cart');
	
		//Prepare config for this plugins..
		
		$this->plugins->load_all_plugins();
	
	}
	
	public function create_new_token()
	{
	
		$token=sha1(uniqid(rand(), true));

		setcookie  ( 'webtsys_shop', $token, 0, Routes::$root_url);
		
		return $token;
	
	}
	
	public function show_cart()
	{
		
		
		I18n::load_lang('shop');
	
		Utils::load_libraries(array('table_config'));
	
		$plugin_price=array();
		
		if($this->yes_update==1)
		{
			echo '<p>'.I18n::lang('shop', 'explain_cart_options', 'Desde aquí usted puede cambiar las opciones de sus productos o eliminar su compra.').'</p>';
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
		
		Webmodel::$model['cart_shop']->conditions='where token="'.$this->token.'"';
		
		$query=Webmodel::$model['cart_shop']->select(array('IdCart_shop', 'idproduct', 'price_product', 'units', 'weight', 'details'));
		
		while($arr_product=Webmodel::$model['cart_shop']->fetch_array($query))
		{
			
			settype($arr_id[$arr_product['IdCart_shop']], 'integer');

			$arr_id[$arr_product['IdCart_shop']]++;
			
			//Original price.
			
			$arr_price[$arr_product['IdCart_shop']]=$arr_product['price_product'];
			
			//Product plugins.
			
			/*foreach($plugins_product->arr_plugin_list as $plugin_product)
			{
			
				//$arr_product['
				
				$arr_product['details_name']=$plugins_product->arr_plugins[$plugin_product]->prepare_name_plugin($arr_product['details']);
			
			}*/
			
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
	
			settype($_POST['IdCart_shop'], 'integer');

			$redirect_url=Routes::make_url('shop', 'cart');

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
			
			$arr_product=Webmodel::$model['product']->select_a_row($idproduct, array('IdProduct', 'price', 'weight'));
			
			settype($arr_product['IdProduct'], 'integer');
			
			if($arr_product['IdProduct']>0)
			{
				//Add arr_details from plugins.
					
				$plugins=new PreparePluginClass('product');
				
				$plugins->obtain_list_plugins();
				
				$plugins->load_all_plugins();
				
				foreach($plugins->arr_class_plugin as $idclass => $class_cart)
				{
					
					$arr_details=$class_cart->cart_product_insert_data($_POST, $arr_details);
					
				}
			
				$where_sql='where cart_shop.idproduct='.$idproduct.' and token="'.$this->token.'" and details="'.addslashes(serialize($arr_details)).'"';
				
				Webmodel::$model['cart_shop']->conditions=$where_sql;
				
				if(Webmodel::$model['cart_shop']->select_count()==0)
				{
				
					if($_POST['units']<=0)
					{
					
						$_POST['units']=1;
					
					}
                    
                    $post=array('token' => $this->token, 'idproduct' => $idproduct, 'details' => $arr_details, 'time' => time(), 'units' => $_POST['units'], 'price_product' => $price, 'weight' => $arr_product['weight']);
                    
                    Webmodel::$model['cart_shop']->fields_to_update=array_keys($post);
                    
					if(!Webmodel::$model['cart_shop']->insert($post))
					{
                        
						return 0;

					}
                    
				}
				else
				{
				
                    Webmodel::$model['cart_shop']->conditions=$where_sql;
				
					$arr_cart=Webmodel::$model['cart_shop']->select_a_row_where();
					
					if($_POST['units']<=0 && $defined_post==1)
					{
					
                        Webmodel::$model['cart_shop']->conditions=$where_sql;
					
						Webmodel::$model['cart_shop']->delete();
					
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
						
						$arr_cart['details']=PhangoApp\PhaModels\CoreFields\SerializeField::unserialize($arr_cart['details']);
						
						Webmodel::$model['cart_shop']->conditions=$where_sql;
						
						Webmodel::$model['cart_shop']->fields_to_update=['details', 'units'];
						
						Webmodel::$model['cart_shop']->update($arr_cart);
					
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
				
				Utils::load_libraries(array('redirect'));
				die( redirect_webtsys( $redirect_url, PhangoVar::$l_['common']->lang('redirect', 'Redirect'), PhangoVar::$l_['common']->lang('success', 'Success'), PhangoVar::$l_['common']->lang('press_here_redirecting', 'Press here for redirecting') , $arr_block) );

			}
			else
			{
                
				return 1;

			}
	
	}
	
	public function sum_product_to_cart($idcart_shop, $units)
	{
	
	
		if($units>0)
		{
		
			Webmodel::$model['cart_shop']->reset_require();
		
            Webmodel::$model['cart_shop']->conditions='where IdCart_shop='.$idcart_shop.' and token="'.$this->token.'"';
		
			return Webmodel::$model['cart_shop']->update(array('units' => $units));
		
		}
		else
		if($units<=0)
		{
		
            Webmodel::$model['cart_shop']->conditions='where IdCart_shop='.$idcart_shop.' and token="'.$this->token.'"';
		
			return Webmodel::$model['cart_shop']->delete();
		
		}
	
	}
	
	public function obtain_simple_cart()
	{
	
		$num_product=0;
		$total_price_product=0;
		$total_weight_product=0;
	
		$plugin_price=array();
		
		Webmodel::$model['cart_shop']->conditions='where token="'.$this->token.'"';
		
		$query=Webmodel::$model['cart_shop']->select(array('idproduct', 'price_product', 'units', 'weight'));
		
		while(list($idproduct, $price_product, $units, $weight)=Webmodel::$model['cart_shop']->fetch_row($query))
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
	
	public function check_order()
	{
	
        Webmodel::$model['order_shop']->conditions='where token="'.$this->token.'"';
	
		$arr_order=Webmodel::$model['order_shop']->select_a_row_where();
		
		settype($arr_order['IdOrder_shop'], 'integer');
		
		if($arr_order['IdOrder_shop']!=0)
		{
			
			ConfigShop::$arr_order=$arr_order;
			
			return true;
		
		}
		else
		{
		
			return false;
		
		}
		
	}
	
	public function create_order_shop($iduser_shop)
	{
	
		$post['token']=$this->token;
					
		Webmodel::$model['user_shop']->components['country']->name_field_to_field='name';
		Webmodel::$model['user_shop']->components['country']->fields_related_model=array('name');
		
		Webmodel::$model['address_transport']->components['country_transport']->name_field_to_field='name';
		Webmodel::$model['address_transport']->components['country_transport']->fields_related_model=array('name');
		
		ConfigShop::$arr_fields_address[]='email';
		
		$arr_address=Webmodel::$model['user_shop']->select_a_row($iduser_shop, ConfigShop::$arr_fields_address);
		
		$arr_address['country']=unserialize($arr_address['country']);
		
		if(ConfigShop::$config_shop['no_transport']==0)
		{
		
			settype($_SESSION['idaddress'], 'integer');
		
			$arr_address_transport=Webmodel::$model['address_transport']->select_a_row($_SESSION['idaddress'], ConfigShop::$arr_fields_transport);
		
			$arr_address_transport['country_transport']=unserialize($arr_address_transport['country_transport']);
		
			$arr_transport=Webmodel::$model['transport']->select_a_row($_SESSION['idtransport'], array('name'));
			
			$post['address_transport_id']=$_SESSION['idaddress'];
			
			$post['transport']=$arr_transport['name'];
	
		}
		
		$post=array_merge($post, $arr_address, $arr_address_transport);
		
		/*$post['name_payment']=$arr_payment['name'];
		
		$post['make_payment']=1;*/
		
		$post['date_order']=DateTimeNow::$today;
		
		$post['iduser']=$iduser_shop;
		
		list($num_product, $total_price_product, $total_weight_product)=$this->obtain_simple_cart();
		
		$post['total_price']=$total_price_product;
		
		list($total_price_transport, $num_packs, $name)=$this->obtain_transport_price($total_weight_product, $total_price_product, $_SESSION['idtransport']);
		
		$post['price_transport']=$total_price_transport;
		
		//$post['price_payment']=$arr_payment['price_payment'];
		
		$method='insert';
		
		if($this->check_order())
		{
		
			$method='update';
		
		}
		
		if(!Webmodel::$model['order_shop']->$method($post, 'where token="'.$this->token.'"'))
		{
			
			echo Webmodel::$model['order_shop']->std_error;
			return false;
		
		}
		
		return true;
		
	}
	
	public function payment_gateway($iduser_shop, $idpayment)
	{
	
		settype($_GET['op_payment'], 'integer');
		//$cart=new CartClass();
	
		//Here define the payment and notify that the product was paid. Also fill order_shop and delete cart.
		
		$arr_payment=Webmodel::$model['payment_form']->select_a_row($idpayment);
				
		settype($arr_payment['IdPayment_form'], 'integer');
		
		if($arr_payment['IdPayment_form']>0)
		{
			
			if(!include(PhangoVar::$base_path.'modules/shop/payment/'.basename($arr_payment['code'])))
			{
		
				echo I18n::lang('shop', 'error_no_proccess_payment_send_email', 'Error: no se pudo procesar el pago ni el envio del email de respuesta').': '.$config_data['portal_email'];

			}
			else
			{
			
				$name_class=str_replace('.php', '', $arr_payment['code']).'PaymentClass';
						
				$payment_class=new $name_class($this);
					
				$payment_class->checkout($this);
			
			}
	
		}
	
	}
	
	public function finish()
	{
	
	
		$this->send_mail_order();
		
		$this->clean_cart();
			
		simple_redirect_location(Routes::make_url('shop', 'cart_finished'));
						
		
	
	}
	
	public function obtain_transport_price($total_weight, $total_price, $idtransport)
	{

        Webmodel::$model['transport']->conditions='where IdTransport='.$idtransport;
	
		$query=Webmodel::$model['transport']->select(array('name', 'type'));
		
		list($name, $type)=Webmodel::$model['transport']->fetch_row($query);
		
		if($type==0)
		{

			$query=MySQLClass::webtsys_query('select price from price_transport where weight>='.$total_weight.' and idtransport='.$idtransport.' order by price ASC limit 1');
				
			list($price_transport)=MySQLClass::webtsys_fetch_row($query);

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

				$query=MySQLClass::webtsys_query('select weight, price from price_transport order by price DESC limit 1');

				list($max_weight, $max_price)=MySQLClass::webtsys_fetch_row($query);

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
			
				$query=MySQLClass::webtsys_query('select price from price_transport where weight>='.$weight_last.' and idtransport='.$idtransport.' order by price ASC limit 1');
				
				list($price_transport)=MySQLClass::webtsys_fetch_row($query);

				settype($price_transport, 'double');
				
				$total_price_transport+=$price_transport;

				$num_packs=ceil($num_packs+1);

				return array($total_price_transport, $num_packs, $name);
				
			}
			
		}
		else
		{
		
			$query=MySQLClass::webtsys_query('select price from price_transport_price where min_price>='.$total_price.' and idtransport='.$idtransport.' order by min_price ASC limit 1');
			
			list($price_transport)=MySQLClass::webtsys_fetch_row($query);

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

				$query=MySQLClass::webtsys_query('select min_price, price from price_transport_price order by min_price DESC limit 1');

				list($max_min_price, $max_price)=MySQLClass::webtsys_fetch_row($query);
				
				return array($max_price, 1, $name);

				
			}
		
		}

	}
	
	public function clean_cart()
	{
	
		if(isset($_SESSION['idaddress']))
		{
		
			unset($_SESSION['idaddress']);
		
		}
		
		if(isset($_SESSION['idaddress']))
		{
		
			unset($_SESSION['idtransport']);
	
		}
		
		$this->create_new_token();
		
		//die;
	
	}
	
	public function num_items_cart()
	{

	
        Webmodel::$model['cart_shop']->conditions='where token="'.$this->token.'"';
	
		return Webmodel::$model['cart_shop']->select_count();
	
	}
	
	public function send_mail_order()
	{
	
		if(count(Webmodel::$model['user_shop']->forms)==0)
		{
	
			Webmodel::$model['user_shop']->create_form();
			
		}
		
		if(count(Webmodel::$model['address_transport']->forms)==0)
		{
			Webmodel::$model['address_transport']->create_form();
			
		}
	
        Webmodel::$model['order_shop']->conditions='where token="'.$this->token.'"';
	
		$arr_order_shop=Webmodel::$model['order_shop']->select_a_row_where();
		
		$arr_address=$arr_order_shop;
		
		Webmodel::$model['address_transport']->components['country_transport']->name_field_to_field ='name';
		
		$arr_address_transport=Webmodel::$model['address_transport']->select_a_row($arr_order_shop['address_transport_id']);
		
		//$arr_order_shop, $arr_address, $arr_address_transport, $iduser_shop
	
		Utils::load_libraries(array('utilities/set_admin_link', 'send_email'));
		
		$arr_address['country']=I18nField::show_formatted(serialize($arr_address['country']));
		
		$arr_address_transport['country_transport']=I18nField::show_formatted($arr_address_transport['country_transport']);
		
		//Prepare email
		
		$arr_user=Webmodel::$model['user_shop']->select_a_row($arr_address['iduser'], array('email'));

		$content_mail_user=load_view(array($arr_address, $arr_address_transport, $arr_order_shop, $this, 0), 'shop/mailcart');
		
		$content_mail_admin=load_view(array($arr_address, $arr_address_transport, $arr_order_shop, $this, 0), 'shop/mailadmincart');
		
		//If no send mail write a message with the reference, for send to mail shop...

		if( !send_mail($arr_user['email'], I18n::lang('shop', 'your_orders', 'Su pedido'), $content_mail_user, 'html') || !send_mail(PhangoVar::$portal_email, I18n::lang('shop', 'orders', 'Pedidos'), $content_mail_admin, 'html') )
		{

			echo '<p>'.I18n::lang('shop', 'error_cannot_send_email', 'Error: no puedo enviar el email').', '.I18n::lang('shop', 'use_this_id_for_contact_with_us', 'Este es su número de pedido, especifíquelo en el email que nos envíe.').': <strong>'.$arr_order_shop['IdOrder_shop'].'</strong></p>';

		}
	
	}
	
	public function cancel_order()
	{
	
        Webmodel::$model['order_shop']->conditions='where token="'.$this->token.'"';
	
		return Webmodel::$model['order_shop']->delete();
	
	}

}

function show_text_form($idcart_shop, $units)
{
	
	return new PhangoApp\PhaModels\Forms\TextForm('num_products['.$idcart_shop.']', $units);
	
}
	
function no_show_text_form($idcart_shop, $units)
{
	
	return $units;
	
}


?>