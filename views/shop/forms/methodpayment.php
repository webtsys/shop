<?php

function MethodPaymentView($arr_payment)
{

	//global PhangoVar::$lang, PhangoVar::$base_url;
	
	ob_start();
	
	?>
	<script language="javascript">
	$(document).ready( function () {
	
		$('#cancel_order').click( function () {
	
			location.href="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_cancel_order', array(), array('op' => 1));?>";
			
		});
	
	});
	</script>
	<?php
	
	PhangoVar::$arr_cache_header[]=ob_get_contents();
	
	ob_end_clean();

?>	
	<form method="post" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_finish_checkout', array(), array('op' => 1));?>">
	<?php set_csrf_key(); ?>
	<h2><?php echo PhangoVar::$l_['shop']->lang('payment_form', 'Forma de pago'); ?></h2>
	<p><?php echo PhangoVar::$l_['shop']->lang('explain_payment_type_transport_type', 'Por favor, elija el medio de pago para terminar la transacción'); ?></p>
	<h3><?php echo PhangoVar::$l_['shop']->lang('payment_type', 'Tipo de pago'); ?></h3>
	<?php

		echo '<p>'.SelectForm('payment_form', '', $arr_payment ).'</p>';
	
	?>
	<p><input type="submit" value="<?php echo PhangoVar::$l_['shop']->lang('send_order_and_checkout', 'Enviar pedido y pagar'); ?>" /> <input type="button" id="cancel_order" value="<?php echo PhangoVar::$l_['shop']->lang('cancel_order', 'Cancelar pedido'); ?>" /></p>
	</form>
	<?php

}

?>

