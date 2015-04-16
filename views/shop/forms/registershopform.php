<?php

function RegisterShopFormView($login)
{

	//global $lang;
	
	echo '<h1>'.PhangoVar::$l_['users']->lang('register', 'Register in the web').'</h1>';
	
	echo '<p>'.PhangoVar::$l_['shop']->lang('register_explain', 'register_explain').'</p>';
	
	$login->create_account_form();

}

?>