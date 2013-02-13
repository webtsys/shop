<?php

function Cart()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $arr_taxes, $arr_order_shop, $language, $lang_taxes;

	$arr_block='';

	$cont_index='';

	$cont_cart='';

	$arr_block=select_view(array('shop'));
	
	//In cart , blocks showed are none always...

	$arr_block='/none';

	load_lang('shop');
	load_model('shop');
	
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');
	
	if($config_shop['ssl_url']==1)
	{
		
		if(!isset($_SERVER['HTTPS']))
		{
		
			//Redirect to https if cart isn't in https.
			
			unset($_GET['']);
			
			die(header('Location:'.make_fancy_url($base_url, 'shop', 'cart', $lang['shop']['cart'], $_GET ) ) );
			
		}
	
	}
	//If exists idtax and $config_shop['yes_taxes']==0, we need show the taxes to the client in the cart, yes_taxes is valid only for show products.

	if($config_shop['yes_taxes']==0 && $config_shop['idtax']>0)
	{

		$config_shop['yes_taxes']=1;
	
	}
	
	//If no yes_transport don't need transport_fields in order shop
					
	if($config_shop['yes_transport']==0)
	{
	
		$arr_fields_trans=array('name_transport', 'last_name_transport', 'address_transport', 'zip_code_transport', 'city_transport', 'region_transport', 'country_transport', 'phone_transport', 'zone_transport', 'transport');
	
		foreach($arr_fields_trans as $name_trans)
		{
		
			$model['order_shop']->components[$name_trans]->required=0;
		
		}
		
	
	}
	
	
	settype($_GET['op'], 'integer');

	$sha1_token=@sha1($_COOKIE['webtsys_shop']);

	$num_products=$model['cart_shop']->select_count('where token=\''.$sha1_token.'\'', 'IdProduct');

	$query=$model['order_shop']->select('where token=\''.$sha1_token.'\'');

	$arr_order_shop=webtsys_fetch_array($query);

	settype($arr_order_shop['IdOrder_shop'], 'integer');

	//Arrays for update models...

	$update_fields=array('name', 'last_name', 'enterprise_name', 'email', 'nif', 'address', 'zip_code', 'city', 'region', 'country', 'phone', 'fax');

	$update_transport=array('name_transport', 'last_name_transport', 'enterprise_name_transport', 'address_transport', 'zip_code_transport', 'city_transport', 'region_transport', 'country_transport', 'zone_transport', 'phone_transport');

	//If order is send, then go to payment gateway

	if($num_products>0 && $config_shop['view_only_mode']==0)
	{

		if($arr_order_shop['IdOrder_shop']==0)
		{

			switch($_GET['op'])
			{

				default:

					echo '<p>'.$lang['shop']['explain_cart_options'].'</p>';

					show_cart_simple($sha1_token, 1, 1);

				break;

				case 1:

					//Show form order

					$post_user=array();
					$post_transport=array();
					
					settype($_GET['go_buy'], 'integer');
					
					$url_login=make_fancy_url($base_url, 'shop', 'cart', 'buy_products', array('op' => 1, 'go_buy' => 1));
					
					if($user_data['IdUser']<=0 && $_GET['go_buy']==0)
					{
					
						echo '<p>'.$lang['shop']['explain_buying_without_register'].'</p>';
					
						echo '<p>'.$lang['shop']['login_shop'].', <a href="'.make_fancy_url($base_url, 'user',
						'index', 'login', array('register_page' => urlencode_redirect($url_login)) ).'">'.$lang['shop']['click_here'].'</a></p>';
						
						echo '<p>'.$lang['shop']['register_shop_or_buying'].', <a href="'.$url_login.'">'.$lang['shop']['click_here'].'</a></p>';
					
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

							$query=$model['dir_transport']->select('where iduser='.$user_data['IdUser'], array(), 1);

							$post_transport=webtsys_fetch_array($query);

							settype($post_transport, 'array');

						}

						$query=$model['country_user_shop']->select('where IdUser='.$user_data['IdUser'], array('idcountry'));

						list($idcountry_user)=webtsys_fetch_row($query);

						$post_user['country']=$idcountry_user;

						form_order($sha1_token, $post_user, $post_transport, 0);
						
						
					}

				break;

				case 2:

					//Choose payment and transport type.

					$model['order_shop']->components['token']->required=0;
					$model['order_shop']->components['transport']->required=0;
					$model['order_shop']->components['payment_form']->required=0;
					
					$_POST['zone_transport']=0;

					settype($_POST['country_transport'], 'integer');

					$query=$model['country_shop']->select('where IdCountry_shop='.$_POST['country_transport'], array(), 1);

					$arr_zone_shop=webtsys_fetch_array($query);

					settype($arr_zone_shop['idzone_taxes'], 'integer');
					settype($arr_zone_shop['idzone_transport'], 'integer');

					if($arr_zone_shop['idzone_transport']==0)
					{

						$query=$model['zone_shop']->select('where type=0 and other_countries=1', array('IdZone_shop'));

						list($arr_zone_shop['idzone_transport'])=webtsys_fetch_row($query);
						
					}

					$_POST['zone_transport']=$arr_zone_shop['idzone_transport'];

					$model['user']->forms['password']->type->required=1;
									
					$model['user']->check_all($_POST);

					//echo $_POST['zone_transport'];
					

					if($model['order_shop']->check_all($_POST))
					{
					
						//Prepare post...
						
						$post=array();

						foreach($update_fields as $field)
						{

							$post[$field]=$_POST[$field];

						}
						
						$post_transport=array();
						
						if($config_shop['yes_transport'])
						{

							foreach($update_transport as $field)
							{

								$post_transport[$field]=$_POST[$field];

							}
							
						}

						//Obtain real name for country

						$real_country=$post['country'];

						$query=$model['country_shop']->select('where IdCountry_shop='.$real_country, array('name'));

						list($country_name)=webtsys_fetch_row($query);

						$post['country']=I18nField::show_formatted($country_name);

						//Save order and register to user in first time...

						if($user_data['IdUser']==0)
						{

							load_libraries(array('timestamp_zone', 'generate_admin_ng'));
							load_libraries(array('func_users'), $base_path.'modules/user/libraries/');

							$arr_fields_form=array_keys($post);

							$arr_fields_form[]='private_nick';
							$arr_fields_form[]='password';

							$post['private_nick']=$_POST['private_nick'];
							$post['password']=$_POST['password'];
							$post['repeat_password']=$_POST['repeat_password'];
							
							if(!UserInsertModel('user', $arr_fields_form, $post))
							{

								$post_user=&$_POST;
								$post_transport=&$_POST;

								if($model['user']->forms['email']->std_error!='')
								{

									$model['order_shop']->forms['email']->std_error=$model['user']->forms['email']->std_error;

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

								$user_data['key_csrf']=$prefix_key.'_'.$_SESSION['webtsys_id'];

								setlogin($_POST['email'], $_POST['password'], '', 0, 0);

								$model['country_user_shop']->insert( array('idcountry' => $real_country, 'iduser' => $user_data['IdUser']) );

								$post_transport['iduser']=$user_data['IdUser'];

								$model['dir_transport']->insert($post_transport);

								//Send email for registering user...

								$portal_name=html_entity_decode($config_data['portal_name']);

								$topic_email=$lang['user']['text_confirm'];
							
								$body_email=load_view(array($_POST['private_nick'], $_POST['email'], form_text($_POST['password']) ), 'shop/mailregister');
								
								if( !send_mail($email, $topic_email, $body_email, 'html') )
								{
					
									echo "<p align=\"center\">".$lang['user']['error_email']."</p>";

								}

							}

						}
						else
						{

							//Update user and dir_transport

							$model['user']->components['private_nick']->required=0;
							$model['user']->components['password']->required=0;
							
							$result_update=$model['user']->update($post, 'where IdUser='.$user_data['IdUser']);

							//Create backup for country for user.

							$num_count_country=$model['country_user_shop']->select_count('where IdUser='.$user_data['IdUser'], 'IdCountry_user_shop');

							if($num_count_country>0)
							{

								$model['country_user_shop']->update(array('idcountry' => $real_country), 'where iduser='.$user_data['IdUser']);

							}
							else
							{

								$model['country_user_shop']->insert( array('idcountry' => $real_country, 'iduser' => $user_data['IdUser']) );

							}

							if($config_shop['yes_transport'])
							{
							
								$post['country']=$real_country;

								$num_dir_transport=$model['dir_transport']->select_count('where iduser='.$user_data['IdUser'], 'IdDir_transport');

								settype($num_dir_transport, 'integer');
								
								if($num_dir_transport>0)
								{

									$model['dir_transport']->update($post_transport, 'where iduser='.$user_data['IdUser']);

								}
								else
								{

									$post_transport['iduser']=$user_data['IdUser'];

									$model['dir_transport']->insert($post_transport);

								}
								
							}

						}

						//Choose payment and transport...
						?>	
						<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'checkout', array('op' => 3));?>">
						<?php set_csrf_key(); ?>
						<h2><?php echo $lang['shop']['choose_more_options']; ?></h2>
						<p><?php echo $lang['shop']['explain_payment_type_transport_type']; ?></p>
						<h3><?php echo $lang['shop']['payment_type']; ?></h3>
						<?php
				
						$arr_payment=array(0);
				
						$query=$model['payment_form']->select('', array($model['payment_form']->idmodel, 'name', 'price_payment'));
						
						while(list($idpayment, $name, $price)=webtsys_fetch_row($query))
						{
						
							$name=I18nField::show_formatted($name);
				
							if($price>0)
							{
								$price=MoneyField::currency_format( $price );
							}
							else
							{

								$price=$lang['shop']['mode_payment_free_charge'];

							}

							$arr_payment[]=$name.' - '.$price;
							$arr_payment[]=$idpayment;
				
						}
				
						echo SelectForm('payment_form', '', $arr_payment );


						if($config_shop['yes_transport']==1)
						{
							?>
							<h3><?php echo $lang['shop']['transport']; ?></h3>
							<?php

							settype($_POST['zone_transport'], 'integer');
					
							$arr_transport=array('');
							
							$query=$model['transport']->select('where IdTransport>0 and country='.$_POST['zone_transport'], array($model['transport']->idmodel, 'name'));
							
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

								echo '<p>'.$lang['shop']['error_in_country_no_exists_transport'].'</p>';

							}

						}

						?>
						<?php echo HiddenForm('observations', '', str_replace('"', '&quot;', $_POST['observations'])); ?>
						<p><input type="submit" value="<?php echo $lang['common']['send']; ?>"/></p>
						<p><a href="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'checkout', array('op' => 1) ); ?>"><?php echo $lang['common']['go_back']; ?></a>
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

						echo '<p><span class="error">'.$lang['shop']['error_cannot_access_to_next_step'].'</span></p>';

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

						$query=$model['country_user_shop']->select('where IdUser='.$user_data['IdUser'], array('idcountry'));

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

						$model['cart_shop']->components['idproduct']->fields_related_model=array('referer', 'title', 'price', 'special_offer', 'weight', 'extra_options');

						$query=$model['cart_shop']->select('where token="'.$sha1_token.'"', array('IdCart_shop', 'idproduct'), 0);

						while($arr_product=webtsys_fetch_array($query))
						{
							//print_r($arr_product);
							settype($arr_products[$arr_product['idproduct']]['units'], 'integer');
							
							$price=$arr_product['product_price'];
							
							$arr_products[$arr_product['idproduct']]['units']++;
							
							$sum_tax=calculate_taxes($config_shop['idtax'], $price);
		
							$total_price+=($price+$sum_tax);
								
							$total_weight+=$arr_product['product_weight'];

							/*if($config_shop['yes_taxes']==1)
							{

								$percent_tax=$config_shop['idtax'];

								$price=$arr_product['product_price'];
					
								$sum_tax=$price*($percent_tax/100);
								
								$total_price+=($price+$sum_tax);
								
								$total_weight+=$arr_product['product_weight'];

							}*/

						}
						
						if($config_shop['yes_transport']==1)
						{

							$post['transport']=$_POST['transport'];
							
							$query=$model['dir_transport']->select('where iduser='.$user_data['IdUser'], array(), 1);

							$post_transport=webtsys_fetch_array($query);

							settype($post_transport, 'array');

							$post=array_merge($post, $post_transport);

							//Now obtain prices for the transport...
							
							list($price_total_transport, $num_packs)=obtain_transport_price($total_weight, $total_price, $post['transport']);

						}

						$post['date_order']=TODAY;
						
						$query=$model['order_shop']->insert($post);
				
						if($model['order_shop']->std_error=='')
						{
							ob_end_clean();

							load_libraries(array('redirect'));
							die( redirect_webtsys( make_fancy_url($base_url, 'shop', 'cart', 'checkout', array()), $lang['common']['redirect'], $lang['shop']['success_buy_go_to_payment'], $lang['common']['press_here_redirecting'] , $arr_block) );

						}
						else
						{

							echo $model['order_shop']->std_error;

						}

					}

				break;

				case 4:


					load_libraries(array('table_config'));

					echo '<h3>'.$lang['shop']['modify_product_options'].'</h3>';

					settype($_GET['IdProduct'], 'integer');

					//Load product...

					$query=$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('title', 'referer', 'extra_options'));

					list($title_product, $ref_product, $extra_options)=webtsys_fetch_row($query);

					$title_product=$model['product']->components['title']->show_formatted($title_product);
					
					if($extra_options!='')
					{
						echo '<p>'.$lang['shop']['explain_delete_options'].'</p>';
						?>
						<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'buy', 'modify_product_options', array('IdProduct' => $_GET['IdProduct'], 'delete_products' => 1)); ?>">
						<?php
						set_csrf_key();
						$query=$model['cart_shop']->select('where cart_shop.idproduct='.$_GET['IdProduct'].' and token="'.$sha1_token.'"', array('IdCart_shop', 'details'), 1);

						up_table_config(array($lang['shop']['referer'], $lang['common']['name'], $lang['shop']['option_selected'], $lang['common']['options'], $lang['shop']['select_product']));

						while($arr_product=webtsys_fetch_array($query))
						{
							$details=unserialize($arr_product['details']);
							
							$options_url='<a href="'.make_fancy_url($base_url, 'shop', 'buy', 'modify_product_options', array('IdCart_shop' => $arr_product['IdCart_shop'])).'">'.$lang['shop']['modify_product_options'].'</a>';

							$check_product=CheckBoxForm('idproduct['.$arr_product['IdCart_shop'].']', '', '');

							middle_table_config(array($ref_product, $title_product, $details[0], $options_url, $check_product));

						}

						down_table_config();

						?>
						<p><input type="submit" value="<?php echo $lang['shop']['delete_products_selected']; ?>"/></p>
						</form>
						<?php

					}
					else
					{

						echo '<p>'.$lang['shop']['explain_delete_options_form'].'</p>';

						$num_products=$model['cart_shop']->select_count('where cart_shop.idproduct='.$_GET['IdProduct'].' and token="'.$sha1_token.'"', 'IdProduct');

						$text_num_products=TextForm('num_products', 'units', $num_products);

						?>
						<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'buy', 'modify_product_options', array('IdProduct' => $_GET['IdProduct'], 'add_more_units' => 1)); ?>">
						<?php
						set_csrf_key();

						up_table_config(array($lang['shop']['referer'], $lang['common']['name'], $lang['shop']['num_products']));

						middle_table_config(array($ref_product, $title_product, $text_num_products));

						down_table_config();

						?>
						<p><input type="submit" value="<?php echo $lang['common']['send']; ?>" />
						</form>
						<?php

					}

					echo '<p><a href="'.make_fancy_url($base_url, 'shop', 'cart', 'show_cart', array()).'">'.$lang['common']['go_back'].'</a></p>';

				break;

			}
		
		}
		else
		{

			settype($arr_order_shop['country'], 'integer');

			$query=webtsys_query('select country_shop.idzone_taxes, zone_shop.IdZone_shop, taxes.IdTaxes from country_shop, zone_shop, taxes where country_shop.idzone_taxes=zone_shop.IdZone_shop and taxes.country=zone_shop.IdZone_shop and country_shop.IdCountry_shop='.$arr_order_shop['country']);

			list($idzone_taxes, $idzone_shop, $idtax)=webtsys_fetch_row($query);

			settype($idtax, 'integer');

			$config_shop['idtax']=$idtax;
			
			if($config_shop['idtax']>0)
			{

				echo '<p><strong>'.$lang['shop']['you_choose_a_country_that_have_taxes_about_this_products'].'</strong></p>';

				$config_shop['yes_taxes']=1;

			}

			switch($_GET['op'])
			{

			default:

				//Here put prices, taxes, payment and transport in order_shop.

				if($arr_order_shop['make_payment']==0)
				{
		
					echo '<h3>'.$lang['shop']['order_submited_show_order_and_prices'].'</h3>';

					list($total_price, $discount_name, $discount_principal, $discount_taxes, $name_transport, $price_total_transport_original, $discount_transport, $name_payment, $price_payment_original, $discount_payment)=show_total_prices($sha1_token);
					
					$tax_name=$lang_taxes[$config_shop['idtax']];
					$tax_percent=$arr_taxes[$config_shop['idtax']];

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
			
					$model['order_shop']->reset_require();

					//Update order shop with values...

					$model['order_shop']->update($post, 'where token="'.$sha1_token.'"');

					echo '<hr />';

					echo '<h3>'.$lang['shop']['send_order_and_checkout'].'</h3>';
					
					echo '<p>'.$lang['shop']['explain_send_order_and_checkout'].'</p>';

					?>
					<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'checkout', array('op' => 1) ); ?>">
					<p><input type="submit" value="<?php echo $lang['shop']['checkout_order']; ?>" /></p>
					</form>
					<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'checkout', array('op' => 2) ); ?>">
					<p><input type="submit" value="<?php echo $lang['shop']['cancel_order']; ?>" /></p>
					</form>
					<?php

				}
				else
				{

					header('Location: '.make_fancy_url($base_url, 'shop', 'cart', 'checkout', array('op' => 1)));
					die;

				}

			break;

			case 1:

			if($arr_order_shop['make_payment']==0)
			{

				$query=webtsys_query('select order_shop.payment_form, payment_form.name, payment_form.code from order_shop, payment_form where order_shop.payment_form=payment_form.IdPayment_form and order_shop.token="'.$sha1_token.'"');

				list($idpayment_form, $name_payment, $code_payment)=webtsys_fetch_row($query);
				
				$name_payment=I18nField::show_formatted($name_payment);
				
				if(!include($base_path.'modules/shop/payment/'.basename($code_payment)))
				{
			
					echo $lang['shop']['error_no_proccess_payment_send_email'].': '.$config_data['portal_email'];

				}

			}
			else
			{

				load_libraries(array('forms/textplainform'));

				//Now initialize the text from mail, first address_billing

				$num_bill=calculate_num_bill($arr_order_shop['IdOrder_shop']);

				echo '<p><strong>'.$lang['shop']['referer'].': '.$num_bill.'</strong></p>';

				echo '<h2>'.$lang['shop']['address_billing'].'</h2>';
		
				$model['order_shop']->reset_require();

				foreach($model['order_shop']->forms as $key_form => $form)
				{

					$model['order_shop']->forms[$key_form]->form='TextPlainForm';

				}

				SetValuesForm($arr_order_shop, $model['order_shop']->forms, $show_error=0);

				$query=$model['country_shop']->select('where IdCountry_shop='.$arr_order_shop['country'], array('name'));

				list($name_country)=webtsys_fetch_row($query);
	
				$name_country=$model['country_shop']->components['name']->show_formatted($name_country);

				$query=$model['country_shop']->select('where IdCountry_shop='.$arr_order_shop['country_transport'],  array('name'));

				list($name_country_transport)=webtsys_fetch_row($query);

				$name_country_transport=$model['country_shop']->components['name']->show_formatted($name_country_transport);

				$model['order_shop']->forms['country']->SetForm($name_country);
				$model['order_shop']->forms['country_transport']->SetForm($name_country_transport);

				echo load_view(array($model['order_shop']->forms, array('name', 'last_name', 'email', 'nif', 'address', 'zip_code', 'city', 'region', 'country', 'phone', 'fax'), ''), 'common/forms/modelform');

				if($config_shop['yes_transport']==1)
				{

					echo '<h3>'.$lang['shop']['address_transport'].'</h3>';

					echo load_view(array($model['order_shop']->forms, array('name_transport', 'last_name_transport', 'address_transport', 'zip_code_transport', 'city_transport', 'region_transport', 'country_transport', 'phone_transport'), ''), 'common/forms/modelform');

				}

				echo '<h2>'.$lang['shop']['order'].'</h2>';
				
				show_total_prices($sha1_token);

				echo '<h3>'.$lang['shop']['order_products_options'].'</h3>';

				$z=0;

				//Select id from buy products..

				$arr_idproduct=array();

				$query=webtsys_query('select DISTINCT idproduct from cart_shop where token="'.$sha1_token.'"');

				while(list($idproduct)=webtsys_fetch_row($query))
				{

					$arr_idproduct[]=$idproduct;

				}
				
				$arr_description_type=array();

				$model['product_option']->components['idtype']->fields_related_model=array('title', 'description');
				$model['product_option']->components['idproduct']->fields_related_model=array('title');
				
				$query=$model['product_option']->select('where product_option.idproduct IN ('.implode(', ', $arr_idproduct).')', array('idtype', 'idproduct'));

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

					echo '<p>'.$lang['shop']['order_without_options'].'</p>';

				}

				$content_mail=ob_get_contents();

				ob_clean();

				$portal_name=html_entity_decode($config_data['portal_name']);

				load_libraries(array('send_email'));

				$text_explain_user='<h3>'.$lang['shop']['your_orders']." - ".$portal_name.'</h3>';
				$text_explain_user.=$lang['shop']['explain_petition'].'<p>'.$lang['shop']['if_error_send_email_to'].': '.$config_data['portal_email'].'</p>';

				$query=$model['module']->select('where name="shop"', array('IdModule'));

				list($idmodule)=webtsys_fetch_row($query);
				
				$send_email_admin='<h3>'.$lang['shop']['url_bill_for_admin'].'</h3><p><a href="'.make_fancy_url($base_url, 'admin', 'index', 'obtain_bill', array('IdModule' => $idmodule, 'op' => 16, 'IdOrder_shop' => $arr_order_shop['IdOrder_shop'])).'">'.$lang['shop']['click_here_for_download_bill'].'</a></p>';

				//If no send mail write a message with the reference, for send to mail shop...

				if( !send_mail($user_data['email'], $lang['shop']['your_orders']." - ".$portal_name, $text_explain_user.$content_mail, 'html') || !send_mail($config_data['portal_email'], $lang['shop']['orders']." - ".$portal_name, '<h1>'.$lang['shop']['new_order'].'</h1><p>'.$lang['shop']['explain_new_order'].'</p>'.$content_mail.$send_email_admin, 'html') )
				{

					echo '<p>'.$lang['shop']['error_cannot_send_email'].', '.$lang['shop']['use_this_id_for_contact_with_us'].': <strong>'.$arr_order_shop['IdOrder_shop'].'</strong></p>';

				}

				//Sum 1 to num_sold to solded products.

				$query=webtsys_query('update product set num_sold=num_sold+1 where IdProduct IN ('.implode(', ', $arr_idproduct).')');
				
				//Now proccess the plugins for last cart process. For example, for stores that sell files. The plugin send an tmp link for download the file, and type a text.
				
				$arr_plugin=array();
		
				$query=$model['plugin_shop']->select('where element="cart" order by position ASC', array('plugin'));
				
				while(list($plugin)=webtsys_fetch_row($query))
				{
					
					load_libraries(array($plugin), $base_path.'modules/shop/plugins/cart/');
				
					$func_plugin=ucfirst($plugin).'Show';
					
					//Execute plugin. 
					
					if(function_exists($func_plugin))
					{
					
						$func_plugin($sha1_token);
						
					}
				
				}

				setcookie ( "webtsys_shop", FALSE, 0, $cookie_path);

				echo $lang['shop']['order_success_cart_clean'];

			}

			break;

			case 2:

				//Cancel order.

				//Delete order_shop
				ob_clean();
				$query=$model['order_shop']->delete('where token=\''.$sha1_token.'\'');

				load_libraries(array('redirect'));
				die( redirect_webtsys( make_fancy_url($base_url, 'shop', 'cart', 'cart', array()), $lang['common']['redirect'], $lang['shop']['cancelling_order'], $lang['common']['press_here_redirecting'] , $arr_block) );

			break;

			}

		}

	}
	else
	{

		echo $lang['shop']['empty_cart'];

	}

	$cont_cart=ob_get_contents();

	ob_clean();

	echo load_view(array($lang['shop']['cart'], $cont_cart), 'content');

	$cont_index=ob_get_contents();

	ob_end_clean();

	echo load_view(array($config_shop['title_shop'], $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ''), $arr_block);

}

function show_cart_simple($token, $show_button_buy=1, $type_cart=0)
{

	global $base_url, $lang, $model, $arr_taxes, $config_shop;

	load_libraries(array('table_config'));

	if($type_cart==1)
	{

		//Functions for list_cart

		function head_list_cart($options=1)
		{

			global $lang, $base_url;

			?>
			<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'deleteproduct', 'deleteproduct', array()); ?>">
			
			<?php
			set_csrf_key();

			$fields=array($lang['shop']['referer'], $lang['common']['name'], $lang['shop']['num_products'], $lang['shop']['price']);
		
			/*$fields[]=$lang['shop']['taxes'];
			$fields[]=$lang['shop']['price_with_taxes'];*/

			$fields=name_field_taxes($fields);

			if($options==1)
			{

				$fields[]=$lang['common']['options'];

			}

			$fields[]=$lang['shop']['select_product'];

			up_table_config( $fields );

		}

		function middle_list_cart($idproduct, $arr_id, $referer, $title, $price_without_tax, $price, $offer, $idtax, $sum_tax, $options=1, $extra_options='')
		{

			global $base_url, $arr_taxes, $lang_taxes, $lang, $model;

			$price*=$arr_id[$idproduct];
			$price_without_tax*=$arr_id[$idproduct];

			$title=$model['product']->components['title']->show_formatted($title);

			$change_num_products=$arr_id[$idproduct]; //TextForm('change_num_products', 'units', $arr_id[$idproduct]);

			$fields=array($referer, $title, $change_num_products, MoneyField::currency_format($price_without_tax));

			$fields=add_field_taxes($fields, $price, $idtax, $sum_tax*$arr_id[$idproduct]);
			
			/*if($options==1 && $extra_options!='')
			{*/

				
			$url_modify_options=make_fancy_url( $base_url, 'shop', 'cart', 'modify_product_options', array('op' => 4, 'IdProduct' => $idproduct) );

			$fields[]='<a href="'.$url_modify_options.'">'.$lang['shop']['modify_options'].'</a>';
			$fields[]=CheckBoxForm('idproduct['.$idproduct.']', '', '');
				//$fields[2]=$arr_id[$idproduct];

			/*}
			else
			{

				$fields[]=$lang['shop']['no_options_product'];

			}*/

			middle_table_config($fields);

		}

		function bottom_list_cart()
		{

			global $lang;

			down_table_config(array());
			?>
			<p><input type="submit" value="<?php echo $lang['shop']['delete_products_selected']; ?>"/></p>
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

			global $base_url, $model;

			$price*=$arr_id[$idproduct];
			$price_without_tax*=$arr_id[$idproduct];

			$title=$model['product']->components['title']->show_formatted($title);

			echo '<a href="'.make_fancy_url($base_url, 'shop', 'viewproduct', 'viewproduct', array('IdProduct' => $idproduct) ).'"><strong>'.$arr_id[$idproduct].' x '.$referer.' '.$title."</strong></a> - ".MoneyField::currency_format($price_without_tax)."<br />\n";

		}

		function bottom_list_cart()
		{

			return '';

		}

	}

	if(!isset($_COOKIE['webtsys_shop']))
	{

		echo $lang['shop']['cart_empty'];

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

		$idtax=$config_shop['idtax'];

		head_list_cart();
		
		$query=$model['product']->select('where IdProduct IN ('.implode(',', array_keys($arr_id) ).')', array('IdProduct', 'referer', 'title', 'price', 'special_offer', 'weight', 'extra_options'), 1);
		
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

			$total_sum_tax+=$sum_tax;
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

			echo '<p><strong>'.$lang['shop']['total'].': </strong>'.MoneyField::currency_format($total_sum).' </p>';
			
			list($text_price_total, $text_taxes_total, $sum_taxes_final)=add_text_taxes_final($final_price, $idtax);
			
			if($sum_taxes_final>0)
			{

				echo '<p><strong>'.$text_taxes_total.'</strong>: '.MoneyField::currency_format($sum_taxes_final).'</p>';

			}

			echo '<p>'.$text_price_total.'</p>';
			
			//Here the discounts...
			
			list($total_sum_final, $text_discount, $discount_name, $discount_principal, $discount_taxes, $discount_transport, $discount_payment)=obtain_discounts($final_price, $sum_taxes_final);
			
			//$lang['shop']['total_price_with_all_payments_and_discounts']

			if($discount_name!='')
			{

				echo $text_discount;

				echo '<h3>'.$lang['shop']['total_price_with_discounts'].'</h3>';

				echo '<p style="font-size:18px;">'.MoneyField::currency_format( $total_sum_final ).'</p>';

			}

			//Here the button if $show_button_buy==1

			if($show_button_buy==1)
			{
				?>
				<p><form action="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'cart'); ?>" method="get"><input type="hidden" name="op" value="1" /><input type="submit" value="<?php echo $lang['shop']['buy']; ?>" /></form></p>
				<?php

			}

		}
		else if($total_sum==0)
		{

			echo $lang['shop']['empty_cart'];

		}

	}
	
	return array($total_sum_final, $total_weight, $total_sum_tax, $discount_name, $discount_principal, $discount_taxes, $discount_transport, $discount_payment);

}

function form_order($sha1_token, $post_user, $post_transport, $show_error=0)
{

	global $lang, $language, $model, $config_data, $config_shop, $user_data, $base_url;

	load_libraries(array('generate_forms', 'forms/selectmodelform'));

	echo '<h2>'.$lang['shop']['make_order'].'</h2>';

	echo '<p>'.$lang['shop']['explain_order'].'<p>';

	show_cart_simple($sha1_token, 0);

	//Send address

	echo '<h3>'.$lang['shop']['address_billing'].'</h3>';

	//$model['order_shop']->create_form();

	$set_user_form=array('private_nick' => &$model['user']->forms['private_nick'], 'password' => &$model['user']->forms['password'], 'repeat_password' => &$model['user']->forms['repeat_password']);

	//Set values for zone_transport..

	$model['order_shop']->forms['country']->form='SelectModelForm';
			
	$model['order_shop']->forms['country']->parameters=array('country', '', '', 'country_shop', 'name', $where='');

	$model['order_shop']->forms['country_transport']->form='SelectModelForm';
			
	$model['order_shop']->forms['country_transport']->parameters=array('country_transport', '', '', 'country_shop', 'name', $where='');

	SetValuesForm($post_user, $model['order_shop']->forms, $show_error);
	SetValuesForm($post_user, $set_user_form, $show_error);
	SetValuesForm($post_transport, $model['order_shop']->forms, $show_error);
	
	?>
	<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'checkout', array('op' => 2)); ?>" name="shopping">
	<?php
	set_csrf_key();

	echo load_view(array($model['order_shop']->forms, array('name', 'last_name', 'enterprise_name', 'email', 'nif', 'address', 'zip_code', 'city', 'region', 'country', 'phone', 'fax'), ''), 'common/forms/modelform');
	
	if($config_shop['yes_transport']==1)
	{
		?>
		<div class="form">
		<p><label for="click"><?php echo $lang['shop']['send_address_equal_shopping_address']; ?></label> <?php echo $lang['common']['yes']; ?><input type="radio" name="click" onclick="javascript:add_data_transport();"/></p>
		</div>
		<?php
		echo '<h3>'.$lang['shop']['address_transport'].'</h3>';

		//'zone_transport'

		echo load_view(array($model['order_shop']->forms, array('name_transport', 'last_name_transport', 'enterprise_name_transport', 'address_transport', 'zip_code_transport', 'city_transport', 'region_transport', 'country_transport', 'phone_transport'), ''), 'common/forms/modelform');

	}

	if($user_data['IdUser']==0)
	{

		echo '<h3>'.$lang['common']['register_user'].'</h3>';

		echo '<p>'.$lang['shop']['register_user_if_not_register'].'</p>';

		$model['user']->forms['password']->required=1;
		$model['user']->forms['repeat_password']->required=1;

		echo load_view(array($set_user_form, array('private_nick', 'password', 'repeat_password'), ''), 'common/forms/modelform');

	}

	?>
	<hr />
	<h3><?php echo $lang['shop']['observations']; ?></h3>
	<p><?php echo $lang['shop']['observations_text']; ?></p>
	<div class="form">
	<?php
	echo TextareaForm('observations', "form", "");
	?>
	</div>
	<hr />
	<h3><?php echo $lang['shop']['terms_of_sale']; ?></h3>
	<p><?php echo $lang['shop']['accept_terms_of_sale_push_send_button']; ?></p>
	
	<div class="form">
	<iframe src="<?php echo make_fancy_url($base_url, 'shop', 'conditions', $lang['shop']['conditions'], array()); ?>"></iframe>
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
	<p><input type="submit" value="<?php echo $lang['common']['send']; ?>" /></p>
	</form>
	<?php

}

function obtain_transport_price($total_weight, $total_price, $idtransport)
{

	global $model;

	$query=$model['transport']->select('where IdTransport='.$idtransport, array('type'));
	
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
	
		$query=webtsys_query('select price from price_transport_price where min_price>='.$total_price.' and idtransport='.$idtransport.' order by price ASC limit 1');
			
		list($price_transport)=webtsys_fetch_row($query);

		settype($price_transport, 'double');
		
		if($price_transport>0)
		{

			return array($price_transport, 1);

		}
		else
		{

			$min_price_substract=0;
			$price_transport=0;
			$total_price_transport=0;

			$query=webtsys_query('select min_price, price from price_transport_price order by price DESC limit 1');

			list($max_min_price, $max_price)=webtsys_fetch_row($query);

			//Tenemos que ver en cuanto supera los kilos...

			//Dividimos y obtenemos el resto...

			if($max_min_price==0)
			{

				$max_min_price=1;

			}

			$num_packs=($total_price/$max_min_price)-1;
			
			for($x=0;$x<$num_packs;$x++)
			{

				$total_price_transport+=$max_price;
				$min_price_substract+=$max_min_price;

			}

			$min_price_last=$total_min_price-$min_price_substract;
		
			$query=webtsys_query('select price from price_transport_price where min_price>='.$min_price_last.' and idtransport='.$idtransport.' order by price ASC limit 1');
			
			list($price_transport)=webtsys_fetch_row($query);

			settype($price_transport, 'double');
			
			$total_price_transport+=$price_transport;

			$num_packs=ceil($num_packs+1);

			return array($total_price_transport, $num_packs);
			
		}
	
	}

}

function show_total_prices($sha1_token, $type_cart=0)
{

	global $lang, $config_shop, $model, $arr_order_shop;

	//obtain products...
	
	list($total_sum, $total_weight, $total_taxes, $discount_name, $discount_principal, $discount_taxes, $discount_transport, $discount_payment)=show_cart_simple($sha1_token, 0, $type_cart);
	
	$name_transport=0;
	$price_total_transport=0;
	$price_total_transport_original=0;
	
	//obtain prices for transport...
					
	if($config_shop['yes_transport']==1)	
	{

		echo '<h3><strong>'.$lang['shop']['price_transport'].'</strong></h3>';
		
		list($price_total_transport, $num_packs)=obtain_transport_price($total_weight, $total_sum, $arr_order_shop['transport']);
		
		$query=$model['transport']->select('where IdTransport='.$arr_order_shop['transport'], array('name'));
		
		list($name_transport)=webtsys_fetch_row($query);

		?>
		
		<p><strong><?php echo $lang['shop']['order_sended_with']; ?></strong> <?php echo $name_transport; ?></p>
		<p><strong><?php echo $lang['shop']['price_total_transport']; ?>:</strong> <?php echo MoneyField::currency_format($price_total_transport); ?></p>

		<?php

		//Here discount transport...

		$price_total_transport_original=$price_total_transport;

		if($discount_transport>0)
		{
			$substract_transport=$price_total_transport*($discount_transport/100);

			$price_total_transport-=$substract_transport;

			echo '<p><strong>'.$lang['shop']['discount_transport'].':</strong> '.number_format($discount_transport, 2).'% | <span style="text-decoration: line-through;">'.MoneyField::currency_format($price_total_transport_original).'</span> &nbsp; -> '.MoneyField::currency_format($price_total_transport).'</p>';
	
		}
		
		//$total_sum+=$price_total_transport;
		
	}

	$price_payment=0;

	$query=$model['payment_form']->select('where IdPayment_form='.$arr_order_shop['payment_form'], array('name', 'price_payment'));

	list($name_payment_form, $price_payment)=webtsys_fetch_row($query);
	
	$name_payment_form=I18nField::show_formatted($name_payment_form);

	settype($price_payment, 'double');

	echo '<h3><strong>'.$lang['shop']['shipping_costs'].'</strong></h3>';
	
	?>
		
	<p><strong><?php echo $lang['shop']['payment_with']; ?></strong> <?php echo $name_payment_form; ?></p>
	<p><strong><?php echo $lang['shop']['price_payment']; ?>:</strong> <?php echo MoneyField::currency_format($price_payment); ?> </p>

	<?php

	$price_payment_original=$price_payment;

	if($discount_payment>0)
	{
		
		$substract_payment=$price_payment*($discount_payment/100);

		$price_payment-=$substract_payment;

		echo '<p><strong>'.$lang['shop']['discount_payment'].':</strong> '.number_format($discount_payment, 2).'% | <span style="text-decoration: line-through;">'.MoneyField::currency_format($price_payment_original).'</span>&nbsp; -> '.number_format($price_payment, 2).'</p>';

	}

	//Here discount payment...

	echo '<h2>'.$lang['shop']['total_price_with_all_payments_and_discounts'].'</h2>';

	$total_sum_final=$total_sum+$price_total_transport+$price_payment;
	
	echo '<p style="font-size:18px;">'.MoneyField::currency_format( $total_sum_final  ).'</p>';

	//$total_sum+=$price_payment;
	
	//Here add discounts...
	
	/*list($total_sum_final, $text_discount)=obtain_discounts($total_sum, $total_taxes, $price_total_transport, $price_payment);

	echo $text_discount;

	echo '<h3>'.$lang['shop']['total_price_with_all_payments_and_discounts'].'</h3>';

	echo '<p>'.number_format ( $total_sum_final , 2 ).' &euro;</p>';*/

	return array($total_sum_final, $discount_name, $discount_principal, $discount_taxes, $name_transport, $price_total_transport_original, $discount_transport, $name_payment_form, $price_payment_original, $discount_payment);

}

function obtain_discounts($total_sum, $price_total_taxes)
{

	global $user_data, $config_shop, $lang, $model;
	//Add discount if in group...
	
	ob_start();

	echo '<h3>'.$lang['shop']['discounts'].'</h3>';

	$z=0;
	$discounts=0;
	$no_transport=array();
	$no_taxes=array();
	$no_shipping_costs=array();
	$discount_taxes=0;
	$discount_transport=0;
	$discount_payment=0;

	//Choose the group discount more big for this user...

	$query=$model['group_shop_users']->select('where group_shop_users.iduser='.$user_data['IdUser'].' order by group_shop_discount DESC, group_shop_transport_for_group DESC, group_shop_shipping_costs_for_group DESC limit 1');
	
	/*while($arr_group=webtsys_fetch_array($query))
	{*/
	$arr_group=webtsys_fetch_array($query);

	if($arr_group['group_shop_discount']>0)
	{

		$arr_group['group_shop_name']=$model['group_shop']->components['name']->show_formatted($arr_group['group_shop_name']);

		$division=100/$arr_group['group_shop_discount'];
		
		$discounts+=($total_sum/$division);
		
		$no_transport[]=$arr_group['group_shop_transport_for_group'];
		$no_taxes[]=$arr_group['group_shop_taxes_for_group'];
		$no_shipping_costs[]=$arr_group['group_shop_shipping_costs_for_group'];

		echo '<p>'.$arr_group['group_shop_name'].'</p>';

		$total_sum_original=$total_sum;

		$total_sum-=$discounts;

		echo '<p><strong>'.$lang['shop']['discounts'].'</strong>: '.number_format($arr_group['group_shop_discount'], 2).'% | <span style="text-decoration: line-through;">'.MoneyField::currency_format($total_sum_original).' </span>&nbsp;-> '.MoneyField::currency_format($discounts).'</p>';

	}

	//create new taxes for new price...

	$taxes_calculated=calculate_taxes($config_shop['idtax'], $total_sum);
	
	//Now discounts

	//Taxes are added to raw price discount
	
	if(count($no_taxes)>0)
	{
		echo '<h3>'.$lang['shop']['taxes_calculated_in_new_price'].'</h3>';

		echo '<p><strong>'.$lang['shop']['new_taxes_calculated'].': </strong>'.MoneyField::currency_format($taxes_calculated).'</p>';

		//echo $taxes_total_transport;
		
		$discount_taxes=max($no_taxes);
		
		if($discount_taxes>0)
		{

			$discount_taxes_calculated=$taxes_calculated/(100/$discount_taxes);
			
			//$total_sum+=($taxes_calculated-$discount_taxes_calculated);

			$taxes_calculated_orig=$taxes_calculated;

			$taxes_calculated-=$discount_taxes_calculated;

			echo '<p><strong>'.$lang['shop']['discount_taxes'].'</strong>: '.number_format($discount_taxes, 2).'% | <span style="text-decoration: line-through;">'.MoneyField::currency_format($taxes_calculated_orig).' </span>&nbsp;-> '.MoneyField::currency_format($taxes_calculated).'</p>';
		
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

	$text_return=ob_get_contents();

	ob_end_clean();

	return array($total_sum, $text_return, $arr_group['group_shop_name'], $arr_group['group_shop_discount'], $discount_taxes, $discount_transport, $discount_payment);

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
	
	$query=$model['product']->select('where IdProduct IN ('.implode(',', array_keys($arr_id) ).')', array('IdProduct', 'title', 'price', 'weight', 'idtax'), 1);
	
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

function add_discount_to_element($arr_discount, $price_total_element, $lang_discount)
{

	$discount_element=max($arr_discount);
	$discount_element_final=0;

	if($discount_element>0)
	{

		echo '<p><strong>'.$lang_discount.'</strong>: '.$discount_element.'%</p>';
		
		$discount_element_final=$price_total_element/(100/$discount_element);
	
	}

	return $discount_element_final;


}

?>
