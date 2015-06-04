<?php

function LoginFormView($model_user, $model_login)
{
	
	$model_user->forms['no_expire_session']=new ModelForm('form_login', 'no_expire_session', 'CheckBoxForm', I18n::lang('users', 'automatic_login', 'Automatic login'), new BooleanField(), $required=1, $parameters='');

	$arr_fields_login=array($model_login->field_user, $model_login->field_password, 'no_expire_session');
	
	$model_user->forms['no_expire_session']->label_class='expire_button';
	
	?>
	<form method="post" action="<?php echo $model_login->url_login; ?>">
	<?php
		Utils::set_csrf_key();
		
		echo View::loadView(array($model_user->forms, $arr_fields_login), 'common/forms/modelform');

	?>
	<p><a href="<?php echo $model_login->url_recovery; ?>"><?php echo I18n::lang('users', 'remember_password', 'Remember password'); ?></a></p>
	<p><input type="submit" value="<?php echo I18n::lang('common', 'login', 'Login'); ?>" /></p>
	</form>
	<?php

}

?>
