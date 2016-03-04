<?php

use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaView\View;

function MethodPaymentView($arr_payment)
{

	//global PhangoVar::$lang, PhangoVar::$base_url;
	
	ob_start();
	
	?>
	<script language="javascript">
	$(document).ready( function () {
	
		$('#cancel_order').click( function () {
	
			location.href="<?php echo Routes::make_simple_url('shop/cart/cancel_order', array(), array('op' => 1));?>";
			
		});
	
	});
	</script>
	<?php
	
	View::$header[]=ob_get_contents();
	
	ob_end_clean();

?>	
	<form method="post" action="<?php echo Routes::make_simple_url('shop/cart/finish_checkout', array(), array('op' => 1));?>">
	<?php Utils::set_csrf_key(); ?>
	<h2><?php echo I18n::lang('shop', 'payment_form', 'Forma de pago'); ?></h2>
	<p><?php echo I18n::lang('shop', 'explain_payment_type_transport_type', 'Por favor, elija el medio de pago para terminar la transacción'); ?></p>
	<h3><?php echo I18n::lang('shop', 'payment_type', 'Tipo de pago'); ?></h3>
	<?php

        $form=new PhangoApp\PhaModels\Forms\SelectForm('payment_form', '');
	
        $form->arr_select=$arr_payment;
	
		echo '<p>'.$form->form().'</p>';
	
	?>
	<p><input type="submit" value="<?php echo I18n::lang('shop', 'send_order_and_checkout', 'Enviar pedido y pagar'); ?>" /> <input type="button" id="cancel_order" value="<?php echo I18n::lang('shop', 'cancel_order', 'Cancelar pedido'); ?>" /></p>
	</form>
	<?php

}

?>

