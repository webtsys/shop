<?php

function ChangeCurrency()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $lang_taxes;

	load_lang('shop');
	load_model('shop');
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');
	
	if($_SERVER['HTTP_REFERER']=='')
	{

		$_SERVER['HTTP_REFERER']=$base_url;

	}

	settype($_GET['idcurrency'], 'integer');

	$num_currency=$model['currency']->select_count('where IdCurrency='.$_GET['idcurrency'], 'IdCurrency');

	if($num_currency==1)
	{

		$_SESSION['idcurrency']=$_GET['idcurrency'];

	}

	header('Location: '.$_SERVER['HTTP_REFERER']);

	

}

?>
