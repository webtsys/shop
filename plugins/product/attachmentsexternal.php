<?php

function AttachmentsExternalLink()
{

	global $lang;
	
	load_lang('shop_attachmentsexternal');

	//Now, attach the game...
	
	return $lang['shop_attachmentsexternal']['add_external_attachments'];

}

function AttachmentsExternalAdmin($idproduct)
{

	global $model, $lang, $base_url, $base_path, $language, $config_shop, $user_data, $arr_i18n, $header, $arr_block, $arr_plugin_list, $arr_plugin_product_list;
	
	load_model('shop/attachmentsexternal');
	load_libraries(array('generate_admin_ng'));
	load_lang('shop_attachments');
	
	$arr_fields=array('name');
	$arr_fields_edit=array();
	//http://localhost/phangodev/index.php/admin/show/index/edit_cat_shop/IdModule/8/op/22/IdProduct/10/plugin/attachments/element_choice/product
	
	$url_options=make_fancy_url($base_url, 'admin', 'index', $lang['shop_attachments']['add_attachments'], array('IdModule' => $_GET['IdModule'], 'op' => 22, 'IdProduct' => $idproduct, 'plugin' => 'attachmentsexternal', 'element_choice' => 'product') );
	
// 	$model['external_attachments']->components['idproduct']->form='HiddenForm';
	
	$model['external_attachments']->create_form();
	
	$model['external_attachments']->forms['idproduct']->form='HiddenForm';
	
	$model['external_attachments']->forms['idproduct']->SetForm($idproduct);
	
	$model['external_attachments']->forms['file']->parameters=array($name="file", $class='', $value='', $delete_inline=0, $model['external_attachments']->components['file']->url_path);

	$model['external_attachments']->set_enctype_binary();
	
	generate_admin_model_ng('external_attachments', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where idproduct="'.$idproduct.'"', $arr_fields_form=array(), $type_list='Basic');
	
}

function AttachmentsExternalShow($idproduct)
{

	global $model, $lang, $base_url, $base_path, $language, $config_shop, $user_data, $arr_i18n, $header, $arr_block, $arr_plugin_list, $arr_plugin_product_list;
	
	load_lang('shop_attachmentsexternal');
	
	return '<p>'.$lang['shop_attachmentsexternal']['when_you_pay_the_product_you_download_file'].'</p>';

	//$query=$model['external_attachments']->select('where idproduct='.$idproduct, array(), 1);
	
	//Load view..., pass the query?.
	
	//return load_view(array($query), 'shop/plugins/attachments');

}


?>