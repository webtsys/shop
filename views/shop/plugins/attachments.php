<?php

function AttachmentsView($query)
{

	global $model, $lang;
	
	load_lang('shop_attachments');
	
	echo '<h3>'.$lang['shop_attachments']['attachments'].'</h3>';
	
	echo '<ul>';

	while($arr_attachment=webtsys_fetch_array($query))
	{
	
		echo '<li><a href="'.$model['product_attachments']->components['file']->url_path.'/'.$arr_attachment['file'].'">'.$arr_attachment['name'].'</a></li>';
	
	}
	
	echo '</ul>';

}

?>