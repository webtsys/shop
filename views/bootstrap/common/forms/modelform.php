<?php

function ModelFormView($model_form, $fields=array(), $html_id='')
{


$arr_required[0]='';
$arr_required[1]='*';

if(count($fields)==0)
{

	$fields=array_keys($model_form);

}

?>

<div class="form" id="<?php echo $html_id; ?>">
		<?php
		
		foreach($fields as $field)
		{
			
			$func_form=$model_form[$field]->form;
			
			switch($func_form)
			{

			default:
			
				$label_class=$model_form[$field]->label_class;
				
				?>

				<p>
				<label class="<?php echo $label_class; ?>"><?php echo ucfirst(str_replace('_', ' ', $model_form[$field]->label) );?> <?php echo $arr_required[$model_form[$field]->required]; ?> <span class="error"><?php echo $model_form[$field]->std_error; ?></span>: </label>
				<?php
				
				echo call_user_func_array($func_form , $model_form[$field]->parameters);
				
				?>
				</p>
			<?php

			break;

			case "HiddenForm":
				
				echo call_user_func_array($func_form , $model_form[$field]->parameters)."\n";
	
			break;

			}

		}

		?>
</div>

<?php

}

?>
