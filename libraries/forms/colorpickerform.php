<?php

function ColorPickerForm($name="", $class='', $value='')
{

	View::$js[]='jquery.min.js';
	View::$js['shop'][]='jscolor.js';

	/*ob_start();
	
	?>
	<script language="javascript">
	
	
	</script>
	<?php
	
	PhangoVar::$arr_cache_header[]=ob_get_contents();
	
	ob_end_clean();*/

	return '<input type="text" name="'.$name.'" id="'.$name.'_field_form" class="'.$class.' color {hash:true,caps:false}" value="'.$value.'" />';

}

//Prepare a value for input text

function ColorPickerFormSet($post, $value)
{

	$value = Utils::replace_quote_text( $value );
	return $value;

}

?>