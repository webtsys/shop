<?php

function RegisterShopFormView($login)
{

	//global $lang;
	
	echo '<h1>'.i18n_lang('users', 'register', 'Register in the web').'</h1>';
	
	echo '<p>'.i18n_lang('shop', 'register_explain', 'register_explain').'</p>';
	
	$login->create_account_form();

}

?>