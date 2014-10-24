<?php

function LoginShopFormView($login)
{

	global $lang;

	?>
	<hr />
	
	<?php
	
	echo '<h1>'.$lang['user']['login'].'</h1>';
	
	$login->login_form();

}

?>