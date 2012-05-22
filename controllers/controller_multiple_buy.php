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

	if($user_data['IdUser']>0 && $config_shop['view_only_mode']==0)
	{

		$arr_product=array();

		foreach($_POST['idproduct'] as $idproduct => $num_units)
		{

			$arr_product[$idproduct]=$num_units;

		}
		
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

		

		$query=$model['product']->select('where IdProduct IN ('.implode(',', $arr_idproduct).')', array('IdProduct', 'referer', 'stock', 'price', 'special_offer', 'about_order', 'extra_options' ));
		
		while(list($idproduct, $referer, $stock, $price, $special_offer, $about_order, $extra_options)=webtsys_fetch_row($query))
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
		
		$redirect_url=make_fancy_url($base_url, 'shop', 'cart', 'cart', array()); //$base_url.'/shop/cart.php';

		load_libraries(array('redirect'));
		die( redirect_webtsys( $redirect_url, $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );

	}

}

?>