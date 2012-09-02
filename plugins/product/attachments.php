<?php

function AttachmentsLink()
{

	global $lang;
	
	load_lang('shop_attachments');

	//Now, attach the game...
	
	return $lang['shop_attachments']['add_attachments'];

}

function AttachmentsAdmin($idproduct)
{

	global $model, $lang, $base_url, $base_path, $language, $config_shop, $user_data, $arr_i18n, $header, $arr_block, $arr_plugin_list, $arr_plugin_product_list;
	
	load_libraries(array('generate_admin_ng'));
	load_lang('shop_attachments');
	
	$arr_fields=array('name');
	$arr_fields_edit=array();
	//http://localhost/phangodev/index.php/admin/show/index/edit_cat_shop/IdModule/8/op/22/IdProduct/10/plugin/attachments/element_choice/product
	
	$url_options=make_fancy_url($base_url, 'admin', 'index', $lang['shop_attachments']['add_attachments'], array('IdModule' => $_GET['IdModule'], 'op' => 22, 'IdProduct' => $idproduct, 'plugin' => 'attachments', 'element_choice' => 'product') );
	
	$model['product_attachments']->components['idproduct']->form='HiddenForm';
	
	$model['product_attachments']->create_form();
	
	$model['product_attachments']->forms['idproduct']->SetForm($idproduct);
	
	$model['product_attachments']->forms['file']->parameters=array($name="file", $class='', $value='', $delete_inline=0, $model['product_attachments']->components['file']->url_path);

	$model['product_attachments']->set_enctype_binary();
	
	generate_admin_model_ng('product_attachments', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where idproduct="'.$idproduct.'"', $arr_fields_form=array(), $type_list='Basic');
	
}

function AttachmentsShow($idproduct)
{

	global $model, $lang, $base_url, $base_path, $language, $config_shop, $user_data, $arr_i18n, $header, $arr_block, $arr_plugin_list, $arr_plugin_product_list;

	$query=$model['product_attachments']->select('where idproduct='.$idproduct, array(), 1);
	
	//Load view..., pass the query?.
	
	return load_view(array($query), 'shop/plugins/attachments');

}

?>