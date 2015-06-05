<?php

function ViewProductView($arr_product)
{
	//global PhangoVar::$base_url, $lang, PhangoVar::$model, ConfigShop::$config_shop, PhangoVar::$arr_cache_jscript, PhangoVar::$base_path;

	$arr_hierarchy_links=hierarchy_links('cat_product', 'subcat', 'title', $arr_product['product_relationship_idcat_product']);

	echo load_view(array($arr_hierarchy_links, 'shop', 'viewcategory', 'IdCat_product', array(), 1), 'common/utilities/hierarchy_links');
	
	ob_start();

	//Prepare images

	//$idtax=ConfigShop::$config_shop['idtax'];
	
	PhangoVar::$arr_cache_jscript[]='show_big_image.js';
	
	$arr_stock[0]=i18n_lang('shop', 'no_stock', 'Sin stock');
	$arr_stock[1]=i18n_lang('shop', 'in_stock', 'En stock');
	
	if($arr_product['about_order']==0)
	{

		$stock_text=$arr_stock[$arr_product['stock']];

	}
	else
	{

		$stock_text=i18n_lang('shop', 'served_on_request', 'Servido bajo pedido');

	}
	
	//Define price...
	
	$price=$arr_product['price'];
	
	/*$add_tax=calculate_taxes($idtax, $price);
	
	$tax=add_text_taxes($idtax);*/
	
	if($price>0)
	{

		//$price=moneyfield::currency_format($price+$add_tax);

		if($arr_product['special_offer']>0)
		{
			$add_tax_special_offer=calculate_taxes($idtax, $arr_product['special_offer']);

			$price= '<strong>'.i18n_lang('shop', 'special_offer', 'Oferta especial').'</strong> <span style="text-decoration: line-through;">'.moneyfield::currency_format( ($price+$add_tax) ).'</span> -> '.moneyfield::currency_format( ($arr_product['special_offer']+$add_tax_special_offer) );

		}
		
	}
	else
	{
	
		$price=i18n_lang('shop', 'free_product', 'Gratuito');
	
	}

	?>
	<div class="product">
	
		<p align="center">
			<?php
			foreach($arr_product['images'] as $key => $image)
			{
			
				$image_min=PhangoVar::$model['image_product']->components['photo']->url_path.'/'.'mini_'.$image['photo'];
				
			?>
				<a href="<?php echo PhangoVar::$model['image_product']->components['photo']->url_path.'/'.$image['photo']; ?>" id="image<?php echo $key; ?>"><img class="img_desc" src="<?php echo $image_min; ?>" /></a>
				<script language="javascript"> show_big_image('#image<?php echo $key; ?>'); </script>
			<?php

			}

			?>
		</p>
		<p>
			<?php echo I18nField::show_formatted($arr_product['description']); ?>
		</p>
		<p>
			<strong><?php echo i18n_lang('shop', 'pvp', 'PVP'); ?>:</strong> <?php echo MoneyField::currency_format($arr_product['price']); ?>
		</p>	
		<p>
			<?php echo $stock_text; ?>
		</p>
		<!--<p>
			<?php echo i18n_lang('shop', 'weight', 'Peso'); ?>: <?php echo $arr_product['weight']; ?> <?php echo i18n_lang('shop', 'kg', 'Kg'); ?>
		</p>-->
		<?php
		if(ConfigShop::$config_shop['view_only_mode']==0 && $arr_product['view_only_mode']==0)
		{
		
			if($arr_product['stock']!=0 || $arr_product['about_order']==1)
			{
			?>
			<a onclick="javascript:buy_product(<?php echo $arr_product['IdProduct']; ?>); return false;" href="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'buy', array('IdProduct' => $arr_product['IdProduct']) ); ?>" class="ship">
			<span id="text_buy_<?php echo $arr_product['IdProduct']; ?>"><?php echo i18n_lang('shop', 'buy_product', 'Comprar producto'); ?></span>
			</a><img id="loading_buy_<?php echo $arr_product['IdProduct']; ?>" src="<?php echo PhangoVar::$base_url; ?>/media/default/images/loading.gif" alt="<?php echo i18n_lang('shop', 'buying_product', 'Comprando producto'); ?>" style="display: none;" />

			<br clear="all" /><div id="show_process_buying"><p id="buying_<?php echo $arr_product['IdProduct']; ?>" style="display: none;"><span class="error"><?php echo i18n_lang('shop', 'buying_product', 'Comprando producto'); ?></span></p><p id="sucess_buy_<?php echo $arr_product['IdProduct']; ?>" style="display: none;"><span class="error"><?php echo i18n_lang('shop', 'success_buy', 'Se añadio este producto al carrito de la compra'); ?></span></p></div>
			
			<?php
			}
		}
	?>
	<br />
	</div>
	
	<?php
	
	$arr_plugin=$arr_product['plugins'];
	
	foreach($arr_plugin as $plugin => $func_plugin)
	{
	
		load_libraries(array($plugin), PhangoVar::$base_path.'modules/shop/plugins/product/');
		
		echo $func_plugin($arr_product['IdProduct']);
	
	}
	
	?>
	<?php
	
	$cont_view=ob_get_contents();
	
	ob_end_clean();
	
	echo load_view(array(I18nField::show_formatted($arr_product['title']), $cont_view), 'content');
	
}

?>