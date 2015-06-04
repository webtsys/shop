<?php

function LoginAdminView($content)
{

?>
<!DOCTYPE html>
<html>
	<head>
	<title><?php echo I18n::lang('users', 'login', 'Login'); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<?php 
		View::$css[]='login.css';
	
		echo View::loadJS();
		echo View::loadCSS();
		echo View::loadHeader();
	?>
	</head>
	<body>
		<div id="logo_phango"></div>
		<div id="login_block">
			<?php echo $content; ?>
		</div>
	</body>
</html>

<?php

}

?>