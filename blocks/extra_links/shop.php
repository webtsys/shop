<?php

$arr_select=array();

$select_page[]=$lang['shop_admin']['shop_categories'];
$select_page[]='optgroup';

load_lang('shop');
load_model('shop');

$myquery=$model['cat_product']->select('order by subcat ASC, title ASC', array($model['cat_product']->idmodel , 'title', 'subcat') );

while(list($id, $title, $subcat)=webtsys_fetch_row($myquery))
{

	$title=$model['cat_product']->components['title']->show_formatted($title);

	$arr_select[$subcat][$id]=array(ucfirst($title), make_fancy_url($base_url, 'shop', 'viewcategory', $title, array('IdCat_product' => $id) ) );

}

settype($arr_select[0], 'array');

foreach($arr_select[0] as $key => $arr_opt)
{

	$select_page[]=$arr_opt[0];//$model['cat_product']->components['title']->show_formatted($arr_opt[0]);
	$select_page[]=$arr_opt[1];

	settype($arr_select[$key], 'array');

	foreach($arr_select[$key] as $anot_opt)
	{

		$select_page[]='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$anot_opt[0];
		$select_page[]=$anot_opt[1];

	}

}

$select_page[]='';
$select_page[]='end_optgroup';

?>
