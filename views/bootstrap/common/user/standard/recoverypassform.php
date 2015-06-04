<?php

function RecoveryPassFormView($model_login, $login)
{

	?>
		<h3><?php echo I18n::lang('users', 'remember_password_explain', 'Please enter the email address you registered on the website, which is where you receive your new password'); ?></h3>
		<form method="post" action="<?php echo $login->url_recovery_send; ?>">
			<?php Utils::set_csrf_key(); ?>
			<label for="email"></label>
			<?php
				echo TextForm('email', '');
			?>
			<p><input type="submit" value="<?php echo I18n::lang('users', 'remember_password', 'Remember password'); ?>" /></p>
		</form>

	<?php

}

?>