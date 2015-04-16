<?php

function LoginShopFormView($login)
{

	//global $lang;

	?>
	<hr />
	
	<?php
	
	echo '<h1>'.PhangoVar::$l_['users']->lang('login', 'Login').'</h1>';
	
	$login->login_form();

}

?>