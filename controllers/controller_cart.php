<?php

use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaLibs\LoginClass;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaRouter\Controller;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaView\View;

Webmodel::load_model('vendor/phangoapp/shop/models/models_shop');
Utils::load_config('shop');
//Utils::load_libraries(array('login'));
I18n::load_lang('shop');
Utils::load_libraries(array('config_shop', 'class_cart'), 'vendor/phangoapp/shop/libraries');

class CartController extends Controller
{

	public $login;
	public $cart;
	public $m;

	public function __construct($route, $name, $yes_view=1)
	{
		
		$this->m=&Webmodel::$model;

        $this->m['user_shop']->create_forms();

        /*$this->m['user_shop']->forms['country']->form='SelectModelForm';

        $this->m['user_shop']->forms['country']->parameters=array('country', '', '', 'country_shop', 'name', $where='order by `name_'.$_SESSION['language'].'` ASC');*/

        $this->m['user_shop']->forms['name']->label=I18n::lang('s', 'name', 'Name');

        $this->m['user_shop']->forms['last_name']->label=I18n::lang('s', 'last_name', 'Lastname');

        $this->m['user_shop']->forms['nif']->label=I18n::lang('s', 'nif', 'Nif');

        $this->m['user_shop']->forms['address']->label=I18n::lang('n', 'address', 'Address');

        $this->m['user_shop']->forms['city']->label=I18n::lang('s', 'city', 'City');

        $this->m['user_shop']->forms['region']->label=I18n::lang('n', 'region', 'Region');

        $this->m['user_shop']->forms['country']->label=I18n::lang('n', 'country', 'Country');

        $this->m['user_shop']->forms['zip_code']->label=I18n::lang('s', 'zip_code', 'Zip code');

        $this->m['user_shop']->forms['phone']->label=I18n::lang('n', 'phone', 'Phone');

        $this->m['user_shop']->forms['fax']->label=I18n::lang('n', 'fax', 'Fax');

        $this->m['address_transport']->create_forms();
        /*
        $this->m['address_transport']->forms['country_transport']->form='SelectModelForm';

        $this->m['address_transport']->forms['country_transport']->parameters=array('country_transport', '', '', 'country_shop', 'name', $where='order by `name_'.$_SESSION['language'].'` ASC');*/

        $this->m['address_transport']->forms['name_transport']->label=I18n::lang('s', 'name', 'Name');

        $this->m['address_transport']->forms['last_name_transport']->label=I18n::lang('s', 'last_name', 'Lastname');

        $this->m['address_transport']->forms['address_transport']->label=I18n::lang('n', 'address', 'Address');

        $this->m['address_transport']->forms['city_transport']->label=I18n::lang('s', 'city', 'City');

        $this->m['address_transport']->forms['region_transport']->label=I18n::lang('n', 'region', 'Region');

        $this->m['address_transport']->forms['country_transport']->label=I18n::lang('n', 'country', 'Country');

        $this->m['address_transport']->forms['zip_code_transport']->label=I18n::lang('s', 'zip_code', 'Zip code');

        $this->m['address_transport']->forms['phone_transport']->label=I18n::lang('n', 'phone', 'Phone');
		
		//parent::__construct();
	
		$this->login=new LoginClass($this->m['user_shop'], 'email', 'password', 'token_client', $arr_user_session=array(), $arr_user_insert=array());
		
		$this->login->url_insert=Routes::make_simple_url('shop/cart/get_user_save');
	
		$this->login->url_login=Routes::make_simple_url('shop/cart/login');
	
		$this->login->url_recovery=Routes::make_simple_url('shop/cart/recovery_password');
		
		$this->login->url_recovery_send=Routes::make_simple_url('shop/cart/recovery_password_send');
		
		$this->cart=new CartClass();
		
		parent::__construct($route, $name, $yes_view);
	
	}

	public function index()
	{
	
		/*$arr_block=select_view(array('shop'));
	
		//In cart , blocks showed are none always...

		$arr_block='/none';*/
		
		if(!$this->cart->check_order())
		{
		
			ob_start();
			
			$this->cart->show_cart();
			
			$cont_index=ob_get_contents();
			
			ob_end_clean();
			
			echo View::load_view(array(I18n::lang('p', 'cart', 'Carrito'), $cont_index), 'home');
			
		}
		else
		{
		
			$this->finish_checkout();
		
		}
	
	}
	
	public function update()
	{
	
		if(!$this->cart->check_order())
		{
	
			load_libraries(array('class_cart'), PhangoVar::$base_path.'modules/shop/libraries/');
			
			settype($_POST['num_products'], 'array');
			
			foreach($_POST['num_products'] as $cart_id => $units)
			{
			
				settype($cart_id, 'integer');
				settype($units, 'integer');
				
				$this->cart->sum_product_to_cart($cart_id, $units);
			
			}
			
			//Redirect
		
			$this->redirect( make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', array()), I18n::lang('n', 'press_here_redirecting', 'Press here for redirecting'));
			
		}
		else
		{
		
			$this->finish_checkout();
		
		}
	
	}
	
	public function delete()
	{
	
		if(!$this->cart->check_order())
		{
	
			settype($_GET['IdCart_shop'], 'integer');
		
			load_libraries(array('class_cart'), $this->base_path.'modules/shop/libraries/');

			$this->cart->sum_product_to_cart($_GET['IdCart_shop'], 0);
		
			$this->redirect( make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', array()), I18n::lang('n', 'press_here_redirecting', 'Press here for redirecting'));
			
		}
		else
		{
		
			$this->finish_checkout();
		
		}
	
	}
	
	public function get_address()
	{
	
		//global $model, PhangoVar::$lang, PhangoVar::$base_url;
	
	
		/*$arr_block=select_view(array('shop'));
	
		$arr_block='/none';*/
		
		if(!$this->cart->check_order())
		{
	
			ob_start();
			
			if(!$this->login->check_login())
			{
						
				echo load_view(array($this->login), 'shop/forms/registershopform');
				
				//$this->login->create_account_form();
				
				echo load_view(array($this->login), 'shop/forms/loginshopform');
		
			}
			else
			{
				
				$arr_user=$this->m['user_shop']->select_a_row($this->login->session['IdUser_shop']);
			
				ModelForm::set_values_form($arr_user, $this->m['user_shop']->forms, $show_error=1);
				
				echo load_view(array(), 'shop/forms/addressform');
				
			
			}
			
			$cont_index=ob_get_contents();
				
			ob_end_clean();
				
			echo load_view(array(I18n::lang('p', 'cart', 'Carrito'), $cont_index), 'home');
			
		}
		else
		{
		
			$this->finish_checkout();
		
		}
	}
	
	public function save_address()
	{
		//
		
		if($this->login->check_login())
		{
		
			if(!$this->cart->check_order())
			{
			
				ob_start();
				
				$this->m['user_shop']->components['email']->required=0;
				$this->m['user_shop']->components['password']->required=0;
				$this->m['user_shop']->components['token_client']->required=0;
				$this->m['user_shop']->components['token_recovery']->required=0;
				
				//$this->m['user_shop']->unset_components(ConfigShop::$arr_fields_address);
				
				$this->m['user_shop']->arr_fields_updated=&ConfigShop::$arr_fields_address;
			
				if($this->m['user_shop']->update($_POST, 'where IdUser_shop='.$this->login->session['IdUser_shop']))
				{
				
					$url_return=make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_set_transport');
				
					$this->redirect($url_return, I18n::lang('n', 'success', 'Success'), PhangoVar::$lang['common']	['press_here_redirecting']);
				
				}
				else
				{
				
					ModelForm::set_values_form($_POST, $this->m['user_shop']->forms, $show_error=1);
				
					echo load_view(array(), 'shop/forms/addressform');
				
				}
				
				$cont_index=ob_get_contents();
					
				ob_end_clean();
				
				$this->load_theme(I18n::lang('p', 'cart', 'Carrito'), $cont_index);
			
			}
			
		}
		else
		{
		
			$this->finish_checkout();
		
		}
	
	}
	
	public function get_user_save()
	{
	
		if(!$this->cart->check_order())
		{
			ob_start();
			
			if($this->login->create_account())
			{
			
				$iduser=webtsys_insert_id();
			
				$this->login->automatic_login($iduser);
				
				load_libraries(array('redirect'));
				
				$url_return=make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'cat_get_address');
				
				simple_redirect($url_return, I18n::lang('n', 'press_here_redirecting', 'Press here for redirecting'), $content_view='content');
			
			}
			else
			{
			
				//echo load_view(array($this->login), 'shop/forms/registerform');
				$this->login->create_account_form();
			
			}
		
			$cont_index=ob_get_contents();
				
			ob_end_clean();
			
			echo load_view(array(I18n::lang('p', 'cart', 'Carrito'), $cont_index,), 'home');
			
		}
		else
		{
		
			$this->finish_checkout();
		
		}
	
	}
	
	public function set_transport()
	{
	
		
		if($this->login->check_login())
		{
		
			if(!$this->cart->check_order())
			{
		
				if(ConfigShop::$config_shop['no_transport']==0)
				{
				
					ob_start();
					
					$arr_transport=$this->m['address_transport']->select_to_array('where iduser='.$this->login->session['IdUser_shop'].' limit '.ConfigShop::$num_address_transport, array('IdAddress_transport', 'address_transport', 'region_transport'));
					
					echo load_view(array($arr_transport), 'shop/forms/transportform');
					
					$cont_index=ob_get_contents();
					
					ob_end_clean();
				
					$this->load_theme(I18n::lang('p', 'cart', 'Carrito'), $cont_index);
				
				}
				else
				{
				
					$this->simple_redirect(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_checkout'));
				
				}
			}
			else
			{
			
				$this->finish_checkout();
			
			}
		}
	
	}
	
	public function save_transport_address()
	{
		
		if($this->login->check_login())
		{
		
			if(!$this->cart->check_order())
			{
			
				ob_start();
				
				$this->m['address_transport']->arr_fields_updated=&ConfigShop::$arr_fields_transport;
				
				ConfigShop::$arr_fields_transport[]='iduser';
			
				$_POST['iduser']=$this->login->session['IdUser_shop'];
			
				if($this->m['address_transport']->select_count('where iduser='.$this->login->session['IdUser_shop'])<5)
				{
					if($this->m['address_transport']->insert($_POST))
					{
					
						$url_return=make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_set_transport');
					
						$this->redirect($url_return, I18n::lang('n', 'success', 'Success'), PhangoVar::$lang['common']	['press_here_redirecting']);
					
					}
					else
					{
						
						ModelForm::set_values_form($_POST, $this->m['address_transport']->forms, $show_error=1);
					
						echo load_view(array($arr_transport=array(), 1), 'shop/forms/transportform');
					
					}
					
				}
				else
				{
				
					echo '<p>'.I18n::lang('p', 'cannot_add_more_address', 'cannot_add_more_address').'</p>';
				
				}
				
				$cont_index=ob_get_contents();
					
				ob_end_clean();
				
				$this->load_theme(I18n::lang('p', 'cart', 'Carrito'), $cont_index);
			}
			else
			{
			
				$this->finish_checkout();
			
			}
		}
	
	}
	
	public function save_choose_address_transport()
	{
		
		if($this->login->check_login())
		{
		
			if(!$this->cart->check_order())
			{
			
				settype($_GET['idaddress'], 'integer');
				
				if($this->m['address_transport']->select_count('where iduser='.$this->login->session['IdUser_shop'].' and IdAddress_transport='.$_GET['idaddress'])==1)
				{
				
					$_SESSION['idaddress']=$_GET['idaddress'];
					
					//ob_start();
					
					//Now, select transport
					
					$this->simple_redirect($this->get_method_url('cart_set_method_transport'));
					
					/*$cont_index=ob_get_contents();
						
					ob_end_clean();
					
					$this->load_theme(I18n::lang('p', 'cart', 'Carrito'), $cont_index);*/
					
					
				
				}
				else
				{
				
					$this->simple_redirect($this->get_method_url('index'));
				
				}
			}
			else
			{
			
				$this->finish_checkout();
			
			}
		}
	}
	
	public function set_method_transport()
	{
	
		if($this->login->check_login() && isset($_SESSION['idaddress']))
		{
		
			if(!$this->cart->check_order())
			{
	
				ob_start();
					
				//Now, load country_shop and zone_shop.
				
				//$this->m['address_transport']->components['country_transport']->name_field_to_field='name';
				$this->m['address_transport']->components['country_transport']->fields_related_model=array('idzone_transport');
				
				$address_transport=$this->m['address_transport']->select_a_row($_SESSION['idaddress'], array('country_transport'));
				
				settype($address_transport['country_transport'], 'integer');
				
				if($address_transport['country_transport']>0)
				{
				
					$this->cart=new CartClass(0);
					
					list($num_product, $total_price_product, $total_weight_product)=$this->cart->obtain_simple_cart();
				
					//Choose zone..
					
					//$zone_transport=$this->m['zone_shop']->select_a_row('where IdZone_shop='.$address_transport['country_shop_idzone_transport'], array('));
					
					$arr_transport=$this->m['transport']->select_to_array('where country='.$address_transport['country_shop_idzone_transport']);
				
					//print_r($arr_transport);
					echo load_view(array($arr_transport, $total_price_product, $total_weight_product, $this->cart), 'shop/forms/choosetransport'); 
				
				}
				
				//Load zone_transport
				
				//$zone_transport=$this->m['zone_transport']->
				
				$cont_index=ob_get_contents();
					
				ob_end_clean();
				
				$this->load_theme(I18n::lang('p', 'cart', 'Carrito'), $cont_index);
				
			}
			else
			{
			
				$this->finish_checkout();
			
			}
			
		}
	
	}
	
	public function save_choose_transport()
	{
	
		if($this->login->check_login())
		{
	
			if(!$this->cart->check_order())
			{
		
				settype($_GET['idtransport'], 'integer');
				
				if($this->m['transport']->select_count('where IdTransport='.$_GET['idtransport'])==1)
				{
				
					$_SESSION['idtransport']=$_GET['idtransport'];
					
					//ob_start();
					
					//Now, select transport
					
					$this->simple_redirect($this->get_method_url('cart_checkout', array()));
					
					/*$cont_index=ob_get_contents();
						
					ob_end_clean();
					
					$this->load_theme(I18n::lang('p', 'cart', 'Carrito'), $cont_index);*/
					
					
				
				}
				else
				{
				
					$this->simple_redirect($this->get_method_url('cart', array()));
				
				}
			}
			else
			{
			
				$this->finish_checkout();
			
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
			if(!$this->cart->check_order())
			{
		
				if(ConfigShop::$config_shop['no_transport']==0)
				{
				
					if(!isset($_SESSION['idtransport']) && !isset($_SESSION['idaddress']))
					{
						
						$yes_use_transport=0;
					
					}
					else
					{

						
						$arr_address_transport=$this->m['address_transport']->select_a_row($_SESSION['idaddress'], array(), 0);
						
						$arr_country=$this->m['country_shop']->select_a_row($arr_address_transport['country_transport'], array('name'));
						
						$arr_address_transport['country_transport']=I18nField::show_formatted($arr_country['name']);
					
					}
				
				}
				
				$arr_address=$this->m['user_shop']->select_a_row($this->login->session['IdUser_shop']);
						
				$arr_country=$this->m['country_shop']->select_a_row($arr_address['country'], array('name'));
				
				$arr_address['country']=I18nField::show_formatted($arr_country['name']);
				
				if($yes_use_transport==1)
				{
					
					
					
					echo load_view(array($arr_address, $arr_address_transport, $this->cart), 'shop/checkoutcart');
				
				}
				else
				{
				
					$this->simple_redirect(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart'));
				
				}
				
				$cont_index=ob_get_contents();
							
				ob_end_clean();
				
				$this->load_theme(I18n::lang('p', 'cart', 'Carrito'), $cont_index);
			}
			else
			{
			
				$this->finish_checkout();
			
			}
		}
		else
		{
		
			$this->simple_redirect(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart'));
		
		}
	
	}
	
	public function finish_checkout()
	{
		
		ob_start();
		
		settype($_GET['op'], 'integer');
		
		//$yes_use_transport=1;
		
		if($this->login->check_login())
		{
			if(!$this->cart->check_order())
			{
				if(!$this->cart->create_order_shop($this->login->session['IdUser_shop']))
				{
				
					echo load_view(array('Error', 'Error, cannot create order shop'), 'content');
				
				}
				else
				{
				
					//$this->finish_checkout();
					//http://localhost/phango2/index.php/shop/cart/finish_checkout
					//simple_redirect(PhangoVar::$base_url, 'shop', 'cart', 'di);
					$this->simple_redirect(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'finish_checkout'));
				}
			}
			else
			{
				
				if(ConfigShop::$arr_order['finished']==0)
				{
					
					switch($_GET['op'])
					{
				
					default:
					
						$arr_payment=array(0);

						$query=$this->m['payment_form']->select('', array($this->m['payment_form']->idmodel, 'name', 'price_payment'));
						
						while(list($idpayment, $name, $price)=webtsys_fetch_row($query))
						{
						
							$name=I18nField::show_formatted($name);

							if($price>0)
							{
								$price=MoneyField::currency_format( $price );
							}
							else
							{

								$price=I18n::lang('p', 'mode_payment_free_charge', 'Modo de pago libre de cargo');

							}

							$arr_payment[]=$name.' - '.$price;
							$arr_payment[]=$idpayment;

						}
				
						echo load_view(array($arr_payment), 'shop/forms/methodpayment');
					
					break;
					
					case 1:
					
						settype($_POST['payment_form'], 'integer');
						
						if($this->cart->num_items_cart()>0)
						{
						
							//The payment gateway
						
							$this->cart->payment_gateway($this->login->session['IdUser_shop'], $_POST['payment_form']);
					
						}
						else
						{
						
							$this->simple_redirect(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart'));
						
						}
					
					break;
					
					}
					
				}
				else
				{
				
					simple_redirect_location(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_finished'));
				
				}
			
			}
			
			$cont_index=ob_get_contents();
								
			ob_end_clean();
					
			$this->load_theme(I18n::lang('p', 'cart', 'Carrito'), $cont_index);
			
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
			
			$this->redirect($url_return, I18n::lang('n', 'press_here_redirecting', 'Press here for redirecting'));
		
		}
		else
		{
			
			ob_start();
		
			echo load_view(array($this->login), 'shop/forms/loginshopform');
			
			$cont_index=ob_get_contents();
			
			ob_end_clean();
			
			$this->load_theme(I18n::lang('p', 'cart', 'Carrito'), $cont_index);
		
		}
	
	}
	
	public function recovery_password()
	{
			
		ob_start();
	
		$this->login->recovery_password_form();
		
		$cont_index=ob_get_contents();
			
		ob_end_clean();
		
		$this->load_theme(I18n::lang('p', 'cart', 'Carrito'), $cont_index);
	
	}
	
	public function recovery_password_send()
	{
	
		ob_start();
	
		$this->login->recovery_password();
		
		$cont_index=ob_get_contents();
		
		ob_end_clean();
		
		$this->load_theme(I18n::lang('p', 'cart', 'Carrito'), $cont_index);
	
	}
	
	public function finished()
	{
	
		ob_start();
		
		if($this->login->check_login())
		{
			
			if($this->cart->check_order())
			{
			
				if(ConfigShop::$arr_order['finished']==1)
				{
			
					$this->cart->clean_cart();
			
					echo load_view(array( I18n::lang('p', 'order_success_cart_clean', '<p><strong>Se realizó el pedido con éxito.</strong></p><p>Recibirá un email con los datos de su compra así como el número de referencia con el cual podrá hacer una reclamación de este pedido si se produjera alguna indidencia.</p>') ), 'content');
					
				}
				else
				{
				
					$this->cart->clean_cart();
				
					echo load_view(array( I18n::lang('p', 'error_contact_with_us', 'error_contact_with_us') ), 'content');
				
				}
				
			}
		
		}
		
		$cont_index=ob_get_contents();
		
		ob_end_clean();
		
		$this->load_theme(I18n::lang('p', 'cart', 'Carrito'), $cont_index);
	
	}
	
	public function cancel_order()
	{
	
		$this->cart->cancel_order();
		
		$this->simple_redirect(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart'));
	
	}
	
	public function logout()
	{
	
		$this->login->logout();
	
		$this->simple_redirect(make_fancy_url(PhangoVar::$base_url, 'shop', 'cart'));
	
	}

	
}

?>
