<?php
function CheckOptionsProduct()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop;

	load_lang('shop');
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');

	load_model('shop');

	settype($_GET['IdProduct'], 'integer');

	$return_options=0;

	$query=$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('extra_options'));
	
	list($extra_options)=webtsys_fetch_row($query);
	
	if($extra_options!='')
	{

		$return_options=1;

	}

	$jsondata['check_options_product']=$return_options;

	echo json_encode($jsondata);

}

?>