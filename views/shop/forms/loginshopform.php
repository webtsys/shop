<?php

function LoginShopFormView($login)
{

	//global $lang;

	?>
	<hr />
	
	<?php
	
	echo '<h1>'.i18n_lang('users', 'login', 'Login').'</h1>';
	
	$login->login_form();

}

?>