<?php

use PhangoApp\PhaLibs\ParentLinks;
use PhangoApp\PhaView\View;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaModels\CoreFields\I18nField;
use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaI18n\I18n;

function ViewProductView($arr_product)
{
	//global PhangoVar::$base_url, $lang, PhangoVar::$model, ConfigShop::$config_shop, PhangoVar::$arr_cache_jscript, PhangoVar::$base_path;

	/*$arr_hierarchy_links=hierarchy_links('cat_product', 'subcat', 'title', $arr_product['product_relationship_idcat_product']);

	echo load_view(array($arr_hierarchy_links, 'shop', 'viewcategory', 'IdCat_product', array(), 1), 'common/utilities/hierarchy_links');*/
	
	$url=Routes::make_simple_url('shop/viewcategory');
	
	$hierarchy_links=new ParentLinks($url, 'cat_product', 'subcat', 'title', $arr_product['product_relationship_idcat_product'], $last_link=1, $arr_parameters=[], $arr_pretty_parameters=[]);
	
	echo $hierarchy_links->show();
	
	ob_start();
	
	?>
    <script>
    
        $('.img_desc').ShowBigImage();
    
    </script>
	<?php
	
	View::$header[]=ob_get_contents();
	
	ob_end_clean();
	
	ob_start();
    
	//Prepare images

	//$idtax=ConfigShop::$config_shop['idtax'];
	
	View::$js[]='jquery.min.js';
	View::$js[]='show_big_image2.js';
	
	$arr_stock[0]=I18n::lang('shop', 'no_stock', 'Sin stock');
	$arr_stock[1]=I18n::lang('shop', 'in_stock', 'En stock');
	
	if($arr_product['about_order']==0)
	{

		$stock_text=$arr_stock[$arr_product['stock']];

	}
	else
	{

		$stock_text=I18n::lang('shop', 'served_on_request', 'Servido bajo pedido');

	}
	
	//Define price...
	
	$price=$arr_product['price'];
	
	if($price>0)
	{

		//$price=ShopMoneyFIeld::currency_format($price+$add_tax);

		if($arr_product['special_offer']>0)
		{
			$add_tax_special_offer=0; //calculate_taxes($idtax, $arr_product['special_offer']);

			$price= '<strong>'.I18n::lang('shop', 'special_offer', 'Oferta especial').'</strong> <span style="text-decoration: line-through;">'.ShopMoneyFIeld::currency_format( ($price+$add_tax) ).'</span> -> '.ShopMoneyFIeld::currency_format( ($arr_product['special_offer']+$add_tax_special_offer) );

		}
		
	}
	else
	{
	
		$price=I18n::lang('shop', 'free_product', 'Gratuito');
	
	}

	?>
	<div class="product">
	
		<p align="center">
			<?php
			foreach($arr_product['images'] as $key => $image)
			{
			
				$image_min=Webmodel::$model['image_product']->components['photo']->url_path.'/'.'medium_'.$image['photo'];
				/*
			?>
				<a href="<?php echo Webmodel::$model['image_product']->components['photo']->url_path.'/'.$image['photo']; ?>" id="image<?php echo $key; ?>"><img class="img_desc" src="<?php echo $image_min; ?>" /></a>
				<script language="javascript"> show_big_image('#image<?php echo $key; ?>'); </script>
			<?php*/
			
                ?>
                <!--<div class="image_product">-->
                <div align="center">
                    <a href="<?php echo Webmodel::$model['image_product']->components['photo']->url_path.'/'.$image['photo']; ?>" id="image<?php echo $key; ?>" class="img_desc">
                        <img src="<?php echo $image_min; ?>" />
                    </a>
                    <div class="product_buyed" style="display:none;" id="successful_product_<?php echo $arr_product['IdProduct']; ?>">Añadido al carrito con éxito</div>
                    <div class='uil-squares-css' style='transform:scale(0.12);display:none;' id="add_product_<?php echo $arr_product['IdProduct']; ?>"><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div></div>
                </div>
                <!--</div>-->
                <?php

			}

			?>
		</p>
		<p>
			<?php echo I18nField::show_formatted($arr_product['description']); ?>
		</p>
		<p>
			<strong><?php echo I18n::lang('shop', 'pvp', 'PVP'); ?>:</strong> <?php echo ShopMoneyField::currency_format($arr_product['price']); ?>
		</p>	
		<p>
			<?php echo $stock_text; ?>
		</p>
		<!--<p>
			<?php echo I18n::lang('shop', 'weight', 'Peso'); ?>: <?php echo $arr_product['weight']; ?> <?php echo I18n::lang('shop', 'kg', 'Kg'); ?>
		</p>-->
		<?php
		if(ConfigShop::$config_shop['view_only_mode']==0 && $arr_product['view_only_mode']==0)
		{
		
			if($arr_product['stock']!=0 || $arr_product['about_order']==1)
			{
			/*
			?>
			<a onclick="javascript:buy_product(<?php echo $arr_product['IdProduct']; ?>); return false;" href="<?php echo Routes::make_simple_url('shop/buy', [], array('IdProduct' => $arr_product['IdProduct']) ); ?>" class="ship">
			<span id="text_buy_<?php echo $arr_product['IdProduct']; ?>"><?php echo I18n::lang('shop', 'buy_product', 'Comprar producto'); ?></span>
			</a><img id="loading_buy_<?php echo $arr_product['IdProduct']; ?>" src="<?php echo Routes::$root_url; ?>/media/default/images/loading.gif" alt="<?php echo I18n::lang('shop', 'buying_product', 'Comprando producto'); ?>" style="display: none;" />

			<br clear="all" /><div id="show_process_buying"><p id="buying_<?php echo $arr_product['IdProduct']; ?>" style="display: none;"><span class="error"><?php echo I18n::lang('shop', 'buying_product', 'Comprando producto'); ?></span></p><p id="sucess_buy_<?php echo $arr_product['IdProduct']; ?>" style="display: none;"><span class="error"><?php echo I18n::lang('shop', 'success_buy', 'Se añadio este producto al carrito de la compra'); ?></span></p></div>
			
			<?php
			*/
			?>
			<a href="#" class="buy_button" onclick="javascript:Cart.buy_product(<?php echo $arr_product['IdProduct']; ?>); return false;">Comprar <i class="fa fa-shopping-cart"></i></a>
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
	
		Utils::load_libraries(array($plugin), 'vendor/phangoapp/shop/plugins/product');
		
		echo $func_plugin($arr_product['IdProduct']);
	
	}
	
	?>
	<?php
	
	$cont_view=ob_get_contents();
	
	ob_end_clean();
	
	echo View::load_view(array(I18nField::show_formatted($arr_product['title']), $cont_view), 'content');
	
}

?>