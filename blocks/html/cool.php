<?php

global $base_url, $lang, $model, $arr_taxes, $lang_taxes, $config_shop;

load_lang('shop');
load_model('shop');
load_libraries(array('utilities/wrap_words'));
load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');

$arr_prod=array();
$arr_image=array();

$query=$model['product']->select('where cool=1 order by title DESC limit 5', array('IdProduct', 'title', 'description'));

while(list($idproduct, $title_product, $desc_product)=webtsys_fetch_row($query))
{

	$arr_prod[$idproduct]=array('title' => I18nField::show_formatted($title_product), 'description' => wrap_words(I18nField::show_formatted($desc_product), 15));

}

if(count($arr_prod)>0)
{

	$query=$model['image_product']->select('where idproduct IN ('.implode(', ', array_keys($arr_prod) ).') and principal=1', array('IdImage_product', 'idproduct', 'photo'), 1);

	while(list($idimage, $idproduct, $photo)=webtsys_fetch_row($query))
	{

		$arr_image[$idproduct]=$model['image_product']->components['photo']->url_path.'/small_'.$photo;

	}

	echo load_view(array($arr_prod, $arr_image), 'shop/list_block', 'shop');

}

?>