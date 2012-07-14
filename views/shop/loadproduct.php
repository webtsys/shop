<?php

function LoadProductView($title, $cont_index, $idcat_product)
{

	//Show links for categories
		
	$arr_hierarchy_links=hierarchy_links('cat_product', 'subcat', 'title', $idcat_product);

	echo load_view(array($arr_hierarchy_links, 'shop', 'viewcategory', 'IdCat_product', array(), 1), 'common/utilities/hierarchy_links');

	echo load_view(array($title, $cont_index), 'content');

}

?>