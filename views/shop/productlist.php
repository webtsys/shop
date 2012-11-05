<?php

function ProductListView($idproduct, $title_product, $description, $image, $price, $stock, $tax, $weight, $view_only_mode)
{

global $base_url, $lang, $model, $config_data, $config_shop, $arr_cache_jscript;

$arr_cache_jscript[]='show_big_image.js';
//Set image 1

if($image!='')
{

	$image=$model['image_product']->components['photo']->url_path.'/mini_'.$image;

}
else
{

	$image=$base_url.'/media/'.$config_data['dir_theme'].'/images/mini_default.png';

}

?>
<div class="product">
	<div class="title">
		<?php echo $title_product; ?>
	</div>
	<div class="cont">
		<div class="image1">
			<img src="<?php echo $image; ?>" /></div>
		<div class="description_product">
			<?php echo $description; ?>
			<br /><br />
			<strong><?php echo $lang['shop']['pvp']; ?>:</strong> <?php echo $price; ?>
			<br />
			<?php echo $stock; ?>
			<br />
			<strong><?php echo $tax; ?></strong> 
			<br />
			<strong><?php echo $lang['shop']['weight_in_kg']; ?>: </strong> <?php echo $weight; ?> <?php echo $lang['shop']['kg']; ?>.
			<br />
			<br />
			<a href="<?php echo make_fancy_url($base_url, 'shop', 'viewproduct', $title_product, array('IdProduct' => $idproduct) ); ?>" class="see">
				<?php echo $lang['shop']['see_product']; ?>
			</a>
			<?php
		if($config_shop['view_only_mode']==0 && $view_only_mode==0)
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

?>
