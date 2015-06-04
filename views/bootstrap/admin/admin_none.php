<?php

function Admin_NoneView($title, $content, $block_title, $block_content, $block_urls, 
$block_type, $block_id, $config_data, $headers='')
{

	View::$js[]='jquery.min.js';
	
	View::$css[]='admin.css';

	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

		<html>
		<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title><?php echo $title; ?></title>
		<?php echo $headers; ?>
		<?php 
		echo View::loadCSS(); 
		echo View::loadJS(); 
		echo View::loadHeader(); 		
		?>
		</head>
		<body>
		<div id="center_body">
			<div id="header"><span id="title_phango">Phango</span> <span id="title_framework">Framework!</span></div>
			<div class="content">
				<div class="cont none_cont">
					<?php echo $content; ?>
				</div>
			</div>
		</div>
		</body>
		</html>
	<?php

}

?>