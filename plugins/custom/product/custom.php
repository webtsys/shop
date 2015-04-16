<?php

load_lang('shop');
load_libraries(array('table_config'));

class CustomProductClass {

	//For use in views, by flexibility.You can create different methods.

	public function show_plugin_product($arr_row)
	{
	
		$tpl_plugin='';
	
		$arr_chars=$this->obtain_chars($arr_row['IdProduct']);
		
		$z=0;
	
		foreach($arr_chars as $id => $arr_char)
		{
		
			$char_name=I18nField::show_formatted($arr_char['idcharacteristic']);
			
			echo '<p>'.$char_name.': ';
			
			echo '<input type="hidden" name="characteristic_name[]" value="'.$char_name.'" id="characteristic_name_'.$z.'"/>';
			
			echo '<select name="characteristic_option[]" id="characteristic_option_'.$z.'">';
			
			$arr_options_chars=$this->load_options_chars($arr_char['characteristic_IdCharacteristic'], $arr_row['IdProduct']);
			
			$selected='selected';
			
			foreach($arr_options_chars as $arr_char_option)
			{
			
				$name_option=I18nField::show_formatted($arr_char_option['name']);
				
				echo '<option value="'.$name_option.'" '.$selected.'>'.$name_option.'</option>';
			
				$selected='';
			
			}
			
			echo '</select>';
			
			echo'</p>';
		
		}
	
		return $tpl_plugin;
	
	}
	
	public function cart_product_insert_data($post, $arr_data)
	{
	
		//Simple validation
		
		settype($post['characteristic_name'], 'array');
		
		foreach($post['characteristic_name'] as $id =>  $char)
		{
		
			$post['characteristic_name'][$id]=@form_text($post['characteristic_name'][$id]);
			$post['characteristic_option'][$id]=@form_text($post['characteristic_option'][$id]);
			
			if($post['characteristic_name'][$id]!='' && $post['characteristic_option'][$id]!='')
			{
				;
				$arr_data['characteristic']['characteristic_name'][$id]=$post['characteristic_name'];
				$arr_data['characteristic']['characteristic_option'][$id]=$post['characteristic_option'];
			
			}
			
		}
		
		return $arr_data;
	
	}
	
	public function prepare_name_plugin($idcart_shop, $arr_details)
	{
	
		
	
	}

	public function admin_show_options($arr_row)
	{
	
		return '<a href="'.set_plugin_link_product($arr_row['IdProduct'], 'custom', 0).'">'.PhangoVar::$l_['shop']->lang('add_custom_characteristics', 'add_custom_characteristics').'</a>';
	
	}
	
	public function admin_plugin()
	{
	
		settype($_GET['op_plugin'], 'integer');
		settype($_GET['id'], 'integer');
		
		//$arr_menu[0]=array(PhangoVar::$lang['plugin_product_admin_home'], set_admin_link('shop', array('op' => 22)) );
		
		$arr_menu[0]=array(PhangoVar::$l_['shop']->lang('plugin_product_admin_home', 'plugin_product_admin_home'), set_admin_link('shop', array('op' => 23, 'plugin' => $_GET['plugin'], 'element_choice' => 'product', 'op_plugin' => 0)) );
		
		$arr_menu[1]=array(PhangoVar::$l_['shop']->lang('add_characteristic_to_cat', 'add_characteristic_to_cat'), set_admin_link('shop', array('op' => 23, 'plugin' => $_GET['plugin'], 'element_choice' => 'product', 'op_plugin' => 1, 'id' => $_GET['id'])) );
		
		echo '<a href="'.set_admin_link('shop', array('op' => 20, 'element_choice' => 'product')).'">'.PhangoVar::$l_['shop']->lang('plugin_admin', 'Administración de plugins').'</a> &gt;&gt; '.menu_barr_hierarchy($arr_menu, 'op_plugin', $yes_last_link=0, $arr_final_menu=array(), $return_arr_menu=0);
	
		switch($_GET['op_plugin'])
		{
		
			default:
				
				echo '<h3>'.PhangoVar::$l_['shop']->lang('add_product_characteristics', 'add_product_characteristics').'</h3>';
			
				PhangoVar::$model['characteristic']->create_form();
				
				PhangoVar::$model['characteristic']->forms['type']->form='SelectForm';
				
				$arr_form=array('', PhangoVar::$l_['shop']->lang('choose_element', 'choose_element'), '');
				
				settype(ConfigShop::$arr_plugin_options['custom']['types'], 'array');
				
				foreach(ConfigShop::$arr_plugin_options['custom']['types'] as $type_form => $arr_form_type)
				{
					
					$arr_form[]=ucfirst($type_form);
					$arr_form[]=$type_form;
				
				}
				
				PhangoVar::$model['characteristic']->forms['type']->set_parameter_value($arr_form);
			
				$admin=new GenerateAdminClass('characteristic');
				
				$url_post=set_admin_link('shop', array('op' => 23, 'element_choice' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => $_GET['op_plugin']));
				
				$admin->set_url_post($url_post);
				
				$admin->arr_fields=array('name');
				
				$admin->options_func='CustomOptionsListModel';
				
				$admin->show();
			
			break;
			
			case 1:
			
				load_libraries(array('forms/selectmodelformbyorder'));
			
				settype($_GET['id'], 'integer');
				
				if(PhangoVar::$model['characteristic']->element_exists($_GET['id']))
				{
			
					echo '<h3>'.PhangoVar::$l_['shop']->lang('add_characteristic_to_cat', 'add_characteristic_to_cat').'</h3>';
					
					PhangoVar::$model['characteristic_cat']->create_form();
					
					PhangoVar::$model['characteristic_cat']->forms['idcat']->form='SelectModelFormByOrder';
					
					PhangoVar::$model['characteristic_cat']->forms['idcat']->set_parameter(5, 'subcat');
					
					PhangoVar::$model['characteristic_cat']->forms['idcharacteristic']->form='HiddenForm';
					
					PhangoVar::$model['characteristic_cat']->forms['idcharacteristic']->set_parameter_value($_GET['id']);
					
					$admin=new GenerateAdminClass('characteristic_cat');
					
					$url_post=set_admin_link('shop', array('op' => 23, 'element_choice' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 1, 'id' => $_GET['id']));
					
					$admin->set_url_post($url_post);
					
					$admin->arr_fields=array('idcat');
					
					$admin->where_sql='where idcharacteristic='.$_GET['id'];
					
					$admin->show();
					
				}
			
			break;
			
			case 2:
			
				settype($_GET['id'], 'integer');
				
				$arr_cat=PhangoVar::$model['characteristic']->select_a_row($_GET['id'], array(), 1);
				
				settype($arr_cat['IdCharacteristic'], 'integer');
				
				if($arr_cat['IdCharacteristic']>0)
				{
				
					if(ConfigShop::$arr_plugin_options['custom']['types'][$arr_cat['type']][0]!='')
					{
					
						load_libraries( array(ConfigShop::$arr_plugin_options['custom']['types'][$arr_cat['type']][1]), ConfigShop::$arr_plugin_options['custom']['types'][$arr_cat['type']][0] );
					
					}
				
					echo '<h3>'.PhangoVar::$l_['shop']->lang('add_product_characteristics_option', 'add_product_characteristics_option').' - '.I18nField::show_formatted($arr_cat['name']).'</h3>';
			
					PhangoVar::$model['characteristic_standard_option']->create_form();
					
					//PhangoVar::$model['characteristic_standard_option']->forms['name']->form=$arr_cat['type'];
					
					PhangoVar::$model['characteristic_standard_option']->forms['name']->set_parameter(3, $arr_cat['type']);
					
					PhangoVar::$model['characteristic_standard_option']->forms['idcharacteristic']->form='HiddenForm';
					
					PhangoVar::$model['characteristic_standard_option']->forms['idcharacteristic']->set_parameter_value($_GET['id']);
				
					$admin=new GenerateAdminClass('characteristic_standard_option');
					
					$url_post=set_admin_link('shop', array('op' => 23, 'element_choice' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => $_GET['op_plugin'], 'id' => $_GET['id']));
					
					$admin->arr_fields_edit=array('name', 'added_price', 'idcharacteristic');
					
					$admin->set_url_post($url_post);
					
					$admin->arr_fields=array('name');
					
					$admin->where_sql='where option_delete=0 and idcharacteristic='.$arr_cat['IdCharacteristic'].' and idproduct IS NULL';
					
					//$admin->options_func='CustomOptionsListModel';
					
					$admin->show();
					
				}
			
			break;
		
		}
	
	}
	
	public function obtain_chars($idproduct)
	{
	
		$arr_relationship=PhangoVar::$model['product_relationship']->select_to_array('where product_relationship.idproduct='.$idproduct, array('idcat_product'), 1);
				
		$arr_id_cat_prod=array();
		
		foreach($arr_relationship as $arr_rel)
		{
		
			$arr_id_cat_prod[]=$arr_rel['idcat_product'];
		
		}
		
		//Load all cat products id for order. 
		
		$arr_order_cat=array();
		
		$arr_id_cat=PhangoVar::$model['cat_product']->select_to_array('', array('IdCat_product', 'subcat'));
		
		$arr_order_cat[0]=array(0 => 0);
		
		foreach($arr_id_cat as $id => $arr_subcat)
		{
		
			$arr_order_cat[$id][]=$arr_subcat['subcat'];
		
		}
		
		$arr_final_cat[0]=0;
		
		foreach($arr_id_cat_prod as $id)
		{
		
			$arr_final_cat=load_hierarchy_cat($arr_order_cat, $arr_final_cat, $id);
			
		}
		
		//order recursively
		
		PhangoVar::$model['characteristic_cat']->components['idcharacteristic']->fields_related_model=array('IdCharacteristic');
		
		return PhangoVar::$model['characteristic_cat']->select_to_array('where idcat IN ('.implode(',', $arr_final_cat).')', array());
	
	}
	
	public function load_options_chars($idcharacteristic, $idproduct)
	{
	
		$arr_chars=PhangoVar::$model['characteristic_standard_option']->select_to_array('where characteristic_standard_option.idcharacteristic='.$idcharacteristic.' AND (characteristic_standard_option.idproduct IS NULL OR characteristic_standard_option.idproduct='.$idproduct.') order by position ASC, name ASC', array(), 1);
					
		$arr_deleted_char=array();
		
		foreach($arr_chars as $id => $arr_char)
		{
		
			$arr_deleted_char[$arr_char['option_delete']]=$id;
		
		}
		
		foreach($arr_chars as $id => $arr_char)
		{
		
			settype($arr_deleted_char[$id], 'integer');
		
			if($arr_deleted_char[$id] || $arr_char['name']=='')
			{
			
				unset($arr_chars[$id]);
			
			}
		
		}
		
		return $arr_chars;
	
	}
	
	public function admin_plugin_product()
	{
	
		settype($_GET['idcat'], 'integer');
		settype($_GET['IdProduct'], 'integer');
		settype($_GET['op_plugin'], 'integer');
		settype($_GET['plugin'], 'string');
		settype($_GET['IdProduct'], 'integer');
		settype($_GET['idcharacteristic'], 'integer');
		settype($_GET['idcharacteristic_option'], 'integer');
		
		$arr_product=PhangoVar::$model['product']->select_a_row($_GET['IdProduct']);
		
		settype($arr_product['IdProduct'], 'integer');
		
		if($arr_product['IdProduct']>0)
		{
		
			$arr_menu[0]=array(PhangoVar::$l_['shop']->lang('add_product_characteristics', 'add_product_characteristics'), set_admin_link('shop', array('op' => 22, 'IdProduct' => $_GET['IdProduct'], 'type' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 0, 'idcat' => $_GET['idcat'])));
			$arr_menu[1]=array(PhangoVar::$l_['shop']->lang('add_new_option', 'add_new_option'), set_admin_link('shop', array('op' => 22, 'IdProduct' => $_GET['IdProduct'], 'type' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 1, 'idcharacteristic' => $_GET['idcharacteristic'], 'idcat' => $_GET['idcat'])) );
			
			echo '<a href="'.set_admin_link('shop', array('op' => 3, 'idcat' => $_GET['idcat'])).'">'.PhangoVar::$l_['shop']->lang('products', 'Productos').'</a> &gt;&gt; '.menu_barr_hierarchy($arr_menu, 'op_plugin', $yes_last_link=0, $arr_final_menu=array(), $return_arr_menu=0);
		
			echo '<h3>'.PhangoVar::$l_['shop']->lang('add_product_characteristics', 'add_product_characteristics').' - '.PhangoVar::$model['product']->components['title']->show_formatted($arr_product['title']).'</h3>';
			
			//Need obtain the category  and fathers.
			
			//Load relationship of this product
			
			switch($_GET['op_plugin'])
			{
			
				default:
			
				$arr_chars=$this->obtain_chars($_GET['IdProduct']);
				
				echo up_table_config(array(PhangoVar::$l_['common']->lang('name', 'name'), PhangoVar::$l_['common']->lang('options', 'Options')));
				
				foreach($arr_chars as $arr_char)
				{
				
					$url_set_options=set_admin_link('shop', array('op' => 22, 'IdProduct' => $arr_product['IdProduct'], 'type' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 1, 'idcharacteristic' => $arr_char['characteristic_IdCharacteristic'], 'idcat' => $_GET['idcat']));
					
					echo middle_table_config( array(PhangoVar::$model['characteristic']->components['name']->show_formatted($arr_char['idcharacteristic']), '<a href="'.$url_set_options.'">'.PhangoVar::$l_['shop']->lang('edit_options', 'edit_options').'</a>') );
				
				}
				
				echo down_table_config();
				
				break;
				
				case 1:
				
					settype($_GET['IdProduct'], 'integer');
					settype($_GET['idcharacteristic'], 'integer');
				
					/*$admin=new GenerateAdminClass('characteristic_standard_option');
					
					$admin->arr_fields=array('name');
					
					$admin->set_url_post(set_admin_link('shop', array('op' => 22, 'IdProduct' => $_GET['IdProduct'], 'type' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 1, 'idcharacteristic' => $_GET['idcharacteristic'])));
					
					$admin->where_sql='where characteristic_standard_option.idcharacteristic='.$_GET['idcharacteristic'].' AND (characteristic_standard_option.idproduct IS NULL OR characteristic_standard_option.idproduct='.$_GET['IdProduct'].')';
					
					$admin->no_search=1;
					
					$admin->options_func='SetOptionsListModel';
					
					$admin->show();*/
					
					echo '<p><a href="'.set_admin_link('shop', array('op' => 22, 'IdProduct' => $_GET['IdProduct'], 'type' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 3, 'idcharacteristic' => $_GET['idcharacteristic'], 'idcat' => $_GET['idcat'])).'">'.PhangoVar::$l_['shop']->lang('add_new_option', 'add_new_option').'</a></p>';
					
					$arr_chars=$this->load_options_chars($_GET['idcharacteristic'], $_GET['IdProduct']);
					
					echo up_table_config(array(PhangoVar::$l_['common']->lang('name', 'name'), PhangoVar::$l_['common']->lang('options', 'Options')));
					
					foreach($arr_chars as $id => $arr_char)
					{
					
						$url=set_admin_link('shop', array('op' => 22, 'IdProduct' => $_GET['IdProduct'], 'type' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 2, 'idcharacteristic' => $_GET['idcharacteristic'], 'idcharacteristic_option' => $id, 'idcat' => $_GET['idcat']));
						
						/*settype($arr_deleted_char[$id], 'integer');
						
						if(!$arr_deleted_char[$id] && $arr_char['name']!='')
						{*/
						
							echo middle_table_config( array(I18nField::show_formatted($arr_char['name']), '<a href="'.$url.'" class="check_click">'.PhangoVar::$l_['common']->lang('delete', 'Delete').'</a>') );
						
						//}
						
						/*if($arr_char['option_delete']==0)
						{
					
							echo middle_table_config( array(I18nField::show_formatted($arr_char['name']), '<a href="'.$url.'" class="check_click">'.PhangoVar::$l_['common']->lang('delete', 'Delete').'</a>') );
							
						}
						else
						{
						
							
						
						}*/
						
					
					}
					
					echo down_table_config();
					
					//print_r($arr_chars);
					
					
				
				break;
				
				case 2:
				
					//Falta delete las caracteristicas de producto creadas.
				
					settype($_GET['plugin'], 'string');
					settype($_GET['IdProduct'], 'integer');
					settype($_GET['idcharacteristic'], 'integer');
					settype($_GET['idcharacteristic_option'], 'integer');
					
					load_libraries(array('redirect'));
					
					PhangoVar::$model['characteristic_standard_option']->reset_require();
					
					//option_delete idproduct, idcharacteristic
					
					$url_back=set_admin_link('shop', array('op' => 22, 'IdProduct' => $_GET['IdProduct'], 'type' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 1, 'idcharacteristic' => $_GET['idcharacteristic'], 'idcharacteristic_option' => $_GET['idcharacteristic_option'], 'idcat' => $_GET['idcat']));
					
					$arr_post=array('option_delete' => $_GET['idcharacteristic_option'], 'idproduct' => $_GET['IdProduct'], 'idcharacteristic' => $_GET['idcharacteristic']);
					
					PhangoVar::$model['characteristic_standard_option']->insert($arr_post);
					
					simple_redirect( $url_back, PhangoVar::$l_['common']->lang('redirect', 'Redirect'), PhangoVar::$l_['common']->lang('success', 'Success'), PhangoVar::$l_['common']->lang('press_here_redirecting', 'Press here for redirecting'));
				
				break;
				
				case 3:
				
					settype($_GET['plugin'], 'string');
					settype($_GET['IdProduct'], 'integer');
					settype($_GET['idcharacteristic'], 'integer');
					settype($_GET['idcharacteristic_option'], 'integer');
				
					/*if(!isset($_GET['op_action']))
					{
					
						$_GET['op_action']=1;
					
					}*/
					
					PhangoVar::$model['characteristic_standard_option']->create_form();
					
					PhangoVar::$model['characteristic_standard_option']->forms['idcharacteristic']->form='HiddenForm';
					
					PhangoVar::$model['characteristic_standard_option']->forms['idcharacteristic']->set_parameter_value($_GET['idcharacteristic']);
					
					PhangoVar::$model['characteristic_standard_option']->forms['idproduct']->form='HiddenForm';
					
					PhangoVar::$model['characteristic_standard_option']->forms['idproduct']->set_parameter_value($_GET['IdProduct']);
				
					echo '<h3>'.PhangoVar::$l_['shop']->lang('add_product_option', 'add_product_option').'</h3>';
					
					$admin=new GenerateAdminClass('characteristic_standard_option');
					
					$admin->arr_fields=array('name');
					
					$admin->set_url_post(set_admin_link('shop', array('op' => 22, 'IdProduct' => $_GET['IdProduct'], 'type' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 3, 'idcharacteristic' => $_GET['idcharacteristic'], 'idcat' => $_GET['idcat'])));
					
					$admin->set_url_back(set_admin_link('shop', array('op' => 22, 'IdProduct' => $_GET['IdProduct'], 'type' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 1, 'idcharacteristic' => $_GET['idcharacteristic'], 'idcat' => $_GET['idcat'])));
					
					//$admin->where_sql='where characteristic_standard_option.idcharacteristic='.$_GET['idcharacteristic'].' AND (characteristic_standard_option.idproduct IS NULL OR characteristic_standard_option.idproduct='.$_GET['IdProduct'].')';
					
					$admin->arr_fields_edit=array('name', 'added_price', 'idcharacteristic', 'idproduct');
					
					$admin->insert_model_form();
				
				break;
				
			}
		
		}
	
	}

}

function load_hierarchy_cat($arr_order_cat, $arr_final_cat, $id)
{
	
	if($id!=0)
	{
	
		foreach($arr_order_cat[$id] as $id_father)
		{
		
			$arr_final_cat[]=$id;
			
			
			$arr_final_cat=load_hierarchy_cat($arr_order_cat, $arr_final_cat, $id_father);
		
		}
		
	}

	return $arr_final_cat;

}

function set_plugin_link_product($idproduct, $plugin, $op_plugin)
{

	return set_admin_link('shop', array('op' => 22, 'IdProduct' => $idproduct, 'type' => 'product', 'plugin' => $plugin, 'op_plugin' => $op_plugin, 'idcat' => $_GET['idcat']));

}

function CustomOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$url=set_admin_link('shop', array('op' => 23, 'element_choice' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 1, 'id' => $id));
	
	$url_standard=set_admin_link('shop', array('op' => 23, 'element_choice' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 2, 'id' => $id));
	
	$arr_options[]='<a href="'. $url_standard.'">'.PhangoVar::$l_['shop']->lang('add_standard_options', 'add_standard_options').'</a>';
	$arr_options[]='<a href="'. $url.'">'.PhangoVar::$l_['shop']->lang('add_characteristic_to_cat', 'add_characteristic_to_cat').'</a>';

	return $arr_options;

}

function SetOptionsListModel($url_options, $model_name, $id, $arr_row)
{
	//Change the option if have idproduct on.
	//$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$url=set_admin_link('shop', array('op' => 22, 'IdProduct' => $_GET['IdProduct'], 'type' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 2, 'idcharacteristic' => $_GET['idcharacteristic'], 'idcat' => $_GET['idcat']));
	
	$arr_options[]='<a href="'. $url.'">'.PhangoVar::$l_['shop']->lang('delete_option_from_product', 'delete_option_from_product').'</a>';

	return $arr_options;

}


?>