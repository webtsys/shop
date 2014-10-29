<?php

function AddressFormView()
{

	global $model, $lang, $config_shop, $base_url;
	// ModelFormView($model_form, $fields=array(), $html_id='')
	
	$arr_fields_edit=array('name', 'last_name', 'nif', 'address', 'city', 'region', 'country', 'zip_code', 'phone', 'fax');
	
	?>
	<h2><?php echo $lang['shop']['address_billing']; ?></h2>
	<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'set_address', array('action' => 'save_address')); ?>">
	<?php
	
	set_csrf_key();
	
	echo load_view(array($model['user_shop']->forms, $arr_fields_edit), 'common/forms/modelform');
	
	echo '<span class="error">'.$model['user_shop']->std_error.'</span>';
	
	?>
	<p><input type="submit" value="<?php echo $lang['common']['send']; ?>" /></p>
	</form>
	<?php
}

?>