<?php

class  ViewcategorySwitchClass extends ControllerSwitchClass
{

	function index($idcat_product=0, $title_slugify='')
	{

		//global $user_data, PhangoVar::$model, $ip, PhangoVar::$lang, $config_data, PhangoVar::$base_path, PhangoVar::$base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ConfigShop::$config_shop, PhangoVar::$lang_taxes;

		load_lang('shop');
		load_model('shop');
		load_libraries(array('config_shop'), PhangoVar::$base_path.'modules/shop/libraries/');
		load_libraries(array('pages', 'forms/selectmodelformbyorder', 'generate_admin_ng', 'utilities/hierarchy_links'));

		/*$cont_index='';

		$arr_block=select_view(array('shop'));*/

		//Load page...

		settype($idcat_product, 'integer');
		
		$num_news=ConfigShop::$config_shop['num_news'];
		
		//Obtain category...
		
		$arr_cat=PhangoVar::$model['cat_product']->select_a_row($idcat_product, array(), 1);
		
		settype($arr_cat['IdCat_product'], 'string');
		
		//Select childrens cats ids too
		
		$arr_children=array();
		
		$arr_all_cats=PhangoVar::$model['cat_product']->select_to_array('', array('subcat'));
		
		foreach($arr_all_cats as $idcat => $arr_subcat)
		{
		
			//echo $idcat;
			$arr_children[$arr_subcat['subcat']][]=$idcat;
		
		}
		
		settype($arr_children[$arr_cat['IdCat_product']], 'array');
		
		$arr_children[$arr_cat['IdCat_product']][]=0;
		
		$id_subcat=implode(', ', $arr_children[$arr_cat['IdCat_product']]);
		
		$where_sql='where product_relationship.idcat_product IN ('.$arr_cat['IdCat_product'].', '.$id_subcat.') and product.stock=1';
		
		if($arr_cat['IdCat_product']==0)
		{
		
			$arr_cat['title']=i18n_lang('shop', 'all_products', 'Todos los productos');
			$arr_cat['description']=i18n_lang('shop', 'desc_all_products', 'Aquí encontrará un listado de todos los productos');
			$arr_cat['subcat']=0;
			$arr_cat['view_only_mode']=ConfigShop::$config_shop['view_only_mode'];
			$where_sql='';
		
		}
		else
		{
		
			$arr_cat['title']=I18nField::show_formatted($arr_cat['title']);
			$arr_cat['description']=I18nField::show_formatted($arr_cat['description']);
		
		}
		
		PhangoVar::$model['product']->related_models=array('product_relationship' => array('idproduct'));
		
		PhangoVar::$model['product']->create_form();
		
		$url_options=make_fancy_url(PhangoVar::$base_url, 'shop', 'viewcategory', array($idcat_product, $arr_cat['title']));
		
		$arr_fields_orders=array('date', 'title_'.$_SESSION['language']);
		$arr_fields_search=array('title_'.$_SESSION['language']);
		
		PhangoVar::$model['product']->forms['title_'.$_SESSION['language']]->label=i18n_lang('common', 'title', 'Title');
		PhangoVar::$model['product']->forms['date']->label=i18n_lang('common', 'date', 'date');
		
		$cont_search='';
		
		if(!isset($_GET['order_desc']))
		{
		
			$_GET['order_desc']=1;
		
		}
		
		ob_start();
		
		list($where_sql, $arr_where_sql, $location, $arr_order)=SearchInField('product', $arr_fields_orders, $arr_fields_search, $where_sql, $url_options, 0);
		
		$cont_search=ob_get_contents();
		
		ob_end_clean();
		
		$where_sql.=$arr_where_sql.' order by '.$location.'`'.$_GET['order_field'].'` '.$arr_order[$_GET['order_desc']];
		
		$total_elements=PhangoVar::$model['product']->select_count($where_sql, 'IdProduct');
		
		//Now, set where with searchs...
		
		//Now select products...
		
		$arr_product=PhangoVar::$model['product']->select_to_array($where_sql.' limit '.$_GET['begin_page'].', '.$num_news, array());
		
		//Select ids...
		
		$arr_id=array_keys($arr_product);
		
		$arr_id[]=0;
		
		//Select images...
		
		$arr_photo=array();
		
		foreach($arr_id as $id)
		{
		
			$arr_photo[$id]='default_image.jpg';
		
		}
		
		$query=PhangoVar::$model['image_product']->select('where idproduct IN (\''.implode("', '", $arr_id).'\') and principal=1', array('photo', 'idproduct'), true);

		while(list($photo, $idproduct)=webtsys_fetch_row($query))
		{

		
				$arr_photo[$idproduct]=$photo;
				

		}
		
		echo load_view(array($idcat_product, $arr_cat, $arr_product, $arr_photo, $cont_search, $total_elements), 'shop/viewcategory');
		
		$cont_index.=ob_get_contents();

		ob_end_clean();

		//$arr_block($title_category, $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, '');
		echo load_view(array($arr_cat['title'], $cont_index), 'home');
	}
}

?>
