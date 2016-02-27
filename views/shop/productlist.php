<?php

use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaModels\CoreFields\I18nField;
use PhangoApp\PhaView\View;

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
        
	</div>
</div>

<?php
}

?>
