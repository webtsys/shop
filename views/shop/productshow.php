<?php

function ProductShowView($title_category, $description_cat, $view_only_mode)
{
	
	global $lang, $model, $base_url, $config_shop;

	$arr_hierarchy_links=hierarchy_links('cat_product', 'subcat', 'title', $_GET['IdCat_product']);

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
	
	$model['product']->forms['title_'.$_SESSION['language']]->label=$lang['common']['title'];
	$model['product']->forms['date']->label=$lang['common']['date'];
	
	$arr_fields=array('title_'.$_SESSION['language'], 'date');
	$where_sql='';
	$url_options=make_fancy_url($base_url, 'shop', 'viewcategory', 'viewcategory', array('IdCat_product' => $_GET['IdCat_product']));
	
	if(!isset($_GET['order_field']))
	{
	
		$_GET['order_field']='date';
		$_GET['order_desc']=1;
	
	}
	
	//$_GET['IdCat_product']
	
	if($_GET['IdCat_product']>0)
	{
	
		$where_sql='where idcat='.$_GET['IdCat_product'];
	
	}
	
	list($where_sql, $arr_where_sql, $location, $arr_order)=SearchInField('product', $arr_fields, $where_sql, $url_options, 0);
	
	$where_sql.=$arr_where_sql.' order by '.$location.$_GET['order_field'].' '.$arr_order[$_GET['order_desc']];
	
	//Get ids for get images...
	
	$num_news=$config_shop['num_news'];

	$arr_id=array();
	$arr_photo=array();

	$query=$model['product']->select($where_sql.' limit '.$_GET['begin_page'].', '.$num_news, array($model['product']->idmodel), true);

	while(list($idproduct)=webtsys_fetch_row($query))
	{

		$arr_id[]=$idproduct;

	}

	$query=$model['image_product']->select('where idproduct IN (\''.implode("', '", $arr_id).'\') and principal=1', array('photo', 'idproduct'), true);

	while(list($photo, $idproduct)=webtsys_fetch_row($query))
	{

		$arr_photo[$idproduct]=$photo;

	}
	
	$query=$model['product']->select($where_sql.' limit '.$_GET['begin_page'].', '.$num_news, array($model['product']->idmodel, 'title', 'description', 'price', 'special_offer', 'stock', 'about_order', 'weight'), true);

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
		
		echo load_view(array($idproduct, $model['product']->components['title']->show_formatted($title), $model['product']->components['description']->show_formatted($description), $image, $price, $stock, $text_taxes, $weight, $view_only_mode), 'shop/productlist');

		$z++;

	}

	if($z==0)
	{

		echo '<p>'.$lang['shop']['no_products_in_category'].'</p>';

	}
	else
	{
	
		load_libraries(array('pages'));
	
		$total_elements=$model['product']->select_count($where_sql, 'IdProduct');
		
		$url_next=make_fancy_url($base_url, 'shop', 'viewcategory', $title_category, array('IdCat_product' => $_GET['IdCat_product']) );
		
		echo '<p>'.$lang['common']['pages'].': '.pages( $_GET['begin_page'], $total_elements, $num_news, $url_next).'</p>';
	
	}
	
	

}

?>