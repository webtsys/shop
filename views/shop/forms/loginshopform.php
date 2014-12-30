<?php

function LoginShopFormView($login)
{

	//global $lang;

	?>
	<hr />
	
	<?php
	
	echo '<h1>'.PhangoVar::$lang['users']['login'].'</h1>';
	
	$login->login_form();

}

?>