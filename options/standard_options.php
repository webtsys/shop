<?php

settype($_GET['IdCart_shop'], 'integer');

$query=$model['cart_shop']->select('where IdCart_shop='.$_GET['IdCart_shop'], array('details'));

$arr_product=webtsys_fetch_array($query);

$product_data=unserialize($arr_product['details']);

switch($_GET['action'])
{

default:
	
	show_form_options($name_product, $product_data);

break;

case 1:

	$arr_ident=array();

	$z=0;
	$no_blank=0;

	$query=webtsys_query('select type_product_option.title, type_product_option.question, type_product_option.description, type_product_option.options, type_product_option.price,product_option.IdProduct_option,product_option.idtype, product_option.field_required from type_product_option,product_option where product_option.idproduct='.$_GET['IdProduct'].' and type_product_option.IdType_product_option=product_option.idtype');

	while(list($title, $question, $description, $options, $price_options, $idproduct_option, $idtype_product_option, $option_required)=webtsys_fetch_row($query))
	{
		
		$options=$model['type_product_option']->components['options']->show_formatted($options);
		
		if($options!='')
		{
		
			$arr_options=explode('|', $options);

			settype($_POST['option'][$z], 'string');
			
			$_POST['option'][$z]=trim($_POST['option'][$z]);
			
			if($_POST['option'][$z]!='')
			{
			
				if(in_array($_POST['option'][$z], $arr_options))
				{

					$_POST['option'][$z]=@form_text($_POST['option'][$z]);
					$arr_ident[$z]=$_POST['option'][$z];
					$no_blank++;

				}
				else if( $_POST['option'][$z]!='' && trim($options)=='' )
				{
					
					$_POST['option'][$z]=str_replace('<', '&lt', $_POST['option'][$z]);
					$_POST['option'][$z]=str_replace('>', '&gt', $_POST['option'][$z]);

					$arr_ident[$z]=$_POST['option'][$z];

					$no_blank++;

				}
				
				$price+=$price_options;
				
			}
			else if( $_POST['option'][$z]=='' && $option_required==0 )
			{
			
				$no_blank++;
			
			}

		}
		else
		{	

			$_POST['option'][$z]=form_text($_POST['option'][$z]);
			
			if($_POST['option'][$z]=='')
			{
				
				if($option_required==0)
				{

					$no_blank++;

				}

			}
			else
			{
			
				$arr_ident[$z]=$_POST['option'][$z];
			
				$price+=$price_options;

				$no_blank++;

			}
		
		}

		$z++;

	}
	
	if($no_blank==$z)
	{
		add_cart( $arr_ident , $price, $special_offer);
		
	}
	else
	{
		
		echo '<p>'.$lang['shop']['need_minimum_an_option'].'.</p>';
		
		show_form_options($name_product, $product_data, $_POST, 1);
		
		//echo '<p><a href="javascript:history.back();">'.$lang['common']['go_back'].'</a></p>';

	}


break;

}


function show_form_options($name_product, $product_data, $post=array(), $show_error=0)
{

	global $lang, $model, $base_url;
	
	echo '<h2>'.$lang['shop']['options_for_product'].' '.$name_product.'</h2>';
	
	//UpdateModelFormView($model_form, $arr_fields=array(), $url_post, $enctype='')
	
	$arr_form_options=array();
	$z=0;
	
	//($name_form, $name_field, $form, $label, $type, $required=0, $parameters='')
	
	$query=webtsys_query('select type_product_option.title, type_product_option.question, type_product_option.options, type_product_option.price,product_option.IdProduct_option,product_option.idtype, product_option.field_required from type_product_option,product_option where product_option.idproduct='.$_GET['IdProduct'].' and type_product_option.IdType_product_option=product_option.idtype');
	
	while(list($title, $question, $options, $price, $idproduct_option, $idtype_product_option, $required)=webtsys_fetch_row($query))
	{
		
		$title=$model['type_product_option']->components['title']->show_formatted($title);
		$question=$model['type_product_option']->components['question']->show_formatted($question);
		
		//echo '<h3>'.$title.'</h3>';
		
		settype($product_data[$z], 'string');
		
		$arr_select=array($product_data[$z], $lang['common']['any_option'], '');

		$price_text='';
		
		if($price>0)
		{
		
			$price_text=$lang['shop']['add_extra_price'].': '.MoneyField::currency_format($price);
		
		}
		
		?>
		<?php //echo $question; ?>
		<?php
		$options=$model['type_product_option']->components['options']->show_formatted($options);
		$arr_options=explode('|', $options);
		if($options!='')
		{	
			foreach($arr_options as $option)
			{
				$option=trim($option);
				$arr_select[]=$option;
				$arr_select[]=$option;

			}
			
			//echo SelectForm('option['.$z.']', '', $arr_select).' '.$price_text;
			$parameters=$arr_select;
			$form='SelectForm';
		}
		else
		{

			//echo TextForm('option['.$z.']', '', $product_data[$z]).' '.$price_text;
			$parameters=$product_data[$z];
			$form='TextForm';

		}
		
		$arr_form_options['option['.$z.']']=new ModelForm('options_form', 'option['.$z.']', $form, $question, new TextField(), $required, $parameters);
		
		$z++;

	}
	
	//<input type="hidden" name="IdCart_shop" value="<?php echo $_GET['IdCart_shop']; 
	$arr_form_options['IdCart_shop']=new ModelForm('options_form', 'IdCart_shop', 'HiddenForm', $_GET['IdCart_shop'], new IntegerField(), 0, $_GET['IdCart_shop']);
	
	if(isset($post['option']))
	{
		foreach($post['option'] as $key => $value)
		{
		
			$post['option['.$key.']']=$value;
			
		
		}
		unset($post['option']);
	}
	
	if($show_error==1)
	{
	
		ModelForm::check_form($arr_form_options, $post);
		
	}
	
	SetValuesForm($post, $arr_form_options, $show_error);
	
	echo load_view(array($arr_form_options, array(),  make_fancy_url($base_url, 'shop', 'buy', 'buy_product', array('IdProduct' => $_GET['IdProduct'], 'action' => 1) ) ) , 'common/forms/updatemodelform');
	
	/*
	?>
	
	<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'buy', 'buy_product', array('IdProduct' => $_GET['IdProduct'], 'action' => 1) ); ?>">
	<?php
	set_csrf_key();
		
		$z=0;

		$query=webtsys_query('select type_product_option.title, type_product_option.question, type_product_option.options, type_product_option.price,product_option.IdProduct_option,product_option.idtype from type_product_option,product_option where product_option.idproduct='.$_GET['IdProduct'].' and type_product_option.IdType_product_option=product_option.idtype');

		while(list($title, $question, $options, $price, $idproduct_option, $idtype_product_option)=webtsys_fetch_row($query))
		{
			
			$title=$model['type_product_option']->components['title']->show_formatted($title);
			$question=$model['type_product_option']->components['question']->show_formatted($question);
			
			echo '<h3>'.$title.'</h3>';

			settype($product_data[$z], 'string');

			$arr_select=array($product_data[$z], $lang['common']['any_option'], '');

			$price_text='';
			
			if($price>0)
			{
			
				$price_text=$lang['shop']['add_extra_price'].': '.MoneyField::currency_format($price);
			
			}
			
			?>
			<p>
				<?php echo $question; ?>: 
				<?php
				$options=$model['type_product_option']->components['options']->show_formatted($options);
				$arr_options=explode('|', $options);
				if($options!='')
				{	
					foreach($arr_options as $option)
					{
						$option=trim($option);
						$arr_select[]=$option;
						$arr_select[]=$option;
	
					}
					
					echo SelectForm('option['.$z.']', '', $arr_select).' '.$price_text;
				}
				else
				{

					echo TextForm('option['.$z.']', '', $product_data[$z]).' '.$price_text;

				}

				?>
			</p>
			
			<?php

			$z++;

		}

		

	?>
	<input type="hidden" name="IdCart_shop" value="<?php echo $_GET['IdCart_shop']; ?>" />
	<p><input type="submit" value="<?php echo $lang['common']['send']; ?>" /></p>
	</form>
	
	<?php
	*/

}
