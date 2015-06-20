<?php

function UpdateModelFormView($model_form, $arr_fields=array(), $url_post, $enctype='', $form_html_id='', $arr_categories=array('default' => array()))
{
	
	View::$js[]='jquery.min.js';
	
	$hide_button_tab=0;
	
	if(isset($arr_categories['default']))
	{
		
		$arr_categories['default']=array('fields' => &$arr_fields, 'name_fields' => 'default');
	
		$hide_button_tab=1;
	
	}

	ob_start();
	?>
	<script language="javascript">
	
	$(document).ready( function () {
	
		<?php
		
		if($hide_button_tab==1)
		{
		
		?>
		
		$('.form_button_tab').hide();
		$('#name_fields').hide();
		
		<?php
		
		}
		else
		{
		
		?>
		
		$('.form_tab').hide();
		
		$('#form<?php echo $form_html_id; ?> .form_tab:first').show();
		
		$('.form_button_tab:first').removeClass('form_button_tab').addClass('form_button_tab_selected');
		
		//Show the first tab.
	
		$('.class_button_tab').click( function () {
		
			$('.form_tab').hide();
		
			//$(this).show();
		
			id=$(this).attr('id');
			
			//alert(id);
		
			final_id=id.replace('_button_tab', '');
			
			$('#'+final_id+'_tab').show();
		
			$('.form_button_tab_selected').removeClass('form_button_tab_selected').addClass('form_button_tab');
		
			$(this).removeClass('form_button_tab').addClass('form_button_tab_selected');
		
		
		});
		
		<?php
		
		}
		
		?>
	
	});
	
	</script>
	<?php
	
	View::$header[]=ob_get_contents();
	
	ob_end_clean();
	
	$html_tabs='';
	
	?>
	<form method="post" action="<?php echo $url_post; ?>" name="form" id="form<?php echo $form_html_id; ?>" <?php echo $enctype; ?>>
	<?php
	Utils::set_csrf_key();
	
	$arr_button_tabs=array();
	
	ob_start();
	
	foreach($arr_categories as $category => $arr_fields_tab)
	{
	
		$sum_error=0;
	
		foreach($arr_fields_tab['fields'] as $field)
		{
		
			if($model_form[$field]->std_error!='')
			{
			
				$sum_error++;
			
			}
		
		}
		
		if($sum_error>0)
		{
		
			$arr_cache_header[]='<script language="javascript">
			
			$(document).ready( function () { 
				
				$("#'.$category.'_button_tab").append(" <span class=\"error\">('.$sum_error.')</span>"); 
			
			});
			</script>';
		
		}
	
		$arr_button_tabs[]='<a href="#" class="form_button_tab class_button_tab" id="'.$category.'_button_tab">'.$arr_fields_tab['name_fields'].'</a>';
	
	
		?>
		<div id="<?php echo $category; ?>_tab" class="form_tab">
		
		<h2 id="name_fields"><?php echo $arr_fields_tab['name_fields']; ?></h2>
		
		<?php
		
		echo View::load_view(array($model_form, $arr_fields_tab['fields']), 'common/forms/modelform');
	
		?>
		</div>
		<?php

	}
	
	$html_tabs=ob_get_contents();
	
	ob_end_clean();
		
	echo '<p>'.implode("  ", $arr_button_tabs).'</p>';
	
	echo $html_tabs;
	
	?>
	<input type="submit" value="<?php echo I18n::lang('common', 'send', 'Send'); ?>" />
	<p class="error"><?php echo I18n::lang('common', 'with_*_field_required', '* Field required'); ?></p>
	</form>
	<?php

}

?>
