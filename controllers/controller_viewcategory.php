<?php

function ViewCategory()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $lang_taxes;

	load_lang('shop');
	load_model('shop');
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');
	load_libraries(array('pages', 'forms/selectmodelformbyorder', 'generate_admin_ng', 'utilities/hierarchy_links'));

	$cont_index='';

	$arr_block=select_view(array('shop'));

	//Load page...

	settype($_GET['IdCat_product'], 'integer');
	
	$num_news=$config_shop['num_news'];
	
	//Obtain category...
	
	$arr_cat=$model['cat_product']->select_a_row($_GET['IdCat_product'], array(), 1);
	
	settype($arr_cat['IdCat_product'], 'integer');
	
	$where_sql='where idcat='.$arr_cat['IdCat_product'];
	
	if($arr_cat['IdCat_product']==0)
	{
	
		$arr_cat['title']=$lang['shop']['all_products'];
		$arr_cat['description']=$lang['shop']['desc_all_products'];
		$arr_cat['subcat']=0;
		$arr_cat['view_only_mode']=$config_shop['view_only_mode'];
		$where_sql='';
	
	}
	
	$model['product']->create_form();
	
	$url_options=make_fancy_url($base_url, 'shop', 'viewcategory', 'viewcategory', array('IdCat_product' => $_GET['IdCat_product']));
	
	$arr_fields_orders=array('title_'.$_SESSION['language'], 'date');
	$arr_fields_search=array('title_'.$_SESSION['language']);
	
	$model['product']->forms['title_'.$_SESSION['language']]->label=$lang['common']['title'];
	$model['product']->forms['date']->label=$lang['common']['date'];
	
	ob_start();
	
	list($where_sql, $arr_where_sql, $location, $arr_order)=SearchInField('product', $arr_fields_orders, $arr_fields_search, $where_sql, $url_options, 0);
	
	$cont_search=ob_get_contents();
	
	ob_end_clean();
	
	$where_sql.=$arr_where_sql.' order by `'.$location.$_GET['order_field'].'` '.$arr_order[$_GET['order_desc']];
	
	//Now, set where with searchs...
	
	//Now select products...
	
	//Select ids...
	
	$arr_id=array(0);
	
	$query=$model['product']->select($where_sql.' limit '.$_GET['begin_page'].', '.$num_news, array($model['product']->idmodel), true);

	while(list($idproduct)=webtsys_fetch_row($query))
	{

		$arr_id[]=$idproduct;

	}
	
	$arr_product=$model['product']->select_to_array($where_sql.' limit '.$_GET['begin_page'].', '.$num_news, array());
	
	//Select images...
	
	$arr_photo=array();
	
	$query=$model['image_product']->select('where idproduct IN (\''.implode("', '", $arr_id).'\') and principal=1', array('photo', 'idproduct'), true);

	while(list($photo, $idproduct)=webtsys_fetch_row($query))
	{

		$arr_photo[$idproduct]=$photo;

	}
	
	/*if(count($arr_product)>0)
	{
	
		$arr_product['images']=&$arr_photo;
		
	}*/
	
	echo load_view(array($arr_cat, $arr_product, $arr_photo, $cont_search), 'shop/viewcategory');
	
	//Load list for objects..

	/*$query=$model['cat_product']->select('where IdCat_product='.$_GET['IdCat_product'], array('IdCat_product', 'title', 'description', 'subcat', 'view_only_mode'));

	list($idcat_product, $title_category, $description_cat, $subcat, $view_only_mode)=webtsys_fetch_row($query);
	
	if($idcat_product==0)
	{
	
		$title_category=$lang['shop']['all_products'];
		$description_cat=$lang['shop']['desc_all_products'];
		$subcat=0;
		$view_only_mode=$config_shop['view_only_mode'];
		$_GET['IdCat_product']=0;
	
	}
	else
	{
	
		$title_category=$model['cat_product']->components['title']->show_formatted($title_category);
		$description_cat=$model['cat_product']->components['description']->show_formatted($description_cat);
	 
	}
	
	settype($idcat_product, 'integer');*/
	
	/*if($idcat_product>0)
	{*/
		
		//echo load_view(array($title_category, $description_cat, $view_only_mode), 'shop/productshow');

		/*$arr_hierarchy_links=hierarchy_links('cat_product', 'subcat', 'title', $_GET['IdCat_product']);

		echo load_view(array($arr_hierarchy_links, 'shop', 'viewcategory', 'IdCat_product', array(), 0), 'common/utilities/hierarchy_links');
	
		ob_start();
		?>
		<form method="get" action="<?php echo make_fancy_url($base_url, 'shop', 'viewcategory', $title_category, array()); ?>">
		<p>
		<?php
		
		echo $lang['shop']['select_category_shop'].': '.SelectModelFormByOrder('IdCat_product', '', $_GET['IdCat_product'], 'cat_product', 'title', 'subcat', $where='');

		?>
		<input type="submit" value="<?php echo $lang['shop']['choose_category']; ?>"/>
		</p>
		<form>
		<?php

		$ob_get_search=ob_get_contents();
		ob_end_clean();

		echo load_view(array($title_category, $description_cat. $ob_get_search), 'content');
		
		$model['product']->create_form();
		
		$model['product']->forms['title']->label=$lang['common']['title'];
		
		$arr_fields=array('title');
		$where_sql='';
		$url_options=make_fancy_url($base_url, 'shop', 'viewcategory', 'viewcategory', array('IdCat_product' => $_GET['IdCat_product']));
		
		list($where_sql, $arr_where_sql, $location, $arr_order)=SearchInField('product', $arr_fields, $where_sql='where idcat='.$_GET['IdCat_product'], $url_options, 0);
		
		$where_sql.=$arr_where_sql.' order by '.$location.$_GET['order_field'].' '.$arr_order[$_GET['order_desc']];
		
		//Get ids for get images...
		
		$num_news=$config_shop['num_news'];

		$arr_id=array();
		$arr_photo=array();

		$query=$model['product']->select($where_sql.' limit '.$num_news, array($model['product']->idmodel), true);

		while(list($idproduct)=webtsys_fetch_row($query))
		{

			$arr_id[]=$idproduct;

		}

		$query=$model['image_product']->select('where idproduct IN (\''.implode("', '", $arr_id).'\') and principal=1', array('photo', 'idproduct'), true);

		while(list($photo, $idproduct)=webtsys_fetch_row($query))
		{

			$arr_photo[$idproduct]=$photo;

		}
		
		$query=$model['product']->select($where_sql.' limit '.$num_news, array($model['product']->idmodel, 'title', 'description', 'price', 'special_offer', 'stock', 'about_order', 'weight'), true);

		$z=0;

		$image='';

		$idtax=$config_shop['idtax'];

		while(list($idproduct, $title, $description, $price, $offer, $stock, $about_order, $weight)=webtsys_fetch_row($query))
		{
			
			settype($arr_photo[$idproduct], 'string');

			$image=$arr_photo[$idproduct];
			
			$add_tax=0;

			$add_tax=calculate_taxes($idtax, $price);

			$text_taxes=add_text_taxes($idtax);
			
			$price_real=number_format($price+$add_tax, 2);

			$price=MoneyField::currency_format($price_real);

			if($offer>0)
			{

				$add_tax_offer=calculate_taxes($idtax, $offer);
				$offer+=$add_tax_offer;

				$price= '<strong>'.$lang['shop']['offer'].'</strong> <span style="text-decoration: line-through;">'.MoneyField::currency_format($price_real).' </span> -> '.MoneyField::currency_format($offer);

			}

			$arr_stock[0]=$lang['shop']['no_stock'];
			$arr_stock[1]=$lang['shop']['in_stock'];

			if($about_order==0)
			{

				$stock=$arr_stock[$stock];

			}
			else
			{

				$stock=$lang['shop']['served_on_request'];

			}
			
			echo load_view(array($idproduct, $model['product']->components['title']->show_formatted($title), $model['product']->components['description']->show_formatted($description), $image, $price, $stock, $text_taxes, $weight), 'shop/productlist');

			$z++;

		}

		if($z==0)
		{

			echo '<p>'.$lang['shop']['no_products_in_category'].'</p>';

		}*/

	//}
	
	$cont_index.=ob_get_contents();

	ob_end_clean();

	//$arr_block($title_category, $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, '');
	echo load_view(array(I18nField::show_formatted($arr_cat['title']), $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ''), $arr_block);

}

?>
