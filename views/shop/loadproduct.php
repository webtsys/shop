<?php

function LoadProductView($title, $arr_product_view, $idcat_product)
{

	//Show links for categories
		
	$arr_hierarchy_links=hierarchy_links('cat_product', 'subcat', 'title', $idcat_product);

	echo load_view(array($arr_hierarchy_links, 'shop', 'viewcategory', 'IdCat_product', array(), 1), 'common/utilities/hierarchy_links');
	
	ob_start();
	
	echo load_view($arr_product_view, 'shop/product');
	
	$cont_product=ob_get_contents();
	
	ob_end_clean();

	echo load_view(array($title, $cont_product), 'content');

}

?>