<?php

function AdminView($header, $title, $content, $name_modules, $url_modules, $extra_data)
{

	View::$js[]='jquery.min.js';
	
	View::$css[]='admin.css';
	
	?>
	<!DOCTYPE html>

		<html>
		<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title><?php echo $title; ?></title>
		<?php echo View::loadCSS(); ?>
		<?php echo View::loadJS(); ?>
		<?php echo View::loadHeader(); ?> 
		</head>
		<body>
		<div id="languages_general">
		<?php

		$arr_selected=array();



		foreach(I18n::$arr_i18n as $lang_item)
		{
			//set

			$arr_selected[Utils::slugify($lang_item)]='no_choose_flag_general';
			$arr_selected[Utils::slugify(I18n::$language)]='choose_flag_general';

			?>
			<a class="<?php echo $arr_selected[Utils::slugify($lang_item)]; ?>" href="<?php echo Routes::makeStaticUrl( 'lang', 'index', 'index', array('language' => $lang_item));?>"><img src="<?php echo View::getMediaUrl('images/languages/'.$lang_item.'.png'); ?>" alt="<?php echo $lang_item; ?>"/></a> 
			<?php

		}

		?>
		</div>
		
		<div id="logout">
		<a href="<?php echo Routes::makeUrl('admin', 'logout');?>">Logout</a>
		</div>

		<div id="center_body">
			<div id="header"><span id="title_phango">Phango</span> <span id="title_framework">Framework!</span> <?php echo $title; ?></div>
			<div class="content_admin">
				<div id="menu">
					<div class="menu_title"><?php echo I18n::lang('admin', 'applications', 'Applications'); ?></div>
					<?php

					foreach($name_modules as $key_module => $name_module)
					{
						?>
						<a href="<?php echo $url_modules[$key_module]; ?>"><?php echo $name_module; ?></a>
						<?php
						
						//If have $key_module with an extra_url element from extra_data, put here.
						
						if(isset($extra_data['extra_url'][$key_module]))
						{
						
							foreach($extra_data['extra_url'][$key_module]['url_module'] as $key => $url_module)
							{
						
								?>
								<a class="sub_module" href="<?php echo $url_module; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ucfirst($extra_data['extra_url'][$key_module]['name_module'][$key]); ?></a>
								<?php
							}
						
						}
					}

					?>
				</div>
				<div class="contents">
					<?php echo View::show_flash(); ?>
					<?php echo $content; ?>
				</div>
			</div>
		</div>
		<div id="loading_ajax">
		</div>
	</body>
	</html>
	<?php

}

?>
