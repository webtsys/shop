<?php

global $base_url, $lang, $model, $arr_taxes, $lang_taxes, $config_shop;

load_lang('shop');
load_model('shop');
load_libraries(array('utilities/wrap_words'));
load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');

echo '<ul>';

$query=$model['cat_product']->select('where subcat=0', array('IdCat_product', 'title'));

while(list($idcat_product, $title)=webtsys_fetch_row($query))
{

	$title=I18nField::show_formatted($title);

	echo '<li><a href="'.make_fancy_url($base_url, 'shop', 'viewcategory', $title, array('IdCat_product' => $idcat_product)).'">'.$title.'</a></li>';

}

echo '</ul>';

?>