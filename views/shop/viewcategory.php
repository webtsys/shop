<?php

function ViewCategoryView($arr_cat, $arr_product, $arr_photo, $search_product)
{

global $lang, $config_shop, $base_url, $model;

$idtax=$config_shop['idtax'];
$title_category=$arr_cat['title'];

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

echo load_view(array($title_category, $arr_cat['description'].$ob_get_search), 'content');

echo $search_product;

foreach($arr_product as $key_prod => $product)
{
	
	$idproduct=$product['IdProduct'];
	$title_product=I18nField::show_formatted($product['title']);
	
	$tax=add_text_taxes($idtax);

	$add_tax=0;

	$add_tax=calculate_taxes($idtax, $product['price']);

	$text_taxes=add_text_taxes($idtax);
	
	$price_real=number_format($product['price']+$add_tax, 2);

	$price=MoneyField::currency_format($price_real);

	if($product['special_offer']>0)
	{

		$offer=$product['special_offer'];
	
		$add_tax_offer=calculate_taxes($idtax, $product['special_offer']);
		$offer+=$add_tax_offer;

		$price= '<strong>'.$lang['shop']['offer'].'</strong> <span style="text-decoration: line-through;">'.MoneyField::currency_format($price_real).' </span> -> '.MoneyField::currency_format($offer);

	}
	
	$stock=$product['stock'];
	$stock_txt='';
	
	$arr_stock[0]=$lang['shop']['no_stock'];
	$arr_stock[1]=$lang['shop']['in_stock'];

	if($product['about_order']==0)
	{

		$stock_txt=$arr_stock[$stock];

	}
	else
	{

		$stock_txt=$lang['shop']['served_on_request'];

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
				<img src="<?php echo $model['image_product']->components['photo']->show_image_url('mini_'.$arr_photo[$key_prod]); ?>" />
				<?php
				}
				?>
			</div>
			<div class="description_product">
				<?php echo I18nField::show_formatted($product['description']); ?>
				<br /><br />
				<strong><?php echo $lang['shop']['pvp']; ?>:</strong> <?php echo $price; ?>
				<br />
				<?php echo $stock_txt; ?>
				<br />
				<strong><?php echo $tax; ?></strong> 
				<br />
				<strong><?php echo $lang['shop']['weight_in_kg']; ?>: </strong> <?php echo $product['weight']; ?> <?php echo $lang['shop']['kg']; ?>.
				<br />
				<br />
				<a href="<?php echo make_fancy_url($base_url, 'shop', 'viewproduct', $title_product, array('IdProduct' => $idproduct) ); ?>" class="see">
					<?php echo $lang['shop']['see_product']; ?>
				</a>
				<?php
			if($config_shop['view_only_mode']==0 && $arr_cat['view_only_mode']==0 && $stock==1)
			{
			?>
			<a onclick="javascript:buy_product(<?php echo $idproduct; ?>); return false;" href="<?php echo make_fancy_url($base_url, 'shop', 'buy', 'buy_product', array('IdProduct' => $idproduct) ); ?>" class="ship"><span id="text_buy_<?php echo $idproduct; ?>"><?php echo $lang['shop']['buy_product']; ?></span>
			</a>
			<img id="loading_buy_<?php echo $idproduct; ?>" src="<?php echo $base_url; ?>/media/default/images/loading.gif" alt="<?php echo $lang['shop']['buying_product']; ?>" style="display: none;" />
			
			<br clear="all" /><div id="show_process_buying"><p id="buying_<?php echo $idproduct; ?>" style="display: none;"><span class="error"><?php echo $lang['shop']['buying_product']; ?></span></p><p id="sucess_buy_<?php echo $idproduct; ?>" style="display: none;"><span class="error"><?php echo $lang['shop']['success_buy']; ?></span></p></div>
			<?php
			}
			?>
			</div>
		</div>
	</div>
	<?php

	}

}


?>