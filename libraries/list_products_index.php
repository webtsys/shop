<?php

function list_products_index($where)
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $lang_taxes, $arr_taxes;
	
	load_libraries(array('pages'));

	$num_news=$config_shop['num_news'];
	
	//Get ids for get images...

	$arr_id=array();
	$arr_photo=array();
	$arr_idcat=array();
	
	$model['product']->related_models=array('product_relationship' => array('idproduct', 'idcat_product'));
	
	$query=$model['product']->select($where.' limit '.$num_news, array($model['product']->idmodel), false);

	while(list($idproduct, $idproduct_rel, $idcat)=webtsys_fetch_row($query))
	{
		
		$arr_id[]=$idproduct;
		$arr_idcat[$idcat]=0;

	}
	
	$query=$model['image_product']->select('where idproduct IN (\''.implode("', '", $arr_id).'\') and principal=1', array('photo', 'idproduct'), true);

	while(list($photo, $idproduct)=webtsys_fetch_row($query))
	{

		$arr_photo[$idproduct]=$photo;

	}
	
	//Obtain view_only_mode for cats...
	
	$query=$model['cat_product']->select('where IdCat_product IN (\''.implode("', '", array_keys($arr_idcat)).'\')', array('IdCat_product', 'view_only_mode'));
	
	while(list($idcat, $view_only_mode)=webtsys_fetch_row($query))
	{
	
		$arr_idcat[$idcat]=$view_only_mode;
	
	}

	$image='';

	$idtax=$config_shop['idtax'];
	
	$total_elements=$model['product']->select_count($where, 'IdProduct');
	
	//Now, set where with searchs...
	
	//Now select products...
	
	$arr_product=$model['product']->select_to_array($where.' limit '.$_GET['begin_page'].', '.$num_news, array());
	
	echo load_view(array($arr_idcat, $arr_product, $arr_photo, $total_elements), 'shop/viewindex');
	

}

?>