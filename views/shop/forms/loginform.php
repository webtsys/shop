<?php

function LoginShopFormView($login)
{

	?>
	<hr />
	
	<?php
	
	echo '<h1>'.i18n_lang('user', 'login', 'login').'</h1>';
	
	$login->login_form();

}

?>