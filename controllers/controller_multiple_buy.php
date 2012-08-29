<?php

function Multiple_Buy()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $lang_taxes;

	ob_start();

	$arr_block=select_view(array('shop'));

	/*include($base_path.'themes/'.$config_data['dir_theme'].'/'.$arr_block.'.php');
	include($base_path.'models/shop.php');*/
	load_lang('shop');
	load_model('shop');

	//Check if exists the id...

	settype($_POST['idproduct'], 'array');
	settype($_GET['action'], 'integer');

	load_lang('shop');

	//settype($_POST['num_product'], 'integer');

	//print_r($_POST);
	
	$arr_product=array(0);
	$arr_cat_view=array(0);
	$arr_check_product=array();

	foreach($_POST['idproduct'] as $idproduct => $num_units)
	{

		$arr_product[$idproduct]=$num_units;

	}
	
	$query=$model['product']->select( 'where IdProduct IN ('.implode(',', $arr_product).')', array('IdProduct', 'idcat'), 1);
	
	//Reset the array...
	
	while(list($idproduct, $idcat)=webtsys_fetch_row($query))
	{
	
		$arr_check_product[$idproduct]=1;
		$arr_cat_view[$idcat]=1;
	
	}
	
	$query=$model['cat_product']->select( 'where IdCat_product IN ('.implode(',', array_keys($arr_cat_view) ).')', array('IdCat_product', 'view_only_mode'), 1);
	
	while(list($idcat, $view_only_mode)=webtsys_fetch_row($query))
	{
	
		$arr_cat_view[$idcat]=$view_only_mode;
	
	}

	if($user_data['IdUser']>0 && $config_shop['view_only_mode']==0 && count($arr_check_product)>0)
	{
		
		$arr_idproduct=array_keys($arr_product);
		
		$arr_idproduct[]=0;

		settype($_COOKIE['webtsys_shop'], 'string');

		$token=$_COOKIE['webtsys_shop'];
		
		$query=$model['cart_shop']->select('where token="'.sha1($token).'" limit 1');
		
		$arr_cart=webtsys_fetch_array($query);

		settype($arr_cart['IdCart_shop'], 'integer');

		if($arr_cart['IdCart_shop']==0)
		{

			$token=sha1(uniqid(rand(), true));

			setcookie  ( 'webtsys_shop', $token, 0, $cookie_path);

		}

		

		$query=$model['product']->select('where IdProduct IN ('.implode(',', $arr_idproduct).')', array('IdProduct', 'referer', 'stock', 'price', 'special_offer', 'about_order', 'extra_options' ,'idcat'));
		
		while(list($idproduct, $referer, $stock, $price, $special_offer, $about_order, $extra_options, $idcat)=webtsys_fetch_row($query))
		{

			if($arr_cat_view[$idcat]==0)
			{
				
				if($special_offer>0)
				{

					$price=$special_offer;

				}

				$num_units=$arr_product[$idproduct];
				
				for($x=0;$x<$num_units;$x++)
				{

					$query2=$model['cart_shop']->insert( array('token' => sha1($token), 'idproduct' => $idproduct, 'time' => time(), 'price_product' => $price) );
					
				}
				
				if(isset($_SESSION['products'][$idproduct]))
				{	
					unset($_SESSION['products'][$idproduct]);
				}
				
			}

		}
		
		$redirect_url=make_fancy_url($base_url, 'shop', 'cart', 'cart', array()); //$base_url.'/shop/cart.php';

		load_libraries(array('redirect'));
		die( redirect_webtsys( $redirect_url, $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );

	}

}

?>