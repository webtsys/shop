<?php

function ChooseTransportView($arr_transport, $total_price_product, $total_weight_product)
{

	global $lang, $base_url;

	?>
		<form method="get" action="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'choose_transport', array('action' => 'save_choose_transport/')); ?>">
		<h2><?php echo $lang['shop']['choose_transport']; ?></h2>
		<p><?php echo $lang['shop']['explain_choose_transport']; ?></p>
		<?php
	
		$arr_choose_transport=array(0);
	
		foreach($arr_transport as $transport)
		{
		
			$arr_transport_id[]=$transport['IdTransport'];
		
			list($price_transport, $num_packages)=obtain_transport_price($total_weight_product, $total_price_product, $transport['IdTransport']);
			
			$arr_choose_transport[]=$transport['name'].' ('.MoneyField::currency_format($price_transport).')';
			$arr_choose_transport[]=$transport['IdTransport'];
		
		}
		
		$arr_choose_transport[0]=$arr_transport_id[0];
		
		echo RadioIntForm($name="idtransport", $class='', $arr_choose_transport, $more_options='');
		
		?>
		<p><input type="submit" value="<?php echo $lang['common']['send']; ?>"  /></p>
		</form>
		<?php

}

?>