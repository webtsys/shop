<?php

$query=$model['cat_product']->select('where subcat=0');

while($arr_cat=webtsys_fetch_array($query))
{

	$arr_title_cat[$arr_cat['IdCat_product']]=$model['cat_product']->components['title']->show_formatted($arr_cat['title']);
	$arr_description_cat[$arr_cat['IdCat_product']]=$model['cat_product']->components['description']->show_formatted($arr_cat['description']);
	
}

echo load_view(array($arr_title_cat, $arr_description_cat), 'shop/viewcategories');

?>
