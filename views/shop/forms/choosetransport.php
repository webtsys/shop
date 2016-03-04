<?php

use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaView\View;
use PhangoApp\PhaModels\Webmodel;

function ChooseTransportView($arr_transport, $total_price_product, $total_weight_product, $cart)
{

	//global PhangoVar::$lang, PhangoVar::$base_url;

	?>
		<form method="get" action="<?php echo Routes::make_simple_url('shop/cart/save_choose_transport'); ?>">
		<h2><?php echo I18n::lang('shop', 'choose_transport', 'Elegir transporte'); ?></h2>
		<p><?php echo I18n::lang('shop', 'explain_choose_transport', 'Por favor, eliga su método de transporte'); ?></p>
		<?php
	
		$arr_transport_id=[];
	
		$arr_choose_transport=[];
	
		foreach($arr_transport as $transport)
		{
			
			$arr_transport_id[]=$transport['IdTransport'];
		
			list($price_transport, $num_packages)=$cart->obtain_transport_price($total_weight_product, $total_price_product, $transport['IdTransport']);
			
			$arr_choose_transport[$transport['IdTransport']]=$transport['name'].' ('.ShopMoneyField::currency_format($price_transport).')';
			//$arr_choose_transport[]=$transport['IdTransport'];
		
		}
		
		if(count($arr_transport_id)>0)
		{
		
			//$arr_choose_transport[0]=$arr_transport_id[0];
		
            $select=new PhangoApp\PhaModels\Forms\SelectForm('idtransport', '');
            
            $select->arr_select=$arr_choose_transport;
            
            echo $select->form();
		
			//echo '<p>'.RadioIntForm($name="idtransport", $class='', $arr_choose_transport, $more_options='').'</p>';
		
		}
		else
		{
		
			echo '<p>No hay disponibles métodos de transporte en este momento</p>';
		
		}
		
		?>
		<p class="error"><?php echo I18n::lang('common', 'with_*_field_required', '* Field required'); ?></p>
		<p><input type="submit" value="<?php echo I18n::lang('common', 'send', 'Send'); ?>"  /></p>
		</form>
		<?php

}

?>