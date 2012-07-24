<?php

function ViewCategoriesView($title_cat, $description_cat, $idcat)
{

	global $base_url, $lang, $model;

	echo load_view(array($title_cat, $description_cat.'<p><a href="'.make_fancy_url($base_url, 'shop', 'viewcategory', 'viewcategory', array('IdCat_product' => $idcat) ).'" class="see_products">'.$lang['shop']['see_products'].'</a></p>'), 'content');

}

?>