<?php

settype($_GET['IdCart_shop'], 'integer');

$query=$model['cart_shop']->select('where IdCart_shop='.$_GET['IdCart_shop'], array('details'));

$arr_product=webtsys_fetch_array($query);

$product_data=unserialize($arr_product['details']);

switch($_GET['action'])
{

default:

	?>
	<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'buy', 'buy_product', array('IdProduct' => $_GET['IdProduct'], 'action' => 1) ); ?>">
	<?php
	set_csrf_key();
		echo '<h2>'.$lang['shop']['options_for_product'].' '.$name_product.'</h2>';
		$z=0;

		$query=webtsys_query('select type_product_option.title, type_product_option.question, type_product_option.options,product_option.IdProduct_option,product_option.idtype from type_product_option,product_option where product_option.idproduct='.$_GET['IdProduct'].' and type_product_option.IdType_product_option=product_option.idtype');

		while(list($title, $question, $options, $idproduct_option, $idtype_product_option)=webtsys_fetch_row($query))
		{
			
			$title=$model['type_product_option']->components['title']->show_formatted($title);
			$question=$model['type_product_option']->components['question']->show_formatted($question);
			
			echo '<h3>'.$title.'</h3>';

			settype($product_data[$z], 'string');

			$arr_select=array($product_data[$z]);

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
					
					echo SelectForm('option['.$z.']', '', $arr_select);
				}
				else
				{

					echo TextForm('option['.$z.']', '', $product_data[$z]);

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

break;

case 1:

	$arr_ident=array();

	$z=0;
	$no_blank=0;

	$query=webtsys_query('select type_product_option.title, type_product_option.question, type_product_option.description, type_product_option.options,product_option.IdProduct_option,product_option.idtype, product_option.field_required from type_product_option,product_option where product_option.idproduct='.$_GET['IdProduct'].' and type_product_option.IdType_product_option=product_option.idtype');

	while(list($title, $question, $description, $options, $idproduct_option, $idtype_product_option, $option_required)=webtsys_fetch_row($query))
	{
		
		$options=$model['type_product_option']->components['options']->show_formatted($options);
		
		if($options!='')
		{

			$arr_options=explode('|', $options);

			settype($_POST['option'][$z], 'string');
			
			if(in_array($_POST['option'][$z], $arr_options))
			{

				$_POST['option'][$z]=@form_text($_POST['option'][$z]);
				$arr_ident[$z]=$_POST['option'][$z];
				$no_blank++;

			}
			else if( trim($_POST['option'][$z])!='' && trim($options)=='' )
			{
				
				$_POST['option'][$z]=str_replace('<', '&lt', $_POST['option'][$z]);
				$_POST['option'][$z]=str_replace('>', '&gt', $_POST['option'][$z]);

				$arr_ident[$z]=$_POST['option'][$z];

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

				$no_blank++;

			}

			$arr_ident[$z]=$_POST['option'][$z];
		
		}

		$z++;

	}
	
	if($no_blank==$z)
	{
		add_cart( $arr_ident , $price, $special_offer);
		
	}
	else
	{

		echo '<p>'.$lang['shop']['need_minimum_an_option'].'</p>';
		echo '<p><a href="javascript:history.back();">'.$lang['common']['go_back'].'</a></p>';

	}


break;

}
