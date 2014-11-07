<?php

function MethodPaymentView($arr_payment)
{

	global $lang, $base_url;

?>	
	<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'checkout', array('action' => 'finish_checkout', 'op' => 1));?>">
	<?php set_csrf_key(); ?>
	<h2><?php echo $lang['shop']['payment_form']; ?></h2>
	<p><?php echo $lang['shop']['explain_payment_type_transport_type']; ?></p>
	<h3><?php echo $lang['shop']['payment_type']; ?></h3>
	<?php

		echo SelectForm('payment_form', '', $arr_payment );
	
	?>
	<p><input type="submit" value="<?php echo $lang['shop']['send_order_and_checkout']; ?>" /></p>
	</form>
	<?php

}

?>

