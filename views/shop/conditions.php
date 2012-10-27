<?php

function ConditionsView($title, $content)
{

global $base_url, $base_path, $arr_i18n, $language, $lang, $user_data, $arr_cache_jscript, $arr_check_table;


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<title><?php echo $config_data['portal_name'].' - '.$title; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="<?php echo $base_url; ?>/media/default/style.css" rel="stylesheet" type="text/css" />
	</head>
<body>
	<?php echo $content; ?>
</body>	
</html>

<?php

}



?>
