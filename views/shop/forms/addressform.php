<?php

use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaView\View;
use PhangoApp\PhaModels\Webmodel;

function AddressFormView()
{

	//global PhangoVar::$model, PhangoVar::$lang, $config_shop, $base_url;
	// ModelFormView(PhangoVar::$model_form, $fields=array(), $html_id='')
	
	?>
	<h2><?php echo I18n::lang('shop', 'address_billing', 'Dirección de facturación'); ?></h2>
	<div class="content">
		<form method="post" action="<?php echo Routes::make_simple_url('shop/cart/save_address'); ?>">
		<?php
		
		Utils::set_csrf_key();
		//ModelFormView($model_form, $fields=array(), $html_id='')
		echo View::load_view(array(Webmodel::$model['user_shop']->forms, ConfigShop::$arr_fields_address), 'forms/modelform');
		
		echo '<span class="error">'.Webmodel::$model['user_shop']->std_error.'</span>';
		
		?>
		<p class="error"><?php echo I18n::lang('common', 'with_*_field_required', '* Field required'); ?></p>
		<p><input type="submit" value="<?php echo I18n::lang('common', 'send', 'Send'); ?>" /></p>
		</form>
	</div>
	<?php
}

?>