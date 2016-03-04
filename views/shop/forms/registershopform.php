<?php

use PhangoApp\PhaI18n\I18n;

function RegisterShopFormView($login)
{

	//global $lang;
	
	echo '<h1>'.I18n::lang('users', 'register', 'Register in the web').'</h1>';
	
	echo '<p>'.I18n::lang('shop', 'register_explain', 'register_explain').'</p>';
	
	$login->create_account_form();

}

?>