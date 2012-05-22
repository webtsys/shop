<?php
function BuyProduct()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop;

	load_lang('shop');
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');

	load_model('shop');

	settype($_GET['IdProduct'], 'integer');

	//Only for buy products without options.

	$buy_return=0;

	$query=$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('price', 'special_offer','extra_options'));
	
	list($price, $special_offer, $extra_options)=webtsys_fetch_row($query);
	 
	if($extra_options=='')
	{

		//No extra_options, add to cart...

		$buy_return=add_cart($arr_details=array(), $price, $special_offer, $redirect=0);

		
		

	}

	$jsondata['buy_return']=$buy_return;

	echo json_encode($jsondata);

}

?>