<?php

function AttachmentsExternalShow($token)
{

	global $model, $lang, $base_url, $base_path, $language, $config_shop, $user_data, $arr_i18n, $header, $arr_block, $arr_plugin_list, $arr_plugin_product_list;
	
	load_model('shop/attachmentsexternal');
	load_lang('shop_attachmentsexternal');
	
	//Now get the products with files in cart_shop.
	
	$arr_idproduct=array();
	
	$query=$model['cart_shop']->select('where token="'.$token.'"', array('idproduct'));
	
	while(list($idproduct)=webtsys_fetch_row($query))
	{
	
		$arr_idproduct[]=$idproduct;
	
	}
	
	$num_count=$model['external_attachments']->select_count('where idproduct IN ('.implode(', ', $arr_idproduct).')', 'IdExternal_attachments');
	
	if($num_count>0)
	{
	
		echo '<h3>'.$lang['shop_attachmentsexternal']['important_information'].'</h3>';
		echo '<p>'.$lang['shop_attachmentsexternal']['you_buy_product_attachment_can_download_this_file'].'</p>';
	
	}

}


?>