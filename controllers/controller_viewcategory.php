<?php

function ViewCategory()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $lang_taxes;

	load_lang('shop');
	load_model('shop');
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');
	load_libraries(array('pages', 'forms/selectmodelformbyorder', 'generate_admin_ng'));

	$cont_index='';

	$arr_block=select_view(array('shop'));

	//Load page...

	settype($_GET['IdCat_product'], 'integer');
	
	//Load list for objects..

	$query=$model['cat_product']->select('where IdCat_product='.$_GET['IdCat_product'], array('IdCat_product', 'title', 'description', 'subcat'));

	list($idcat_product, $title_category, $description_cat, $subcat)=webtsys_fetch_row($query);
	
	settype($idcat_product, 'integer');
	
	if($idcat_product>0)
	{

		$title_category=$model['cat_product']->components['title']->show_formatted($title_category);
		$description_cat=$model['cat_product']->components['description']->show_formatted($description_cat);
		ob_start();
		?>
		<form method="get" action="<?php echo make_fancy_url($base_url, 'shop', 'viewcategory', 'viewcategory', array()); ?>">
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

			//$image='mini_'.$arr_photo[$idproduct];
			$image=$arr_photo[$idproduct];
			
			/*if($image!='mini_')
			{

				$image=$model['image_product']->components['photo']->url_path.'/'.$image;

			}
			else
			{

				$image=$base_url.'/media/'.$config_data['dir_theme'].'/images/mini_default.png';

			}*/

			/*if($image=='')
			{

				$image='default.png';

			}*/
			
			$add_tax=0;

		/*	if($config_shop['yes_taxes']==1)
			{

				$add_tax=$price*($arr_taxes[$idtax]/100);

			}*/

			$add_tax=calculate_taxes($idtax, $price);

			$text_taxes=add_text_taxes($idtax);
			
			$price_real=number_format($price+$add_tax, 2);

			$price=MoneyField::currency_format($price_real);

			if($offer>0)
			{

				$add_tax_offer=calculate_taxes($idtax, $offer);//$offer*($arr_taxes[$idtax]/100);
				$offer+=$add_tax_offer;

				$price= '<strong>'.$lang['shop']['offer'].'</strong> <span style="text-decoration: line-through;">'.MoneyField::currency_format($price_real).' </span> -> '.MoneyField::currency_format($offer);

			}

			$arr_stock[0]=$lang['shop']['in_stock'];
			$arr_stock[1]=$lang['shop']['no_stock'];

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

		}

	}
	
	$cont_index.=ob_get_contents();

	ob_end_clean();

	//$arr_block($title_category, $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, '');
	echo load_view(array($title_category, $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ''), $arr_block);

}

?>