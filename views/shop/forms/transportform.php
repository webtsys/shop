<?php

function TransportFormView($arr_transport, $only_form=0)
{

	//global PhangoVar::$model, PhangoVar::$lang, ConfigShop::$config_shop, PhangoVar::$base_url, PhangoVar::$arr_cache_header;
	
	if($only_form==0)
	{
	
	ob_start();
	
	?>
	<script language="javascript">
	$(document).ready( function () {
	
		$('#add_new_address_form').hide();
	
		$('#add_new_address').click( function() {
		
			if($('#add_new_address_form').css('display')=='none')
			{
				$('#add_new_address_form').show();
			}
			else
			{
			
				$('#add_new_address_form').hide();
			
			}
		
		});
	
	});
	</script>
	<?php
	
	PhangoVar::$arr_cache_header[]=ob_get_contents();
	
	ob_end_clean();
	
	?>
	<h2><?php echo i18n_lang('shop', 'choose_address_transport', 'Elegir dirección de transporte'); ?></h2>
	
	<p><?php echo i18n_lang('shop', 'explain_address_transport', 'Por favor, elija la dirección a la que enviaremos su pedido'); ?></p>
	<?php
	
	if(count($arr_transport)==0)
	{
	
		echo '<p>'.i18n_lang('shop', 'no_exists_address', 'no_exists_address').'</p>';
	
	}
	else
	{
		?>
		<form class="form" method="get" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_save_choose_address_transport'); ?>">
		<?php
	
		$arr_choose_transport=array(0);
	
		foreach($arr_transport as $transport)
		{
		
			$arr_transport_id[]=$transport['IdAddress_transport'];
		
			//echo '<li>'.$transport['address_transport'].' '..'</li>';
			$arr_choose_transport[]=$transport['address_transport'].' ('.$transport['region_transport'].')';
			$arr_choose_transport[]=$transport['IdAddress_transport'];
		
		}
		
		$arr_choose_transport[0]=$arr_transport_id[0];
		
		echo '<p>'.RadioIntForm($name="idaddress", $class='', $arr_choose_transport, $more_options='').'</p>';
		
		?>
		<p><input type="submit" value="<?php echo i18n_lang('common', 'send', 'Send'); ?>"  /></p>
		</form>
		<?php
		
	}
	
	?>
	<p><a href="#" id="add_new_address"><?php echo i18n_lang('shop', 'add_new_address', 'add_new_address'); ?><span class="plus">[+]</a></p>
	<?php
	}
	?>
	<div id="add_new_address_form">
		<h2><?php echo i18n_lang('shop', 'address_billing', 'Dirección de facturación'); ?></h2>
		<form method="post" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_save_transport_address'); ?>">
		<?php
		
		set_csrf_key();
		
		echo load_view(array(PhangoVar::$model['address_transport']->forms, ConfigShop::$arr_fields_transport), 'common/forms/modelform');
		
		echo '<span class="error">'.PhangoVar::$model['address_transport']->std_error.'</span>';
		
		?>
		<p class="error"><?php echo i18n_lang('common', 'with_*_field_required', '* Field required'); ?></p>
		<p><input type="submit" value="<?php echo i18n_lang('common', 'send', 'Send'); ?>" /></p>
		</form>
	</div>
	<?php
}

?>