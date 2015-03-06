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
			
			$arr_chars=PhangoVar::$model['characteristic_cat']->select_to_array('where idcat IN ('.implode(',', $arr_final_cat).')');
			
			echo up_table_config(array(PhangoVar::$lang['common']['name'], PhangoVar::$lang['common']['options']));
			
			foreach($arr_chars as $arr_char)
			{
				
				echo middle_table_config( array(PhangoVar::$model['characteristic']->components['name']->show_formatted($arr_char['idcharacteristic']), PhangoVar::$lang['shop']['edit_options']) );
			
			}
			
			echo down_table_config();
		
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
	
	$arr_options[]='<a href="'. $url.'">'.PhangoVar::$lang['shop']['add_standard_options'].'</a>';
	$arr_options[]='<a href="'. $url.'">'.PhangoVar::$lang['shop']['add_characteristic_to_cat'].'</a>';

	return $arr_options;

}



?>