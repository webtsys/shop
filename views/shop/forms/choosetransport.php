<?php

function ChooseTransportView($arr_transport, $total_price_product, $total_weight_product, $cart)
{

	//global PhangoVar::$lang, PhangoVar::$base_url;

	?>
		<form method="get" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_save_choose_transport'); ?>">
		<h2><?php echo PhangoVar::$lang['shop']['choose_transport']; ?></h2>
		<p><?php echo PhangoVar::$lang['shop']['explain_choose_transport']; ?></p>
		<?php
	
		$arr_transport_id=[];
	
		$arr_choose_transport=array(0);
	
		foreach($arr_transport as $transport)
		{
			
			$arr_transport_id[]=$transport['IdTransport'];
		
			list($price_transport, $num_packages)=$cart->obtain_transport_price($total_weight_product, $total_price_product, $transport['IdTransport']);
			
			$arr_choose_transport[]=$transport['name'].' ('.MoneyField::currency_format($price_transport).')';
			$arr_choose_transport[]=$transport['IdTransport'];
		
		}
		
		if(count($arr_transport_id)>0)
		{
		
			$arr_choose_transport[0]=$arr_transport_id[0];
		
			echo '<p>'.RadioIntForm($name="idtransport", $class='', $arr_choose_transport, $more_options='').'</p>';
		
		}
		else
		{
		
			echo '<p>No hay disponibles métodos de transporte en este momento</p>';
		
		}
		
		?>
		<p><input type="submit" value="<?php echo PhangoVar::$lang['common']['send']; ?>"  /></p>
		</form>
		<?php

}

?>