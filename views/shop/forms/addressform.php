<?php

function AddressFormView()
{

	//global PhangoVar::$model, PhangoVar::$lang, $config_shop, $base_url;
	// ModelFormView(PhangoVar::$model_form, $fields=array(), $html_id='')
	
	?>
	<h2><?php echo PhangoVar::$lang['shop']['address_billing']; ?></h2>
	<div class="content">
		<form method="post" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_save_address'); ?>">
		<?php
		
		set_csrf_key();
		
		echo load_view(array(PhangoVar::$model['user_shop']->forms, ConfigShop::$arr_fields_address), 'common/forms/modelform');
		
		echo '<span class="error">'.PhangoVar::$model['user_shop']->std_error.'</span>';
		
		?>
		<p><input type="submit" value="<?php echo PhangoVar::$lang['common']['send']; ?>" /></p>
		</form>
	</div>
	<?php
}

?>