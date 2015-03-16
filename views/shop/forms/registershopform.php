<?php

function RegisterShopFormView($login)
{

	//global $lang;
	
	echo '<h1>'.PhangoVar::$lang['users']['register'].'</h1>';
	
	echo '<p>'.PhangoVar::$lang['shop']['register_explain'].'</p>';
	
	$login->create_account_form();

}

?>