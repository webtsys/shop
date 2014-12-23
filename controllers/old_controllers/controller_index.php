<?php

function Index()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $lang_taxes;

	load_lang('shop');
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');
	
	/*$arr_index[0]=$base_path.'modules/shop/libraries/shop.php';
	$arr_index[1]=$base_path.'modules/shop/libraries/categories.php';
	$arr_index[2]=$base_path.'modules/shop/libraries/list.php';
	$arr_index[3]=$base_path.'modules/shop/libraries/bestsellers.php';
	$arr_index[4]=$base_path.'modules/shop/libraries/cool.php';*/
	
	$load_file=$base_path.'modules/shop/libraries/type_index/'.$config_shop['type_index'];
	
	$cont_index='';

	$arr_block='';

	$arr_block=select_view(array('shop'));

	$cont_index.=ob_get_contents();

	ob_clean();

	include($load_file);

	$cont_index.=ob_get_contents();

	ob_end_clean();

	echo load_view(array($config_shop['title_shop'], $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ''), $arr_block);

}

?>