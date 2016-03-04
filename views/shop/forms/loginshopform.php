<?php

use PhangoApp\PhaI18n\I18n;

function LoginShopFormView($login)
{

	//global $lang;

	?>
	<hr />
	
	<?php
	
	echo '<h1>'.I18n::lang('users', 'login', 'Login').'</h1>';
	
	$login->login_form();

}

?>