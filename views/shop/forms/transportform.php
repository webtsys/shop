<?php

function TransportFormView($arr_transport)
{

	global $model, $lang, $config_shop, $base_url, $arr_cache_header;
	// ModelFormView($model_form, $fields=array(), $html_id='')
	
	if(count($arr_transport)==0)
	{
	
		echo '<p>'.$lang['shop']['no_exists_address'].'</p>';
	
	}
	else
	{
	
		foreach($arr_transport as $transport)
		{
		
			echo '<li>'.$arr_transport['address_transport'].'</li>';
		
		}
	
	}
	
	?>
	<h2><?php echo $lang['shop']['address_billing']; ?></h2>
	<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'set_address_transport', array('action' => 'save_transport_address')); ?>">
	<?php
	
	set_csrf_key();
	
	echo load_view(array($model['address_transport']->forms, ConfigShop::$arr_fields_transport), 'common/forms/modelform');
	
	echo '<span class="error">'.$model['user_shop']->std_error.'</span>';
	
	?>
	<p><input type="submit" value="<?php echo $lang['common']['send']; ?>" /></p>
	</form>
	<?php
}

?>