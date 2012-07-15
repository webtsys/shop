<?php

echo load_view(array($config_shop['title_shop'], $model['config_shop']->components['description_shop']->show_formatted($config_shop['description_shop']) ), 'content');

$query=$model['cat_product']->select('where subcat=0');

while($arr_cat=webtsys_fetch_array($query))
{

	echo load_view(array($model['cat_product']->components['title']->show_formatted($arr_cat['title']), $model['cat_product']->components['description']->show_formatted($arr_cat['description']).'<p><a href="'.make_fancy_url($base_url, 'shop', 'viewcategory', 'viewcategory', array('IdCat_product' => $arr_cat['IdCat_product']) ).'" class="see_products">'.$lang['shop']['see_products'].'</a></p>'), 'content');

}

?>
