<?php

load_model('shop');
load_config('shop');
load_libraries(array('login'));
load_lang('shop');
load_libraries(array('config_shop', 'class_cart'), PhangoVar::$base_path.'modules/shop/libraries/');

PhangoVar::$model['user_shop']->create_form();

PhangoVar::$model['user_shop']->forms['country']->form='SelectModelForm';

PhangoVar::$model['user_shop']->forms['country']->parameters=array('country', '', '', 'country_shop', 'name', $where='order by `name_'.$_SESSION['language'].'` ASC');

PhangoVar::$model['address_transport']->create_form();

PhangoVar::$model['address_transport']->forms['country_transport']->form='SelectModelForm';

PhangoVar::$model['address_transport']->forms['country_transport']->parameters=array('country_transport', '', '', 'country_shop', 'name', $where='order by `name_'.$_SESSION['language'].'` ASC');

class CartSwitchClass extends ControllerSwitchClass 
{

	public $login;

	public function __construct()
	{
		
		//parent::__construct();
	
		$this->login=new LoginClass('user_shop', 'email', 'password', 'token_client', $arr_user_session=array(), $arr_user_insert=array());
		
		$this->login->url_insert=make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_get_user_save');
	
		$this->login->url_login=make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_login');
	
		$this->login->url_recovery=make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_recovery_password');
		
		$this->login->url_recovery_send=make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_recovery_password_send');
	
	}

	public function index()
	{
	
		/*$arr_block=select_view(array('shop'));
	
		//In cart , blocks showed are none always...

		$arr_block='/none';*/

		load_libraries(array('send_email'));
		
		$cart=new CartClass();
		
		ob_start();
		
		$cart->show_cart();
		
		$cont_index=ob_get_contents();
		
		ob_end_clean();
		
		echo load_view(array(PhangoVar::$lang['shop']['cart'], $cont_index), 'home');
	
	}
	
	public function update()
	{
	
		load_libraries(array('class_cart'), $this->base_path.'modules/shop/libraries/');
	
		//print_r($_POST);
		
		/*select_view(array('shop'));
		
		$arr_block='/none';*/
		
		$cart=new CartClass();
		
		settype($_POST['num_products'], 'array');
		
		foreach($_POST['num_products'] as $cart_id => $units)
		{
		
			settype($cart_id, 'integer');
			settype($units, 'integer');
			
			$cart->sum_product_to_cart($cart_id, $units);
		
		}
		
		//Redirect
	
		$this->redirect( make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', array()), PhangoVar::$lang['common']['redirect'], PhangoVar::$lang['common']['redirect'], PhangoVar::$lang['common']['press_here_redirecting']);
	
	}
	
	public function delete()
	{
	
		settype($_GET['IdCart_shop'], 'integer');
	
		load_libraries(array('class_cart'), $this->base_path.'modules/shop/libraries/');
		
		$cart=new CartClass();
		
		$cart->sum_product_to_cart($_GET['IdCart_shop'], 0);
	
		$this->redirect( make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', array()), PhangoVar::$lang['common']['redirect'], PhangoVar::$lang['common']['redirect'], PhangoVar::$lang['common']['press_here_redirecting']);
	
	}
	
	public function get_address()
	{
	
		//global $model, PhangoVar::$lang, PhangoVar::$base_url;
	
	
		/*$arr_block=select_view(array('shop'));
	
		$arr_block='/none';*/
	
		ob_start();
		
		if(!$this->login->check_login())
		{
					
			//echo load_view(array($this->login), 'shop/forms/registerform');
			
			$this->login->create_account_form();
			
			echo load_view(array($this->login), 'shop/forms/loginshopform');
	
		}
		else
		{
			
			$arr_user=PhangoVar::$model['user_shop']->select_a_row($this->login->session['IdUser_shop']);
		
			ModelForm::set_values_form($arr_user, PhangoVar::$model['user_shop']->forms, $show_error=1);
			
			echo load_view(array(), 'shop/forms/addressform');
			
		
		}
		
		$cont_index=ob_get_contents();
			
		ob_end_clean();
			
		echo load_view(array(PhangoVar::$lang['shop']['cart'], $cont_index), 'home');
	}
	
	public function save_address()
	{
		//
		
		if($this->login->check_login())
		{
			
			ob_start();
			
			PhangoVar::$model['user_shop']->components['email']->required=0;
			PhangoVar::$model['user_shop']->components['password']->required=0;
			PhangoVar::$model['user_shop']->components['token_client']->required=0;
			PhangoVar::$model['user_shop']->components['token_recovery']->required=0;
			
			//PhangoVar::$model['user_shop']->unset_components(ConfigShop::$arr_fields_address);
			
			PhangoVar::$model['user_shop']->arr_fields_updated=&ConfigShop::$arr_fields_address;
		
			if(PhangoVar::$model['user_shop']->update($_POST, 'where IdUser_shop='.$this->login->session['IdUser_shop']))
			{
			
				$url_return=make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_set_transport');
			
				$this->redirect($url_return, PhangoVar::$lang['common']['redirect'], PhangoVar::$lang['common']['success'], PhangoVar::$lang['common']	['press_here_redirecting']);
			
			}
			else
			{
			
				ModelForm::set_values_form($_POST, PhangoVar::$model['user_shop']->forms, $show_error=1);
			
				echo load_view(array(), 'shop/forms/addressform');
			
			}
			
			$cont_index=ob_get_contents();
				
			ob_end_clean();
			
			$this->load_theme(PhangoVar::$lang['shop']['cart'], $cont_index);
			
		}
	
	}
	
	public function get_user_save()
	{
	
		/*$arr_block=select_view(array('shop'));
	
		$arr_block='/none';*/
		
		ob_start();
		
		if($this->login->create_account())
		{
		
			$iduser=webtsys_insert_id();
		
			$this->login->automatic_login($iduser);
			
			load_libraries(array('redirect'));
			
			$url_return=make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'cat_get_address');
			
			simple_redirect($url_return, PhangoVar::$lang['common']['redirect'], PhangoVar::$lang['common']['success'], PhangoVar::$lang['common']['press_here_redirecting'], $content_view='content');
		
		}
		else
		{
		
			//echo load_view(array($this->login), 'shop/forms/registerform');
			$this->login->create_account_form();
		
		}
	
		$cont_index=ob_get_contents();
			
		ob_end_clean();
		
		echo load_view(array(PhangoVar::$lang['shop']['cart'], $cont_index,), 'home');
	
	}
	
	public function set_transport()
	{
	
		
		if($this->login->check_login())
		{
		
			if(ConfigShop::$config_shop['no_transport']==0)
			{
			
				ob_start();
				
				$arr_transport=PhangoVar::$model['address_transport']->select_to_array('where iduser='.$this->login->session['IdUser_shop'].' limit '.ConfigShop::$num_address_transport, array('IdAddress_transport', 'address_transport', 'region_transport'));
				
				echo load_view(array($arr_transport), 'shop/forms/transportform');
				
				$cont_index=ob_get_contents();
				
				ob_end_clean();
			
				$this->load_theme(PhangoVar::$lang['shop']['cart'], $cont_index);
			
			}
			else
			{
			
				$this->simple_redirect(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_checkout'));
			
			}
			
		}
	
	}
	
	public function save_transport_address()
	{
		
		if($this->login->check_login())
		{
			
			ob_start();
			
			PhangoVar::$model['address_transport']->arr_fields_updated=&ConfigShop::$arr_fields_transport;
			
			ConfigShop::$arr_fields_transport[]='iduser';
		
			$_POST['iduser']=$this->login->session['IdUser_shop'];
		
			if(PhangoVar::$model['address_transport']->select_count('where iduser='.$this->login->session['IdUser_shop'])<5)
			{
				if(PhangoVar::$model['address_transport']->insert($_POST))
				{
				
					$url_return=make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_set_transport');
				
					$this->redirect($url_return, PhangoVar::$lang['common']['redirect'], PhangoVar::$lang['common']['success'], PhangoVar::$lang['common']	['press_here_redirecting']);
				
				}
				else
				{
					
					ModelForm::set_values_form($_POST, PhangoVar::$model['address_transport']->forms, $show_error=1);
				
					echo load_view(array($arr_transport=array(), 1), 'shop/forms/transportform');
				
				}
				
			}
			else
			{
			
				echo '<p>'.PhangoVar::$lang['shop']['cannot_add_more_address'].'</p>';
			
			}
			
			$cont_index=ob_get_contents();
				
			ob_end_clean();
			
			$this->load_theme(PhangoVar::$lang['shop']['cart'], $cont_index);
			
		}
	
	}
	
	public function save_choose_address_transport()
	{
		
		if($this->login->check_login())
		{
			
			settype($_GET['idaddress'], 'integer');
			
			if(PhangoVar::$model['address_transport']->select_count('where iduser='.$this->login->session['IdUser_shop'].' and IdAddress_transport='.$_GET['idaddress'])==1)
			{
			
				$_SESSION['idaddress']=$_GET['idaddress'];
				
				//ob_start();
				
				//Now, select transport
				
				$this->simple_redirect($this->get_method_url('cart_set_method_transport'));
				
				/*$cont_index=ob_get_contents();
					
				ob_end_clean();
				
				$this->load_theme(PhangoVar::$lang['shop']['cart'], $cont_index);*/
				
				
			
			}
			else
			{
			
				$this->simple_redirect($this->get_method_url('index'));
			
			}
			
		}
	}
	
	public function set_method_transport()
	{
	
		if($this->login->check_login() && isset($_SESSION['idaddress']))
		{
	
			ob_start();
				
			//Now, load country_shop and zone_shop.
			
			/*
			$this->fields_related_model=array();
			//Representative field for related model...
			$this->name_field_to_field='';
			*/
			
			//PhangoVar::$model['address_transport']->components['country_transport']->name_field_to_field='name';
			PhangoVar::$model['address_transport']->components['country_transport']->fields_related_model=array('idzone_transport');
			
			$address_transport=PhangoVar::$model['address_transport']->select_a_row($_SESSION['idaddress'], array('country_transport'));
			
			settype($address_transport['country_transport'], 'integer');
			
			if($address_transport['country_transport']>0)
			{
			
				$cart=new CartClass(0);
				
				list($num_product, $total_price_product, $total_weight_product)=$cart->obtain_simple_cart();
			
				//Choose zone..
				
				//$zone_transport=PhangoVar::$model['zone_shop']->select_a_row('where IdZone_shop='.$address_transport['country_shop_idzone_transport'], array('));
				
				$arr_transport=PhangoVar::$model['transport']->select_to_array('where country='.$address_transport['country_shop_idzone_transport']);
			
				//print_r($arr_transport);
				echo load_view(array($arr_transport, $total_price_product, $total_weight_product, $cart), 'shop/forms/choosetransport'); 
			
			}
			
			//Load zone_transport
			
			//$zone_transport=PhangoVar::$model['zone_transport']->
			
			$cont_index=ob_get_contents();
				
			ob_end_clean();
			
			$this->load_theme(PhangoVar::$lang['shop']['cart'], $cont_index);
			
		}
	
	}
	
	public function save_choose_transport()
	{
	
		if($this->login->check_login())
		{
	
			
		
			settype($_GET['idtransport'], 'integer');
			
			if(PhangoVar::$model['transport']->select_count('where IdTransport='.$_GET['idtransport'])==1)
			{
			
				$_SESSION['idtransport']=$_GET['idtransport'];
				
				//ob_start();
				
				//Now, select transport
				
				$this->simple_redirect($this->get_method_url('cart_checkout', array()));
				
				/*$cont_index=ob_get_contents();
					
				ob_end_clean();
				
				$this->load_theme(PhangoVar::$lang['shop']['cart'], $cont_index);*/
				
				
			
			}
			else
			{
			
				$this->simple_redirect($this->get_method_url('cart', array()));
			
			}
			
		}
	
	}
	
	public function checkout()
	{
	
// 		
		ob_start();
		
		$yes_use_transport=1;
		
		$arr_address=array();
		
		$arr_address_transport=array();
		
		if($this->login->check_login())
		{
		
			if(ConfigShop::$config_shop['no_transport']==0)
			{
			
				if(!isset($_SESSION['idtransport']) && !isset($_SESSION['idaddress']))
				{
					
					$yes_use_transport=0;
				
				}
				else
				{

					
					$arr_address_transport=PhangoVar::$model['address_transport']->select_a_row($_SESSION['idaddress'], array(), 0);
					
					$arr_country=PhangoVar::$model['country_shop']->select_a_row($arr_address_transport['country_transport'], array('name'));
					
					$arr_address_transport['country_transport']=I18nField::show_formatted($arr_country['name']);
				
				}
			
			}
			
			$arr_address=PhangoVar::$model['user_shop']->select_a_row($this->login->session['IdUser_shop']);
					
			$arr_country=PhangoVar::$model['country_shop']->select_a_row($arr_address['country'], array('name'));
			
			$arr_address['country']=I18nField::show_formatted($arr_country['name']);
			
			if($yes_use_transport==1)
			{
				
				$cart=new CartClass();
				
				echo load_view(array($arr_address, $arr_address_transport, $cart), 'shop/checkoutcart');
			
			}
			else
			{
			
				$this->simple_redirect(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart'));
			
			}
			
			$cont_index=ob_get_contents();
						
			ob_end_clean();
			
			$this->load_theme(PhangoVar::$lang['shop']['cart'], $cont_index);
	
		}
		else
		{
		
			$this->simple_redirect(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart'));
		
		}
	
	}
	
	public function finish_checkout()
	{
		
		settype($_GET['op'], 'integer');
		
		$yes_use_transport=1;
		
		if($this->login->check_login())
		{
		
			if(ConfigShop::$config_shop['no_transport']==0)
			{
			
				if(!isset($_SESSION['idtransport']) && !isset($_SESSION['idaddress']))
				{
					
					$yes_use_transport=0;
				
				}
			
			}
			
			if($yes_use_transport==1)
			{
	
				ob_start();
				
				switch($_GET['op'])
				{
			
				default:
				
					$arr_payment=array(0);

					$query=PhangoVar::$model['payment_form']->select('', array(PhangoVar::$model['payment_form']->idmodel, 'name', 'price_payment'));
					
					while(list($idpayment, $name, $price)=webtsys_fetch_row($query))
					{
					
						$name=I18nField::show_formatted($name);

						if($price>0)
						{
							$price=MoneyField::currency_format( $price );
						}
						else
						{

							$price=PhangoVar::$lang['shop']['mode_payment_free_charge'];

						}

						$arr_payment[]=$name.' - '.$price;
						$arr_payment[]=$idpayment;

					}
			
					echo load_view(array($arr_payment), 'shop/forms/methodpayment');
				
				break;
				
				case 1:
				
					settype($_POST['payment_form'], 'integer');
					
					$cart=new CartClass();
					
					if($cart->num_items_cart()>0)
					{
					
						$cart->payment_gateway($this->login->session['IdUser_shop'], $_POST['payment_form']);
				
					}
					else
					{
					
						$this->simple_redirect(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart'));
					
					}
				
				break;
				
				}
			
				$cont_index=ob_get_contents();
							
				ob_end_clean();
				
				$this->load_theme(PhangoVar::$lang['shop']['cart'], $cont_index);
			}
		}
		else
		{
		
			$this->simple_redirect(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart'));
		
		}
	
	}
	
	public function login()
	{
	
		settype($_POST['email'], 'string');
		settype($_POST['password'], 'string');
		settype($_POST['no_expire_session'], 'integer');
		
		if($this->login->login($_POST['email'], $_POST['password'], $_POST['no_expire_session']))
		{
		
			//Set $_SESSION['IdUser_shop']
		
			load_libraries(array('redirect'));
			
			$url_return=make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_get_address');
			
			$this->redirect($url_return, PhangoVar::$lang['common']['redirect'], PhangoVar::$lang['common']['success'], PhangoVar::$lang['common']['press_here_redirecting']);
			
			//simple_redirect($url_return, PhangoVar::$lang['common']['redirect'], PhangoVar::$lang['common']['success'], PhangoVar::$lang['common']['press_here_redirecting'], $content_view='content');
		
		}
		else
		{
			
			ob_start();
		
			echo load_view(array($this->login), 'shop/forms/loginshopform');
			
			$cont_index=ob_get_contents();
			
			ob_end_clean();
			
			$this->load_theme(PhangoVar::$lang['shop']['cart'], $cont_index);
		
		}
	
	}
	
	public function recovery_password()
	{
			
		ob_start();
	
		$this->login->recovery_password_form();
		
		$cont_index=ob_get_contents();
			
		ob_end_clean();
		
		$this->load_theme(PhangoVar::$lang['shop']['cart'], $cont_index);
	
	}
	
	public function recovery_password_send()
	{
	
		ob_start();
	
		$this->login->recovery_password();
		
		$cont_index=ob_get_contents();
		
		ob_end_clean();
		
		$this->load_theme(PhangoVar::$lang['shop']['cart'], $cont_index);
	
	}
	
	public function finished()
	{
	
		ob_start();
	
		//$this->login->recovery_password();
		
		echo load_view(array( PhangoVar::$lang['shop']['your_orders'], PhangoVar::$lang['shop']['order_success_cart_clean'] ), 'content');
		
		$cont_index=ob_get_contents();
		
		ob_end_clean();
		
		$this->load_theme(PhangoVar::$lang['shop']['cart'], $cont_index);
	
	}

	
}

/*
function Cart()
{

	global $user_data, $model, $ip, PhangoVar::$lang, $config_data, PhangoVar::$base_path, PhangoVar::$base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ConfigShop::$config_shop, $arr_taxes, $arr_order_shop, PhangoVar::$language, PhangoVar::$lang_taxes, $webtsys_id;

	$arr_block='';

	$cont_index='';

	$cont_cart='';

	$arr_block=select_view(array('shop'));
	
	//In cart , blocks showed are none always...

	$arr_block='/none';

	load_lang('shop');
	load_model('shop');
	
	load_libraries(array('config_shop', 'class_cart'), PhangoVar::$base_path.'modules/shop/libraries/');
	load_libraries(array('send_email'));
	
	$cart=new CartClass();
	
	ob_start();
	
	$cart->show_cart();
	
	$cont_index=ob_get_contents();
	
	ob_end_clean();
	
	echo load_view(array(PhangoVar::$lang['shop']['cart'], $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ''), $arr_block);
	
	die;
	
	/*
	if(ConfigShop::$config_shop['ssl_url']==1)
	{
		
		if(!isset($_SERVER['HTTPS']))
		{
		
			//Redirect to https if cart isn't in https.
			
			unset($_GET['']);
			
			die(header('Location:'.make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', PhangoVar::$lang['shop']['cart'], $_GET ) ) );
			
		}
	
	}
	//If exists idtax and ConfigShop::$config_shop['yes_taxes']==0, we need show the taxes to the client in the cart, yes_taxes is valid only for show products.

	if(ConfigShop::$config_shop['yes_taxes']==0 && ConfigShop::$config_shop['idtax']>0)
	{

		ConfigShop::$config_shop['yes_taxes']=1;
	
	}
	
	//If no yes_transport don't need transport_fields in order shop
					
	if(ConfigShop::$config_shop['yes_transport']==0)
	{
	
		$arr_fields_trans=array('name_transport', 'last_name_transport', 'address_transport', 'zip_code_transport', 'city_transport', 'region_transport', 'country_transport', 'phone_transport', 'zone_transport', 'transport');
	
		foreach($arr_fields_trans as $name_trans)
		{
		
			PhangoVar::$model['order_shop']->components[$name_trans]->required=0;
		
		}
		
	
	}
	
	
	settype($_GET['op'], 'integer');

	$sha1_token=@sha1($_COOKIE['webtsys_shop']);

	$num_products=PhangoVar::$model['cart_shop']->select_count('where token=\''.$sha1_token.'\'', 'IdProduct');

	$query=PhangoVar::$model['order_shop']->select('where token=\''.$sha1_token.'\'', array(), 1);

	$arr_order_shop=webtsys_fetch_array($query);

	settype($arr_order_shop['IdOrder_shop'], 'integer');
	
	//Arrays for update models...

	$update_fields=array('name', 'last_name', 'enterprise_name', 'email', 'nif', 'address', 'zip_code', 'city', 'region', 'country', 'phone', 'fax');

	$update_transport=array('name_transport', 'last_name_transport', 'enterprise_name_transport', 'address_transport', 'zip_code_transport', 'city_transport', 'region_transport', 'country_transport', 'zone_transport', 'phone_transport');

	//If order is send, then go to payment gateway

	if($num_products>0 && ConfigShop::$config_shop['view_only_mode']==0)
	{

		if($arr_order_shop['IdOrder_shop']==0)
		{

			switch($_GET['op'])
			{

				default:

					echo '<p>'.PhangoVar::$lang['shop']['explain_cart_options'].'</p>';

					show_cart_simple($sha1_token, 1, 1);

				break;

				case 1:

					//Show form order

					$post_user=array();
					$post_transport=array();
					
					settype($_GET['go_buy'], 'integer');
					
					$url_login=make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'buy_products', array('op' => 1, 'go_buy' => 1));
					
					if($user_data['IdUser']<=0 && $_GET['go_buy']==0)
					{
					
						echo '<p>'.PhangoVar::$lang['shop']['explain_buying_without_register'].'</p>';
					
						echo '<p>'.PhangoVar::$lang['shop']['login_shop'].', <a href="'.make_fancy_url(PhangoVar::$base_url, 'user',
						'index', 'login', array('register_page' => urlencode_redirect($url_login)) ).'">'.PhangoVar::$lang['shop']['click_here'].'</a></p>';
						
						echo '<p>'.PhangoVar::$lang['shop']['register_shop_or_buying'].', <a href="'.$url_login.'">'.PhangoVar::$lang['shop']['click_here'].'</a></p>';
					
					}
					else if($user_data['IdUser']>0 && $_GET['go_buy']==0)
					{
					
						die(header('Location: '.$url_login));
					
					}
					else if($_GET['go_buy']==1)
					{

						if($user_data['IdUser']>0)
						{

							$post_user=&$user_data;

							$query=PhangoVar::$model['dir_transport']->select('where iduser='.$user_data['IdUser'], array(), 1);

							$post_transport=webtsys_fetch_array($query);

							settype($post_transport, 'array');

						}

						$query=PhangoVar::$model['country_user_shop']->select('where IdUser='.$user_data['IdUser'], array('idcountry'));

						list($idcountry_user)=webtsys_fetch_row($query);

						$post_user['country']=$idcountry_user;

						form_order($sha1_token, $post_user, $post_transport, 0);
						
						
					}

				break;

				case 2:

					//Choose payment and transport type.

					PhangoVar::$model['order_shop']->components['token']->required=0;
					PhangoVar::$model['order_shop']->components['transport']->required=0;
					PhangoVar::$model['order_shop']->components['payment_form']->required=0;
					
					$_POST['zone_transport']=0;

					settype($_POST['country_transport'], 'integer');

					$query=PhangoVar::$model['country_shop']->select('where IdCountry_shop='.$_POST['country_transport'], array(), 1);

					$arr_zone_shop=webtsys_fetch_array($query);

					settype($arr_zone_shop['IdCountry_shop'], 'integer');
					settype($arr_zone_shop['idzone_taxes'], 'integer');
					settype($arr_zone_shop['idzone_transport'], 'integer');

					if($arr_zone_shop['idzone_transport']==0)
					{

						$query=PhangoVar::$model['zone_shop']->select('where type=0 and other_countries=1', array('IdZone_shop'));

						list($arr_zone_shop['idzone_transport'])=webtsys_fetch_row($query);
						
					}

					$_POST['zone_transport']=$arr_zone_shop['idzone_transport'];

					PhangoVar::$model['user']->forms['password']->type->required=1;
									
					PhangoVar::$model['user']->check_all($_POST);

					//echo $_POST['zone_transport'];
					
					
					
			
			
		
						
						?>
						
						<?php
						
						//break;
					
					//}
					

					if(PhangoVar::$model['order_shop']->check_all($_POST))
					{
					
						//Prepare post...
						
						$post=array();

						foreach($update_fields as $field)
						{

							$post[$field]=$_POST[$field];

						}
						
						$post_transport=array();
						
						if(ConfigShop::$config_shop['yes_transport'])
						{

							foreach($update_transport as $field)
							{

								$post_transport[$field]=$_POST[$field];

							}
							
						}

						//Obtain real name for country

						$real_country=$post['country'];

						$query=PhangoVar::$model['country_shop']->select('where IdCountry_shop='.$real_country, array('name'));

						list($country_name)=webtsys_fetch_row($query);

						$post['country']=I18nField::show_formatted($country_name);

						//Save order and register to user in first time...
	
						if($user_data['IdUser']==0)
						{

							load_libraries(array('timestamp_zone', 'generate_admin_ng'));
							load_libraries(array('func_users'), PhangoVar::$base_path.'modules/user/libraries/');

							$arr_fields_form=array_keys($post);

							$arr_fields_form[]='private_nick';
							$arr_fields_form[]='password';
							$arr_fields_form[]='rank';

							$post['private_nick']=$_POST['private_nick'];
							$post['password']=$_POST['password'];
							$post['repeat_password']=$_POST['repeat_password'];
							$post['rank']=1;
							
							if(!UserInsertModel('user', $arr_fields_form, $post))
							{

								$post_user=&$_POST;
								$post_transport=&$_POST;

								if(PhangoVar::$model['user']->forms['email']->std_error!='')
								{

									PhangoVar::$model['order_shop']->forms['email']->std_error=PhangoVar::$model['user']->forms['email']->std_error;

								}

								form_order($sha1_token, $post_user, $post_transport, 1);

								break;

							}
							else
							{

								//Login, obtain IdUser from insert and send via post...

								//update user

								global $prefix_key;

								$user_data['IdUser']=webtsys_insert_id();
								
								//$user_data['key_csrf']=$prefix_key.'_'.$webtsys_id;
								
								setlogin($_POST['email'], $_POST['password'], '', 0, 0);

								PhangoVar::$model['country_user_shop']->insert( array('idcountry' => $real_country, 'iduser' => $user_data['IdUser']) );

								$post_transport['iduser']=$user_data['IdUser'];

								PhangoVar::$model['dir_transport']->insert($post_transport);
								
								//Send email for registering user...

								$portal_name=html_entity_decode($config_data['portal_name']);

								$topic_email=PhangoVar::$lang['user']['text_confirm'];
							
								$body_email=load_view(array($_POST['private_nick'], $_POST['email'], form_text($_POST['password']) ), 'common/user/mailviews/mailregister');
								
								if( !send_mail($_POST['email'], $topic_email, $body_email, 'html') )
								{
					
									echo "<p align=\"center\">".PhangoVar::$lang['user']['error_email']."</p>";

								}

							}

						}
						else
						{

							//Update user and dir_transport

							PhangoVar::$model['user']->components['private_nick']->required=0;
							PhangoVar::$model['user']->components['password']->required=0;
							
							$result_update=PhangoVar::$model['user']->update($post, 'where IdUser='.$user_data['IdUser']);

							//Create backup for country for user.

							$num_count_country=PhangoVar::$model['country_user_shop']->select_count('where IdUser='.$user_data['IdUser'], 'IdCountry_user_shop');

							if($num_count_country>0)
							{

								PhangoVar::$model['country_user_shop']->update(array('idcountry' => $real_country), 'where iduser='.$user_data['IdUser']);

							}
							else
							{

								PhangoVar::$model['country_user_shop']->insert( array('idcountry' => $real_country, 'iduser' => $user_data['IdUser']) );

							}

							if(ConfigShop::$config_shop['yes_transport'])
							{
							
								$post['country']=$real_country;

								$num_dir_transport=PhangoVar::$model['dir_transport']->select_count('where iduser='.$user_data['IdUser'], 'IdDir_transport');

								settype($num_dir_transport, 'integer');
								
								if($num_dir_transport>0)
								{

									PhangoVar::$model['dir_transport']->update($post_transport, 'where iduser='.$user_data['IdUser']);

								}
								else
								{

									$post_transport['iduser']=$user_data['IdUser'];

									PhangoVar::$model['dir_transport']->insert($post_transport);

								}
								
							}

						}

						//Choose payment and transport...
						?>	
						<form method="post" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'checkout', array('op' => 3));?>">
						<?php set_csrf_key(); ?>
						<h2><?php echo PhangoVar::$lang['shop']['choose_more_options']; ?></h2>
						<p><?php echo PhangoVar::$lang['shop']['explain_payment_type_transport_type']; ?></p>
						<h3><?php echo PhangoVar::$lang['shop']['payment_type']; ?></h3>
						<?php
				
						$arr_payment=array(0);
				
						$query=PhangoVar::$model['payment_form']->select('', array(PhangoVar::$model['payment_form']->idmodel, 'name', 'price_payment'));
						
						while(list($idpayment, $name, $price)=webtsys_fetch_row($query))
						{
						
							$name=I18nField::show_formatted($name);
				
							if($price>0)
							{
								$price=MoneyField::currency_format( $price );
							}
							else
							{

								$price=PhangoVar::$lang['shop']['mode_payment_free_charge'];

							}

							$arr_payment[]=$name.' - '.$price;
							$arr_payment[]=$idpayment;
				
						}
				
						echo SelectForm('payment_form', '', $arr_payment );


						if(ConfigShop::$config_shop['yes_transport']==1)
						{
							?>
							<h3><?php echo PhangoVar::$lang['shop']['transport']; ?></h3>
							<?php

							settype($_POST['zone_transport'], 'integer');
					
							$arr_transport=array('');
							
							$query=PhangoVar::$model['transport']->select('where IdTransport>0 and country='.$_POST['zone_transport'], array(PhangoVar::$model['transport']->idmodel, 'name'));
							
							while(list($idtransport, $name)=webtsys_fetch_row($query))
							{
					
								$arr_transport[]=$name;
								$arr_transport[]=$idtransport;
					
							}
							
							if(count($arr_transport)>1)
							{
								echo SelectForm('transport', '', $arr_transport );

							}
							else
							{

								echo '<p>'.PhangoVar::$lang['shop']['error_in_country_no_exists_transport'].'</p>';

							}

						}

						?>
						<?php echo HiddenForm('observations', '', str_replace('"', '&quot;', $_POST['observations'])); ?>
						<p><input type="submit" value="<?php echo PhangoVar::$lang['common']['send']; ?>"/></p>
						<p><a href="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'checkout', array('op' => 1) ); ?>"><?php echo PhangoVar::$lang['common']['go_back']; ?></a>
						</form>
						<?php

					}
					else
					{


						$post_user=&$_POST;
						$post_transport=&$_POST;

						form_order($sha1_token, $post_user, $post_transport, 1);

					}

				break;

				case 3:

					//Now insert order shop and redirect to payment...
		
					if($user_data['IdUser']==0)
					{

						echo '<p><span class="error">'.PhangoVar::$lang['shop']['error_cannot_access_to_next_step'].'</span></p>';

					}
					else
					{
						
						$post=array();

						foreach($update_fields as $field)
						{

							$post[$field]=$user_data[$field];

						}

						settype($_POST['payment_form'], 'integer');
						settype($_POST['transport'], 'integer');

						$post['token']=$sha1_token;
						$post['iduser']=$user_data['IdUser'];
						$post['payment_form']=$_POST['payment_form'];
						$post['observations']=$_POST['observations'];

						$query=PhangoVar::$model['country_user_shop']->select('where IdUser='.$user_data['IdUser'], array('idcountry'));

						list($idcountry_user)=webtsys_fetch_row($query);

						$post['country']=$idcountry_user;

						//Obtain products_list..

						//Added taxes
						$sum_tax=0;
						//Added total_price
						$total_price=0;
						//Total weight of all products...
						$total_weight=0;

						$arr_products=array();

						PhangoVar::$model['cart_shop']->components['idproduct']->fields_related_model=array('referer', 'title', 'price', 'special_offer', 'weight', 'extra_options');

						$query=PhangoVar::$model['cart_shop']->select('where token="'.$sha1_token.'"', array('IdCart_shop', 'idproduct'), 0);

						while($arr_product=webtsys_fetch_array($query))
						{
							//print_r($arr_product);
							settype($arr_products[$arr_product['idproduct']]['units'], 'integer');
							
							$price=$arr_product['product_price'];
							
							$arr_products[$arr_product['idproduct']]['units']++;
							
							$sum_tax=calculate_taxes(ConfigShop::$config_shop['idtax'], $price);
		
							$total_price+=($price+$sum_tax);
								
							$total_weight+=$arr_product['product_weight'];

						}
						
						if(ConfigShop::$config_shop['yes_transport']==1)
						{

							$post['transport']=$_POST['transport'];
							
							$query=PhangoVar::$model['dir_transport']->select('where iduser='.$user_data['IdUser'], array(), 1);

							$post_transport=webtsys_fetch_array($query);

							settype($post_transport, 'array');

							$post=array_merge($post, $post_transport);

							//Now obtain prices for the transport...
							
							list($price_total_transport, $num_packs)=obtain_transport_price($total_weight, $total_price, $post['transport']);

						}

						$post['date_order']=TODAY;
						
						$query=PhangoVar::$model['order_shop']->insert($post);
				
						if(PhangoVar::$model['order_shop']->std_error=='')
						{
							ob_end_clean();

							load_libraries(array('redirect'));
							die( redirect_webtsys( make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'checkout', array()), PhangoVar::$lang['common']['redirect'], PhangoVar::$lang['shop']['success_buy_go_to_payment'], PhangoVar::$lang['common']['press_here_redirecting'] , $arr_block) );

						}
						else
						{

							echo PhangoVar::$model['order_shop']->std_error;

						}

					}

				break;

				case 4:


					load_libraries(array('table_config'));

					echo '<h3>'.PhangoVar::$lang['shop']['modify_product_options'].'</h3>';

					settype($_GET['IdProduct'], 'integer');

					//Load product...

					$query=PhangoVar::$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('title', 'referer', 'extra_options'));

					list($title_product, $ref_product, $extra_options)=webtsys_fetch_row($query);

					$title_product=PhangoVar::$model['product']->components['title']->show_formatted($title_product);
					
					if($extra_options!='')
					{
						echo '<p>'.PhangoVar::$lang['shop']['explain_delete_options'].'</p>';
						?>
						<form method="post" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'buy', 'modify_product_options', array('IdProduct' => $_GET['IdProduct'], 'delete_products' => 1)); ?>">
						<?php
						set_csrf_key();
						$query=PhangoVar::$model['cart_shop']->select('where cart_shop.idproduct='.$_GET['IdProduct'].' and token="'.$sha1_token.'"', array('IdCart_shop', 'details'), 1);

						up_table_config(array(PhangoVar::$lang['shop']['referer'], PhangoVar::$lang['common']['name'], PhangoVar::$lang['shop']['option_selected'], PhangoVar::$lang['common']['options'], PhangoVar::$lang['shop']['select_product']));

						while($arr_product=webtsys_fetch_array($query))
						{
							$details=unserialize($arr_product['details']);
							
							$options_url='<a href="'.make_fancy_url(PhangoVar::$base_url, 'shop', 'buy', 'modify_product_options', array('IdCart_shop' => $arr_product['IdCart_shop'])).'">'.PhangoVar::$lang['shop']['modify_product_options'].'</a>';

							$check_product=CheckBoxForm('idproduct['.$arr_product['IdCart_shop'].']', '', '');

							middle_table_config(array($ref_product, $title_product, $details[0], $options_url, $check_product));

						}

						down_table_config();

						?>
						<p><input type="submit" value="<?php echo PhangoVar::$lang['shop']['delete_products_selected']; ?>"/></p>
						</form>
						<?php

					}
					else
					{

						echo '<p>'.PhangoVar::$lang['shop']['explain_delete_options_form'].'</p>';

						$num_products=PhangoVar::$model['cart_shop']->select_count('where cart_shop.idproduct='.$_GET['IdProduct'].' and token="'.$sha1_token.'"', 'IdProduct');

						$text_num_products=TextForm('num_products', 'units', $num_products);

						?>
						<form method="post" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'buy', 'modify_product_options', array('IdProduct' => $_GET['IdProduct'], 'add_more_units' => 1)); ?>">
						<?php
						set_csrf_key();

						up_table_config(array(PhangoVar::$lang['shop']['referer'], PhangoVar::$lang['common']['name'], PhangoVar::$lang['shop']['num_products']));

						middle_table_config(array($ref_product, $title_product, $text_num_products));

						down_table_config();

						?>
						<p><input type="submit" value="<?php echo PhangoVar::$lang['common']['send']; ?>" />
						</form>
						<?php

					}

					echo '<p><a href="'.make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'show_cart', array()).'">'.PhangoVar::$lang['common']['go_back'].'</a></p>';

				break;

			}
		
		}
		else
		{

			settype($arr_order_shop['country'], 'integer');

			$query=webtsys_query('select country_shop.idzone_taxes, zone_shop.IdZone_shop, taxes.IdTaxes from country_shop, zone_shop, taxes where country_shop.idzone_taxes=zone_shop.IdZone_shop and taxes.country=zone_shop.IdZone_shop and country_shop.IdCountry_shop='.$arr_order_shop['country']);

			list($idzone_taxes, $idzone_shop, $idtax)=webtsys_fetch_row($query);

			settype($idtax, 'integer');

			ConfigShop::$config_shop['idtax']=$idtax;
			
			if(ConfigShop::$config_shop['idtax']>0)
			{

				echo '<p><strong>'.PhangoVar::$lang['shop']['you_choose_a_country_that_have_taxes_about_this_products'].'</strong></p>';

				ConfigShop::$config_shop['yes_taxes']=1;

			}

			switch($_GET['op'])
			{

			default:

				//Here put prices, taxes, payment and transport in order_shop.

				if($arr_order_shop['make_payment']==0)
				{
		
					echo '<h3>'.PhangoVar::$lang['shop']['order_submited_show_order_and_prices'].'</h3>';

					list($total_price, $discount_name, $discount_principal, $discount_taxes, $name_transport, $price_total_transport_original, $discount_transport, $name_payment, $price_payment_original, $discount_payment)=show_total_prices($sha1_token);
					
					$tax_name=PhangoVar::$lang_taxes[ConfigShop::$config_shop['idtax']];
					$tax_percent=$arr_taxes[ConfigShop::$config_shop['idtax']];

					$post['discount']=$discount_name;
					$post['discount_percent']=$discount_principal;

					$post['tax']=$tax_name;
					$post['tax_percent']=$tax_percent;
					$post['tax_discount_percent']=$discount_taxes;

					$post['name_enterprise_transport']=$name_transport;
					$post['price_transport']=$price_total_transport_original;
					$post['transport_discount_percent']=$discount_transport;

					$post['name_payment']=$name_payment;
					$post['price_payment']=$price_payment_original;
					$post['payment_discount_percent']=$discount_payment;
					$post['total_price']=$total_price;
			
					PhangoVar::$model['order_shop']->reset_require();

					//Update order shop with values...

					PhangoVar::$model['order_shop']->update($post, 'where token="'.$sha1_token.'"');

					echo '<hr />';

					echo '<h3>'.PhangoVar::$lang['shop']['send_order_and_checkout'].'</h3>';
					
					echo '<p>'.PhangoVar::$lang['shop']['explain_send_order_and_checkout'].'</p>';

					?>
					<form method="post" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'checkout', array('op' => 1) ); ?>">
					<p><input type="submit" value="<?php echo PhangoVar::$lang['shop']['checkout_order']; ?>" /></p>
					</form>
					<form method="post" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'checkout', array('op' => 2) ); ?>">
					<p><input type="submit" value="<?php echo PhangoVar::$lang['shop']['cancel_order']; ?>" /></p>
					</form>
					<?php

				}
				else
				{

					header('Location: '.make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'checkout', array('op' => 1)));
					die;

				}

			break;

			case 1:

			if($arr_order_shop['make_payment']==0)
			{

				$query=webtsys_query('select order_shop.payment_form, payment_form.name, payment_form.code from order_shop, payment_form where order_shop.payment_form=payment_form.IdPayment_form and order_shop.token="'.$sha1_token.'"');

				list($idpayment_form, $name_payment, $code_payment)=webtsys_fetch_row($query);
				
				$name_payment=I18nField::show_formatted($name_payment);
				
				if(!include(PhangoVar::$base_path.'modules/shop/payment/'.basename($code_payment)))
				{
			
					echo PhangoVar::$lang['shop']['error_no_proccess_payment_send_email'].': '.$config_data['portal_email'];

				}

			}
			else
			{

				load_libraries(array('forms/textplainform'));

				//Now initialize the text from mail, first address_billing

				$num_bill=calculate_num_bill($arr_order_shop['IdOrder_shop']);

				echo '<p><strong>'.PhangoVar::$lang['shop']['referer'].': '.$num_bill.'</strong></p>';

				echo '<h2>'.PhangoVar::$lang['shop']['address_billing'].'</h2>';
		
				PhangoVar::$model['order_shop']->reset_require();

				foreach(PhangoVar::$model['order_shop']->forms as $key_form => $form)
				{

					PhangoVar::$model['order_shop']->forms[$key_form]->form='TextPlainForm';

				}

				SetValuesForm($arr_order_shop, PhangoVar::$model['order_shop']->forms, $show_error=0);

				$query=PhangoVar::$model['country_shop']->select('where IdCountry_shop='.$arr_order_shop['country'], array('name'));

				list($name_country)=webtsys_fetch_row($query);
	
				$name_country=PhangoVar::$model['country_shop']->components['name']->show_formatted($name_country);

				$query=PhangoVar::$model['country_shop']->select('where IdCountry_shop='.$arr_order_shop['country_transport'],  array('name'));

				list($name_country_transport)=webtsys_fetch_row($query);

				$name_country_transport=PhangoVar::$model['country_shop']->components['name']->show_formatted($name_country_transport);

				PhangoVar::$model['order_shop']->forms['country']->SetForm($name_country);
				PhangoVar::$model['order_shop']->forms['country_transport']->SetForm($name_country_transport);

				echo load_view(array(PhangoVar::$model['order_shop']->forms, array('name', 'last_name', 'email', 'nif', 'address', 'zip_code', 'city', 'region', 'country', 'phone', 'fax'), ''), 'common/forms/modelform');

				if(ConfigShop::$config_shop['yes_transport']==1)
				{

					echo '<h3>'.PhangoVar::$lang['shop']['address_transport'].'</h3>';

					echo load_view(array(PhangoVar::$model['order_shop']->forms, array('name_transport', 'last_name_transport', 'address_transport', 'zip_code_transport', 'city_transport', 'region_transport', 'country_transport', 'phone_transport'), ''), 'common/forms/modelform');

				}

				echo '<h2>'.PhangoVar::$lang['shop']['order'].'</h2>';
				
				show_total_prices($sha1_token);

				echo '<h3>'.PhangoVar::$lang['shop']['order_products_options'].'</h3>';

				$z=0;

				//Select id from buy products..

				$arr_idproduct=array();

				$query=webtsys_query('select DISTINCT idproduct from cart_shop where token="'.$sha1_token.'"');

				while(list($idproduct)=webtsys_fetch_row($query))
				{

					$arr_idproduct[]=$idproduct;

				}
				
				$arr_description_type=array();

				PhangoVar::$model['product_option']->components['idtype']->fields_related_model=array('title', 'description');
				PhangoVar::$model['product_option']->components['idproduct']->fields_related_model=array('title');
				
				$query=PhangoVar::$model['product_option']->select('where product_option.idproduct IN ('.implode(', ', $arr_idproduct).')', array('idtype', 'idproduct'));

				while($arr_product_options=webtsys_fetch_array($query))
				{
					$arr_description_type[$arr_product_options['idproduct']][]=$arr_product_options['type_product_option_description'];

				}

				$query=webtsys_query('select cart_shop.IdCart_shop, cart_shop.idproduct, cart_shop.details, product.title from cart_shop, product where cart_shop.token="'.$sha1_token.'" and cart_shop.idproduct=product.IdProduct and product.extra_options!=""');

				while($arr_options=webtsys_fetch_array($query))
				{
					
					$options=@unserialize($arr_options['details']);

					settype($options, 'array');

					echo '<p><b>'.I18nField::show_formatted($arr_options['title']).'</b>: </p>';

					foreach($options as $key => $option)
					{

						echo I18nField::show_formatted($arr_description_type[$arr_options['idproduct']][$key]).': '.$option.'<br />';

					}

					$z++;
		

				}

				if($z==0)
				{

					echo '<p>'.PhangoVar::$lang['shop']['order_without_options'].'</p>';

				}

				$content_mail=ob_get_contents();

				ob_clean();

				$portal_name=html_entity_decode($config_data['portal_name']);

				$text_explain_user='<h3>'.PhangoVar::$lang['shop']['your_orders']." - ".$portal_name.'</h3>';
				$text_explain_user.=PhangoVar::$lang['shop']['explain_petition'].'<p>'.PhangoVar::$lang['shop']['if_error_send_email_to'].': '.$config_data['portal_email'].'</p>';

				$query=PhangoVar::$model['module']->select('where name="shop"', array('IdModule'));

				list($idmodule)=webtsys_fetch_row($query);
				
				$send_email_admin='<h3>'.PhangoVar::$lang['shop']['url_bill_for_admin'].'</h3><p><a href="'.set_admin_link( 'obtain_bill', array('IdModule' => $idmodule, 'op' => 16, 'IdOrder_shop' => $arr_order_shop['IdOrder_shop'])).'">'.PhangoVar::$lang['shop']['click_here_for_download_bill'].'</a></p>';

				//If no send mail write a message with the reference, for send to mail shop...

				if( !send_mail($user_data['email'], PhangoVar::$lang['shop']['your_orders']." - ".$portal_name, $text_explain_user.$content_mail, 'html') || !send_mail($config_data['portal_email'], PhangoVar::$lang['shop']['orders']." - ".$portal_name, '<h1>'.PhangoVar::$lang['shop']['new_order'].'</h1><p>'.PhangoVar::$lang['shop']['explain_new_order'].'</p>'.$content_mail.$send_email_admin, 'html') )
				{

					echo '<p>'.PhangoVar::$lang['shop']['error_cannot_send_email'].', '.PhangoVar::$lang['shop']['use_this_id_for_contact_with_us'].': <strong>'.$arr_order_shop['IdOrder_shop'].'</strong></p>';

				}

				//Sum 1 to num_sold to solded products.

				$query=webtsys_query('update product set num_sold=num_sold+1 where IdProduct IN ('.implode(', ', $arr_idproduct).')');
				
				//Now proccess the plugins for last cart process. For example, for stores that sell files. The plugin send an tmp link for download the file, and type a text.
				
				$arr_plugin=array();
		
				$query=PhangoVar::$model['plugin_shop']->select('where element="cart" order by position ASC', array('plugin'));
				
				while(list($plugin)=webtsys_fetch_row($query))
				{
					
					load_libraries(array($plugin), PhangoVar::$base_path.'modules/shop/plugins/cart/');
				
					$func_plugin=ucfirst($plugin).'Show';
					
					//Execute plugin. 
					
					if(function_exists($func_plugin))
					{
					
						$func_plugin($sha1_token);
						
					}
				
				}

				setcookie ( "webtsys_shop", FALSE, 0, $cookie_path);

				echo PhangoVar::$lang['shop']['order_success_cart_clean'];

			}

			break;

			case 2:

				//Cancel order.

				//Delete order_shop
				ob_clean();
				$query=PhangoVar::$model['order_shop']->delete('where token=\''.$sha1_token.'\'');

				load_libraries(array('redirect'));
				die( redirect_webtsys( make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'cart', array()), PhangoVar::$lang['common']['redirect'], PhangoVar::$lang['shop']['cancelling_order'], PhangoVar::$lang['common']['press_here_redirecting'] , $arr_block) );

			break;

			}

		}

	}
	else
	{

		echo PhangoVar::$lang['shop']['empty_cart'];

	}

	$cont_cart=ob_get_contents();

	ob_clean();

	echo load_view(array(PhangoVar::$lang['shop']['cart'], $cont_cart), 'content');

	$cont_index=ob_get_contents();

	ob_end_clean();

	echo load_view(array(ConfigShop::$config_shop['title_shop'], $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ''), $arr_block);

}

function show_cart_simple($token, $show_button_buy=1, $type_cart=0)
{

	global PhangoVar::$base_url, PhangoVar::$lang, $model, $arr_taxes, ConfigShop::$config_shop, $sha1_token;

	load_libraries(array('table_config'));

	if($type_cart==1)
	{

		//Functions for list_cart

		function head_list_cart($options=1)
		{

			global PhangoVar::$lang, PhangoVar::$base_url;

			?>
			<form method="post" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'deleteproduct', 'deleteproduct', array()); ?>">
			
			<?php
			set_csrf_key();

			$fields=array(PhangoVar::$lang['shop']['referer'], PhangoVar::$lang['common']['name'], PhangoVar::$lang['shop']['num_products'], PhangoVar::$lang['shop']['price']);

			$fields=name_field_taxes($fields);

			if($options==1)
			{

				$fields[]=PhangoVar::$lang['common']['options'];

			}

			$fields[]=PhangoVar::$lang['shop']['select_product'];

			up_table_config( $fields );

		}

		function middle_list_cart($idproduct, $arr_id, $referer, $title, $price_without_tax, $price, $offer, $idtax, $sum_tax, $options=1, $extra_options='')
		{

			global PhangoVar::$base_url, $arr_taxes, PhangoVar::$lang_taxes, PhangoVar::$lang, $model;

			$price*=$arr_id[$idproduct];
			$price_without_tax*=$arr_id[$idproduct];

			$title=PhangoVar::$model['product']->components['title']->show_formatted($title);

			$change_num_products=$arr_id[$idproduct]; //TextForm('change_num_products', 'units', $arr_id[$idproduct]);

			$fields=array($referer, $title, $change_num_products, MoneyField::currency_format($price_without_tax));

			$fields=add_field_taxes($fields, $price, $idtax, $sum_tax*$arr_id[$idproduct]);

				
			$url_modify_options=make_fancy_url( PhangoVar::$base_url, 'shop', 'cart', 'modify_product_options', array('op' => 4, 'IdProduct' => $idproduct) );

			$fields[]='<a href="'.$url_modify_options.'">'.PhangoVar::$lang['shop']['modify_options'].'</a>';
			$fields[]=CheckBoxForm('idproduct['.$idproduct.']', '', '');
				//$fields[2]=$arr_id[$idproduct];


			middle_table_config($fields);

		}

		function bottom_list_cart()
		{

			global PhangoVar::$lang;

			down_table_config(array());
			?>
			<p><input type="submit" value="<?php echo PhangoVar::$lang['shop']['delete_products_selected']; ?>"/></p>
			</form>
			<?php

		}

	}
	else
	{

		function head_list_cart()
		{

			return '';

		}

		function middle_list_cart($idproduct, $arr_id, $referer, $title, $price_without_tax, $price, $offer, $idtax)
		{

			global PhangoVar::$base_url, $model;

			$price*=$arr_id[$idproduct];
			$price_without_tax*=$arr_id[$idproduct];

			$title=PhangoVar::$model['product']->components['title']->show_formatted($title);

			echo '<a href="'.make_fancy_url(PhangoVar::$base_url, 'shop', 'viewproduct', 'viewproduct', array('IdProduct' => $idproduct) ).'"><strong>'.$arr_id[$idproduct].' x '.$referer.' '.$title."</strong></a> - ".MoneyField::currency_format($price_without_tax)."<br />\n";

		}

		function bottom_list_cart()
		{

			return '';

		}

	}

	if(!isset($_COOKIE['webtsys_shop']))
	{

		echo PhangoVar::$lang['shop']['cart_empty'];

	}
	else
	{
		
		$arr_id=array(0);
		$arr_price=array();

		$query=webtsys_query('select idproduct,price_product from cart_shop where token="'.$token.'"');
		
		while(list($idproduct, $price_in_cart)=webtsys_fetch_row($query))
		{
			
			settype($arr_id[$idproduct], 'integer');

			$arr_id[$idproduct]++;
			$arr_price[$idproduct]=$price_in_cart;

		}
		
		$total_sum=0;

		$price_with_tax=0;

		$total_sum_tax=0;

		$total_weight=0;

		$arr_lang_taxes=array();

		$idtax=ConfigShop::$config_shop['idtax'];

		head_list_cart();
		
		$query=PhangoVar::$model['product']->select('where IdProduct IN ('.implode(',', array_keys($arr_id) ).')', array('IdProduct', 'referer', 'title', 'price', 'special_offer', 'weight', 'extra_options'), 1);
		
		while( list($idproduct, $referer, $title, $price, $offer, $weight, $extra_options)=webtsys_fetch_row($query) )
		{
		
			$price=$arr_price[$idproduct];

			if($offer>0)
			{

				$price=$offer;

			}
			
			$percent_tax=add_taxes($idtax);
			
			$sum_tax=$price*($percent_tax/100);
			
			$price_without_tax=$price;
			
			$price+=$sum_tax;

			$total_sum_tax+=($sum_tax*$arr_id[$idproduct]);
			$title.=' ';

			middle_list_cart($idproduct, $arr_id, $referer, $title, $price_without_tax, $price, $offer, $idtax, $sum_tax, 1, $extra_options);

			$total_sum+=$price_without_tax*$arr_id[$idproduct];

			$total_weight+=$weight*$arr_id[$idproduct];
			

		}
		
		bottom_list_cart();
		
		$final_price=$total_sum;
		
		//$total_sum=number_format($total_sum, 2);
		
		if($total_sum>0)
		{

			//Here put prices with taxes...

			echo '<p><strong>'.PhangoVar::$lang['shop']['total'].': </strong>'.MoneyField::currency_format($total_sum).' </p>';
			
			//Here the discounts...
			
			//list($yes_discount, $total_sum_final, $text_discount, $discount_taxes, $discount_transport, $discount_payment)=obtain_discounts($final_price, $price_total_transport, $sha1_token);
			
			//$final_price=$total_sum_final;
			
			//Obtain taxes
			
			list($text_price_total, $text_taxes_total, $sum_taxes_final)=add_text_taxes_final($final_price, $idtax);
			
			//PhangoVar::$lang['shop']['total_price_with_all_payments_and_discounts']
			
			
			if($sum_taxes_final>0)
			{

				echo '<p><strong>'.$text_taxes_total.'</strong>: '.MoneyField::currency_format($sum_taxes_final).'</p>';

			}

			echo '<p>'.$text_price_total.'</p>';
			

			//Here the button if $show_button_buy==1

			if($show_button_buy==1)
			{
				?>
				<p><form action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'cart'); ?>" method="get"><input type="hidden" name="op" value="1" /><input type="submit" value="<?php echo PhangoVar::$lang['shop']['buy']; ?>" /></form></p>
				<?php

			}

		}
		else if($total_sum==0)
		{

			echo PhangoVar::$lang['shop']['empty_cart'];

		}

	}
	
	return array($final_price, $total_weight, $total_sum_tax);

}

function form_order($sha1_token, $post_user, $post_transport, $show_error=0)
{

	global PhangoVar::$lang, PhangoVar::$language, $model, $config_data, ConfigShop::$config_shop, $user_data, PhangoVar::$base_url;

	load_libraries(array('generate_forms', 'forms/selectmodelform'));

	echo '<h2>'.PhangoVar::$lang['shop']['make_order'].'</h2>';

	echo '<p>'.PhangoVar::$lang['shop']['explain_order'].'<p>';

	show_cart_simple($sha1_token, 0);

	//Send address

	echo '<h3>'.PhangoVar::$lang['shop']['address_billing'].'</h3>';

	//PhangoVar::$model['order_shop']->create_form();

	$set_user_form=array('private_nick' => &PhangoVar::$model['user']->forms['private_nick'], 'password' => &PhangoVar::$model['user']->forms['password'], 'repeat_password' => &PhangoVar::$model['user']->forms['repeat_password']);

	//Set values for zone_transport..

	PhangoVar::$model['order_shop']->forms['country']->form='SelectModelForm';
			
	PhangoVar::$model['order_shop']->forms['country']->parameters=array('country', '', '', 'country_shop', 'name', $where='order by `name_'.$_SESSION['language'].'` ASC');
	
	//PhangoVar::$model['order_shop']->forms['country_others']=new ModelForm('shopping', 'country_others', 'TextForm', PhangoVar::$lang['shop']['country_others'], new CharField(255), $required=0, $parameters='');

	PhangoVar::$model['order_shop']->forms['country_transport']->form='SelectModelForm';
			
	PhangoVar::$model['order_shop']->forms['country_transport']->parameters=array('country_transport', '', '', 'country_shop', 'name', $where='order by `name_'.$_SESSION['language'].'` ASC');

	SetValuesForm($post_user, PhangoVar::$model['order_shop']->forms, $show_error);
	SetValuesForm($post_user, $set_user_form, $show_error);
	SetValuesForm($post_transport, PhangoVar::$model['order_shop']->forms, $show_error);
	
	?>
	<form method="post" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'checkout', array('op' => 2)); ?>" name="shopping">
	<?php
	set_csrf_key();

	echo load_view(array(PhangoVar::$model['order_shop']->forms, array('name', 'last_name', 'enterprise_name', 'email', 'nif', 'address', 'zip_code', 'city', 'region', 'country', 'phone', 'fax'), ''), 'common/forms/modelform');
	
	if(ConfigShop::$config_shop['yes_transport']==1)
	{
		?>
		<div class="form">
		<p><label for="click"><?php echo PhangoVar::$lang['shop']['send_address_equal_shopping_address']; ?></label> <?php echo PhangoVar::$lang['common']['yes']; ?><input type="radio" name="click" onclick="javascript:add_data_transport();"/></p>
		</div>
		<br clear="all" />
		<?php
		echo '<h3>'.PhangoVar::$lang['shop']['address_transport'].'</h3>';

		//'zone_transport'

		echo load_view(array(PhangoVar::$model['order_shop']->forms, array('name_transport', 'last_name_transport', 'enterprise_name_transport', 'address_transport', 'zip_code_transport', 'city_transport', 'region_transport', 'country_transport',  'phone_transport'), ''), 'common/forms/modelform');

	}

	if($user_data['IdUser']==0)
	{

		echo '<h3>'.PhangoVar::$lang['common']['register_user'].'</h3>';

		echo '<p>'.PhangoVar::$lang['shop']['register_user_if_not_register'].'</p>';

		PhangoVar::$model['user']->forms['password']->required=1;
		PhangoVar::$model['user']->forms['repeat_password']->required=1;

		echo load_view(array($set_user_form, array('private_nick', 'password', 'repeat_password'), ''), 'common/forms/modelform');

	}

	?>
	<hr />
	<h3><?php echo PhangoVar::$lang['shop']['observations']; ?></h3>
	<p><?php echo PhangoVar::$lang['shop']['observations_text']; ?></p>
	<div class="form">
	<?php
	echo TextareaForm('observations', "form", "");
	?>
	</div>
	<hr />
	<h3><?php echo PhangoVar::$lang['shop']['terms_of_sale']; ?></h3>
	<p><?php echo PhangoVar::$lang['shop']['accept_terms_of_sale_push_send_button']; ?></p>
	
	<div class="form">
	<iframe src="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'conditions', PhangoVar::$lang['shop']['conditions'], array()); ?>"></iframe>
	</div>
	<script language="Javascript">
		function add_data_transport()
		{

			document.forms.shopping.name_transport.value=document.forms.shopping.name.value;
			document.forms.shopping.last_name_transport.value=document.forms.shopping.last_name.value;
			document.forms.shopping.enterprise_name_transport.value=document.forms.shopping.enterprise_name.value;
			document.forms.shopping.address_transport.value=document.forms.shopping.address.value;
			document.forms.shopping.zip_code_transport.value=document.forms.shopping.zip_code.value;
			document.forms.shopping.city_transport.value=document.forms.shopping.city.value;
			document.forms.shopping.region_transport.value=document.forms.shopping.region.value;
			document.forms.shopping.country_transport.value=document.forms.shopping.country.value;
			document.forms.shopping.phone_transport.value=document.forms.shopping.phone.value;
			

		}
		
	</script>
	<p><input type="submit" value="<?php echo PhangoVar::$lang['common']['send']; ?>" /></p>
	</form>
	<?php

}

function obtain_transport_price($total_weight, $total_price, $idtransport)
{

	

	$query=PhangoVar::$model['transport']->select('where IdTransport='.$idtransport, array('type'));
	
	list($type)=webtsys_fetch_row($query);
	
	if($type==0)
	{

		$query=webtsys_query('select price from price_transport where weight>='.$total_weight.' and idtransport='.$idtransport.' order by price ASC limit 1');
			
		list($price_transport)=webtsys_fetch_row($query);

		settype($price_transport, 'double');
		
		if($price_transport>0)
		{

			return array($price_transport, 1);

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

			return array($total_price_transport, $num_packs);
			
		}
		
	}
	else
	{
	
		$query=webtsys_query('select price from price_transport_price where min_price>='.$total_price.' and idtransport='.$idtransport.' order by min_price ASC limit 1');
		
		list($price_transport)=webtsys_fetch_row($query);

		//settype($price_transport, 'double');
		
		if($price_transport!='')
		{

			return array($price_transport, 1);

		}
		else
		{

			$min_price_substract=0;
			$price_transport=0;
			$total_price_transport=0;

			$query=webtsys_query('select min_price, price from price_transport_price order by min_price DESC limit 1');

			list($max_min_price, $max_price)=webtsys_fetch_row($query);
			
			return array($max_price, 1);

			//Tenemos que ver en cuanto supera los kilos...

			//Dividimos y obtenemos el resto...

			
		}
	
	}

}

function show_total_prices($sha1_token, $type_cart=0)
{

	global PhangoVar::$lang, ConfigShop::$config_shop, $model, $arr_order_shop;

	//obtain products...
	
	list($total_sum, $total_weight, $total_taxes)=show_cart_simple($sha1_token, 0, $type_cart);
	
	//, $discount_name, $discount_principal, $discount_taxes, $discount_transport, $discount_payment
	
	$discount_name='';
	
	$name_transport=0;
	$price_total_transport=0;
	$price_total_transport_original=0;
	
	//obtain prices for transport...
	$discount_transport=0;
					
	if(ConfigShop::$config_shop['yes_transport']==1)	
	{

		echo '<h3><strong>'.PhangoVar::$lang['shop']['price_transport'].'</strong></h3>';
		
		list($price_total_transport, $num_packs)=obtain_transport_price($total_weight, $total_sum, $arr_order_shop['transport']);
		
		$query=PhangoVar::$model['transport']->select('where IdTransport='.$arr_order_shop['transport'], array('name'));
		
		list($name_transport)=webtsys_fetch_row($query);

		?>
		
		<p><strong><?php echo PhangoVar::$lang['shop']['order_sended_with']; ?></strong> <?php echo $name_transport; ?></p>
		<p><strong><?php echo PhangoVar::$lang['shop']['price_total_transport']; ?>:</strong> <?php echo MoneyField::currency_format($price_total_transport); ?></p>

		<?php

		//Here discount transport...

		$price_total_transport_original=$price_total_transport;
		
		//$total_sum+=$price_total_transport;
		
	}

	$price_payment=0;

	$query=PhangoVar::$model['payment_form']->select('where IdPayment_form='.$arr_order_shop['payment_form'], array('name', 'price_payment'));

	list($name_payment_form, $price_payment)=webtsys_fetch_row($query);
	
	$name_payment_form=I18nField::show_formatted($name_payment_form);

	settype($price_payment, 'double');

	echo '<h3><strong>'.PhangoVar::$lang['shop']['shipping_costs'].'</strong></h3>';
	
	?>
		
	<p><strong><?php echo PhangoVar::$lang['shop']['payment_with']; ?></strong> <?php echo $name_payment_form; ?></p>
	<p><strong><?php echo PhangoVar::$lang['shop']['price_payment']; ?>:</strong> <?php echo MoneyField::currency_format($price_payment); ?> </p>

	<?php

	$price_payment_original=$price_payment;

	//Here discount payment...
	
	list($yes_discount, $text_discount, $discount_principal, $discount_taxes, $discount_transport, $discount_payment)=obtain_discounts($total_sum, $price_total_transport, $sha1_token);

	if($yes_discount>0)
	{

		echo '<h3>'.PhangoVar::$lang['shop']['total_price_with_discounts'].'</h3>';
		
		echo $text_discount;
		
		if($discount_principal>0)
		{
		
			$total_sum_original=$total_sum;
			
			$total_sum-=$total_sum*($discount_principal/100);
			
			//Recalculate taxes...
			
			$idtax=ConfigShop::$config_shop['idtax'];
			
			$total_taxes=calculate_taxes($idtax, $total_sum);
			
			echo '<p><strong>'.PhangoVar::$lang['shop']['discounts'].':</strong> '.number_format($discount_principal, 2).'% | <span style="text-decoration: line-through;">'.MoneyField::currency_format($total_sum_original).'</span> &nbsp; -> '.MoneyField::currency_format($total_sum).'</p>';
		}
		//Discount transport...
	
		if($discount_transport>0)
		{
			$substract_transport=$price_total_transport*($discount_transport/100);

			$price_total_transport-=$substract_transport;

			echo '<p><strong>'.PhangoVar::$lang['shop']['discount_transport'].':</strong> '.number_format($discount_transport, 2).'% | <span style="text-decoration: line-through;">'.MoneyField::currency_format($price_total_transport_original).'</span> &nbsp; -> '.MoneyField::currency_format($price_total_transport).'</p>';
	
		}
		
		//Discount taxes...
		

		//echo '<p style="font-size:18px;">'.MoneyField::currency_format( $final_price ).'</p>';

	}

	echo '<h2>'.PhangoVar::$lang['shop']['total_price_with_all_payments_and_discounts'].'</h2>';

	$total_sum_final=$total_sum+$price_total_transport+$price_payment+$total_taxes;
	
	echo '<p style="font-size:18px;">'.MoneyField::currency_format( $total_sum_final  ).'</p>';

	//$total_sum+=$price_payment;
	
	//Here add discounts...

	return array($total_sum_final, $discount_name, $discount_principal, $discount_taxes, $name_transport, $price_total_transport_original, $discount_transport, $name_payment_form, $price_payment_original, $discount_payment);

}

function obtain_discounts($total_sum, $price_total_transport, $sha1_token)
{

	global $user_data, ConfigShop::$config_shop, PhangoVar::$lang, $model, PhangoVar::$base_path;
	//Add discount if in group...
	
	ob_start();

	echo '<h3>'.PhangoVar::$lang['shop']['discounts'].'</h3>';

	$z=0;
	$discounts=0;
	$no_transport=array();
	$no_taxes=array();
	$no_shipping_costs=array();
	$discount_price=0;
	$discount_taxes=0;
	$discount_transport=0;
	$discount_payment=0;
	$total_sum_original=$total_sum;
	$yes_discount=0;
	
	//Plugins for discounts...
	
	$arr_plugin=array();
		
	$query=PhangoVar::$model['plugin_shop']->select('where element="discounts" order by position ASC', array('plugin'));
	
	while(list($plugin)=webtsys_fetch_row($query))
	{
		
		load_libraries(array($plugin), PhangoVar::$base_path.'modules/shop/plugins/discounts/');
	
		$func_plugin=ucfirst($plugin).'Show';
		
		//Execute plugin. 
		
		if(function_exists($func_plugin))
		{
			
			list($yes_discount_plugin, $discount_plugin_ret, $discount_transport)=$func_plugin($total_sum, $price_total_transport, $sha1_token);
			
			//$total_sum=$total_sum-$discount_plugin;
			
			$discount_price+=$discount_plugin_ret;
			
			if($yes_discount_plugin>0)
			{
				
				$yes_discount++;
			
			}
			
		}
	
	}
	
	//Choose the group discount more big for this user...
	/*
	$query=PhangoVar::$model['group_shop_users']->select('where group_shop_users.iduser='.$user_data['IdUser'].' order by group_shop_discount DESC, group_shop_transport_for_group DESC, group_shop_shipping_costs_for_group DESC limit 1');
	
	$arr_group=webtsys_fetch_array($query);

	if($arr_group['group_shop_discount']>0)
	{

		$arr_group['group_shop_name']=PhangoVar::$model['group_shop']->components['name']->show_formatted($arr_group['group_shop_name']);

		$division=100/$arr_group['group_shop_discount'];
		
		$discounts+=($total_sum/$division);
		
		$no_transport[]=$arr_group['group_shop_transport_for_group'];
		$no_taxes[]=$arr_group['group_shop_taxes_for_group'];
		$no_shipping_costs[]=$arr_group['group_shop_shipping_costs_for_group'];

		echo '<p>'.$arr_group['group_shop_name'].'</p>';
		$total_sum-=$discounts;

		echo '<p><strong>'.PhangoVar::$lang['shop']['discounts'].'</strong>: '.number_format($arr_group['group_shop_discount'], 2).'% | <span style="text-decoration: line-through;">'.MoneyField::currency_format($total_sum_original).' </span>&nbsp;-> '.MoneyField::currency_format($discounts).'</p>';

	}

	//create new taxes for new price...

	$taxes_calculated=calculate_taxes(ConfigShop::$config_shop['idtax'], $total_sum);
	
	//Now discounts

	//Taxes are added to raw price discount
	
	if(count($no_taxes)>0)
	{
		echo '<h3>'.PhangoVar::$lang['shop']['taxes_calculated_in_new_price'].'</h3>';

		echo '<p><strong>'.PhangoVar::$lang['shop']['new_taxes_calculated'].': </strong>'.MoneyField::currency_format($taxes_calculated).'</p>';

		//echo $taxes_total_transport;
		
		$discount_taxes=max($no_taxes);
		
		if($discount_taxes>0)
		{

			$discount_taxes_calculated=$taxes_calculated/(100/$discount_taxes);
			
			//$total_sum+=($taxes_calculated-$discount_taxes_calculated);

			$taxes_calculated_orig=$taxes_calculated;

			$taxes_calculated-=$discount_taxes_calculated;

			echo '<p><strong>'.PhangoVar::$lang['shop']['discount_taxes'].'</strong>: '.number_format($discount_taxes, 2).'% | <span style="text-decoration: line-through;">'.MoneyField::currency_format($taxes_calculated_orig).' </span>&nbsp;-> '.MoneyField::currency_format($taxes_calculated).'</p>';
		
		}

	}

	//Add final taxes to total_sum
	
	$total_sum+=$taxes_calculated;
	
	if(count($no_transport)>0)
	{

		$discount_transport=max($no_transport);

	}

	if(count($no_shipping_costs)>0)
	{

		$discount_payment=max($no_shipping_costs);

	}
	*/
	
	/*
	$text_return=ob_get_contents();
	
	ob_end_clean();
	
	return array($yes_discount, $text_return, $discount_price, $discount_taxes, $discount_transport, $discount_payment);

}

function calculate_price_cart_raw($token)
{

	$arr_id=array(0);

	$query=webtsys_query('select idproduct from cart_shop where token="'.$token.'"');
	
	while(list($idproduct)=webtsys_fetch_row($query))
	{
		
		settype($arr_id[$idproduct], 'integer');

		$arr_id[$idproduct]++;

	}
	
	$total_sum=0;

	$total_weight=0;

	$arr_lang_taxes=array();
	
	$query=PhangoVar::$model['product']->select('where IdProduct IN ('.implode(',', array_keys($arr_id) ).')', array('IdProduct', 'title', 'price', 'weight', 'idtax'), 1);
	
	while( list($idproduct, $title, $price, $weight, $idtax)=webtsys_fetch_row($query) )
	{
		
		$percent_tax=add_taxes($idtax);
		
		$sum_tax=$price*($percent_tax/100);
		
		$price+=$sum_tax;
		$title.=' ';

		$total_sum+=$price*$arr_id[$idproduct];

		$total_weight+=$weight*$arr_id[$idproduct];
		

	}
	
	return $total_sum;

}

function add_discount_to_element($arr_discount, $price_total_element, PhangoVar::$lang_discount)
{

	$discount_element=max($arr_discount);
	$discount_element_final=0;

	if($discount_element>0)
	{

		echo '<p><strong>'.PhangoVar::$lang_discount.'</strong>: '.$discount_element.'%</p>';
		
		$discount_element_final=$price_total_element/(100/$discount_element);
	
	}

	return $discount_element_final;



}
*/
?>
