<?php

function InsertUserFormView($model_user, $model_login)
{
	
	?>
	<form method="post" action="<?php echo $model_login->url_insert; ?>">
	<?php
	
	
	echo View::loadView(array($model_user->forms, $model_login->arr_user_insert), 'common/forms/modelform');
		

	?>
	<p><input type="submit" value="<?php echo I18n::lang('users', 'register', 'Register in the web'); ?>"/></p>
	</form>
	<?php

}

?>