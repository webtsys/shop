<?php

function ViewCategoryView($idcat_product, $arr_cat, $arr_product, $arr_photo, $search_product, $total_elements)
{

//global PhangoVar::$lang, ConfigShop::$config_shop, PhangoVar::$base_url, PhangoVar::$model;

$arr_hierarchy_links=hierarchy_links('cat_product', 'subcat', 'title', $idcat_product);

echo load_view(array($arr_hierarchy_links, 'shop', 'viewcategory', 'IdCat_product', array(), 0), 'common/utilities/hierarchy_links');

ob_start();

?>



<?php

PhangoVar::$arr_cache_header[]=ob_get_contents();

ob_end_clean();

$title_category=$arr_cat['title'];
$num_news=ConfigShop::$config_shop['num_news'];

ob_start();

?>
<form method="get" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'viewcategory', array(0, slugify($title_category))); ?>">
	<p>
	<?php

	echo PhangoVar::$lang['shop']['select_category_shop'].': '.SelectModelFormByOrder('IdCat_product', '', $idcat_product, 'cat_product', 'title', 'subcat', $where='');

	?>
	<input type="submit" value="<?php echo PhangoVar::$lang['shop']['choose_category']; ?>"/>
	</p>
<form>
<?php

$ob_get_search=ob_get_contents();

ob_end_clean();

echo load_view(array($title_category, $arr_cat['description'].$ob_get_search), 'content');

echo $search_product;

$z=0;

foreach($arr_product as $key_prod => $product)
{
	
	$idproduct=$product['IdProduct'];
	$title_product=I18nField::show_formatted($product['title']);

	
	$price_real=number_format($product['price'], 2);

	$price=MoneyField::currency_format($price_real);

	if($product['special_offer']>0)
	{

		$offer=$product['special_offer'];
	
		/*$add_tax_offer=calculate_taxes($idtax, $product['special_offer']);
		$offer+=$add_tax_offer;*/

		$price= '<strong>'.PhangoVar::$lang['shop']['offer'].'</strong> <span style="text-decoration: line-through;">'.MoneyField::currency_format($price_real).' </span> -> '.MoneyField::currency_format($offer);

	}
	
	$stock=$product['stock'];
	$stock_txt='';
	
	$arr_stock[0]=PhangoVar::$lang['shop']['no_stock'];
	$arr_stock[1]=PhangoVar::$lang['shop']['in_stock'];

	if($product['about_order']==0)
	{

		$stock_txt=$arr_stock[$stock];

	}
	else
	{

		$stock_txt=PhangoVar::$lang['shop']['served_on_request'];

	}
	
	?>
		<div class="product">
		<div class="title">
			<?php echo I18nField::show_formatted($product['title']); ?>
		</div>
		<div class="cont">
			<div class="image_list_prod">
				<?php
				if(isset($arr_photo[$key_prod]))
				{
				?>
				<img src="<?php echo PhangoVar::$model['image_product']->components['photo']->show_image_url('mini_'.$arr_photo[$key_prod]); ?>" />
				<?php
				}
				?>
			</div>
			<div class="description_product">
				<?php echo I18nField::show_formatted($product['description']); ?>
				<br /><br />
				<strong><?php echo PhangoVar::$lang['shop']['pvp']; ?>:</strong> <?php echo $price; ?>
				<br />
				<?php echo $stock_txt; ?>
				<!--<br />
				<strong><?php echo PhangoVar::$lang['shop']['weight_in_kg']; ?>: </strong> <?php echo $product['weight']; ?> <?php echo PhangoVar::$lang['shop']['kg']; ?>-->.
				<br />
				<br />
				<a href="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'viewproduct', array($idproduct, $title_product) ); ?>" class="see">
					<?php echo PhangoVar::$lang['shop']['see_product']; ?>
				</a>
				<?php
			/*if(ConfigShop::$config_shop['view_only_mode']==0 && $arr_cat['view_only_mode']==0 && $stock==1)
			{
			?>
			<a onclick="javascript:buy_product(<?php echo $idproduct; ?>); return false;" href="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'buy', 'buy_product', array('IdProduct' => $idproduct) ); ?>" class="ship"><span id="text_buy_<?php echo $idproduct; ?>"><?php echo PhangoVar::$lang['shop']['buy_product']; ?></span>
			</a>
			<img id="loading_buy_<?php echo $idproduct; ?>" src="<?php echo PhangoVar::$base_url; ?>/media/default/images/loading.gif" alt="<?php echo PhangoVar::$lang['shop']['buying_product']; ?>" style="display: none;" />
			
			<br clear="all" /><div id="show_process_buying"><p id="buying_<?php echo $idproduct; ?>" style="display: none;"><span class="error"><?php echo PhangoVar::$lang['shop']['buying_product']; ?></span></p><p id="sucess_buy_<?php echo $idproduct; ?>" style="display: none;"><span class="error"><?php echo PhangoVar::$lang['shop']['success_buy']; ?></span></p></div>
			<?php
			}*/
			?>
			</div>
		</div>
	</div>
	<?php
	
		$z++;

	}
	
	
	if($z==0)
	{

		echo '<p>'.PhangoVar::$lang['shop']['no_products_in_category'].'</p>';

	}
	else
	{

		
		$url_next=make_fancy_url(PhangoVar::$base_url, 'shop', 'viewcategory', array($idcat_product, slugify($title_category)) );
		
		echo '<p>'.PhangoVar::$lang['common']['pages'].': '.pages( $_GET['begin_page'], $total_elements, $num_news, $url_next).'</p>';

	}

}

?>