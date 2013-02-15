<?php

function ViewProduct()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $lang_taxes;

	$arr_block='';

	$cont_index='';

	$arr_block=select_view(array('shop'));

	//Load page...

	load_model('shop');

	load_lang('shop');
	load_libraries(array('utilities/hierarchy_links'));
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');

	settype($_GET['IdProduct'], 'integer');
	settype($_GET['img_big'], 'integer');

	$idtax=$config_shop['idtax'];

	$query=$model['product']->select('where IdProduct='.$_GET['IdProduct'], array($model['product']->idmodel, 'title', 'description', 'idcat',  'price', 'special_offer', 'stock', 'about_order', 'weight', 'referer'), 1);

	list($idproduct, $title, $description, $idcat_product, $price, $offer, $stock, $about_order, $weight, $referer)=webtsys_fetch_row($query);
	
	settype($idcat_product, 'integer');
	
	$title=$model['product']->components['title']->show_formatted($title);
	$description=$model['product']->components['description']->show_formatted($description);

	$query=$model['cat_product']->select('where IdCat_product='.$idcat_product, array('view_only_mode'));
	
	list($view_only_mode)=webtsys_fetch_row($query);
	
	settype($idproduct, 'integer');

	if($idproduct>0)
	{
	
		//Load product

		$arr_image=array();
		$arr_image_mini=array();

		$query=$model['image_product']->select('where idproduct='.$idproduct.' order by principal DESC', array('photo'));

		while(list($photo)=webtsys_fetch_row($query))
		{

			/*$image='mini_'.$photo;
			$image=$model['image_product']->components['photo']->url_path.'/'.$image;*/

			$arr_image_mini[]=$photo;

			$image=$model['image_product']->components['photo']->url_path.'/'.$photo;

			$arr_image[]=$image;

		}

		//$add_tax=$price*$arr_taxes[$idtax];
		$add_tax=calculate_taxes($idtax, $price);
		
		if($price>0)
		{

			$price=MoneyField::currency_format($price+$add_tax);

			if($offer>0)
			{
				$add_tax_offer=calculate_taxes($idtax, $offer);

				$price= '<strong>'.$lang['shop']['offer'].'</strong> <span style="text-decoration: line-through;">'.MoneyField::currency_format( ($price+$add_tax) ).'</span> -> '.MoneyField::currency_format( ($offer+$add_tax_offer) );

			}
			
		}
		else
		{
		
			$price=$lang['shop']['free_product'];
		
		}
		
		ob_clean();
		
		//ProductView($idproduct, $description, $arr_image, $price, $stock, $tax, $weight)

		$text_taxes=add_text_taxes($idtax);
		
		//Obtain plugins...
		
		$arr_plugin=array();
		
		$query=$model['plugin_shop']->select('where element="product" order by position ASC', array('plugin'));
		
		while(list($plugin)=webtsys_fetch_row($query))
		{
			
			load_libraries(array($plugin), $base_path.'modules/shop/plugins/product/');
		
			$func_plugin=ucfirst($plugin).'Show';
			
			$arr_plugin[$plugin]=$func_plugin($idproduct);
		
		}
		
		//echo load_view(array($idproduct, $description, $arr_image_mini, $arr_image, $price, $stock, $text_taxes, $weight, $view_only_mode, $arr_plugin), 'shop/product');
		
		$arr_product_view=array($idproduct, $description, $arr_image_mini, $arr_image, $price, $stock, $about_order, $text_taxes, $weight, $view_only_mode, $arr_plugin, $referer);
		
		echo load_view(array($title, $arr_product_view, $idcat_product), 'shop/loadproduct');

	}
	else
	{

		//echo load_view(array($lang['shop']['no_exists_product'], $lang['shop']['this_product_is_not_found']), 'content');
		$title=$lang['shop']['no_exists_product'];
		echo $lang['shop']['this_product_is_not_found'];

	}

	$cont_index=ob_get_contents();

	ob_clean();
	
	//Show links for categories

	echo load_view(array($title, $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ''), $arr_block);

}

?>
