<?php

function LoginShopFormView($login)
{

	?>
	<hr />
	
	<?php
	
	echo '<h1>'.PhangoVar::$lang['user']['login'].'</h1>';
	
	$login->login_form();

}

?>