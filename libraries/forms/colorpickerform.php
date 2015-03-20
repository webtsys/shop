<?php

function ColorPickerForm($name="", $class='', $value='')
{

	PhangoVar::$arr_cache_jscript[]='jquery.min.js';
	PhangoVar::$arr_cache_jscript['shop'][]='jscolor.js';

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

	$value = replace_quote_text( $value );
	return $value;

}

?>