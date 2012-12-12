<?php

function DownloadFile()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $arr_taxes, $arr_order_shop, $language, $lang_taxes;

	$arr_block='';

	$cont_index='';

	$cont_cart='';
	
	load_lang('shop');
	load_model('shop');
	
	load_model('shop/attachmentsexternal');
	load_lang('shop_attachmentsexternal');

	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');
	
	if($user_data['IdUser']>0)
	{

		settype($_GET['IdExternal_attachments'], 'integer');
		
		$query=$model['external_attachments']->select('where IdExternal_attachments='.$_GET['IdExternal_attachments'], array('name', 'file', 'idproduct'), true);
		
		list($name, $file, $idproduct)=webtsys_fetch_row($query);
		
		settype($idproduct, 'integer');
		
		$arr_token=array();
	
		$query=$model['order_shop']->select('where iduser='.$user_data['IdUser'].' and make_payment=1', array('token'));
		
		while(list($token)=webtsys_fetch_row($query))
		{
		
			$arr_token[]=$token;
		
		}
		
		$query=$model['cart_shop']->select('where token IN (\''.implode('\', \'', $arr_token).'\') and cart_shop.idproduct='.$idproduct, array('idproduct'), true);
		
		list($idproduct_final)=webtsys_fetch_row($query);
		
		settype($idproduct_final, 'integer');
		
		if($idproduct_final>0)
		{
		
			//Download file...
			
			$file_download=$model['external_attachments']->components['file']->path.'/'.$file;
			
			header( "Content-disposition: filename=" . $file );
			header( "Content-type: application/octet-stream" );
			header( "Pragma: no-cache" );
			header( "Expires: 0" );

			readfile( $file_download );
			
			//echo file_get_contents($file_download);
		
		}
	
	}
	
	/*$arr_block=select_view(array('shop'));
	
	//In cart , blocks showed are none always...

	$arr_block='/none';

	load_lang('shop');
	load_model('shop');
	
	load_model('shop/attachmentsexternal');
	load_lang('shop_attachmentsexternal');

	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');
	
	if($user_data['IdUser']>0)
	{
	
		$query=$model['order_shop']->select('where iduser='.$user_data['IdUser'], array('token'));
		
		list($token)=webtsys_fetch_row($query);
		
		$query=$model['cart_shop']->select('where token="'.$token.'"', array('idproduct'));
		
		while(list($idproduct)=webtsys_fetch_row($query))
		{
		
			$arr_idproduct[]=$idproduct;
		
		}
		
		echo '<ul>';
		
		$query=$model['external_attachments']->select('where external_attachments.idproduct IN ('.implode(', ', $arr_idproduct).')', array(), true);
		
		while($arr_attachment=webtsys_fetch_array($query))
		{
		
			//print_r($arr_attachment);
			$url_download=make_fancy_url($base_url, 'shop', 'downloadfile', $arr_attachment['name'], array('IdProduct' => $arr_attachment['idproduct']));
			
			echo '<li>'.$arr_attachment['name'].' - <a href="'.$url_download.'">'.$lang['shop_attachmentsexternal']['download_file'].'</a></li>';
		
		}
		
		echo '</ul>';
		
	}
	
	$cont_index=ob_get_contents();

	ob_end_clean();

	echo load_view(array($config_shop['title_shop'], $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ''), $arr_block);*/

}

?>