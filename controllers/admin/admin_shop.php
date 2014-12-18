<?php

function ShopAdmin()
{

	global $config_shop, $user_data, $arr_plugin_list, $arr_plugin_product_list;

	load_lang('shop');
	load_model('shop');
	load_config('shop');
	
	load_libraries(array('generate_admin_ng', 'admin/generate_admin_class', 'forms/selectmodelformbyorder', 'forms/selectmodelform', 'forms/textareabb', 'utilities/menu_selected', 'utilities/menu_barr_hierarchy'));

	settype($_GET['op'], 'string');

	$arr_link_options[1]=array('link' => set_admin_link( 'shop', array('op' => 1) ), 'text' => PhangoVar::$lang['shop']['config_shop']);
	$arr_link_options[2]=array('link' => set_admin_link( 'shop', array('op' => 2) ), 'text' => PhangoVar::$lang['shop']['products_categories']);
	$arr_link_options[3]=array('link' => set_admin_link( 'shop', array('op' => 3) ), 'text' => PhangoVar::$lang['shop']['products']);
	//$arr_link_options[4]=array('link' => set_admin_link( 'standard_options_for_products', array('op' => 4) ), 'text' => PhangoVar::$lang['shop']['standard_options_for_products']);
	//$arr_link_options[6]=array('link' => set_admin_link( 'taxes', array('op' => 6) ), 'text' => PhangoVar::$lang['shop']['taxes']);
	$arr_link_options[7]=array('link' => set_admin_link( 'shop', array('op' => 7) ), 'text' => PhangoVar::$lang['shop']['transport']);
	$arr_link_options[10]=array('link' => set_admin_link( 'shop', array('op' => 10) ), 'text' => PhangoVar::$lang['shop']['gateways_payment']);
	//$arr_link_options[11]=array('link' => set_admin_link( 'discount_groups', array('op' => 11) ), 'text' => PhangoVar::$lang['shop']['discount_groups']);
	$arr_link_options[13]=array('link' => set_admin_link( 'shop', array('op' => 13) ), 'text' => PhangoVar::$lang['shop']['orders']);
	$arr_link_options[15]=array('link' => set_admin_link( 'shop', array('op' => 15) ), 'text' => PhangoVar::$lang['shop']['countries']);

	$arr_link_options[17]=array('link' => set_admin_link( 'shop', array('op' => 17) ), 'text' => PhangoVar::$lang['shop']['currency']);
	
	$arr_link_options[20]=array('link' => set_admin_link( 'shop', array('op' => 20) ), 'text' => PhangoVar::$lang['shop']['plugins_shop']);
	
	$arr_link_options[25]=array('link' => set_admin_link( 'shop', array('op' => 25) ), 'text' => PhangoVar::$lang['shop']['admin_users']);
	
	menu_selected($_GET['op'], $arr_link_options);
	
	/*
	?>
	<ul>
		<li><a href="<?php echo set_admin_link( 'config_shop', array('op' => 1) ); ?>"><?php echo PhangoVar::$lang['shop']['config_shop']; ?></a></li>
		<li><a href="<?php echo set_admin_link( 'products_categories', array('op' => 2) ); ?>"><?php echo PhangoVar::$lang['shop']['products_categories']; ?></a></li>
		<li><a href="<?php echo set_admin_link( 'standard_options_for_products', array('op' => 4) ); ?>"><?php echo PhangoVar::$lang['shop']['standard_options_for_products']; ?></a></li>
		<li><a href="<?php echo set_admin_link( 'taxes', array('op' => 6) ); ?>"><?php echo PhangoVar::$lang['shop']['taxes']; ?></a></li>
		<li><a href="<?php echo set_admin_link( 'transport', array('op' => 7) ); ?>"><?php echo PhangoVar::$lang['shop']['transport']; ?></a></li>
		<li><a href="<?php echo set_admin_link( 'gateways_payment', array('op' => 10) ); ?>"><?php echo PhangoVar::$lang['shop']['gateways_payment']; ?></a></li>
		<li><a href="<?php echo set_admin_link( 'discount_groups', array('op' => 11) ); ?>"><?php echo PhangoVar::$lang['shop']['discount_groups']; ?></a></li>
		<li><a href="<?php echo set_admin_link( 'orders', array('op' => 13) ); ?>"><?php echo PhangoVar::$lang['shop']['orders']; ?></a></li>
		<li><a href="<?php echo set_admin_link( 'countries', array('op' => 15) ); ?>"><?php echo PhangoVar::$lang['shop']['countries']; ?></a></li>
	</ul>

	<?php
	*/
	//Here add modules.

	switch($_GET['op'])
	{

		case 1:
		
			//Load type_index
			
			/*$arr_type_index=array('new_products');
			
			if ($dh = opendir(PhangoVar::$base_path.'modules/shop/libraries/type_index/')) 
			{
				while ($file = readdir($dh))
				{
				
					if($file!='.' && $file!='..')
					{

						$file=basename($file);
						$filename=ucfirst(str_replace('.php', '', $file) );

						$arr_type_index[]=$filename;
						$arr_type_index[]=$file;
				
					}
			
				}
			
				closedir($dh);
			}*/

			echo '<h3>'.PhangoVar::$lang['shop']['edit_config_shop'].'</h3>';

			PhangoVar::$model['config_shop']->create_form();

			PhangoVar::$model['config_shop']->set_enctype_binary();

			PhangoVar::$model['config_shop']->forms['image_bill']->parameters=array('image_bill', '', '', 1, PhangoVar::$model['config_shop']->components['image_bill']->url_path);

			PhangoVar::$model['config_shop']->forms['title_shop']->parameters=array('title_shop', '', '', 'TextForm');
			PhangoVar::$model['config_shop']->forms['description_shop']->parameters=array('description_shop', '', '', 'TextAreaBBForm');
			PhangoVar::$model['config_shop']->forms['conditions']->parameters=array('conditions', '', '', 'TextAreaBBForm');

			PhangoVar::$model['config_shop']->forms['idcurrency']->form='SelectModelForm';
			
			PhangoVar::$model['config_shop']->forms['idcurrency']->parameters=array('idcurrency', '', '', 'currency', 'name', $where='order by name ASC');

			PhangoVar::$model['config_shop']->func_update='Config';

			//labels

			PhangoVar::$model['config_shop']->forms['image_bill']->label=PhangoVar::$lang['common']['image'];
			PhangoVar::$model['config_shop']->forms['num_news']->label=PhangoVar::$lang['shop']['num_news'];
			//PhangoVar::$model['config_shop']->forms['yes_taxes']->label=PhangoVar::$lang['shop']['yes_taxes'];
			//PhangoVar::$model['config_shop']->forms['idtax']->label=PhangoVar::$lang['shop']['taxes'];
			//PhangoVar::$model['config_shop']->forms['yes_transport']->label=PhangoVar::$lang['shop']['yes_transport'];
			//PhangoVar::$model['config_shop']->forms['type_index']->label=PhangoVar::$lang['shop']['type_index'];
			//PhangoVar::$model['config_shop']->forms['explain_discounts_page']->label=PhangoVar::$lang['shop']['explain_discounts_page'];
			PhangoVar::$model['config_shop']->forms['conditions']->label=PhangoVar::$lang['shop']['conditions'];
			//PhangoVar::$model['config_shop']->forms['ssl_url']->label=PhangoVar::$lang['shop']['ssl_url'];
			PhangoVar::$model['config_shop']->forms['title_shop']->label=PhangoVar::$lang['shop']['title_shop'];
			PhangoVar::$model['config_shop']->forms['description_shop']->label=PhangoVar::$lang['shop']['description_shop'];	
			PhangoVar::$model['config_shop']->forms['head_bill']->label=PhangoVar::$lang['shop']['head_bill'];
			PhangoVar::$model['config_shop']->forms['num_begin_bill']->label=PhangoVar::$lang['shop']['num_begin_bill'];
			PhangoVar::$model['config_shop']->forms['elements_num_bill']->label=PhangoVar::$lang['shop']['elements_num_bill'];
			PhangoVar::$model['config_shop']->forms['bill_data_shop']->label=PhangoVar::$lang['shop']['bill_data_shop'];
			PhangoVar::$model['config_shop']->forms['footer_bill']->label=PhangoVar::$lang['shop']['footer_bill'];
			PhangoVar::$model['config_shop']->forms['idcurrency']->label=PhangoVar::$lang['shop']['currency'];
			PhangoVar::$model['config_shop']->forms['view_only_mode']->label=PhangoVar::$lang['shop']['view_only_mode'];

			$query=PhangoVar::$model['config_shop']->select('limit 1', array(), 1);
			
			$result=webtsys_fetch_array($query);
			
			ModelForm::set_values_form($result, PhangoVar::$model['config_shop']->forms, $show_error=0);

			//InsertModelForm('config_shop', set_admin_link( 'config_shop', array('op' => 1) ), set_admin_link( 'config_shop', array('op' => 1) ), array('title_shop', 'image_bill', 'view_only_mode', 'idcurrency', 'num_news', 'type_index', 'description_shop', 'conditions', 'head_bill', 'num_begin_bill', 'elements_num_bill', 'bill_data_shop', 'footer_bill'));
			
			$admin=new GenerateAdminClass('config_shop');
			
			$admin->show_config_mode();

		break;

		case 2:

			settype($_GET['subcat'], 'integer');

			$query=PhangoVar::$model['cat_product']->select('where IdCat_product='.$_GET['subcat'], array('title', 'subcat'));

			list($title, $parent)=webtsys_fetch_row($query);

			$title=PhangoVar::$model['cat_product']->components['title']->show_formatted($title);

			$title=' - '.$title;

			if($title==' - ')
			{

				$title='';

			}
			
			//Get view_only_mode from config_shop
			
			$query=PhangoVar::$model['config_shop']->select('limit 1', array('view_only_mode'), 1);
			
			list($view_only_mode)=webtsys_fetch_row($query);

			echo '<h3>'.PhangoVar::$lang['shop']['edit_categories_shop'].' '.$title.'</h3>';

			PhangoVar::$model['cat_product']->create_form();
			
			PhangoVar::$model['cat_product']->set_enctype_binary();

			PhangoVar::$model['cat_product']->forms['subcat']->form='SelectModelFormByOrder';

			PhangoVar::$model['cat_product']->forms['subcat']->parameters=array('subcat', '', '', 'cat_product', 'title', 'subcat', $where='');

			PhangoVar::$model['cat_product']->forms['title']->label=PhangoVar::$lang['common']['title'];
			PhangoVar::$model['cat_product']->forms['subcat']->label=PhangoVar::$lang['shop']['subcat'];
			PhangoVar::$model['cat_product']->forms['description']->label=PhangoVar::$lang['shop']['description'];
			PhangoVar::$model['cat_product']->forms['description']->parameters=array('description', $class='', array(), $type_form='TextAreaBBForm');
			
			PhangoVar::$model['cat_product']->forms['view_only_mode']->set_param_value_form($view_only_mode);
			PhangoVar::$model['cat_product']->forms['view_only_mode']->label=PhangoVar::$lang['shop']['view_only_mode'];
			
			PhangoVar::$model['cat_product']->forms['image_cat']->label=PhangoVar::$lang['common']['image'];
			PhangoVar::$model['cat_product']->forms['image_cat']->parameters=array('image_cat', '', '', 1, PhangoVar::$model['cat_product']->components['image_cat']->url_path);

			$url_options=set_admin_link( 'shop', array('op' => 2, 'subcat' => $_GET['subcat']) );

			$arr_fields=array('title');
			$arr_fields_edit=array();

			//generate_admin_model_ng('cat_product', $arr_fields, $arr_fields_edit, $url_options, $options_func='ShopOptionsListModel', $where_sql='where subcat='.$_GET['subcat'], $arr_fields_form=array(), $type_list='Basic');
			
			$admin=new GenerateAdminClass('cat_product');
			
			$admin->arr_fields=$arr_fields;
			
			$admin->set_url_post($url_options);
			
			$admin->options_func='ShopOptionsListModel';
			
			$admin->where_sql='where subcat='.$_GET['subcat'];
			
			$admin->show();
			
			/*if($_GET['op_edit']>0)
			{

				echo '<p><a href="'. set_admin_link( 'shop', array('op' => 2, 'subcat' => $parent) ).'">'.PhangoVar::$lang['common']['go_back'].'</a></p>';

			}
			else*/
			

		//break;
		
		case '2_5':
		
		if($_GET['op_edit']==0 && $_GET['op_action']==0)
		{
		
			//Order principal categories, util for various things.
			
			echo '<h3>'.PhangoVar::$lang['shop']['order_cats'].'</h3>';
		
			//GeneratePositionModel('cat_product', 'title', 'position', set_admin_link( 'shop', array('op' => 2)), $where='');
			
			$admin->generate_position_model('title', 'position', set_admin_link( 'shop', array('op' => '2_5')), $where='');
		
		}
		
		break;

		case 3:

			settype($_GET['idcat'], 'integer');
			settype($_GET['IdProduct'], 'integer');

			$query=PhangoVar::$model['cat_product']->select('where IdCat_product='.$_GET['idcat'], array('IdCat_product', 'title', 'subcat'));

			list($idcat, $title, $parent)=webtsys_fetch_row($query);
			
			settype($idcat, 'integer');
			
			if($idcat>0)
			{
			
				$title=PhangoVar::$model['cat_product']->components['title']->show_formatted($title);
				
				//Obtain id's from product_relantionship
				
				$arr_id=PhangoVar::$model['product_relationship']->select_a_field('where idcat_product='.$idcat, 'idproduct');
				
				$arr_id[]=0;
				
				$where_sql='where IdProduct IN ('.implode(',', $arr_id).')';
				
			
			}
			else
			{
			
				$title=PhangoVar::$lang['shop']['no_category_defined'];
				$where_sql='';
				
				$cont_edit_product='';
			
			}

			echo '<h3>'.PhangoVar::$lang['shop']['edit_products_from_category'].': '.$title.'</h3>';
			
			ob_start();
			
			?>
			<script language="javascript">
				$(document).ready( function () {
				
					$('#idcat_field_form').change( function () {
						
						location.href='<?php echo set_admin_link( 'shop', array('op' => 3)); ?>/idcat/'+$('#idcat_field_form').val();
					
					});
				
				});
			</script>
			
			<?php
			
			PhangoVar::$arr_cache_header[]=ob_get_contents();
			
			ob_end_clean();
			
			echo '<p><strong>'.PhangoVar::$lang['shop']['choose_category'].'</strong>: '.SelectModelFormByOrder('idcat', '', $idcat, 'cat_product', 'title', 'subcat', $where='').'</p>';
			
			$arr_fields=array('referer', 'title');
			$arr_fields_edit=array( 'IdProduct', 'referer', 'title', 'description', 'description_short', 'price', 'special_offer', 'stock', 'date', 'about_order', 'weight', 'num_sold', 'cool' );
			
			$url_options=set_admin_link( 'shop', array('op' => 3, 'idcat' => $_GET['idcat']) );

			PhangoVar::$model['product']->create_form();

			/*PhangoVar::$model['product']->forms['idcat']->form='SelectModelFormByOrder';

			PhangoVar::$model['product']->forms['idcat']->parameters=array('idcat', '', $_GET['idcat'], 'cat_product', 'title', 'subcat', $where='');*/

			$arr_options=array('', PhangoVar::$lang['common']['any_option'], '');
			$arr_options_check=array();

			$dir = opendir(PhangoVar::$base_path.'modules/shop/options');

			while ($file = readdir($dir)) 
			{
				if(!preg_match('/^\./', $file))
				{

					$arr_options[]=ucfirst(str_replace('.php', '', $file));
					$arr_options[]=$file;
					$arr_options_check[]=$file;

				}
			}

			/*PhangoVar::$model['product']->components['extra_options']->arr_values=&$arr_options_check;
			PhangoVar::$model['product']->forms['extra_options']->SetParameters($arr_options);*/

			PhangoVar::$model['product']->forms['description']->parameters=array('description', '', '', 'TextAreaBBForm');
			PhangoVar::$model['product']->forms['description_short']->parameters=array('description_short', '', '', 'TextAreaBBForm');
			
			PhangoVar::$model['product']->forms['stock']->set_param_value_form(1);

			//Labels for forms..

			PhangoVar::$model['product']->forms['referer']->label=PhangoVar::$lang['shop']['referer'];
			PhangoVar::$model['product']->forms['title']->label=PhangoVar::$lang['common']['title'];
			PhangoVar::$model['product']->forms['description']->label=PhangoVar::$lang['common']['description'];
			PhangoVar::$model['product']->forms['description_short']->label=PhangoVar::$lang['shop']['description_short'];
			//PhangoVar::$model['product']->forms['idcat']->label=PhangoVar::$lang['shop']['idcat'];
			PhangoVar::$model['product']->forms['price']->label=PhangoVar::$lang['shop']['price'];
			PhangoVar::$model['product']->forms['special_offer']->label=PhangoVar::$lang['shop']['special_offer'];
			PhangoVar::$model['product']->forms['stock']->label=PhangoVar::$lang['shop']['stock'];
			PhangoVar::$model['product']->forms['date']->label=PhangoVar::$lang['common']['date'];
			PhangoVar::$model['product']->forms['about_order']->label=PhangoVar::$lang['shop']['about_order'];
			//PhangoVar::$model['product']->forms['extra_options']->label=PhangoVar::$lang['shop']['extra_options'];
			PhangoVar::$model['product']->forms['weight']->label=PhangoVar::$lang['shop']['weight'];
			PhangoVar::$model['product']->forms['num_sold']->label=PhangoVar::$lang['shop']['num_sold'];
			PhangoVar::$model['product']->forms['cool']->label=PhangoVar::$lang['shop']['cool'];

			//Set enctype for this model...

			PhangoVar::$model['product']->set_enctype_binary();
			
			//Load plugins for show links to ProductOptionsListModel
			
			$arr_plugin_product_list=array();
			
			$query=PhangoVar::$model['plugin_shop']->select('where element="product" order by position ASC', array('plugin'));
			
			while(list($plugin)=webtsys_fetch_row($query))
			{
			
				$arr_plugin_product_list[]=$plugin;
				
			}
			
			$admin=new GenerateAdminClass('product');
			
			$admin->arr_fields=&$arr_fields;
			$admin->arr_fields_edit=&$arr_fields_edit;
			$admin->set_url_post($url_options);
			$admin->options_func='ProductOptionsListModel';
			$admin->where_sql=&$where_sql;
			
			$admin->show();
			

			//generate_admin_model_ng('product', $arr_fields, $arr_fields_edit, $url_options, $options_func='ProductOptionsListModel', $where_sql, $arr_fields_form=array(), $type_list='Basic');
			
			

			if($_GET['IdProduct']==0 && !isset($_GET['op_update']))
			{

				echo '<p><a href="'. set_admin_link( 'config_shop', array('op' => 2, 'subcat' => $parent) ).'">'.PhangoVar::$lang['common']['go_back'].'</a></p>';

			}

			

		break;

		case 4:

			echo '<h3>'.PhangoVar::$lang['shop']['edit_options_for_product'].'</h3>';

			$url_options=set_admin_link( 'config_options_shop', array('op' => 4) );

			$arr_fields=array('title');
			$arr_fields_edit=array();

			PhangoVar::$model['type_product_option']->create_form();

			PhangoVar::$model['type_product_option']->forms['title']->label=PhangoVar::$lang['common']['title'];
			PhangoVar::$model['type_product_option']->forms['description']->label=PhangoVar::$lang['common']['description'];
			PhangoVar::$model['type_product_option']->forms['question']->label=PhangoVar::$lang['shop']['question'];
			PhangoVar::$model['type_product_option']->forms['options']->label=PhangoVar::$lang['shop']['options_product'];
			PhangoVar::$model['type_product_option']->forms['price']->label=PhangoVar::$lang['shop']['price'];

			generate_admin_model_ng('type_product_option', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

		break;

		case 5:

			settype($_GET['IdProduct'], 'integer');

			$query=PhangoVar::$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('title', 'idcat'));

			list($title, $idcat)=webtsys_fetch_row($query);

			echo '<h3>'.PhangoVar::$lang['shop']['add_options_to_product'].': </h3>';

			$url_options=set_admin_link( 'add_options_to_product', array('op' => 5, 'IdProduct' => $_GET['IdProduct']) );

			$arr_fields=array('idtype');
			$arr_fields_edit=array('idtype', 'field_required', 'idproduct');

			PhangoVar::$model['product_option']->create_form();

			PhangoVar::$model['product_option']->forms['idproduct']->SetForm($_GET['IdProduct']);

			PhangoVar::$model['product_option']->forms['idtype']->form='SelectModelForm';

			PhangoVar::$model['product_option']->forms['idtype']->label=PhangoVar::$lang['shop']['option_type'];
			
			PhangoVar::$model['product_option']->forms['idtype']->parameters=array('idtype', '', '', 'type_product_option', 'title', $where='');
			
			PhangoVar::$model['product_option']->forms['field_required']->label=PhangoVar::$lang['shop']['option_required'];

			generate_admin_model_ng('product_option', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

			if(!isset($_GET['op_edit']))
			{

				echo '<p><a href="'. set_admin_link( 'edit_product', array('op' => 3, 'idcat' => $idcat) ).'">'.PhangoVar::$lang['common']['go_back'].'</a></p>';
		
			}

		break;

		case 6:

			echo '<h3>'.PhangoVar::$lang['shop']['edit_taxes'].'</h3>';

			?>
			<p>
			<a href="<?php echo set_admin_link( 'zones_shop', array('op' => 8, 'type' => 1) ); ?>"><?php echo PhangoVar::$lang['shop']['zones_taxes']; ?></a>
			</p>
			<?php

			$url_options=set_admin_link( 'edit_taxes', array('op' => 6) );

			$arr_fields=array('name');
			$arr_fields_edit=array();

			PhangoVar::$model['taxes']->create_form();
		
			PhangoVar::$model['taxes']->forms['country']->form='SelectModelForm';
	
			PhangoVar::$model['taxes']->forms['name']->label=PhangoVar::$lang['common']['name'];
			PhangoVar::$model['taxes']->forms['percent']->label=PhangoVar::$lang['shop']['percent'];
			PhangoVar::$model['taxes']->forms['country']->label=PhangoVar::$lang['shop']['zone'];
			
			PhangoVar::$model['taxes']->forms['country']->parameters=array('country', '', '', 'zone_shop', 'name', $where='where type=1');

			generate_admin_model_ng('taxes', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

		break;

		case 7:

			echo '<h3>'.PhangoVar::$lang['shop']['edit_transport'].'</h3>';

			?>
			<p>
			<a href="<?php echo set_admin_link( 'shop', array('op' => 8, 'type' => 0) ); ?>"><?php echo PhangoVar::$lang['shop']['zones_transport']; ?></a>
			</p>
			<?php

			$url_options=set_admin_link( 'shop', array('op' =>7) );

			$arr_fields=array('name');
			$arr_fields_edit=array();
	
			PhangoVar::$model['transport']->create_form();
		
			PhangoVar::$model['transport']->forms['country']->form='SelectModelForm';
			
			PhangoVar::$model['transport']->forms['country']->parameters=array('country', '', '', 'zone_shop', 'name', $where='where type=0');
	
			PhangoVar::$model['transport']->forms['name']->label=PhangoVar::$lang['common']['name'];
			PhangoVar::$model['transport']->forms['country']->label=PhangoVar::$lang['shop']['zone'];
			PhangoVar::$model['transport']->forms['type']->label=PhangoVar::$lang['shop']['type_transport'];
			
			$arr_type_transport=array(0, PhangoVar::$lang['shop']['type_by_weight'], 0, PhangoVar::$lang['shop']['type_by_price'], 1);
			
			PhangoVar::$model['transport']->forms['type']->set_parameter_value($arr_type_transport);
			
			$admin= new GenerateAdminClass('transport');
			
			$admin->arr_fields=&$arr_fields;
			
			$admin->arr_fields_edit=&$arr_fields_edit;
			
			$admin->set_url_post($url_options);
			
			$admin->show();

			//generate_admin_model_ng('transport', $arr_fields, $arr_fields_edit, $url_options, $options_func='TransportOptionsListModel', $where_sql='where IdTransport>0', $arr_fields_form=array(), $type_list='Basic');

		break;

		case 8:

			settype($_GET['type'], 'integer');

			$arr_type_zone[$_GET['type']]=PhangoVar::$lang['shop']['countries_zones_transport'];
			$arr_type_zone[0]=PhangoVar::$lang['shop']['countries_zones_transport'];
			$arr_type_zone[1]=PhangoVar::$lang['shop']['countries_zones_taxes'];

			$sql_type_zone[$_GET['type']]='where type=0';
			$sql_type_zone[0]='where type=0';
			$sql_type_zone[1]='where type=1';

			$back_type_zone[$_GET['type']]=0;
			$back_type_zone[0]=7;
			$back_type_zone[1]=6;

			echo '<h3>'.PhangoVar::$lang['shop']['countries_zones'].' - '.$arr_type_zone[$_GET['type']].'</h3>';

			$url_options=set_admin_link( 'shop', array('op' => 8, 'type' => $_GET['type']) );

			PhangoVar::$model['zone_shop']->create_form();

			PhangoVar::$model['zone_shop']->forms['type']->set_parameter_value($_GET['type']);

			/*foreach(PhangoVar::$arr_i18n as $lang_i18n)
			{

				PhangoVar::$model['zone_shop']->forms['name_'.$lang_i18n]->label=PhangoVar::$lang['common']['name'].' '.$lang_i18n;

			}*/

			PhangoVar::$model['zone_shop']->forms['name']->label=PhangoVar::$lang['common']['name'];

			PhangoVar::$model['zone_shop']->forms['code']->label=PhangoVar::$lang['shop']['country_code'];
			
			PhangoVar::$model['zone_shop']->forms['other_countries']->label=PhangoVar::$lang['shop']['other_countries'];

			$arr_fields=array('name');
			$arr_fields_edit=array('name', 'code', 'type');

			if($_GET['type']==0)
			{

				$arr_fields_edit[]='other_countries';

			}

			//generate_admin_model_ng('zone_shop', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql=$sql_type_zone[$_GET['type']], $arr_fields_form=array(), $type_list='Basic');
			
			$admin=new GenerateAdminClass('zone_shop');
			
			$admin->set_url_post($url_options);
			
			$admin->arr_fields=&$arr_fields;
			
			$admin->arr_fields_edit=&$arr_fields_edit;
			
			$admin->show();

			$go_back_text[0]=PhangoVar::$lang['shop']['go_back_to_transport'];
			$go_back_text[1]=PhangoVar::$lang['shop']['go_back_to_taxes'];

			echo '<p><a href="'. set_admin_link( 'shop', array('op' => $back_type_zone[$_GET['type']]) ).'">'.$go_back_text[$_GET['type']].'</a></p>';

		break;

		case 9:

			settype($_GET['IdTransport'], 'integer'); 

			$query=PhangoVar::$model['transport']->select('where IdTransport='.$_GET['IdTransport'], array('name', 'type'));

			list($name_transport, $type)=webtsys_fetch_row($query);

			echo '<h3>'.PhangoVar::$lang['shop']['price_transport_for'].': '.$name_transport.'</h3>';

			$url_options=set_admin_link( 'price_transport', array('op' => 9, 'IdTransport' => $_GET['IdTransport'] ) );
			
			if($type==0)
			{

				$arr_fields=array('price', 'weight');
				$arr_fields_edit=array();

				PhangoVar::$model['price_transport']->create_form();
				
				PhangoVar::$model['price_transport']->forms['idtransport']->SetForm($_GET['IdTransport']);

				PhangoVar::$model['price_transport']->forms['price']->label=PhangoVar::$lang['shop']['price'];
				PhangoVar::$model['price_transport']->forms['weight']->label=PhangoVar::$lang['shop']['weight'];

				generate_admin_model_ng('price_transport', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where idtransport='.$_GET['IdTransport'], $arr_fields_form=array(), $type_list='Basic');
				
			}
			else
			{
				
				$arr_fields=array('price', 'min_price');
				$arr_fields_edit=array();

				PhangoVar::$model['price_transport_price']->create_form();
				
				PhangoVar::$model['price_transport_price']->forms['idtransport']->SetForm($_GET['IdTransport']);

				PhangoVar::$model['price_transport_price']->forms['price']->label=PhangoVar::$lang['shop']['price'];
				PhangoVar::$model['price_transport_price']->forms['min_price']->label=PhangoVar::$lang['shop']['min_price'];

				generate_admin_model_ng('price_transport_price', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where idtransport='.$_GET['IdTransport'], $arr_fields_form=array(), $type_list='Basic');
			
			}

			if(!isset($_GET['op_edit']))
			{

				echo '<p><a href="'. set_admin_link( 'edit_transport', array('op' => 7) ).'">'.PhangoVar::$lang['common']['go_back'].'</a></p>';

			}

		break;

		case 10:

			echo '<h3>'.PhangoVar::$lang['shop']['gateways_payment'].'</h3>';

			$url_options=set_admin_link( 'shop', array('op' => 10) );

			$arr_fields=array('name');
			$arr_fields_edit=array();

			/*$arr_code=array('');
			$arr_code_check=array();

			$dir=opendir(PhangoVar::$base_path.'modules/shop/payment');

			while($file=readdir($dir))
			{
				if(!preg_match('/^\./', $file))
				{
					$arr_code[]=ucfirst(str_replace('.php', '',$file));
					$arr_code[]=$file;
					$arr_code_check[]=$file;
				}

			}*/
			list($arr_code, $arr_code_check)=obtain_payment_form();
			
			PhangoVar::$model['payment_form']->create_form();
			//$this->arr_values=$arr_values;
			PhangoVar::$model['payment_form']->components['code']->arr_values=&$arr_code_check;

			PhangoVar::$model['payment_form']->forms['code']->set_parameter_value($arr_code);

			PhangoVar::$model['payment_form']->forms['name']->label=PhangoVar::$lang['common']['name'];
			PhangoVar::$model['payment_form']->forms['code']->label=PhangoVar::$lang['shop']['code_payment'];
			PhangoVar::$model['payment_form']->forms['price_payment']->label=PhangoVar::$lang['shop']['price_payment'];

			//generate_admin_model_ng('payment_form', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');
			
			$admin=new GenerateAdminClass('payment_form');
			
			$admin->arr_fields=&$arr_fields;
			
			$admin->set_url_post($url_options);
			
			$admin->show();

		break;

		case 11:

			echo '<h3>'.PhangoVar::$lang['shop']['group_shop'].'</h3>';

			$url_options=set_admin_link( 'edit_group_shop', array('op' => 11) );

			$arr_fields=array('name');
			$arr_fields_edit=array();

			PhangoVar::$model['group_shop']->create_form();

			PhangoVar::$model['group_shop']->forms['name']->label=PhangoVar::$lang['common']['name'];
			PhangoVar::$model['group_shop']->forms['discount']->label=PhangoVar::$lang['shop']['discount'];
			PhangoVar::$model['group_shop']->forms['taxes_for_group']->label=PhangoVar::$lang['shop']['taxes_for_group'];
			PhangoVar::$model['group_shop']->forms['transport_for_group']->label=PhangoVar::$lang['shop']['transport_for_group'];
			PhangoVar::$model['group_shop']->forms['shipping_costs_for_group']->label=PhangoVar::$lang['shop']['shipping_costs_for_group'];
		
			generate_admin_model_ng('group_shop', $arr_fields, $arr_fields_edit, $url_options, $options_func='GroupShopOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

		break;

		case 12:


			settype($_GET['IdGroup_shop'], 'integer');

			PhangoVar::$model['group_shop_users']->create_form();

			$url_options=set_admin_link( 'edit_group_shop', array('op' => 12) );

			PhangoVar::$model['group_shop_users']->forms['group_shop']->SetForm($_GET['IdGroup_shop']);
			PhangoVar::$model['group_shop_users']->forms['iduser']->label=PhangoVar::$lang['common']['user'];

			PhangoVar::$model['group_shop_users']->forms['iduser']->form='SelectModelForm';
			
			PhangoVar::$model['group_shop_users']->forms['iduser']->parameters=array('iduser', '', '', 'user', 'private_nick', $where='where iduser>0');

			generate_admin_model_ng('group_shop_users', array('iduser'), array(),  $url_options, $options_func='BasicOptionsListModel', $where_sql='');

			$url_back=set_admin_link( 'edit_group_shop', array('op' => 11) );

			if($_GET['op_edit']==0)
			{

			?>
				<p><a href="<?php echo $url_back; ?>"><?php echo PhangoVar::$lang['common']['go_back']; ?></a></p>
			<?php

			}

		break;

		case 13:

			//?order_field=date_order&order_desc=1&search_word=&search_field=IdOrder_shop
			
			echo '<h2>'.PhangoVar::$lang['shop']['orders'].'</h2>';

			if(!isset($_GET['order_field']))
			{

				$_GET['order_field']='date_order';
				$_GET['order_desc']=1;

			}

			PhangoVar::$model['order_shop']->components['token']->required=0;
			//PhangoVar::$model['order_shop']->components['payment_form']->required=0;
			
			settype($_GET['op_payment'], 'integer');
			
			$where_sql='';

			$arr_fields=array('name', 'last_name', 'email', 'total_price', 'make_payment', 'date_order');
			
			if($_GET['op_payment']==1)
			{
			
				unset($arr_fields[2]);
			
			}
			
			$arr_fields_edit=array('name', 'last_name', 'enterprise_name', 'email', 'nif', 'address', 'zip_code', 'city', 'region', 'country', 'phone', 'fax', 'name_transport', 'last_name_transport', 'address_transport', 'zip_code_transport', 'city_transport', 'region_transport', 'country_transport', 'phone_transport', 'date_order', 'observations', 'transport', 'name_payment', 'make_payment', 'total_price');
			
			$url_options=set_admin_link( 'shop', array('op' => 13, 'op_payment' => $_GET['op_payment']) );
	
			$arr_country=array('');

			$query=PhangoVar::$model['country_shop']->select('', array('IdCountry_shop', 'name'));

			while(list($idcountry_shop, $name_country)=webtsys_fetch_row($query))
			{

				$arr_country[]=I18nField::show_formatted($name_country);
				$arr_country[]=$idcountry_shop;

			}

			PhangoVar::$model['order_shop']->forms['country']->form='SelectForm';
			PhangoVar::$model['order_shop']->forms['country']->set_parameter_value($arr_country);

			PhangoVar::$model['order_shop']->forms['country_transport']->form='SelectForm';
			PhangoVar::$model['order_shop']->forms['country_transport']->set_parameter_value($arr_country);

			PhangoVar::$model['order_shop']->forms['name']->label=PhangoVar::$lang['common']['name'];
			PhangoVar::$model['order_shop']->forms['last_name']->label=PhangoVar::$lang['common']['last_name'];
			PhangoVar::$model['order_shop']->forms['email']->label=PhangoVar::$lang['common']['email'];
			PhangoVar::$model['order_shop']->forms['total_price']->label=PhangoVar::$lang['shop']['total_price'];
			PhangoVar::$model['order_shop']->forms['make_payment']->label=PhangoVar::$lang['shop']['make_payment'];
			PhangoVar::$model['order_shop']->forms['date_order']->label=PhangoVar::$lang['common']['date'];

			//Zone_transport...

			$arr_transport=array('');

			$query=PhangoVar::$model['transport']->select('', array('IdTransport', 'name'));

			while(list($idtransport, $name_transport)=webtsys_fetch_row($query))
			{

				$arr_transport[]=$name_transport;
				$arr_transport[]=$idtransport;

			}

			PhangoVar::$model['order_shop']->forms['transport']->form='SelectForm';
			PhangoVar::$model['order_shop']->forms['transport']->set_parameter_value($arr_transport);
			
			$arr_link_orders[0]=array('link' => set_admin_link( 'shop', array('op' => 13, 'op_payment' => 0) ), 'text' => PhangoVar::$lang['shop']['payment_orders']);
			
			$arr_link_orders[1]=array('link' => set_admin_link( 'shop', array('op' => 13, 'op_payment' => 1) ), 'text' => PhangoVar::$lang['shop']['no_payment_orders']);
			
			menu_selected($_GET['op_payment'], $arr_link_orders, 1);
			
			switch($_GET['op_payment'])
			{
			
				default:
			
				echo '<h3>'.PhangoVar::$lang['shop']['payment_orders'].'</h3>';

				//ListModel('order_shop', $arr_fields, $url_options, $options_func='BillOptionsListModel', $where_sql='where make_payment=1', $arr_fields_edit, 0);
				
				$list=new ListModelClass('order_shop', $arr_fields, $url_options, $options_func='BillOptionsListModel', $where_sql='where make_payment=1', $arr_fields_edit, 0);
				
				$list->show();
				
				break;
				
				case 1:
			
				echo '<h3>'.PhangoVar::$lang['shop']['no_payment_orders'].'</h3>';

				//ListModel('order_shop', $arr_fields, $url_options, $options_func='BillOptionsListModel', $where_sql='where make_payment=0', $arr_fields_edit, 0);
				
				$list=new ListModelClass('order_shop', $arr_fields, $url_options, $options_func='BillOptionsListModel', $where_sql='where make_payment=0', $arr_fields_edit, 0);
				
				$list->show();
				
				break;
				
			}

		break;

		case 14:

			settype($_GET['IdProduct'], 'integer');

			$query=PhangoVar::$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('title'));
			
			list($title)=webtsys_fetch_row($query);
			
			$title=I18nField::show_formatted($title);

			echo '<h3>'.PhangoVar::$lang['shop']['edit_image_product'].' - '.$title.'</h3>';
			
			$url_add_images=set_admin_link( 'edit_image_product',array('op' => 19, 'IdProduct' => $_GET['IdProduct']) );
			
			echo '<p><a href="'.$url_add_images.'">'.PhangoVar::$lang['shop']['add_new_images'].'</a></h3>';

			$url_options=set_admin_link( 'shop', array('op' => 14, 'IdProduct' => $_GET['IdProduct']) );

			$arr_fields=array('photo', 'principal');
			$arr_fields_edit=array('photo', 'idproduct', 'principal');

			PhangoVar::$model['image_product']->create_form();

			PhangoVar::$model['image_product']->forms['photo']->parameters=array('photo', '', '', 0, PhangoVar::$model['image_product']->components['photo']->url_path);

			PhangoVar::$model['image_product']->forms['idproduct']->form='HiddenForm';

			PhangoVar::$model['image_product']->forms['idproduct']->set_param_value_form($_GET['IdProduct']);

			PhangoVar::$model['image_product']->forms['photo']->label=PhangoVar::$lang['common']['image'];
			PhangoVar::$model['image_product']->forms['principal']->label=PhangoVar::$lang['shop']['principal_photo'];

			//order_field=photo&order_desc=1&search_word=&search_field=IdImage_product

			if(!isset($_GET['order_field']))
			{

				$_GET['order_field']='principal';
				$_GET['order_desc']=1;

			}
			PhangoVar::$model['image_product']->set_enctype_binary();
			
			//generate_admin_model_ng('image_product', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where IdProduct='.$_GET['IdProduct'], $arr_fields_form=array(), $type_list='Basic');
			
			$admin=new GenerateAdminClass('image_product');
			
			$admin->arr_fields=&$arr_fields;
			$admin->arr_fields_edit=&$arr_fields_edit;
			
			$admin->set_url_post($url_options);
			
			$admin->show();

			if($_GET['op_action']==0 && $_GET['op_edit']==0)
			{

				echo '<p><a href="'. set_admin_link( 'shop', array('op' => 3) ).'">'.PhangoVar::$lang['common']['go_back'].'</a></p>';

			}

		break;

		case 15:

			echo '<h3>'.PhangoVar::$lang['shop']['countries'].'</h3>';

			$url_options=set_admin_link( 'shop', array('op' => 15) );

			$arr_fields=array('name');
			$arr_fields_edit=array('name', 'code', 'idzone_transport');

			PhangoVar::$model['country_shop']->create_form();

			PhangoVar::$model['country_shop']->forms['idzone_transport']->form='SelectModelForm';
			
			PhangoVar::$model['country_shop']->forms['idzone_transport']->parameters=array('idzone_transport', '', '', 'zone_shop', 'name', $where='where type=0');

			/*PhangoVar::$model['country_shop']->forms['idzone_taxes']->form='SelectModelForm';
			
			PhangoVar::$model['country_shop']->forms['idzone_taxes']->parameters=array('idzone_taxes', '', '', 'zone_shop', 'name', $where='where type=1');*/

			/*foreach(PhangoVar::$arr_i18n as $lang_i18n)
			{

				PhangoVar::$model['country_shop']->forms['name_'.$lang_i18n]->label=PhangoVar::$lang['common']['name'].' '.$lang_i18n;

			}*/

			PhangoVar::$model['country_shop']->forms['name']->label=PhangoVar::$lang['common']['name'];

			PhangoVar::$model['country_shop']->forms['code']->label=PhangoVar::$lang['shop']['code_country'];
			//PhangoVar::$model['country_shop']->forms['idzone_taxes']->label=PhangoVar::$lang['shop']['idzone_taxes'];
			PhangoVar::$model['country_shop']->forms['idzone_transport']->label=PhangoVar::$lang['shop']['idzone_transport'];

			//generate_admin_model_ng('country_shop', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');
			
			$admin=new GenerateAdminClass('country_shop');
			
			$admin->arr_fields=&$arr_fields;
			$admin->arr_fields_edit=&$arr_fields_edit;
			
			$admin->set_url_post($url_options);
			
			$admin->show();


		break;

		case 16:
		
			ob_end_clean();
			
			settype($_GET['IdOrder_shop'], 'integer');
			
			echo load_view(array(), 'shop/ordershop', 'shop');
			
			die;
		
			/*
			//Load pdf class...

			load_libraries(array('config_shop', 'fpdf/fpdf'), PhangoVar::$base_path.'/modules/shop/libraries/');
			load_libraries(array('form_date'));
			
			if(!function_exists('iconv'))
			{
			
				echo '<p>Error: iconv function don\'t exists. Install php iconv module</p>';
			
				break;
			
			}

			
			class PDF extends FPDF
			{
				// Tabla simple

				function Header()
				{

					global $model, $config_shop, $lang;

					$this->SetXY(10,5);

					if($config_shop['image_bill']!='')
					{

						$this->Image(PhangoVar::$model['config_shop']->components['image_bill']->path.$config_shop['image_bill']);

					}

					//$this->Ln();

				}

				function Footer()
				{

					global $config_shop;

					$this->SetFont('Arial','',10);

					$this->SetXY(5,290);

					//$this->Write(5, iconv("UTF-8", "CP1252", $config_shop['footer_bill'] ));

					$this->Cell(200,9,iconv("UTF-8", "CP1252", $config_shop['footer_bill'] ),0,0,'C');

				}

				function DataClient($order_shop)
				{

					global $model, $config_shop, $lang;

					$this->SetFont('Arial','',10);

					$this->SetXY(105,5);

					$this->MultiCell(95,4, iconv("UTF-8", "CP1252", $config_shop['bill_data_shop'] ),0,'LR');

					//Date client...

					$name_client=iconv("UTF-8", "CP1252", $order_shop['name'].' '.$order_shop['last_name']);

					if($order_shop['enterprise_name']!='')
					{

						$name_client=iconv("UTF-8", "CP1252", $order_shop['enterprise_name']);
			
					}
				
					$text_address_client[]=iconv("UTF-8", "CP1252", PhangoVar::$lang['shop']['client'].': '.$name_client);
					$text_address_client[]=iconv("UTF-8", "CP1252", PhangoVar::$lang['common']['city'].': '.$order_shop['city']);
					$text_address_client[]=iconv("UTF-8", "CP1252", PhangoVar::$lang['common']['region'].': '.$order_shop['region'] );
					$text_address_client[]=iconv("UTF-8", "CP1252", PhangoVar::$lang['common']['email'].': '.$order_shop['email'] );
					$text_address_client[]=iconv("UTF-8", "CP1252", PhangoVar::$lang['common']['country'].': '.$order_shop['country'] );
					
					$text_address_client_other[]=iconv("UTF-8", "CP1252", PhangoVar::$lang['common']['address'].': '.$order_shop['address'] );
					$text_address_client_other[]=iconv("UTF-8", "CP1252", PhangoVar::$lang['common']['zip_code'].': '.$order_shop['zip_code'] );
					$text_address_client_other[]=iconv("UTF-8", "CP1252", PhangoVar::$lang['shop']['fiscal_identity'].': '.$order_shop['nif'] );
					$text_address_client_other[]=iconv("UTF-8", "CP1252", PhangoVar::$lang['common']['phone'].': '.$order_shop['phone'] );
					$text_address_client_other[]=iconv("UTF-8", "CP1252", PhangoVar::$lang['common']['fax'].': '.$order_shop['fax'] );

					$this->SetFont('Arial','',10);

					$this->SetXY(10, 32);

					$this->MultiCell(90,6,implode("\n", $text_address_client),'TB', 'LR');
					
					$this->SetXY(100, 32);

					$this->MultiCell(100,6,implode("\n", $text_address_client_other),'TB','LR');

				}

				function DateOrder($date, $num_bill)
				{

					global $lang;

					$this->SetFont('Arial','',14);

					$this->Cell(90,9,PhangoVar::$lang['common']['date'].': '.$date,0,0,'LR');
					$this->Cell(95,9,iconv("UTF-8", "CP1252", PhangoVar::$lang['shop']['num_bill']).': '.$num_bill,0,0,'LR');

					$this->Ln();

				}

				function TotalPrice($order_shop, $total_price)
				{

					global $lang;

					// Elemento descuento precio

					// 30 20 20

					$this->SetXY(10,220);

					$this->Cell(40,8,'', 0);
					$this->Cell(40,8,PhangoVar::$lang['shop']['value'],1);
					$this->Cell(40,8,PhangoVar::$lang['shop']['discount'],1);
					$this->Cell(40,8,PhangoVar::$lang['shop']['final_value'],1);
					$this->Ln();
					//Here total price...

					//total_price | tax | tax_percent | tax_discount_percent | price_transport | transport_discount_percent | price_payment | payment_discount_percent | discount      | discount_percent | name_payment

					$this->Cell(40, 8, PhangoVar::$lang['shop']['total_price'], 1);
					$this->Cell(40, 8, iconv("UTF-8", "CP1252", MoneyField::currency_format($total_price)), 1);

					$discount=0;
					$total_price_original=$total_price;

					if($order_shop['discount_percent']>0)
					{

						$discount=obtain_discount($order_shop['discount_percent'], $total_price);
						
					}
					
					
					$this->Cell(40, 8, number_format($order_shop['discount_percent'], 2) .' %', 1);

					$total_price-=$discount;
					
					$this->Cell(40, 8, iconv("UTF-8", "CP1252", MoneyField::currency_format($total_price)),1);
					$this->Ln();

					//taxes
					$tax_final=0;
					if($order_shop['tax']!='')
					{

						$tax_name=iconv("UTF-8", "CP1252", $order_shop['tax']);

						$this->Cell(40,8, $tax_name, 1);
						//Calculate tax
							
						$add_tax_original=calculate_raw_taxes($order_shop['tax_percent'] , $total_price_original);
						
						$this->Cell(40,8,iconv("UTF-8", "CP1252", MoneyField::currency_format($add_tax_original) ),1);
						
						$add_tax=calculate_raw_taxes($order_shop['tax_percent'] , $total_price);

						$discount_tax=obtain_discount($order_shop['tax_discount_percent'], $add_tax);

						$this->Cell(40,8, number_format($order_shop['tax_discount_percent'], 2).' %',1);
	
						$tax_final=$add_tax-$discount_tax;

						$this->Cell(40,8,iconv("UTF-8", "CP1252", MoneyField::currency_format($tax_final) ),1);
						$this->Ln();
					}
					$transport_final=0;
					if($order_shop['transport']!='')
					{

						$tax_name=iconv("UTF-8", "CP1252", PhangoVar::$lang['shop']['transport_price']);

						$this->Cell(40,8, $tax_name, 1);

						$transport_price=iconv("UTF-8", "CP1252", MoneyField::currency_format($order_shop['price_transport']) );

						$this->Cell(40,8, $transport_price,1);

						$discount_transport=obtain_discount($order_shop['transport_discount_percent'], $transport_price);

						$this->Cell(40,8, number_format($order_shop['transport_discount_percent'],2).' %',1);

						$transport_final=$order_shop['price_transport']-$discount_transport;

						$this->Cell(40,8,iconv("UTF-8", "CP1252", MoneyField::currency_format($transport_final) ),1);
						$this->Ln();

					}

					$payment_final=0;

					if($order_shop['price_payment']!='')
					{

						$payment_name=iconv("UTF-8", "CP1252", PhangoVar::$lang['shop']['shipping_costs']);

						$this->Cell(40,8, $payment_name, 1);

						$payment_price=iconv("UTF-8", "CP1252", MoneyField::currency_format($order_shop['price_payment']) );

						$this->Cell(40,8, $payment_price,1);

						$discount_payment=obtain_discount($order_shop['payment_discount_percent'], $payment_price);

						$this->Cell(40,8, number_format($order_shop['payment_discount_percent'], 2).' %',1);

						$payment_final=$order_shop['price_payment']-$discount_payment;

						$this->Cell(40,8,iconv("UTF-8", "CP1252", MoneyField::currency_format($payment_final) ),1);
						$this->Ln();

					}


					//TOTAL
					$this->SetFont('Arial','',18);

					$total=iconv("UTF-8", "CP1252", MoneyField::currency_format($total_price+$tax_final+$transport_final+$payment_final) );
	
					$this->Cell(90,14, strtoupper(PhangoVar::$lang['shop']['total']).': '.$total,0);

				}
				
				function ImprovedTable($header, $data)
				{
					// Width of the columns...
					$this->SetFont('Arial','',14);

					$w = array(30, 60, 25, 25, 25, 25);
					// Cabeceras
					for($i=0;$i<count($header);$i++)
					{
						$this->Cell($w[$i],7,$header[$i],1,0,'LR');
					}

					$this->Ln();

					$this->SetFont('Arial','',12);

					// Datos
					foreach($data as $row)
					{
						$this->Cell($w[0],8,$row[0],'LR');
						$this->Cell($w[1],8,$row[1],'LR');
						$this->Cell($w[2],8,$row[2],'LR');
						$this->Cell($w[3],8,$row[3],'LR');
						$this->Cell($w[4],8,$row[4],'LR');
						$this->Cell($w[5],8,$row[5],'LR');
						$this->Ln();
					}

					// Línea de cierre
					$this->Cell(array_sum($w),0,'','T');

					$this->Ln();
				}

			}

			settype($_GET['IdOrder_shop'], 'integer');

			$query=PhangoVar::$model['order_shop']->select('where IdOrder_shop='.$_GET['IdOrder_shop'], array(), 0);

			$arr_order=webtsys_fetch_array($query);
			
			settype($arr_order['IdOrder_shop'], 'integer');

			if($arr_order['IdOrder_shop']>0)
			{

				//Num invoice...
				// Columns titles

				$query=PhangoVar::$model['country_shop']->select('where IdCountry_shop='.$arr_order['country'], array('name'), 1);

				list($arr_order['country'])=webtsys_fetch_row($query);

				$arr_order['country']=PhangoVar::$model['country_shop']->components['name']->show_formatted($arr_order['country']);

				$header = array(iconv("UTF-8", "CP1252",PhangoVar::$lang['shop']['referer']), iconv("UTF-8", "CP1252",PhangoVar::$lang['shop']['description']), iconv("UTF-8", "CP1252", PhangoVar::$lang['shop']['units']), iconv("UTF-8", "CP1252", PhangoVar::$lang['shop']['price']), iconv("UTF-8", "CP1252",PhangoVar::$lang['shop']['discount']), iconv("UTF-8", "CP1252",PhangoVar::$lang['shop']['total']) );

				//here query from cart_shop

				$cart_shop=array();

				$arr_units=array();

				$arr_product_total=array();

				$query=PhangoVar::$model['cart_shop']->select('where token="'.$arr_order['token'].'"');

				while($arr_product=webtsys_fetch_array($query))
				{

					//Array ( [IdCart_shop] => 94 [token] => 9b5c9df47765dad23014b5908d9b4ee2aee04d03 [idproduct] => 1 [price_product] => 10 [name_taxes_product] => 0 [taxes_product] => 0 [details] => a:1:{i:0;s:1:"S";} [time] => 1329709993 [product_referer] => 2456456 [product_title] => Mongol afgano [product_extra_options] => standard_options.php ) 

					settype($arr_units[$arr_product['idproduct']], 'integer');
					settype($arr_product_total[$arr_product['idproduct']], 'integer');

					$arr_units[$arr_product['idproduct']]++;

					$arr_product['price_product']*=$arr_units[$arr_product['idproduct']];

					$arr_product_total[$arr_product['idproduct']]=$arr_product['price_product'];

					//Apply discount

					$price_final=apply_discount($arr_order['discount_percent'], $arr_product_total[$arr_product['idproduct']]);
					
					$arr_product['product_title']=iconv("UTF-8", "CP1252", substr( PhangoVar::$model['product']->components['title']->show_formatted( $arr_product['product_title'] ) , 0, 20).'[..]' );
					$price_product=iconv("UTF-8", "CP1252", MoneyField::currency_format($arr_product['price_product']) );
					$price_final_product=iconv("UTF-8", "CP1252", MoneyField::currency_format($price_final) );

					$cart_shop[$arr_product['idproduct']]=array($arr_product['product_referer'], $arr_product['product_title'], $arr_units[$arr_product['idproduct']], $price_product, number_format($arr_order['discount_percent'], 2).' %', $price_final_product);

				}

				$num_bill=calculate_num_bill($arr_order['invoice_num']);

				//Here pdf

				$pdf = new PDF();
				
				$pdf->AddPage();

				//Write header...

				$pdf->DataClient($arr_order);

				$pdf->DateOrder(form_date( $arr_order['date_order'], $user_data['format_date'], $user_data['format_time']), $num_bill);

				//now write the products table

				$pdf->ImprovedTable($header,$cart_shop);

				$sum_total=array_sum($arr_product_total);

				$pdf->TotalPrice($arr_order, $sum_total);

				ob_end_clean();

				$pdf->Output($num_bill.'.pdf', 'D');
				
				die;

			}*/

		break;

		case 17:

			echo '<h3>'.PhangoVar::$lang['shop']['currency'].'</h3>';

			$url_options=set_admin_link( 'shop', array('op' => 17) );

			$arr_fields=array('name', 'symbol');
			$arr_fields_edit=array('name', 'symbol');

			PhangoVar::$model['currency']->create_form();

			PhangoVar::$model['currency']->forms['name']->label=PhangoVar::$lang['common']['name'];
			PhangoVar::$model['currency']->forms['symbol']->label=PhangoVar::$lang['shop']['symbol'];
		
			//generate_admin_model_ng('currency', $arr_fields, $arr_fields_edit, $url_options, $options_func='CurrencyOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');
			$admin=new GenerateAdminClass('currency');
			
			$admin->arr_fields=&$arr_fields;
			$admin->arr_fields_edit=&$arr_fields_edit;
			
			$admin->set_url_post($url_options);
			
			$admin->show();
			

		break;
	
		case 18:

			settype($_GET['IdCurrency'], 'integer');

			$query=PhangoVar::$model['currency']->select('where IdCurrency='.$_GET['IdCurrency'], array('name'));

			list($name_currency)=webtsys_fetch_row($query);

			$name_currency=I18nField::show_formatted($name_currency);

			echo '<h3>'.PhangoVar::$lang['shop']['modify_change_currencies'].': '.$name_currency.'</h3>';

			$url_options=set_admin_link( 'modify_change_currencies', array('op' => 18, 'IdCurrency' => $_GET['IdCurrency']) );

			$arr_fields=array('idcurrency_related');
			$arr_fields_edit=array();
			
			PhangoVar::$model['currency_change']->components['idcurrency_related']->name_field_to_field='name';

			PhangoVar::$model['currency_change']->create_form();

			PhangoVar::$model['currency_change']->forms['idcurrency']->form='HiddenForm';
			PhangoVar::$model['currency_change']->forms['idcurrency']->SetForm($_GET['IdCurrency']);

			PhangoVar::$model['currency_change']->forms['idcurrency_related']->label=PhangoVar::$lang['shop']['currency'];

			PhangoVar::$model['currency_change']->forms['idcurrency_related']->form='SelectModelForm';
			
			PhangoVar::$model['currency_change']->forms['idcurrency_related']->parameters=array('idcurrency_related', '', '', 'currency', 'name', $where='where IdCurrency!='.$_GET['IdCurrency']);

			PhangoVar::$model['currency_change']->forms['change_value']->label=PhangoVar::$lang['shop']['explain_change_value'].' '.$name_currency;
		
			generate_admin_model_ng('currency_change', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where currency_change.idcurrency='.$_GET['IdCurrency'], $arr_fields_form=array(), $type_list='Basic');

			if($_GET['op_action']==0 && $_GET['op_edit']==0)
			{

				echo '<p><a href="'. set_admin_link( 'edit_currency', array('op' => 17) ).'">'.PhangoVar::$lang['common']['go_back'].'</a></p>';

			}

		break;
		
		case 19:
		
			settype($_GET['op_image'], 'integer');
			settype($_GET['IdProduct'], 'integer');
			
			//ew ImageField('photo', PhangoVar::$base_path.'application/media/shop/images/products/', PhangoVar::$base_url.'/media/shop/images/products', 'image', 1, array('small' => 45, 'mini' => 150, 'medium' => 300, 'preview' => 600));
			
			$arr_field['image_form1']=clone PhangoVar::$model['image_product']->components['photo'];
			$arr_field['image_form1']->name_file='image_form1';
		
			$arr_form['image_form1']=new ModelForm('create_image', 'image_form1', 'ImageForm', PhangoVar::$lang['common']['image'].' 1', $arr_field['image_form1'], $required=1, $parameters='');
			
			/*$bool[1]=new BooleanField();
			
			$arr_form['principal1']=new ModelForm('create_image', 'principal1', 'SelectForm', PhangoVar::$lang['shop']['principal_photo'].' 1', $bool[1], $required=0, $parameters=$bool[1]->get_parameters_default());*/
			
			for($x=2;$x<11;$x++)
			{
			
				$arr_field['image_form'.$x]=clone PhangoVar::$model['image_product']->components['photo'];
				$arr_field['image_form'.$x]->name_file='image_form'.$x;
			
				$arr_form['image_form'.$x]=new ModelForm('create_image', 'image_form'.$x.'', 'ImageForm', PhangoVar::$lang['common']['image'].' '.$x, $arr_field['image_form'.$x], $required=0, $parameters='');
				
				/*$bool[$x]=new BooleanField();
				
				$arr_form['principal'.$x]=new ModelForm('create_image', 'principal'.$x, 'SelectForm', PhangoVar::$lang['shop']['principal_photo'].' 1', $bool[$x], $required=0, $parameters=$bool[$x]->get_parameters_default());*/
			
			}
			
			switch($_GET['op_image'])
			{
			
				default:
				
					ob_start();
					
					$query=PhangoVar::$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('title'));
					
					list($title)=webtsys_fetch_row($query);
					
					$title=I18nField::show_formatted($title);
				
					$url_post=set_admin_link( 'edit_image_product',array('op' => 19, 'IdProduct' => $_GET['IdProduct'], 'op_image' => 1) );
			
					echo load_view(array($arr_form, array(), $url_post, 'enctype="multipart/form-data"'), 'common/forms/updatemodelform');
					
					$cont_add=ob_get_contents();
					
					ob_end_clean();
					
					echo load_view(array(PhangoVar::$lang['shop']['add_new_images'].' - '.$title, $cont_add), 'content');
					
					echo '<p><a href="'.$url_post=set_admin_link( 'edit_image_product',array('op' => 14, 'IdProduct' => $_GET['IdProduct']) ).'">'.PhangoVar::$lang['common']['go_back'].'</a></p>';
				
				break;
				
				case 1:
					
					$arr_post=ModelForm::check_form($arr_form, $_POST);
					
					if($arr_post!=0)
					{
					
						//Insert images..
						
						foreach($arr_form as $img_form)
						{
							
							if($img_form->error_flag==0)
							{
							
								$file_name=$img_form->type->name_file;
							
								PhangoVar::$model['image_product']->insert(array('photo' => $arr_post[$file_name], 'principal' => 0, 'idproduct' => $_GET['IdProduct']));
							
							}
						
						}
					
					
					
						ob_end_clean();
						load_libraries(array('redirect'));
						die( redirect_webtsys( $url_post=set_admin_link( 'edit_image_product',array('op' => 14, 'IdProduct' => $_GET['IdProduct']) ), PhangoVar::$lang['common']['redirect'], PhangoVar::$lang['common']['success'], PhangoVar::$lang['common']['press_here_redirecting'] , $arr_block) );
						
					}
					else
					{
						
						$url_go_back=set_admin_link( 'edit_image_product',array('op' => 19, 'IdProduct' => $_GET['IdProduct']) );
						
						echo '<p>'.PhangoVar::$lang['common']['error_cannot_upload_this_image_to_the_server'].'</p>';
					
						echo '<p><a href="'.$url_go_back.'">'.PhangoVar::$lang['common']['go_back'].'</a></p>';
					
					}
					
				
				break;
			
			}
		
		break;
		
		case 20:
		
			//First, select form for choose an module, for now, products.
			
			echo '<h3>'.PhangoVar::$lang['shop']['plugin_admin'].'</h3>';
			
			settype($_GET['element_choice'], 'string');
			
			$arr_elements_plugin=array($_GET['element_choice'], '', '', 'products', 'product', 'cart', 'cart');
			
			echo '<form method="get" action="'.set_admin_link( 'shop', array('op' => 20)).'">';
			
			echo '<p>'.PhangoVar::$lang['shop']['element_choice'].': '.SelectForm('element_choice', '', $arr_elements_plugin).' <input type="submit" value="'.PhangoVar::$lang['common']['send'].'" /></p>';
			
			echo '</form>';
			
			//Now the form...
			
			$element_choice=PhangoVar::$model['plugin_shop']->components['element']->check($_GET['element_choice']);
			
			//settype($arr_plugin_list[$element_choice], 'array');
			
			if($element_choice!='')
			{
			
				PhangoVar::$model['plugin_shop']->create_form();
				
				PhangoVar::$model['plugin_shop']->forms['element']->form='HiddenForm';
				PhangoVar::$model['plugin_shop']->forms['element']->set_parameter_value($element_choice);
				
				$arr_plugins=array('', '', '');
				
				/*foreach($arr_plugin_list[$element_choice] as $plugin)
				{
				
					$arr_plugins[]=$plugin;
					$arr_plugins[]=$plugin;
					
				
				}*/

				$arr_plugin_choice=array();
				
				$dir = opendir( PhangoVar::$base_path."modules/shop/plugins" );

				while ( $plugin_dir = readdir( $dir ) )
				{
				
					if(!preg_match('/^\./', $plugin_dir))
					{
					
						$subdir=opendir(PhangoVar::$base_path."modules/shop/plugins/".$plugin_dir);
						
						while ( $plugin_subdir = readdir( $subdir ) )
						{
						
							if($plugin_subdir==$element_choice)
							{
								
								$arr_plugins[]=ucfirst($plugin_dir);
								$arr_plugins[]=$plugin_dir;
								
								$arr_plugin_choice[]=$plugin_dir;
					
							}
						
						}
						
						closedir($subdir);
					
					}
				
				}
				
				closedir($dir);
				
				PhangoVar::$model['plugin_shop']->components['plugin']->arr_values=&$arr_plugin_choice;
				
				PhangoVar::$model['plugin_shop']->components['plugin']->restart_formatted();
				
				PhangoVar::$model['plugin_shop']->forms['plugin']->parameters=array('plugin', '', $arr_plugins);
			
				PhangoVar::$model['plugin_shop']->forms['name']->label=PhangoVar::$lang['common']['name'];
			
				$arr_fields=array('name', 'plugin');
				$arr_fields_edit=array('name', 'element', 'plugin');
				$url_options=set_admin_link( 'shop', array('op' => 20, 'element_choice' => $element_choice));
			
				//generate_admin_model_ng('plugin_shop', $arr_fields, $arr_fields_edit, $url_options, $options_func='PluginsOptionsListModel', 
				
				$where_sql='where element="'.$element_choice.'"';
				//, $arr_fields_form=array(), $type_list='Basic');
				
				$admin=new GenerateAdminClass('plugin_shop');
				
				$admin->arr_fields=&$arr_fields;
				
				$admin->arr_fields_edit=&$arr_fields_edit;
				
				$admin->set_url_post($url_options);
				
				$admin->where_sql=$where_sql;
				
				$admin->options_func='PluginsOptionsListModel';
				
				$admin->show();
				
				//Now the order...
				
				echo '<p><a href="'.set_admin_link( 'shop', array('op' => 21, 'element_choice' => $element_choice)).'">'.PhangoVar::$lang['shop']['order_plugins'].'</a></p>';
			
			}
			
			
		
		break;
		
		case 21:
		
			$element_choice=PhangoVar::$model['plugin_shop']->components['element']->check($_GET['element_choice']);
			
			if($element_choice!='')
			{
			
				echo '<h3>'.PhangoVar::$lang['shop']['order_plugins'].'</h3>';
				
				GeneratePositionModel('plugin_shop', 'name', 'position', set_admin_link( 'plugin_admin', array('op' => 21, 'element_choice' => $element_choice)), $where='where element="'.$element_choice.'"');
				
				echo '<p><a href="'.set_admin_link( 'plugin_admin', array('op' => 20, 'element_choice' => $element_choice)).'">'.PhangoVar::$lang['common']['go_back'].'</a></p>';
			
			}
		
		break;
		
		case 22:
		
			settype($_GET['IdProduct'], 'integer');
		
			echo '<h3>'.PhangoVar::$lang['shop']['edit_plugin'].'</h3>';
			
			$element_choice=PhangoVar::$model['plugin_shop']->components['element']->check($_GET['element_choice']);
			
			if($element_choice!='')
			{
			
				$plugin=@form_text($_GET['plugin']);
			
				if( in_array($plugin, $arr_plugin_list[$element_choice]) )
				{
				
					load_libraries(array($plugin), PhangoVar::$base_path.'modules/shop/plugins/product/');
	
					$var_func=ucfirst($plugin).'Admin';
					
					if(function_exists($var_func))
					{
					
						$var_func($_GET['IdProduct']);
						
					}
				
				}
				
			
			}
			
		
		break;
		
		case 23:
		
			$element_choice=PhangoVar::$model['plugin_shop']->components['element']->check($_GET['element_choice']);
			
			if($element_choice!='')
			{
			
				$plugin=@form_text($_GET['plugin']);
			
				if( in_array($plugin, $arr_plugin_list[$element_choice]) )
				{
				
					load_libraries(array($plugin), PhangoVar::$base_path.'modules/shop/plugins/'.$element_choice.'/');
	
					$var_func=ucfirst($plugin).'AdminExternal';
					
					if(function_exists($var_func))
					{
					
						$var_func();
						
					}
				
				}
				
			
			}
		
		break;
		
		case 24:
		
			settype($_GET['idproduct'], 'integer');
			
			$product=PhangoVar::$model['product']->select_a_row($_GET['idproduct'], array('title'), true);
		
			echo '<h3>'.PhangoVar::$lang['shop']['change_shop_category'].' - '.I18nField::show_formatted($product['title']).'</h3>';
			
			$arr_fields=array('idcat_product');
			$arr_fields_edit=array();
			$url_options=set_admin_link( 'shop', array('op' => 24, 'idproduct' => $_GET['idproduct']));
			$url_back=set_admin_link( 'shop', array('op' => 3));
			
			PhangoVar::$model['product_relationship']->components['idproduct']->form='HiddenForm';
			
			PhangoVar::$model['product_relationship']->create_form();
			
			PhangoVar::$model['product_relationship']->forms['idproduct']->form='HiddenForm';
			PhangoVar::$model['product_relationship']->forms['idproduct']->set_param_value_form($_GET['idproduct']);
			
			PhangoVar::$model['product_relationship']->forms['idcat_product']->label=PhangoVar::$lang['shop']['category'];
			PhangoVar::$model['product_relationship']->forms['idcat_product']->form='SelectModelFormByOrder';
			PhangoVar::$model['product_relationship']->forms['idcat_product']->parameters=array('idcat_product', '', 0, 'cat_product', 'title', 'subcat', $where='');
			
			//SelectModelFormByOrder('idcat', '', $idcat, 'cat_product', 'title', 'subcat', $where='')
			
			$admin=new GenerateAdminClass('product_relationship');
			
			$admin->arr_fields=&$arr_fields;
			$admin->arr_fields_edit=&$arr_fields_edit;
			$admin->set_url_post($url_options);
			//$admin->set_url_back($url_back);
			$admin->where_sql='where product_relationship.idproduct='.$_GET['idproduct'];
			
			$admin->show();
			
			//generate_admin_model_ng('product_relationship', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where product_relationship.idproduct='.$_GET['idproduct'], $arr_fields_form=array(), $type_list='Basic');
			
			if($_GET['op_edit']==0 && $_GET['op_action']==0)
			{
			
				echo '<p><a href="'.$url_back.'">'.PhangoVar::$lang['common']['go_back'].'</a></p>';
				
			}
		
		break;
		
		case 25:
		
			echo '<h2>'.PhangoVar::$lang['shop']['admin_users'].'</h2>';
		
			PhangoVar::$model['user_shop']->components['token_client']->required=0;
			
			PhangoVar::$model['user_shop']->components['token_recovery']->required=0;
			
			PhangoVar::$model['user_shop']->components['password']->required=0;
		
			PhangoVar::$model['user_shop']->create_form();
		
			PhangoVar::$model['user_shop']->forms['country']->form='SelectModelForm';
			
			PhangoVar::$model['user_shop']->forms['country']->parameters=array('country', '', '', 'country_shop', 'name', $where='');
			
			$admin=new GenerateAdminClass('user_shop');
			
			$admin->arr_fields=array('email', 'name', 'last_name', 'region');
			
			$admin->arr_fields_edit=ConfigShop::$arr_fields_address;
			
			$admin->arr_fields_edit[]='email';
			
			$admin->arr_fields_edit[]='password';
			
			$url_post=set_admin_link('shop', array('op' => 25));
			
			$admin->set_url_post($url_post);
			
			if($config_shop['no_transport']==0)
			{
			
				$admin->options_func='UserOptionsListModel';
			
			}
			
			$admin->show();
		
		break;
		
		case 26:
		
			settype($_GET['IdUser_shop'], 'integer');
		
			echo '<h2>'.PhangoVar::$lang['shop']['modify_address_transport_user'].'</h2>';
		
			$arr_menu[0]=array('module' => 'admin', 'controller' => 'index', 'text' => PhangoVar::$lang['user']['admin_users'], 'name_op' => 'op', 'params' => array('op' => 25, 'IdOrder_shop' => $_GET['IdUser_shop']));
		
			$arr_menu[1]=array('module' => 'admin', 'controller' => 'index', 'text' => PhangoVar::$lang['shop']['admin_address_users'], 'name_op' => 'op', 'params' => array('op' => 26, 'IdOrder_shop' => $_GET['IdUser_shop']));
		
			echo menu_barr_hierarchy_control($arr_menu);
		
			settype($_GET['IdUser_shop'], 'integer');
			
			PhangoVar::$model['address_transport']->create_form();
		
			PhangoVar::$model['address_transport']->forms['country_transport']->form='SelectModelForm';
			
			PhangoVar::$model['address_transport']->forms['country_transport']->parameters=array('country_transport', '', '', 'country_shop', 'name', $where='');
		
			$admin=new GenerateAdminClass('address_transport');
			
			$url_post=set_admin_link('users', array('op' => 26));
			
			$admin->set_url_post($url_post);
			
			$admin->arr_fields=array('address_transport', 'city_transport');
			
			$admin->arr_fields_edit=ConfigShop::$arr_fields_transport;
			
			$admin->where_sql='where iduser='.$_GET['IdUser_shop'];
		
			$admin->show();
		
		break;

	}

}

function UserOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 26, 'IdUser_shop' => $id) ).'">'.PhangoVar::$lang['shop']['modify_address_transport_user'].'</a>';

	return $arr_options;

}

function CurrencyOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 18, 'IdCurrency' => $id) ).'">'.PhangoVar::$lang['shop']['modify_change_currencies'].'</a>';

	return $arr_options;

}

function ShopOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 3, 'idcat' => $id) ).'">'.PhangoVar::$lang['shop']['modify_products'].'</a>';

	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 2, 'subcat' => $id) ).'">'.PhangoVar::$lang['shop']['subcat_products'].'</a>';	

	return $arr_options;

}

function ProductOptionsListModel($url_options, $model_name, $id, $arr_row_raw)
{
	
	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 24, 'idproduct' => $id) ).'">'.PhangoVar::$lang['shop']['edit_cat_product'].'</a>';
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 14, 'IdProduct' => $id) ).'">'.PhangoVar::$lang['shop']['edit_image_product'].'</a>';

	/*if($arr_row_raw['extra_options']=='standard_options.php')
	{

		$arr_options[]='<a href="'. set_admin_link( PhangoVar::$lang['shop']['add__select_options_to_product'], array('op' => 5, 'IdProduct' => $id) ).'">'.PhangoVar::$lang['shop']['add__select_options_to_product'].'</a>';

	}*/
	
	//Add plugin options
	
	/*foreach($arr_plugin_product_list as $plugin)
	{
	
		//include($);
		
		load_libraries(array($plugin), PhangoVar::$base_path.'modules/shop/plugins/product/');
	
		$var_func=ucfirst($plugin).'Link';
		
		if(function_exists($var_func))
		{
			
			$arr_options[]='<a href="'.set_admin_link( $var_func($id), array('op' => 22, 'IdProduct' => $id, 'plugin' => $plugin, 'element_choice' => 'product') ).'">'.$var_func($id).'</a>';
			
		}
	
	}*/

	return $arr_options;

}

function TransportOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 9, 'IdTransport' => $id) ).'">'.PhangoVar::$lang['shop']['add__select_prices_for_transport'].'</a>';

	return $arr_options;

}

function GroupShopOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 12, 'IdGroup_shop' => $id) ).'">'.PhangoVar::$lang['shop']['add__user_to_group_shop'].'</a>';

	return $arr_options;

}

function BillOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 16, 'IdOrder_shop' => $id) ).'">'.PhangoVar::$lang['shop']['obtain_bill'].'</a>';

	return $arr_options;

}

function PluginsOptionsListModel($url_options, $model_name, $id, $arr_row)
{

	global $arr_plugin_options;

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	
	if(isset($arr_plugin_options[$arr_row['plugin']]['admin_external']))
	{
	
		//$func_admin_plugin=$arr_row['plugin'].'AdminExternal';
		
		
		
		$arr_options[]='<a href="'.set_admin_link( 'shop', array('op' => 23, 'IdProduct' => $id, 'plugin' => $arr_row['plugin'], 'element_choice' => $_GET['element_choice']) ).'">'.PhangoVar::$lang['shop']['edit_plugin_external'].'</a>';
		
		//$func_admin_plugin();
	
	}

	return $arr_options;

}

function obtain_payment_form()
{

	$arr_code=array('');
	$arr_code_check=array();

	$dir=opendir(PhangoVar::$base_path.'modules/shop/payment');

	while($file=readdir($dir))
	{
		if(!preg_match('/^\./', $file))
		{
			$arr_code[]=ucfirst(str_replace('.php', '',$file));
			$arr_code[]=$file;
			$arr_code_check[]=$file;
		}

	}

	return array($arr_code, $arr_code_check);

}

?>