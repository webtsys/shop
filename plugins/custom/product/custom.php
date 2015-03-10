<?php

load_lang('shop');
load_libraries(array('table_config'));

class CustomProductClass {

	public function admin_show_options($arr_row)
	{
	
		return '<a href="'.set_plugin_link_product($arr_row['IdProduct'], 'custom', 0).'">'.PhangoVar::$lang['shop']['add_custom_characteristics'].'</a>';
	
	}
	
	public function admin_plugin()
	{
	
		settype($_GET['op_plugin'], 'integer');

	
		switch($_GET['op_plugin'])
		{
		
			default:
			
				echo '<h3>'.PhangoVar::$lang['shop']['add_product_characteristics'].'</h3>';
			
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
			
					echo '<h3>'.PhangoVar::$lang['shop']['add_characteristic_to_cat'].'</h3>';
					
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
				
					echo '<h3>'.PhangoVar::$lang['shop']['add_product_characteristics_option'].' - '.I18nField::show_formatted($arr_cat['name']).'</h3>';
			
					PhangoVar::$model['characteristic_standard_option']->create_form();
					
					PhangoVar::$model['characteristic_standard_option']->forms['idcharacteristic']->form='HiddenForm';
					
					PhangoVar::$model['characteristic_standard_option']->forms['idcharacteristic']->set_parameter_value($_GET['id']);
				
					$admin=new GenerateAdminClass('characteristic_standard_option');
					
					$url_post=set_admin_link('shop', array('op' => 23, 'element_choice' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => $_GET['op_plugin'], 'id' => $_GET['id']));
					
					$admin->arr_fields_edit=array('name', 'added_price', 'idcharacteristic');
					
					$admin->set_url_post($url_post);
					
					$admin->arr_fields=array('name');
					
					//$admin->options_func='CustomOptionsListModel';
					
					$admin->show();
					
				}
			
			break;
		
		}
	
	}
	
	public function admin_plugin_product()
	{
	
		settype($_GET['IdProduct'], 'integer');
		settype($_GET['op_plugin'], 'integer');
		
		$arr_product=PhangoVar::$model['product']->select_a_row($_GET['IdProduct']);
		
		settype($arr_product['IdProduct'], 'integer');
		
		if($arr_product['IdProduct']>0)
		{
		
			echo '<h3>'.PhangoVar::$lang['shop']['add_product_characteristics'].' - '.PhangoVar::$model['product']->components['title']->show_formatted($arr_product['title']).'</h3>';
			
			//Need obtain the category  and fathers.
			
			//Load relationship of this product
			
			switch($_GET['op_plugin'])
			{
			
				default:
			
				$arr_relationship=PhangoVar::$model['product_relationship']->select_to_array('where product_relationship.idproduct='.$_GET['IdProduct'], array('idcat_product'), 1);
				
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
				
				$arr_chars=PhangoVar::$model['characteristic_cat']->select_to_array('where idcat IN ('.implode(',', $arr_final_cat).')', array());
				
				echo up_table_config(array(PhangoVar::$lang['common']['name'], PhangoVar::$lang['common']['options']));
				
				foreach($arr_chars as $arr_char)
				{
				
					$url_set_options=set_admin_link('shop', array('op' => 22, 'IdProduct' => $arr_product['IdProduct'], 'type' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 1, 'idcharacteristic' => $arr_char['characteristic_IdCharacteristic']));
					
					echo middle_table_config( array(PhangoVar::$model['characteristic']->components['name']->show_formatted($arr_char['idcharacteristic']), '<a href="'.$url_set_options.'">'.PhangoVar::$lang['shop']['edit_options'].'</a>') );
				
				}
				
				echo down_table_config();
				
				break;
				
				case 1:
				
					settype($_GET['IdProduct'], 'integer');
					settype($_GET['idcharacteristic'], 'integer');
				
					$admin=new GenerateAdminClass('characteristic_standard_option');
					
					$admin->arr_fields=array('name');
					
					$admin->set_url_post(set_admin_link('shop', array('op' => 22, 'IdProduct' => $_GET['IdProduct'], 'type' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 1, 'idcharacteristic' => $_GET['idcharacteristic'])));
					
					$admin->where_sql='where characteristic_standard_option.idcharacteristic='.$_GET['idcharacteristic'].' AND (characteristic_standard_option.idproduct IS NULL OR characteristic_standard_option.idproduct='.$_GET['IdProduct'].')';
					
					$admin->no_search=1;
					
					$admin->options_func='SetOptionsListModel';
					
					$admin->show();
				
				break;
				
				case 2:
				
					
				
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

	return set_admin_link('shop', array('op' => 22, 'IdProduct' => $idproduct, 'type' => 'product', 'plugin' => $plugin, 'op_plugin' => $op_plugin));

}

function CustomOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$url=set_admin_link('shop', array('op' => 23, 'element_choice' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 1, 'id' => $id));
	
	$url_standard=set_admin_link('shop', array('op' => 23, 'element_choice' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 2, 'id' => $id));
	
	$arr_options[]='<a href="'. $url_standard.'">'.PhangoVar::$lang['shop']['add_standard_options'].'</a>';
	$arr_options[]='<a href="'. $url.'">'.PhangoVar::$lang['shop']['add_characteristic_to_cat'].'</a>';

	return $arr_options;

}

function SetOptionsListModel($url_options, $model_name, $id)
{

	//$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$url=set_admin_link('shop', array('op' => 22, 'IdProduct' => $_GET['IdProduct'], 'type' => 'product', 'plugin' => $_GET['plugin'], 'op_plugin' => 2, 'idcharacteristic' => $_GET['idcharacteristic']));
	
	$arr_options[]='<a href="'. $url.'">'.PhangoVar::$lang['shop']['delete_option_from_product'].'</a>';

	return $arr_options;

}


?>