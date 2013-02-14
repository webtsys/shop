<?php
//Load the thing...
global $model;


function HomePageLink($id)
{

	global $lang, $arr_index_page, $model;
	
	load_model('shop/homepage');
	load_lang('shop_homepage');
	
	if(gettype($arr_index_page)!='array')
	{
		
		$arr_index_page=array();
	
		$query=$model['homepage_shop']->select('order by position ASC', array('idproduct'), true);

		while(list($idproduct)=webtsys_fetch_row($query))
		{
			
			$arr_index_page[$idproduct]=1;

		}
	
	}
	
	if(isset($arr_index_page[$id]))
	{
	
		
		return $lang['shop_homepage']['product_added_to_homepage'];
		
	
	}
	else
	{
	
		//Now, attach the game...
		
		return $lang['shop_homepage']['add_product_to_homepage'];
		
	}

}

function HomePageAdminExternal()
{

	global $lang, $base_url, $model;
	
	load_model('shop/homepage');
	load_lang('shop_homepage');
	
	$arr_fields=array('idproduct');
	$arr_fields_edit=array();
	
	$url_options=make_fancy_url($base_url, 'admin', 'index', 'admin_external_plugin', array('IdModule' => $_GET['IdModule'], 'op' => 23, 'plugin' => 'homepage', 'element_choice' => 'product'));
	
	settype($_GET['op_homepage'], 'integer');
	
	$model['homepage_shop']->components['idproduct']->fields_related_model=array();
	$model['homepage_shop']->components['idproduct']->name_field_to_field='title'
	
	switch($_GET['op_homepage'])
	{
	
		default:
	
			echo '<h3>'.$lang['shop_homepage']['add_product_to_homepage'].'</h3>';
		
			ListModel('homepage_shop', $arr_fields, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');
			
			//http://localhost/phangodev/index.php/admin/show/index/admin_external_plugin/IdModule/8/op/23/IdProduct/1/plugin/homepage/element_choice/product
			
			echo '<p><a href="'.make_fancy_url($base_url, 'admin', 'index', 'admin_external_plugin', array('IdModule' => $_GET['IdModule'], 'op' => 23, 'plugin' => 'homepage', 'element_choice' => 'product', 'op_homepage' => 1)).'">'.$lang['shop_homepage']['order_product_in_homepage'].'</a></p>';
		
		break;
		
		case 1:
		
			echo '<h3>'.$lang['shop_homepage']['order_product_in_homepage'].'</h3>';
			
			GeneratePositionModel('homepage_shop', 'idproduct', 'position', make_fancy_url($base_url, 'admin', 'index', 'admin_external_plugin', array('IdModule' => $_GET['IdModule'], 'op' => 23, 'plugin' => 'homepage', 'element_choice' => 'product', 'op_homepage' => 1)), $where='');
				
			echo '<p><a href="'.make_fancy_url($base_url, 'admin', 'index', 'admin_external_plugin', array('IdModule' => $_GET['IdModule'], 'op' => 23, 'plugin' => 'homepage', 'element_choice' => 'product', 'op_homepage' => 0)).'">'.$lang['common']['go_back'].'</a></p>';
			
		break;
		
	}

}

function HomePageAdmin($idproduct)
{

	global $model, $lang, $base_url, $base_path, $language, $config_shop, $user_data, $arr_i18n, $header, $arr_block, $arr_plugin_list, $arr_plugin_product_list;
	
	/*load_lang('shop_attachments');
	
	$arr_fields=array('name');
	$arr_fields_edit=array();
	//http://localhost/phangodev/index.php/admin/show/index/edit_cat_shop/IdModule/8/op/22/IdProduct/10/plugin/attachments/element_choice/product
	
	$url_options=make_fancy_url($base_url, 'admin', 'index', $lang['shop_attachments']['add_attachments'], array('IdModule' => $_GET['IdModule'], 'op' => 22, 'IdProduct' => $idproduct, 'plugin' => 'attachments', 'element_choice' => 'product') );
	
	$model['product_attachments']->components['idproduct']->form='HiddenForm';
	
	$model['product_attachments']->create_form();
	
	$model['product_attachments']->forms['idproduct']->SetForm($idproduct);
	
	$model['product_attachments']->forms['file']->parameters=array($name="file", $class='', $value='', $delete_inline=0, $model['product_attachments']->components['file']->url_path);

	$model['product_attachments']->set_enctype_binary();
	
	generate_admin_model_ng('product_attachments', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where idproduct="'.$idproduct.'"', $arr_fields_form=array(), $type_list='Basic');*/
	
	load_model('shop/homepage');
	load_lang('shop_homepage');
	
	settype($_GET['IdProduct'], 'integer');
	
	//Insert homepage.
	
	$query=$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('IdProduct', 'idcat'));
	
	list($idproduct, $idcat)=webtsys_fetch_row($query);
	
	settype($idproduct, 'integer');
	
	$num_insert=0;
	
	$num_insert=$model['homepage_shop']->select_count('where IdProduct='.$idproduct, 'IdProduct');
	
	if($num_insert==0 && $idproduct!=0)
	{
	
		if($model['homepage_shop']->insert( array('idproduct' => $idproduct) ))
		{
		
			ob_end_clean();
			load_libraries(array('redirect'));
			
			//http://localhost/phangodev/index.php/admin/show/index/edit_cat_shop/IdModule/8/op/3/idcat/1
			
			$url_redirect=make_fancy_url($base_url, 'admin', 'index', 'edit_cat_shop', array('IdModule' => 8, 'op' => 3, 'idcat' => $idcat));
			
			die( redirect_webtsys( $url_redirect, $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );
		
		}
		else
		{
		
			
			echo '<p>'.$lang['shop_homepage']['error_product_no_exists'].'</p>';
		
		}
		
	}
	else
	{
	
		
		if($model['homepage_shop']->delete( 'where idproduct='. $idproduct) )
		{
		
			ob_end_clean();
			load_libraries(array('redirect'));
			
			//http://localhost/phangodev/index.php/admin/show/index/edit_cat_shop/IdModule/8/op/3/idcat/1
			
			$url_redirect=make_fancy_url($base_url, 'admin', 'index', 'edit_cat_shop', array('IdModule' => 8, 'op' => 3, 'idcat' => $idcat));
			
			die( redirect_webtsys( $url_redirect, $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );
		
		}
		
	
	}
	
	
}

function HomePageShow($idproduct)
{

	/*global $model, $lang, $base_url, $base_path, $language, $config_shop, $user_data, $arr_i18n, $header, $arr_block, $arr_plugin_list, $arr_plugin_product_list;

	$query=$model['product_attachments']->select('where idproduct='.$idproduct, array(), 1);
	
	//Load view..., pass the query?.
	
	return load_view(array($query), 'shop/plugins/attachments');*/

}

?>