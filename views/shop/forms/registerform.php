<?php

function RegisterFormView($login)
{

	global $lang, $base_url;
	
	?>
	<h1><?php echo $lang['shop']['cart']; ?></h1>
	<p>
	<?php echo $lang['shop']['explain_buying_without_register']; ?>
	</p>
	<?php
	
	//echo '<h1>'.$lang['shop']['send_order_and_checkout'].'</h1>';
	
	echo '<h1>'.$lang['user']['register'].'</h1>';
	
	
			
	$login->create_account_form();
	
	?>
	<hr />
	
	<?php
	
	echo '<h1>'.$lang['user']['login'].'</h1>';

}


?>