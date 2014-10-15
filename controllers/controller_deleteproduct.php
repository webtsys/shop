<?php

function DeleteProduct()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop;

	load_lang('shop'); 
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');

	load_model('shop');

	$arr_block='';

	$arr_block=select_view(array('shop'));

	$arr_block='/none';

	//Check if exists the id...
	$sha1_token=@sha1($_COOKIE['webtsys_shop']);

	$num_order=$model['order_shop']->select_count('where token="'.$sha1_token.'"', 'IdOrder_shop');

	if($num_order==0)
	{

		$arr_del_product=array(0);

		settype($_POST['idproduct'], 'array');

		foreach($_POST['idproduct'] as $key_product => $value_prod)
		{

			settype($key_product, 'integer');

			$arr_del_product[]=$key_product;

		}

		$redirect_url=make_fancy_url($base_url, 'shop', 'cart', 'cart', array(''));
		
		echo $redirect_url;
		
		$query=$model['cart_shop']->delete('where idproduct IN ('.implode(', ', $arr_del_product).') and token="'.$sha1_token.'"');

		die;
		
		load_libraries(array('redirect'));
		die( redirect_webtsys( $redirect_url, $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );
	
	}

}



?>
