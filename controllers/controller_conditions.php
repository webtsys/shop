<?php

function Conditions()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $lang_taxes;

	load_lang('shop');
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');
	
	$cont_index='';

	$arr_block='';

	$arr_block=select_view(array('shop'));
	
	echo load_view(array($lang['shop']['conditions'], $config_shop['conditions']), 'content');

	$cont_index.=ob_get_contents();

	ob_end_clean();

	echo load_view(array($config_shop['title_shop'], $cont_index), 'shop/conditions');

}

?>