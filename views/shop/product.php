<?php

function ProductView($idproduct, $description, $arr_image_mini, $arr_image, $price, $stock, $tax, $weight, $view_only_mode, $arr_plugin)
{

global $base_url, $lang, $model, $config_shop, $arr_cache_jscript;

$arr_cache_jscript[]='show_big_image.js';

?>
<div style="text-align:center;">
	<?php
	
	foreach($arr_image as $key => $image)
	{

		$image_medium='mini_'.$arr_image_mini[$key];
		$image_medium=$model['image_product']->components['photo']->url_path.'/'.$image_medium;
		
	?>
		<a href="<?php echo $image; ?>" id="image<?php echo $key; ?>"><img class="img_desc" src="<?php echo $image_medium; ?>" /></a>
		<script language="javascript"> show_big_image('#image<?php echo $key; ?>'); </script>
	<?php

	}

	?>
</div>
<div style="margin-left:35px;margin-right:35px;">
<div class="desc_product">
	<div class="description_product">
		<?php echo $description; ?>
		<br /><br />
		<strong><?php echo $lang['shop']['pvp']; ?>:</strong> <?php echo $price; ?>
		<br />
		<?php echo $stock; ?>
		<br />
		<strong><?php echo $tax; ?></strong> 
		<br />
		<?php echo $lang['shop']['weight']; ?>: <?php echo $weight; ?> <?php echo $lang['shop']['kg']; ?>
		<br />
		<br />
		<?php
		if($config_shop['view_only_mode']==0 && $view_only_mode==0)
		{
		?>
		
		<a onclick="javascript:buy_product(<?php echo $idproduct; ?>); return false;" href="<?php echo make_fancy_url($base_url, 'shop', 'buy', 'buy_product', array('IdProduct' => $idproduct) ); ?>" class="ship">
				<span id="text_buy_<?php echo $idproduct; ?>"><?php echo $lang['shop']['buy_product']; ?></span>
		</a><img id="loading_buy_<?php echo $idproduct; ?>" src="<?php echo $base_url; ?>/media/default/images/loading.gif" alt="<?php echo $lang['shop']['buying_product']; ?>" style="display: none;" />
		<br clear="all" /><div id="show_process_buying"><p id="buying_<?php echo $idproduct; ?>" style="display: none;"><span class="error"><?php echo $lang['shop']['buying_product']; ?></span></p><p id="sucess_buy_<?php echo $idproduct; ?>" style="display: none;"><span class="error"><?php echo $lang['shop']['success_buy']; ?></span></p></div>
		<?php
		
		}
		?>
		
		
		<?php
		
		foreach($arr_plugin as $plugin)
		{
		
			echo $plugin;
		
		}
		
		?>
	</div>
</div>
</div>


<?php

}

?>