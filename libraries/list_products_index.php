<?php

function list_products_index($where)
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $lang_taxes, $arr_taxes;

	$num_news=$config_shop['num_news'];

	//Get ids for get images...

	$arr_id=array();
	$arr_photo=array();
	$arr_idcat=array();
	
	$query=$model['product']->select($where.' limit '.$num_news, array($model['product']->idmodel, 'idcat'), true);

	while(list($idproduct, $idcat)=webtsys_fetch_row($query))
	{

		$arr_id[]=$idproduct;
		$arr_idcat[$idcat]=1;

	}
	//print_r($arr_idcat);
	$query=$model['image_product']->select('where idproduct IN (\''.implode("', '", $arr_id).'\') and principal=1', array('photo', 'idproduct'), true);

	while(list($photo, $idproduct)=webtsys_fetch_row($query))
	{

		$arr_photo[$idproduct]=$photo;

	}
	
	//Obtain view_only_mode for cats...
	
	$query=$model['cat_product']->select('where IdCat_product IN (\''.implode("', '", array_keys($arr_idcat)).'\')', array('IdCat_product', 'view_only_mode'));
	
	while(list($idcat, $view_only_mode)=webtsys_fetch_row($query))
	{
	
		$arr_idcat[$idcat]=$view_only_mode;
	
	}
	
	$z=0;

	$image='';

	$idtax=$config_shop['idtax'];
	
	$query=$model['product']->select($where.' limit '.$num_news, array($model['product']->idmodel, 'title', 'description', 'price', 'special_offer', 'stock', 'about_order', 'weight', 'idcat'), true);

	while(list($idproduct, $title, $description, $price, $offer, $stock, $about_order, $weight, $idcat)=webtsys_fetch_row($query))
	{
		
		settype($arr_photo[$idproduct], 'string');

		$image=$arr_photo[$idproduct];
		
		/*if($image!='mini_')
		{

			$image=$model['image_product']->components['photo']->url_path.'/'.$image;

		}
		else
		{

			$image=$base_url.'/media/'.$config_data['dir_theme'].'/images/mini_default.png';

		}*/
		
		$add_tax=0;

	/*	if($config_shop['yes_taxes']==1)
		{

			$add_tax=$price*($arr_taxes[$idtax]/100);

		}*/

		$price_real=$price;

		$add_tax=calculate_taxes($idtax, $price_real);

		$text_taxes=add_text_taxes($idtax);
		
		$price=MoneyField::currency_format($price+$add_tax);

		if($offer>0)
		{

			$add_tax_offer=calculate_taxes($idtax, $offer);//$offer*($arr_taxes[$idtax]/100);
			$offer+=$add_tax_offer;

			$price= '<strong>'.$lang['shop']['offer'].'</strong> <span style="text-decoration: line-through;">'.MoneyField::currency_format($price_real).'</span> -> '.MoneyField::currency_format($offer);

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
		
		echo load_view(array($idproduct, $model['product']->components['title']->show_formatted($title), $model['product']->components['description']->show_formatted($description), $image, $price, $stock, $text_taxes, $weight, $arr_idcat[$idcat]), 'shop/productlist', 'shop');

		$z++;

	}

	if($z==0)
	{

		echo '<p>'.$lang['shop']['no_new_products'].'</p>';

	}

}

?>