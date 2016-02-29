<?php

use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaModels\CoreFields\I18nField;
use PhangoApp\PhaView\View;
use PhangoApp\PhaRouter\Routes;

function ProductListView($arr_product, $image, $view_only_mode=0)
{

//Set image 1

if($image!='default_image.jpg')
{

	$image=Webmodel::$model['image_product']->components['photo']->url_path.'/medium_'.$image;

}
else
{

	$image=View::get_media_url('images/default_image.jpg', $module='phangoapp/shop'); //$base_url.'/media/'.$config_data['dir_theme'].'/images/mini_default.png';

}

?>
<div class="product">
    <div class="title_product">
        <?php echo I18nField::show_formatted($arr_product['title']); ?>
    </div>
    <div class="image_product">
        <img src="<?php echo $image; ?>" />
    </div>
	<div class="cont_product">
        <a href="#" class="see_more">Ver + <i class="fa fa-eye"></i></a> <a href="#" class="buy_button" onclick="javascript:buy_product('<?php echo Routes::make_simple_url('shop/cart/ajax/buy'); ?>', <?php echo $arr_product['IdProduct']; ?>)">Comprar <i class="fa fa-shopping-cart"></i></a>
	</div>
</div>

<?php
}

?>
