<?php

function ViewDownloads()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $arr_taxes, $arr_order_shop, $language, $lang_taxes;

	$arr_block='';

	$cont_index='';

	$cont_cart='';

	$arr_block=select_view(array('shop'));
	
	//In cart , blocks showed are none always...

	$arr_block='/none';

	load_lang('shop');
	load_model('shop');
	
	load_model('shop/attachmentsexternal');
	load_lang('shop_attachmentsexternal');

	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');
	
	if($user_data['IdUser']>0)
	{
	
		$arr_token=array();
	
		$query=$model['order_shop']->select('where iduser='.$user_data['IdUser'].' and make_payment=1', array('token'));
		
		while(list($token)=webtsys_fetch_row($query))
		{
		
			$arr_token[]=$token;
		
		}
		
		$query=$model['cart_shop']->select('where token IN (\''.implode('\', \'', $arr_token).'\')', array('idproduct'));
		
		while(list($idproduct)=webtsys_fetch_row($query))
		{
		
			$arr_idproduct[]=$idproduct;
		
		}
		
		echo '<ul>';
		
		$query=$model['external_attachments']->select('where external_attachments.idproduct IN ('.implode(', ', $arr_idproduct).')', array(), true);
		
		while($arr_attachment=webtsys_fetch_array($query))
		{
		
			//print_r($arr_attachment);
			$url_download=make_fancy_url($base_url, 'shop', 'downloadfile', $arr_attachment['name'], array('IdExternal_attachments' => $arr_attachment['IdExternal_attachments']));
			
			echo '<li>'.$arr_attachment['name'].' - <a href="'.$url_download.'">'.$lang['shop_attachmentsexternal']['download_file'].'</a></li>';
		
		}
		
		echo '</ul>';
		
	}
	
	$cont_index=ob_get_contents();

	ob_end_clean();

	echo load_view(array($config_shop['title_shop'], $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ''), $arr_block);

}

?>