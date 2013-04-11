<?php

function ViewCategory()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $lang_taxes;

	load_lang('shop');
	load_model('shop');
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');
	load_libraries(array('pages', 'forms/selectmodelformbyorder', 'generate_admin_ng', 'utilities/hierarchy_links'));

	$cont_index='';

	$arr_block=select_view(array('shop'));

	//Load page...

	settype($_GET['IdCat_product'], 'integer');
	
	$num_news=$config_shop['num_news'];
	
	//Obtain category...
	
	$arr_cat=$model['cat_product']->select_a_row($_GET['IdCat_product'], array(), 1);
	
	settype($arr_cat['IdCat_product'], 'integer');
	
	//$where_sql='where idcat='.$arr_cat['IdCat_product'];
	$where_sql='where product_relationship.idcat_product='.$arr_cat['IdCat_product'];
	
	if($arr_cat['IdCat_product']==0)
	{
	
		$arr_cat['title']=$lang['shop']['all_products'];
		$arr_cat['description']=$lang['shop']['desc_all_products'];
		$arr_cat['subcat']=0;
		$arr_cat['view_only_mode']=$config_shop['view_only_mode'];
		$where_sql='';
	
	}
	else
	{
	
		$arr_cat['title']=I18nField::show_formatted($arr_cat['title']);
		$arr_cat['description']=I18nField::show_formatted($arr_cat['description']);
	
	}
	
	$model['product']->related_models=array('product_relationship' => array('idproduct'));
	
	$model['product']->create_form();
	
	$url_options=make_fancy_url($base_url, 'shop', 'viewcategory', 'viewcategory', array('IdCat_product' => $_GET['IdCat_product']));
	
	$arr_fields_orders=array('date', 'title_'.$_SESSION['language']);
	$arr_fields_search=array('title_'.$_SESSION['language']);
	
	$model['product']->forms['title_'.$_SESSION['language']]->label=$lang['common']['title'];
	$model['product']->forms['date']->label=$lang['common']['date'];
	
	$cont_search='';
	
	ob_start();
	
	list($where_sql, $arr_where_sql, $location, $arr_order)=SearchInField('product', $arr_fields_orders, $arr_fields_search, $where_sql, $url_options, 0);
	
	$cont_search=ob_get_contents();
	
	ob_end_clean();
	
	$where_sql.=$arr_where_sql.' order by '.$location.'`'.$_GET['order_field'].'` '.$arr_order[$_GET['order_desc']];
	
	$total_elements=$model['product']->select_count($where_sql, 'IdProduct');
	
	//Now, set where with searchs...
	
	//Now select products...
	
	$arr_product=$model['product']->select_to_array($where_sql.' limit '.$_GET['begin_page'].', '.$num_news, array());
	
	//Select ids...
	
	$arr_id=array_keys($arr_product);
	
	$arr_id[]=0;
	
	//Select images...
	
	$arr_photo=array();
	
	$query=$model['image_product']->select('where idproduct IN (\''.implode("', '", $arr_id).'\') and principal=1', array('photo', 'idproduct'), true);

	while(list($photo, $idproduct)=webtsys_fetch_row($query))
	{

		$arr_photo[$idproduct]=$photo;

	}
	
	echo load_view(array($arr_cat, $arr_product, $arr_photo, $cont_search, $total_elements), 'shop/viewcategory');
	
	$cont_index.=ob_get_contents();

	ob_end_clean();

	//$arr_block($title_category, $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, '');
	echo load_view(array($arr_cat['title'], $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ''), $arr_block);

}

?>
