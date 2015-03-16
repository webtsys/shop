<?php

function MethodPaymentView($arr_payment)
{

	//global PhangoVar::$lang, PhangoVar::$base_url;

?>	
	<form method="post" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_finish_checkout', array(), array('op' => 1));?>">
	<?php set_csrf_key(); ?>
	<h2><?php echo PhangoVar::$lang['shop']['payment_form']; ?></h2>
	<p><?php echo PhangoVar::$lang['shop']['explain_payment_type_transport_type']; ?></p>
	<h3><?php echo PhangoVar::$lang['shop']['payment_type']; ?></h3>
	<?php

		echo '<p>'.SelectForm('payment_form', '', $arr_payment ).'</p>';
	
	?>
	<p><input type="submit" value="<?php echo PhangoVar::$lang['shop']['send_order_and_checkout']; ?>" /></p>
	</form>
	<?php

}

?>

