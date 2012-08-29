<?php

function Buy()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop;

	load_lang('shop'); 
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');

	load_model('shop');

	$arr_block='';

	$arr_block=select_view(array('shop'));

	$arr_block='/none';

	//Check if exists the id...
	
	settype($_GET['IdCart_shop'], 'integer');
	settype($_GET['ajax'], 'integer');
	$sha1_token=@sha1($_COOKIE['webtsys_shop']);
	
	$query=$model['cart_shop']->select('where IdCart_shop='.$_GET['IdCart_shop'].' and token="'.$sha1_token.'"');

	$cart_shop=webtsys_fetch_array($query);

	settype($cart_shop['idproduct'], 'integer');
	
	if($cart_shop['idproduct']>0)
	{

		$_GET['IdProduct']=$cart_shop['idproduct'];
		
	}

	$arr_product=array();

	settype($_GET['IdProduct'], 'integer');
	settype($_GET['action'], 'integer');

	load_lang('shop');
	
	$query=$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('IdProduct', 'title', 'stock', 'about_order', 'extra_options' , 'price', 'special_offer', 'idcat'));

	list($num_prod, $name_product, $stock, $about_order, $extra_options, $price, $special_offer, $idcat)=webtsys_fetch_row($query);
	
	$name_product=$model['product']->components['title']->show_formatted($name_product);
	
	//Obtain product...
	
	settype($idcat, 'integer');
	
	$query=$model['cat_product']->select('where IdCat_product='.$idcat, array('view_only_mode'));
	
	list($view_only_mode)=webtsys_fetch_row($query);
	
	//$config_shop['view_only_mode']==0
	
	if($num_prod>0 && ( $stock>0 || $about_order==1 ) && ($config_shop['view_only_mode']==0 && $view_only_mode==0))
	{	

		ob_clean();

		if(file_exists($base_path.'modules/shop/options/'.basename($extra_options)) && $extra_options!='')
		{

			settype($_GET['delete_products'], 'integer');

			
			if($_GET['delete_products']==0)
			{

				include($base_path.'modules/shop/options/'.basename($extra_options));

				echo '<p><a href="'.make_fancy_url($base_url, 'shop', 'cart', 'modify_product_options', array('op' => 4, 'IdProduct' => $num_prod)).'">'.$lang['shop']['go_back_cart'].'</a></p>';

				$cont_buy=ob_get_contents();

				ob_clean();

				echo load_view(array($lang['shop']['product_options'], $cont_buy), 'content');

				$cont_index=ob_get_contents();
			
				ob_end_clean();
			
				$arr_block=select_view(array('shop'));
				$arr_block='/none';
				
				echo load_view(array($lang['shop']['shopping_cart'], $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ''), $arr_block);

			}
			else
			{

				$arr_del_product=array(0);

				settype($_POST['idproduct'], 'array');

				foreach($_POST['idproduct'] as $key_product => $value_prod)
				{

					settype($key_product, 'integer');

					$arr_del_product[]=$key_product;

				}

				//echo implode(', ', $arr_del_product);

				$redirect_url=make_fancy_url($base_url, 'shop', 'cart', 'modify_product_options', array('op' => 4, 'IdProduct' => $_GET['IdProduct']));

				$query=$model['cart_shop']->delete('where IdCart_shop IN ('.implode(', ', $arr_del_product).') and token="'.$sha1_token.'"');

				ob_end_clean();

				load_libraries(array('redirect'));
				die( redirect_webtsys( $redirect_url, $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );

			}

		}
		else
		{

			settype($_POST['num_products'], 'integer');
			settype($_GET['add_more_units'], 'integer');
	
			$num_products=$model['cart_shop']->select_count('where cart_shop.idproduct='.$_GET['IdProduct'].' and token="'.$sha1_token.'"', 'IdProduct');

			$redirect_url=make_fancy_url($base_url, 'shop', 'cart', 'modify_product_options', array('op' => 4, 'IdProduct' => $_GET['IdProduct']));

			if($_GET['add_more_units']==1)
			{
			
				if($_POST['num_products']==0)
				{

					/*$redirect_url=make_fancy_url($base_url, 'shop', 'cart', 'cart', array(''));

					$query=$model['cart_shop']->delete('where cart_shop.idproduct='.$_GET['IdProduct'].' and token="'.$sha1_token.'"');
			
					load_libraries(array('redirect'));
					die( redirect_webtsys( $redirect_url, $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );*/

					delete_all_products($_GET['IdProduct'], $sha1_token, $arr_block);

				}
				else
				if($_POST['num_products']<$num_products)
				{

					$delete_products=$num_products-$_POST['num_products'];

					$query=$model['cart_shop']->delete('where cart_shop.idproduct='.$_GET['IdProduct'].' and token="'.$sha1_token.'" limit '.$delete_products);

					ob_end_clean();

					load_libraries(array('redirect'));
					die( redirect_webtsys( $redirect_url, $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );


				}
				else
				if($_POST['num_products']>$num_products)
				{

					$add_products=$_POST['num_products']-$num_products;

					//This rutine need optimitation

					for($x=0;$x<$add_products;$x++)
					{

						//add_cart($arr_details=array(), $price=0, $special_offer=0, $redirect=1)
						add_cart(array(), $price, $special_offer, 0);

					}

					ob_end_clean();

					load_libraries(array('redirect'));
					die( redirect_webtsys( $redirect_url, $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );

				}
				else
				{

					ob_end_clean();

					load_libraries(array('redirect'));
					die( redirect_webtsys( $redirect_url, $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );

				}
				
			}
			else
			{
			
				add_cart(array(), $price, $special_offer);
			
			}

			//add_cart(array(), $price, $special_offer);

		}
	}
	else
	{
		
		echo load_view(array($lang['shop']['no_stock'], $lang['shop']['no_stock_for_this_article']), 'content');

		$cont_index=ob_get_contents();

		ob_end_clean();

		$arr_block=select_view(array('shop'));
		
		echo load_view(array($lang['shop']['no_stock'], $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ''), $arr_block);

	}

}

function delete_all_products($idproduct, $sha1_token, $arr_block)
{

	global $lang, $base_url, $model;

	$redirect_url=make_fancy_url($base_url, 'shop', 'cart', 'cart', array(''));

	$query=$model['cart_shop']->delete('where cart_shop.idproduct='.$idproduct.' and token="'.$sha1_token.'"');

	load_libraries(array('redirect'));
	die( redirect_webtsys( $redirect_url, $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );

}


?>
