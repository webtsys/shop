<?php

function LoginShopFormView($login)
{

	?>
	<hr />
	
	<?php
	
	echo '<h1>'.PhangoVar::$l_['user']->lang('login', 'login').'</h1>';
	
	$login->login_form();

}

?>