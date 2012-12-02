<?php

function ViewCategoriesView($arr_title_cat, $arr_description_cat)
{

	global $base_url, $lang, $model;
	
	foreach($arr_title_cat as $idcat => $title_cat)
	{
	
		$description_cat=$arr_description_cat[$idcat];

		echo load_view(array($title_cat, $description_cat.'<p><a href="'.make_fancy_url($base_url, 'shop', 'viewcategory', 'viewcategory', array('IdCat_product' => $idcat) ).'" class="see_products">'.$lang['shop']['see_products'].'</a></p>'), 'content');

	}
}

?>