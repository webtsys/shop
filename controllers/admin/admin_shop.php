<?php

function ShopAdmin()
{

	global $model, $lang, $base_url, $base_path, $language, $config_shop, $user_data, $arr_i18n, $header, $arr_block, $arr_plugin_list, $arr_plugin_product_list;

	load_lang('shop');
	load_model('shop');

	load_libraries(array('generate_admin_ng', 'forms/selectmodelformbyorder', 'forms/selectmodelform', 'forms/textareabb', 'utilities/menu_selected'));

	$header='<script language="Javascript" src="'.make_fancy_url($base_url, 'jscript', 'load_jscript', 'script', array('input_script' => 'jquery.min.js')).'"></script>';

	settype($_GET['op'], 'integer');

	$arr_link_options[1]=array('link' => make_fancy_url($base_url, 'admin', 'index', 'config_shop', array('IdModule' => $_GET['IdModule'], 'op' => 1) ), 'text' => $lang['shop']['config_shop']);
	$arr_link_options[2]=array('link' => make_fancy_url($base_url, 'admin', 'index', 'products_categories', array('IdModule' => $_GET['IdModule'], 'op' => 2) ), 'text' => $lang['shop']['products_categories']);
	$arr_link_options[4]=array('link' => make_fancy_url($base_url, 'admin', 'index', 'standard_options_for_products', array('IdModule' => $_GET['IdModule'], 'op' => 4) ), 'text' => $lang['shop']['standard_options_for_products']);
	$arr_link_options[6]=array('link' => make_fancy_url($base_url, 'admin', 'index', 'taxes', array('IdModule' => $_GET['IdModule'], 'op' => 6) ), 'text' => $lang['shop']['taxes']);
	$arr_link_options[7]=array('link' => make_fancy_url($base_url, 'admin', 'index', 'transport', array('IdModule' => $_GET['IdModule'], 'op' => 7) ), 'text' => $lang['shop']['transport']);
	$arr_link_options[10]=array('link' => make_fancy_url($base_url, 'admin', 'index', 'gateways_payment', array('IdModule' => $_GET['IdModule'], 'op' => 10) ), 'text' => $lang['shop']['gateways_payment']);
	$arr_link_options[11]=array('link' => make_fancy_url($base_url, 'admin', 'index', 'discount_groups', array('IdModule' => $_GET['IdModule'], 'op' => 11) ), 'text' => $lang['shop']['discount_groups']);
	$arr_link_options[13]=array('link' => make_fancy_url($base_url, 'admin', 'index', 'orders', array('IdModule' => $_GET['IdModule'], 'op' => 13) ), 'text' => $lang['shop']['orders']);
	$arr_link_options[15]=array('link' => make_fancy_url($base_url, 'admin', 'index', 'countries', array('IdModule' => $_GET['IdModule'], 'op' => 15) ), 'text' => $lang['shop']['countries']);

	$arr_link_options[17]=array('link' => make_fancy_url($base_url, 'admin', 'index', 'countries', array('IdModule' => $_GET['IdModule'], 'op' => 17) ), 'text' => $lang['shop']['currency']);
	
	$arr_link_options[20]=array('link' => make_fancy_url($base_url, 'admin', 'index', 'countries', array('IdModule' => $_GET['IdModule'], 'op' => 20) ), 'text' => $lang['shop']['plugins_shop']);
	
	menu_selected($_GET['op'], $arr_link_options);
	
	/*
	?>
	<ul>
		<li><a href="<?php echo make_fancy_url($base_url, 'admin', 'index', 'config_shop', array('IdModule' => $_GET['IdModule'], 'op' => 1) ); ?>"><?php echo $lang['shop']['config_shop']; ?></a></li>
		<li><a href="<?php echo make_fancy_url($base_url, 'admin', 'index', 'products_categories', array('IdModule' => $_GET['IdModule'], 'op' => 2) ); ?>"><?php echo $lang['shop']['products_categories']; ?></a></li>
		<li><a href="<?php echo make_fancy_url($base_url, 'admin', 'index', 'standard_options_for_products', array('IdModule' => $_GET['IdModule'], 'op' => 4) ); ?>"><?php echo $lang['shop']['standard_options_for_products']; ?></a></li>
		<li><a href="<?php echo make_fancy_url($base_url, 'admin', 'index', 'taxes', array('IdModule' => $_GET['IdModule'], 'op' => 6) ); ?>"><?php echo $lang['shop']['taxes']; ?></a></li>
		<li><a href="<?php echo make_fancy_url($base_url, 'admin', 'index', 'transport', array('IdModule' => $_GET['IdModule'], 'op' => 7) ); ?>"><?php echo $lang['shop']['transport']; ?></a></li>
		<li><a href="<?php echo make_fancy_url($base_url, 'admin', 'index', 'gateways_payment', array('IdModule' => $_GET['IdModule'], 'op' => 10) ); ?>"><?php echo $lang['shop']['gateways_payment']; ?></a></li>
		<li><a href="<?php echo make_fancy_url($base_url, 'admin', 'index', 'discount_groups', array('IdModule' => $_GET['IdModule'], 'op' => 11) ); ?>"><?php echo $lang['shop']['discount_groups']; ?></a></li>
		<li><a href="<?php echo make_fancy_url($base_url, 'admin', 'index', 'orders', array('IdModule' => $_GET['IdModule'], 'op' => 13) ); ?>"><?php echo $lang['shop']['orders']; ?></a></li>
		<li><a href="<?php echo make_fancy_url($base_url, 'admin', 'index', 'countries', array('IdModule' => $_GET['IdModule'], 'op' => 15) ); ?>"><?php echo $lang['shop']['countries']; ?></a></li>
	</ul>

	<?php
	*/
	//Here add modules.

	switch($_GET['op'])
	{

		case 1:
		
			//Load type_index
			
			$arr_type_index=array('new_products');
			
			if ($dh = opendir($base_path.'modules/shop/libraries/type_index/')) 
			{
				while ($file = readdir($dh))
				{
				
					if($file!='.' && $file!='..')
					{

						$file=basename($file);
						$filename=ucfirst(str_replace('.php', '', $file) );
						
						/*if(isset($lang['shop'][$file]))
						{
						
							$filename=$lang['shop'][$file];
						
						}
						else if(isset($lang['common'][$file]))
						{
						
							$filename=$lang['common'][$file];
						
						}*/
					
						$arr_type_index[]=$filename;
						$arr_type_index[]=$file;
				
					}
			
				}
			
				closedir($dh);
			}
			

			echo '<h3>'.$lang['shop']['edit_config_shop'].'</h3>';

			$model['config_shop']->create_form();

			$model['config_shop']->set_enctype_binary();

			//array(0, $lang['shop']['new_products'], 0, $lang['common']['categories'], 1, $lang['shop']['listing'], 2, $lang['shop']['bestsellers'], 3, $lang['shop']['cool'], 4)
			
			$model['config_shop']->forms['type_index']->SetParameters($arr_type_index);

			$model['config_shop']->forms['yes_taxes']->form='SelectForm';
			$model['config_shop']->forms['yes_transport']->form='SelectForm';
			$model['config_shop']->forms['type_index']->form='SelectForm';

			$model['config_shop']->forms['idtax']->form='SelectModelForm';
			
			$model['config_shop']->forms['idtax']->parameters=array('idtax', '', '', 'taxes', 'name', $where='');

			$model['config_shop']->forms['explain_discounts_page']->form='SelectModelForm';
			
			$model['config_shop']->forms['explain_discounts_page']->parameters=array('explain_discounts_page', '', '', 'pages|page', 'name', $where='order by name ASC');

			$model['config_shop']->forms['image_bill']->parameters=array('image_bill', '', '', 1, $model['config_shop']->components['image_bill']->url_path);

			$model['config_shop']->forms['title_shop']->parameters=array('title_shop', '', '', 'TextForm');
			$model['config_shop']->forms['description_shop']->parameters=array('description_shop', '', '', 'TextAreaBBForm');
			$model['config_shop']->forms['conditions']->parameters=array('conditions', '', '', 'TextAreaBBForm');

			$model['config_shop']->forms['idcurrency']->form='SelectModelForm';
			
			$model['config_shop']->forms['idcurrency']->parameters=array('idcurrency', '', '', 'currency', 'name', $where='order by name ASC');

			$model['config_shop']->func_update='Config';

			//labels

			$model['config_shop']->forms['image_bill']->label=$lang['common']['image'];
			$model['config_shop']->forms['num_news']->label=$lang['shop']['num_news'];
			$model['config_shop']->forms['yes_taxes']->label=$lang['shop']['yes_taxes'];
			$model['config_shop']->forms['idtax']->label=$lang['shop']['taxes'];
			$model['config_shop']->forms['yes_transport']->label=$lang['shop']['yes_transport'];
			$model['config_shop']->forms['type_index']->label=$lang['shop']['type_index'];
			$model['config_shop']->forms['explain_discounts_page']->label=$lang['shop']['explain_discounts_page'];
			$model['config_shop']->forms['conditions']->label=$lang['shop']['conditions'];
			$model['config_shop']->forms['ssl_url']->label=$lang['shop']['ssl_url'];
			$model['config_shop']->forms['title_shop']->label=$lang['shop']['title_shop'];
			$model['config_shop']->forms['description_shop']->label=$lang['shop']['description_shop'];	
			$model['config_shop']->forms['head_bill']->label=$lang['shop']['head_bill'];
			$model['config_shop']->forms['num_begin_bill']->label=$lang['shop']['num_begin_bill'];
			$model['config_shop']->forms['elements_num_bill']->label=$lang['shop']['elements_num_bill'];
			$model['config_shop']->forms['bill_data_shop']->label=$lang['shop']['bill_data_shop'];
			$model['config_shop']->forms['footer_bill']->label=$lang['shop']['footer_bill'];
			$model['config_shop']->forms['idcurrency']->label=$lang['shop']['currency'];
			$model['config_shop']->forms['view_only_mode']->label=$lang['shop']['view_only_mode'];

			$query=$model['config_shop']->select('limit 1', array(), 1);
			
			$result=webtsys_fetch_array($query);
			
			SetValuesForm($result, $model['config_shop']->forms, $show_error=0);

			InsertModelForm('config_shop', make_fancy_url($base_url, 'admin', 'index', 'config_shop', array('IdModule' => $_GET['IdModule'], 'op' => 1) ), make_fancy_url($base_url, 'admin', 'index', 'config_shop', array('IdModule' => $_GET['IdModule'], 'op' => 1) ), array('title_shop', 'image_bill', 'num_news', 'yes_taxes', 'idtax', 'yes_transport', 'view_only_mode', 'idcurrency', 'type_index', 'explain_discounts_page', 'ssl_url', 'description_shop', 'conditions', 'head_bill', 'num_begin_bill', 'elements_num_bill', 'bill_data_shop', 'footer_bill'));

		break;

		case 2:

			settype($_GET['subcat'], 'integer');

			$query=$model['cat_product']->select('where IdCat_product='.$_GET['subcat'], array('title', 'subcat'));

			list($title, $parent)=webtsys_fetch_row($query);

			$title=$model['cat_product']->components['title']->show_formatted($title);

			$title=' - '.$title;

			if($title==' - ')
			{

				$title='';

			}
			
			//Get view_only_mode from config_shop
			
			$query=$model['config_shop']->select('limit 1', array('view_only_mode'), 1);
			
			list($view_only_mode)=webtsys_fetch_row($query);

			echo '<h3>'.$lang['shop']['edit_categories_shop'].' '.$title.'</h3>';

			$model['cat_product']->create_form();
			
			$model['cat_product']->set_enctype_binary();

			$model['cat_product']->forms['subcat']->form='SelectModelFormByOrder';

			$model['cat_product']->forms['subcat']->parameters=array('subcat', '', '', 'cat_product', 'title', 'subcat', $where='');

			$model['cat_product']->forms['title']->label=$lang['common']['title'];
			$model['cat_product']->forms['subcat']->label=$lang['shop']['subcat'];
			$model['cat_product']->forms['description']->label=$lang['shop']['description'];
			$model['cat_product']->forms['description']->parameters=array('description', $class='', array(), $type_form='TextAreaBBForm');
			
			$model['cat_product']->forms['view_only_mode']->SetForm($view_only_mode);
			$model['cat_product']->forms['view_only_mode']->label=$lang['shop']['view_only_mode'];
			
			$model['cat_product']->forms['image_cat']->label=$lang['common']['image'];
			$model['cat_product']->forms['image_cat']->parameters=array('image_cat', '', '', 1, $model['cat_product']->components['image_cat']->url_path);

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'config_shop', array('IdModule' => $_GET['IdModule'], 'op' => 2, 'subcat' => $_GET['subcat']) );

			$arr_fields=array('title');
			$arr_fields_edit=array();

			generate_admin_model_ng('cat_product', $arr_fields, $arr_fields_edit, $url_options, $options_func='ShopOptionsListModel', $where_sql='where subcat='.$_GET['subcat'], $arr_fields_form=array(), $type_list='Basic');

			if($_GET['subcat']>0)
			{

				echo '<p><a href="'. make_fancy_url($base_url, 'admin', 'index', 'config_shop', array('IdModule' => $_GET['IdModule'], 'op' => 2, 'subcat' => $parent) ).'">'.$lang['common']['go_back'].'</a></p>';

			}

		break;

		case 3:

			settype($_GET['idcat'], 'integer');

			$query=$model['cat_product']->select('where IdCat_product='.$_GET['idcat'], array('title', 'subcat'));

			list($title, $parent)=webtsys_fetch_row($query);

			echo '<h3>'.$lang['shop']['edit_products_from_category'].': '.$model['cat_product']->components['title']->show_formatted($title).'</h3>';

			$arr_fields=array('referer', 'title', 'extra_options');
			$arr_fields_edit=array( 'IdProduct', 'referer', 'title', 'description', 'description_short', 'idcat', 'price', 'special_offer', 'stock', 'date', 'about_order', 'extra_options', 'weight', 'num_sold', 'cool' ) ;
			
			$url_options=make_fancy_url($base_url, 'admin', 'index', 'config_shop', array('IdModule' => $_GET['IdModule'], 'op' => 3, 'idcat' => $_GET['idcat']) );

			$model['product']->create_form();

			$model['product']->forms['idcat']->form='SelectModelFormByOrder';

			$model['product']->forms['idcat']->parameters=array('idcat', '', $_GET['idcat'], 'cat_product', 'title', 'subcat', $where='');

			$arr_options=array('', $lang['common']['any_option'], '');
			$arr_options_check=array();

			$dir = opendir($base_path.'modules/shop/options');

			while ($file = readdir($dir)) 
			{
				if(!preg_match('/^\./', $file))
				{

					$arr_options[]=ucfirst(str_replace('.php', '', $file));
					$arr_options[]=$file;
					$arr_options_check[]=$file;

				}
			}

			$model['product']->components['extra_options']->arr_values=&$arr_options_check;
			$model['product']->forms['extra_options']->SetParameters($arr_options);

			$model['product']->forms['description']->parameters=array('description', '', '', 'TextAreaBBForm');
			$model['product']->forms['description_short']->parameters=array('description_short', '', '', 'TextAreaBBForm');
			
			$model['product']->forms['stock']->SetForm(1);

			//Labels for forms..

			$model['product']->forms['referer']->label=$lang['shop']['referer'];
			$model['product']->forms['title']->label=$lang['common']['title'];
			$model['product']->forms['description']->label=$lang['common']['description'];
			$model['product']->forms['description_short']->label=$lang['shop']['description_short'];
			$model['product']->forms['idcat']->label=$lang['shop']['idcat'];
			$model['product']->forms['price']->label=$lang['shop']['price'];
			$model['product']->forms['special_offer']->label=$lang['shop']['special_offer'];
			$model['product']->forms['stock']->label=$lang['shop']['stock'];
			$model['product']->forms['date']->label=$lang['common']['date'];
			$model['product']->forms['about_order']->label=$lang['shop']['about_order'];
			$model['product']->forms['extra_options']->label=$lang['shop']['extra_options'];
			$model['product']->forms['weight']->label=$lang['shop']['weight'];
			$model['product']->forms['num_sold']->label=$lang['shop']['num_sold'];
			$model['product']->forms['cool']->label=$lang['shop']['cool'];

			//Set enctype for this model...

			$model['product']->set_enctype_binary();
			
			//Load plugins for show links to ProductOptionsListModel
			
			$arr_plugin_product_list=array();
			
			$query=$model['plugin_shop']->select('where element="product" order by position ASC', array('plugin'));
			
			while(list($plugin)=webtsys_fetch_row($query))
			{
			
				$arr_plugin_product_list[]=$plugin;
				
			}

			generate_admin_model_ng('product', $arr_fields, $arr_fields_edit, $url_options, $options_func='ProductOptionsListModel', $where_sql='where idcat='.$_GET['idcat'], $arr_fields_form=array(), $type_list='Basic');

			if($_GET['IdProduct']==0 || !isset($_GET['op_update']))
			{

				echo '<p><a href="'. make_fancy_url($base_url, 'admin', 'index', 'config_shop', array('IdModule' => $_GET['IdModule'], 'op' => 2, 'subcat' => $parent) ).'">'.$lang['common']['go_back'].'</a></p>';

			}

		break;

		case 4:

			echo '<h3>'.$lang['shop']['edit_options_for_product'].'</h3>';

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'config_options_shop', array('IdModule' => $_GET['IdModule'], 'op' => 4) );

			$arr_fields=array('title');
			$arr_fields_edit=array();

			$model['type_product_option']->create_form();

			$model['type_product_option']->forms['title']->label=$lang['common']['title'];
			$model['type_product_option']->forms['description']->label=$lang['common']['description'];
			$model['type_product_option']->forms['question']->label=$lang['shop']['question'];
			$model['type_product_option']->forms['options']->label=$lang['shop']['options_product'];
			$model['type_product_option']->forms['price']->label=$lang['shop']['price'];

			generate_admin_model_ng('type_product_option', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

		break;

		case 5:

			settype($_GET['IdProduct'], 'integer');

			$query=$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('title', 'idcat'));

			list($title, $idcat)=webtsys_fetch_row($query);

			echo '<h3>'.$lang['shop']['add_options_to_product'].': </h3>';

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'add_options_to_product', array('IdModule' => $_GET['IdModule'], 'op' => 5, 'IdProduct' => $_GET['IdProduct']) );

			$arr_fields=array('idtype');
			$arr_fields_edit=array('idtype', 'field_required', 'idproduct');

			$model['product_option']->create_form();

			$model['product_option']->forms['idproduct']->SetForm($_GET['IdProduct']);

			$model['product_option']->forms['idtype']->form='SelectModelForm';

			$model['product_option']->forms['idtype']->label=$lang['shop']['option_type'];
			
			$model['product_option']->forms['idtype']->parameters=array('idtype', '', '', 'type_product_option', 'title', $where='');
			
			$model['product_option']->forms['field_required']->label=$lang['shop']['option_required'];

			generate_admin_model_ng('product_option', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

			if(!isset($_GET['op_edit']))
			{

				echo '<p><a href="'. make_fancy_url($base_url, 'admin', 'index', 'edit_product', array('IdModule' => $_GET['IdModule'], 'op' => 3, 'idcat' => $idcat) ).'">'.$lang['common']['go_back'].'</a></p>';
		
			}

		break;

		case 6:

			echo '<h3>'.$lang['shop']['edit_taxes'].'</h3>';

			?>
			<p>
			<a href="<?php echo make_fancy_url($base_url, 'admin', 'index', 'zones_shop', array('IdModule' => $_GET['IdModule'], 'op' => 8, 'type' => 1) ); ?>"><?php echo $lang['shop']['zones_taxes']; ?></a>
			</p>
			<?php

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'edit_taxes', array('IdModule' => $_GET['IdModule'], 'op' => 6) );

			$arr_fields=array('name');
			$arr_fields_edit=array();

			$model['taxes']->create_form();
		
			$model['taxes']->forms['country']->form='SelectModelForm';
	
			$model['taxes']->forms['name']->label=$lang['common']['name'];
			$model['taxes']->forms['percent']->label=$lang['shop']['percent'];
			$model['taxes']->forms['country']->label=$lang['shop']['zone'];
			
			$model['taxes']->forms['country']->parameters=array('country', '', '', 'zone_shop', 'name', $where='where type=1');

			generate_admin_model_ng('taxes', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

		break;

		case 7:

			echo '<h3>'.$lang['shop']['edit_transport'].'</h3>';

			?>
			<p>
			<a href="<?php echo make_fancy_url($base_url, 'admin', 'index', 'countries_shop', array('IdModule' => $_GET['IdModule'], 'op' => 8, 'type' => 0) ); ?>"><?php echo $lang['shop']['zones_transport']; ?></a>
			</p>
			<?php

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'edit_transport', array('IdModule' => $_GET['IdModule'], 'op' =>7) );

			$arr_fields=array('name');
			$arr_fields_edit=array();
	
			$model['transport']->create_form();
		
			$model['transport']->forms['country']->form='SelectModelForm';
			
			$model['transport']->forms['country']->parameters=array('country', '', '', 'zone_shop', 'name', $where='where type=0');
	
			$model['transport']->forms['name']->label=$lang['common']['name'];
			$model['transport']->forms['country']->label=$lang['shop']['zone'];

			generate_admin_model_ng('transport', $arr_fields, $arr_fields_edit, $url_options, $options_func='TransportOptionsListModel', $where_sql='where IdTransport>0', $arr_fields_form=array(), $type_list='Basic');

		break;

		case 8:

			settype($_GET['type'], 'integer');

			$arr_type_zone[$_GET['type']]=$lang['shop']['countries_zones_transport'];
			$arr_type_zone[0]=$lang['shop']['countries_zones_transport'];
			$arr_type_zone[1]=$lang['shop']['countries_zones_taxes'];

			$sql_type_zone[$_GET['type']]='where type=0';
			$sql_type_zone[0]='where type=0';
			$sql_type_zone[1]='where type=1';

			$back_type_zone[$_GET['type']]=0;
			$back_type_zone[0]=7;
			$back_type_zone[1]=6;

			echo '<h3>'.$lang['shop']['countries_zones'].' - '.$arr_type_zone[$_GET['type']].'</h3>';

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'edit_countries', array('IdModule' => $_GET['IdModule'], 'op' => 8, 'type' => $_GET['type']) );

			$model['zone_shop']->create_form();

			$model['zone_shop']->forms['type']->SetForm($_GET['type']);

			/*foreach($arr_i18n as $lang_i18n)
			{

				$model['zone_shop']->forms['name_'.$lang_i18n]->label=$lang['common']['name'].' '.$lang_i18n;

			}*/

			$model['zone_shop']->forms['name']->label=$lang['common']['name'];

			$model['zone_shop']->forms['code']->label=$lang['shop']['country_code'];
			
			$model['zone_shop']->forms['other_countries']->label=$lang['shop']['other_countries'];

			$arr_fields=array('name');
			$arr_fields_edit=array('name', 'code');

			if($_GET['type']==0)
			{

				$arr_fields_edit[]='other_countries';

			}

			generate_admin_model_ng('zone_shop', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql=$sql_type_zone[$_GET['type']], $arr_fields_form=array(), $type_list='Basic');

			$go_back_text[0]=$lang['shop']['go_back_to_transport'];
			$go_back_text[1]=$lang['shop']['go_back_to_taxes'];

			echo '<p><a href="'. make_fancy_url($base_url, 'admin', 'index', 'edit_transport', array('IdModule' => $_GET['IdModule'], 'op' => $back_type_zone[$_GET['type']]) ).'">'.$go_back_text[$_GET['type']].'</a></p>';

		break;

		case 9:

			settype($_GET['IdTransport'], 'integer'); 

			$query=$model['transport']->select('where IdTransport='.$_GET['IdTransport'], array('name'));

			list($name_transport)=webtsys_fetch_row($query);

			echo '<h3>'.$lang['shop']['price_transport_for'].': '.$name_transport.'</h3>';

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'price_transport', array('IdModule' => $_GET['IdModule'], 'op' => 9, 'IdTransport' => $_GET['IdTransport'] ) );

			$arr_fields=array('weight');
			$arr_fields_edit=array();

			$model['price_transport']->create_form();
			
			$model['price_transport']->forms['idtransport']->SetForm($_GET['IdTransport']);

			$model['price_transport']->forms['price']->label=$lang['shop']['price'];
			$model['price_transport']->forms['weight']->label=$lang['shop']['weight'];

			generate_admin_model_ng('price_transport', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where idtransport='.$_GET['IdTransport'], $arr_fields_form=array(), $type_list='Basic');

			if(!isset($_GET['op_edit']))
			{

				echo '<p><a href="'. make_fancy_url($base_url, 'admin', 'index', 'edit_transport', array('IdModule' => $_GET['IdModule'], 'op' => 7) ).'">'.$lang['common']['go_back'].'</a></p>';

			}

		break;

		case 10:

			echo '<h3>'.$lang['shop']['gateways_payment'].'</h3>';

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'edit_payment', array('IdModule' => $_GET['IdModule'], 'op' => 10) );

			$arr_fields=array('name');
			$arr_fields_edit=array();

			/*$arr_code=array('');
			$arr_code_check=array();

			$dir=opendir($base_path.'modules/shop/payment');

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
			
			$model['payment_form']->create_form();
			//$this->arr_values=$arr_values;
			$model['payment_form']->components['code']->arr_values=&$arr_code_check;

			$model['payment_form']->forms['code']->SetParameters($arr_code);

			$model['payment_form']->forms['name']->label=$lang['common']['name'];
			$model['payment_form']->forms['code']->label=$lang['shop']['code_payment'];
			$model['payment_form']->forms['price_payment']->label=$lang['shop']['price_payment'];

			generate_admin_model_ng('payment_form', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

		break;

		case 11:

			echo '<h3>'.$lang['shop']['group_shop'].'</h3>';

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'edit_group_shop', array('IdModule' => $_GET['IdModule'], 'op' => 11) );

			$arr_fields=array('name');
			$arr_fields_edit=array();

			$model['group_shop']->create_form();

			$model['group_shop']->forms['name']->label=$lang['common']['name'];
			$model['group_shop']->forms['discount']->label=$lang['shop']['discount'];
			$model['group_shop']->forms['taxes_for_group']->label=$lang['shop']['taxes_for_group'];
			$model['group_shop']->forms['transport_for_group']->label=$lang['shop']['transport_for_group'];
			$model['group_shop']->forms['shipping_costs_for_group']->label=$lang['shop']['shipping_costs_for_group'];
		
			generate_admin_model_ng('group_shop', $arr_fields, $arr_fields_edit, $url_options, $options_func='GroupShopOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

		break;

		case 12:


			settype($_GET['IdGroup_shop'], 'integer');

			$model['group_shop_users']->create_form();

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'edit_group_shop', array('IdModule' => $_GET['IdModule'], 'op' => 12) );

			$model['group_shop_users']->forms['group_shop']->SetForm($_GET['IdGroup_shop']);
			$model['group_shop_users']->forms['iduser']->label=$lang['common']['user'];

			$model['group_shop_users']->forms['iduser']->form='SelectModelForm';
			
			$model['group_shop_users']->forms['iduser']->parameters=array('iduser', '', '', 'user', 'private_nick', $where='where iduser>0');

			generate_admin_model_ng('group_shop_users', array('iduser'), array(),  $url_options, $options_func='BasicOptionsListModel', $where_sql='');

			$url_back=make_fancy_url($base_url, 'admin', 'index', 'edit_group_shop', array('IdModule' => $_GET['IdModule'], 'op' => 11) );

			if($_GET['op_edit']==0)
			{

			?>
				<p><a href="<?php echo $url_back; ?>"><?php echo $lang['common']['go_back']; ?></a></p>
			<?php

			}

		break;

		case 13:

			//?order_field=date_order&order_desc=1&search_word=&search_field=IdOrder_shop

			if(!isset($_GET['order_field']))
			{

				$_GET['order_field']='date_order';
				$_GET['order_desc']=1;

			}

			$where_sql='';

			$arr_fields=array('name', 'last_name', 'email', 'total_price', 'make_payment', 'date_order');
			$arr_fields_edit=array('name', 'last_name', 'enterprise_name', 'email', 'nif', 'address', 'zip_code', 'city', 'region', 'country', 'phone', 'fax', 'name_transport', 'last_name_transport', 'address_transport', 'zip_code_transport', 'city_transport', 'region_transport', 'country_transport', 'phone_transport', 'date_order', 'observations', 'transport', 'name_payment', 'make_payment', 'total_price');
			$url_options=make_fancy_url($base_url, 'admin', 'index', 'edit_order', array('IdModule' => $_GET['IdModule'], 'op' => 13) );
	
			$arr_country=array('');

			$query=$model['country_shop']->select('', array('IdCountry_shop', 'name'));

			while(list($idcountry_shop, $name_country)=webtsys_fetch_row($query))
			{

				$arr_country[]=$name_country;
				$arr_country[]=$idcountry_shop;

			}

			$model['order_shop']->forms['country']->form='SelectForm';
			$model['order_shop']->forms['country']->SetParameters($arr_country);

			$model['order_shop']->forms['country_transport']->form='SelectForm';
			$model['order_shop']->forms['country_transport']->SetParameters($arr_country);

			$model['order_shop']->forms['name']->label=$lang['common']['name'];
			$model['order_shop']->forms['last_name']->label=$lang['common']['last_name'];
			$model['order_shop']->forms['email']->label=$lang['common']['email'];
			$model['order_shop']->forms['total_price']->label=$lang['shop']['total_price'];
			$model['order_shop']->forms['make_payment']->label=$lang['shop']['make_payment'];
			$model['order_shop']->forms['date_order']->label=$lang['common']['date'];

			//Zone_transport...

			$arr_transport=array('');

			$query=$model['transport']->select('', array('IdTransport', 'name'));

			while(list($idtransport, $name_transport)=webtsys_fetch_row($query))
			{

				$arr_transport[]=$name_transport;
				$arr_transport[]=$idtransport;

			}

			$model['order_shop']->forms['transport']->form='SelectForm';
			$model['order_shop']->forms['transport']->SetParameters($arr_transport);

			/*list($arr_code, $arr_code_check)=obtain_payment_form();

			$model['order_shop']->forms['payment_form']->form='SelectForm';
			$model['order_shop']->forms['payment_form']->SetParameters($arr_code);*/

			//$model['country_shop']->forms['country']->SetForm($);

			//list($where_sql, $arr_where_sql, $location, $arr_order)=SearchInField('order_shop', $arr_fields, $where_sql, $url_options);
	
			//BasicList('order_shop', $where_sql, $arr_where_sql, $location, $arr_order, $arr_fields, $cell_sizes=array(), $options_func='BasicOptionsListModel', $url_options=1);

			ListModel('order_shop', $arr_fields, $url_options, $options_func='BillOptionsListModel', $where_sql, $arr_fields_edit, 0);

		break;

		case 14:

			settype($_GET['IdProduct'], 'integer');

			$query=$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('title', 'idcat'));

			list($title, $idcat)=webtsys_fetch_row($query);
			
			$title=I18nField::show_formatted($title);

			echo '<h3>'.$lang['shop']['edit_image_product'].' - '.$title.'</h3>';
			
			$url_add_images=make_fancy_url($base_url, 'admin', 'index', 'edit_image_product',array('IdModule' => $_GET['IdModule'], 'op' => 19, 'IdProduct' => $_GET['IdProduct']) );
			
			echo '<p><a href="'.$url_add_images.'">'.$lang['shop']['add_new_images'].'</a></h3>';

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'edit_image_product', array('IdModule' => $_GET['IdModule'], 'op' => 14, 'IdProduct' => $_GET['IdProduct']) );

			$arr_fields=array('photo', 'principal');
			$arr_fields_edit=array('photo', 'idproduct', 'principal');

			$model['image_product']->create_form();

			$model['image_product']->forms['photo']->parameters=array('photo', '', '', 0, $model['image_product']->components['photo']->url_path);

			$model['image_product']->forms['idproduct']->form='HiddenForm';

			$model['image_product']->forms['idproduct']->SetForm($_GET['IdProduct']);

			$model['image_product']->forms['photo']->label=$lang['common']['image'];
			$model['image_product']->forms['principal']->label=$lang['shop']['principal_photo'];

			//order_field=photo&order_desc=1&search_word=&search_field=IdImage_product

			if(!isset($_GET['order_field']))
			{

				$_GET['order_field']='principal';
				$_GET['order_desc']=1;

			}
			$model['image_product']->set_enctype_binary();
			
			generate_admin_model_ng('image_product', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where IdProduct='.$_GET['IdProduct'], $arr_fields_form=array(), $type_list='Basic');

			if($_GET['op_action']==0 && $_GET['op_edit']==0)
			{

				echo '<p><a href="'. make_fancy_url($base_url, 'admin', 'index', 'edit_product', array('IdModule' => $_GET['IdModule'], 'op' => 3, 'idcat' => $idcat) ).'">'.$lang['common']['go_back'].'</a></p>';

			}

		break;

		case 15:

			echo '<h3>'.$lang['shop']['countries'].'</h3>';

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'edit_group_shop', array('IdModule' => $_GET['IdModule'], 'op' => 15) );

			$arr_fields=array('name');
			$arr_fields_edit=array();

			$model['country_shop']->create_form();

			$model['country_shop']->forms['idzone_transport']->form='SelectModelForm';
			
			$model['country_shop']->forms['idzone_transport']->parameters=array('idzone_transport', '', '', 'zone_shop', 'name', $where='where type=0');

			$model['country_shop']->forms['idzone_taxes']->form='SelectModelForm';
			
			$model['country_shop']->forms['idzone_taxes']->parameters=array('idzone_taxes', '', '', 'zone_shop', 'name', $where='where type=1');

			/*foreach($arr_i18n as $lang_i18n)
			{

				$model['country_shop']->forms['name_'.$lang_i18n]->label=$lang['common']['name'].' '.$lang_i18n;

			}*/

			$model['country_shop']->forms['name']->label=$lang['common']['name'];

			$model['country_shop']->forms['code']->label=$lang['shop']['code_country'];
			$model['country_shop']->forms['idzone_taxes']->label=$lang['shop']['idzone_taxes'];
			$model['country_shop']->forms['idzone_transport']->label=$lang['shop']['idzone_transport'];

			generate_admin_model_ng('country_shop', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');


		break;

		case 16:

			//Load pdf class...

			load_libraries(array('config_shop', 'fpdf/fpdf'), $base_path.'/modules/shop/libraries/');
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

						$this->Image($model['config_shop']->components['image_bill']->path.$config_shop['image_bill']);

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

					/*$text_address_enterprise[]=$lang['shop']['name_enterprise'].': ';
					$text_address_enterprise[]=$lang['common']['address'].': ';
					$text_address_enterprise[]=$lang['common']['city'].': ';
					$text_address_enterprise[]=$lang['shop']['fiscal_identity'].': ';*/

					$this->MultiCell(95,4, iconv("UTF-8", "CP1252", $config_shop['bill_data_shop'] ),0,'LR');

					//Date client...

					$name_client=iconv("UTF-8", "CP1252", $order_shop['name'].' '.$order_shop['last_name']);

					if($order_shop['enterprise_name']!='')
					{

						$name_client=iconv("UTF-8", "CP1252", $order_shop['enterprise_name']);
			
					}
				
					$text_address_client[]=iconv("UTF-8", "CP1252", $lang['shop']['client'].': '.$name_client);
					$text_address_client[]=iconv("UTF-8", "CP1252", $lang['common']['address'].': '.$order_shop['address'].' '.$order_shop['zip_code'].' ('.$order_shop['city'].')' );
					$text_address_client[]=iconv("UTF-8", "CP1252", $lang['common']['region'].': '.$order_shop['region'] );
					$text_address_client[]=iconv("UTF-8", "CP1252", $lang['common']['country'].': '.$order_shop['country'] );
					$text_address_client_other[]=iconv("UTF-8", "CP1252", $lang['shop']['fiscal_identity'].': '.$order_shop['nif'] );
					$text_address_client_other[]=iconv("UTF-8", "CP1252", $lang['common']['email'].': '.$order_shop['email'] );
					$text_address_client_other[]=iconv("UTF-8", "CP1252", $lang['common']['phone'].': '.$order_shop['phone'] );
					$text_address_client_other[]=iconv("UTF-8", "CP1252", $lang['common']['fax'].': '.$order_shop['fax'] );

					$this->SetFont('Arial','',12);

					$this->SetXY(10, 32);

					$this->MultiCell(90,6,implode("\n", $text_address_client),1,'LR');
					
					$this->SetXY(100, 32);

					$this->MultiCell(100,6,implode("\n", $text_address_client_other),1,'LR');

				}

				function DateOrder($date, $num_bill)
				{

					global $lang;

					$this->SetFont('Arial','',14);

					$this->Cell(90,9,$lang['common']['date'].': '.$date,0,0,'LR');
					$this->Cell(95,9,iconv("UTF-8", "CP1252", $lang['shop']['num_bill']).': '.$num_bill,0,0,'LR');

					$this->Ln();

				}

				function TotalPrice($order_shop, $total_price)
				{

					global $lang;

					// Elemento descuento precio

					// 30 20 20

					$this->SetXY(10,220);

					$this->Cell(40,8,'', 0);
					$this->Cell(40,8,$lang['shop']['value'],1);
					$this->Cell(40,8,$lang['shop']['discount'],1);
					$this->Cell(40,8,$lang['shop']['final_value'],1);
					$this->Ln();
					//Here total price...

					//total_price | tax | tax_percent | tax_discount_percent | price_transport | transport_discount_percent | price_payment | payment_discount_percent | discount      | discount_percent | name_payment

					$this->Cell(40, 8, $lang['shop']['total_price'], 1);
					$this->Cell(40, 8, iconv("UTF-8", "CP1252", MoneyField::currency_format($total_price)), 1);

					$discount=0;

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
						
						$add_tax=calculate_raw_taxes($order_shop['tax_percent'] , $total_price);

						$this->Cell(40,8,iconv("UTF-8", "CP1252", MoneyField::currency_format($add_tax) ),1);

						$discount_tax=obtain_discount($order_shop['tax_discount_percent'], $add_tax);

						$this->Cell(40,8, number_format($order_shop['tax_discount_percent'], 2).' %',1);
	
						$tax_final=$add_tax-$discount_tax;

						$this->Cell(40,8,iconv("UTF-8", "CP1252", MoneyField::currency_format($tax_final) ),1);
						$this->Ln();
					}
					$transport_final=0;
					if($order_shop['transport']!='')
					{

						$tax_name=iconv("UTF-8", "CP1252", $lang['shop']['transport_price']);

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

						$payment_name=iconv("UTF-8", "CP1252", $lang['shop']['shipping_costs']);

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
	
					$this->Cell(90,14, strtoupper($lang['shop']['total']).': '.$total,0);

				}
				
				function ImprovedTable($header, $data)
				{
					// Anchuras de las columnas
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

			$query=$model['order_shop']->select('where IdOrder_shop='.$_GET['IdOrder_shop'], array(), 0);

			$arr_order=webtsys_fetch_array($query);
			
			settype($arr_order['IdOrder_shop'], 'integer');

			if($arr_order['IdOrder_shop']>0)
			{

				//Num invoice...
				// Columns titles

				$query=$model['country_shop']->select('where IdCountry_shop='.$arr_order['country'], array('name'), 1);

				list($arr_order['country'])=webtsys_fetch_row($query);

				$arr_order['country']=$model['country_shop']->components['name']->show_formatted($arr_order['country']);

				$header = array(iconv("UTF-8", "CP1252",$lang['shop']['referer']), iconv("UTF-8", "CP1252",$lang['shop']['description']), iconv("UTF-8", "CP1252", $lang['shop']['units']), iconv("UTF-8", "CP1252", $lang['shop']['price']), iconv("UTF-8", "CP1252",$lang['shop']['discount']), iconv("UTF-8", "CP1252",$lang['shop']['total']) );

				//here query from cart_shop

				$cart_shop=array();

				$arr_units=array();

				$arr_product_total=array();

				$query=$model['cart_shop']->select('where token="'.$arr_order['token'].'"');

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

					$arr_product['product_title']=iconv("UTF-8", "CP1252", substr( $model['product']->components['title']->show_formatted( $arr_product['product_title'] ) , 0, 25) );
					$price_product=iconv("UTF-8", "CP1252", MoneyField::currency_format($arr_product['price_product']) );
					$price_final_product=iconv("UTF-8", "CP1252", MoneyField::currency_format($price_final) );

					$cart_shop[$arr_product['idproduct']]=array($arr_product['product_referer'], $arr_product['product_title'], $arr_units[$arr_product['idproduct']], $price_product, number_format($arr_order['discount_percent'], 2).' %', $price_final_product);

				}

				//$data=array(0 => array('España', 'Madrid', '120', '100000', '50%', '10000'), 1 => array('España', 'Madrid', '120', '100000', '50%', '10000'));

				/*settype($arr_order['IdOrder_shop'], 'string');

				$num_elements_num_bill=strlen($arr_order['IdOrder_shop']);
				$num_bill_tmp='';

				if($num_elements_num_bill<$config_shop['elements_num_bill'])
				{

					$count_elements_num_bill=$config_shop['elements_num_bill']-$num_elements_num_bill;

					for($x=0;$x<$count_elements_num_bill;$x++)
					{

						$num_bill_tmp.='0';

					}

				}

				$num_bill_tmp.=$arr_order['IdOrder_shop'];

				//echo $count_elements_num_bill;

				$num_bill=$config_shop['head_bill'].$num_bill_tmp;*/

				$num_bill=calculate_num_bill($arr_order['IdOrder_shop']);

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

			}

		break;

		case 17:

			echo '<h3>'.$lang['shop']['currency'].'</h3>';

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'edit_currencies', array('IdModule' => $_GET['IdModule'], 'op' => 17) );

			$arr_fields=array('name', 'symbol');
			$arr_fields_edit=array('name', 'symbol');

			$model['currency']->create_form();

			$model['currency']->forms['name']->label=$lang['common']['name'];
			$model['currency']->forms['symbol']->label=$lang['shop']['symbol'];
		
			generate_admin_model_ng('currency', $arr_fields, $arr_fields_edit, $url_options, $options_func='CurrencyOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

		break;
	
		case 18:

			settype($_GET['IdCurrency'], 'integer');

			$query=$model['currency']->select('where IdCurrency='.$_GET['IdCurrency'], array('name'));

			list($name_currency)=webtsys_fetch_row($query);

			$name_currency=I18nField::show_formatted($name_currency);

			echo '<h3>'.$lang['shop']['modify_change_currencies'].': '.$name_currency.'</h3>';

			$url_options=make_fancy_url($base_url, 'admin', 'index', 'modify_change_currencies', array('IdModule' => $_GET['IdModule'], 'op' => 18, 'IdCurrency' => $_GET['IdCurrency']) );

			$arr_fields=array('idcurrency_related');
			$arr_fields_edit=array();

			$model['currency_change']->create_form();

			$model['currency_change']->forms['idcurrency']->form='HiddenForm';
			$model['currency_change']->forms['idcurrency']->SetForm($_GET['IdCurrency']);

			$model['currency_change']->forms['idcurrency_related']->label=$lang['shop']['currency'];

			$model['currency_change']->forms['idcurrency_related']->form='SelectModelForm';
			
			$model['currency_change']->forms['idcurrency_related']->parameters=array('idcurrency_related', '', '', 'currency', 'name', $where='where IdCurrency!='.$_GET['IdCurrency']);

			$model['currency_change']->forms['change_value']->label=$lang['shop']['explain_change_value'].' '.$name_currency;
		
			generate_admin_model_ng('currency_change', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where currency_change.idcurrency='.$_GET['IdCurrency'], $arr_fields_form=array(), $type_list='Basic');

			if($_GET['op_action']==0 && $_GET['op_edit']==0)
			{

				echo '<p><a href="'. make_fancy_url($base_url, 'admin', 'index', 'edit_currency', array('IdModule' => $_GET['IdModule'], 'op' => 17) ).'">'.$lang['common']['go_back'].'</a></p>';

			}

		break;
		
		case 19:
		
			settype($_GET['op_image'], 'integer');
			settype($_GET['IdProduct'], 'integer');
			
			//ew ImageField('photo', $base_path.'application/media/shop/images/products/', $base_url.'/media/shop/images/products', 'image', 1, array('small' => 45, 'mini' => 150, 'medium' => 300, 'preview' => 600));
			
			$arr_field['image_form1']=clone $model['image_product']->components['photo'];
			$arr_field['image_form1']->name_file='image_form1';
		
			$arr_form['image_form1']=new ModelForm('create_image', 'image_form1', 'ImageForm', $lang['common']['image'].' 1', $arr_field['image_form1'], $required=1, $parameters='');
			
			/*$bool[1]=new BooleanField();
			
			$arr_form['principal1']=new ModelForm('create_image', 'principal1', 'SelectForm', $lang['shop']['principal_photo'].' 1', $bool[1], $required=0, $parameters=$bool[1]->get_parameters_default());*/
			
			for($x=2;$x<11;$x++)
			{
			
				$arr_field['image_form'.$x]=clone $model['image_product']->components['photo'];
				$arr_field['image_form'.$x]->name_file='image_form'.$x;
			
				$arr_form['image_form'.$x]=new ModelForm('create_image', 'image_form'.$x.'', 'ImageForm', $lang['common']['image'].' '.$x, $arr_field['image_form'.$x], $required=0, $parameters='');
				
				/*$bool[$x]=new BooleanField();
				
				$arr_form['principal'.$x]=new ModelForm('create_image', 'principal'.$x, 'SelectForm', $lang['shop']['principal_photo'].' 1', $bool[$x], $required=0, $parameters=$bool[$x]->get_parameters_default());*/
			
			}
			
			switch($_GET['op_image'])
			{
			
				default:
				
					ob_start();
					
					$query=$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('title'));
					
					list($title)=webtsys_fetch_row($query);
					
					$title=I18nField::show_formatted($title);
				
					$url_post=make_fancy_url($base_url, 'admin', 'index', 'edit_image_product',array('IdModule' => $_GET['IdModule'], 'op' => 19, 'IdProduct' => $_GET['IdProduct'], 'op_image' => 1) );
			
					echo load_view(array($arr_form, array(), $url_post, 'enctype="multipart/form-data"'), 'common/forms/updatemodelform');
					
					$cont_add=ob_get_contents();
					
					ob_end_clean();
					
					echo load_view(array($lang['shop']['add_new_images'].' - '.$title, $cont_add), 'content');
					
					echo '<p><a href="'.$url_post=make_fancy_url($base_url, 'admin', 'index', 'edit_image_product',array('IdModule' => $_GET['IdModule'], 'op' => 14, 'IdProduct' => $_GET['IdProduct']) ).'">'.$lang['common']['go_back'].'</a></p>';
				
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
							
								$model['image_product']->insert(array('photo' => $arr_post[$file_name], 'principal' => 0, 'idproduct' => $_GET['IdProduct']));
							
							}
						
						}
					
					
					
						ob_end_clean();
						load_libraries(array('redirect'));
						die( redirect_webtsys( $url_post=make_fancy_url($base_url, 'admin', 'index', 'edit_image_product',array('IdModule' => $_GET['IdModule'], 'op' => 14, 'IdProduct' => $_GET['IdProduct']) ), $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );
						
					}
					else
					{
						
						$url_go_back=make_fancy_url($base_url, 'admin', 'index', 'edit_image_product',array('IdModule' => $_GET['IdModule'], 'op' => 19, 'IdProduct' => $_GET['IdProduct']) );
						
						echo '<p>'.$lang['common']['error_cannot_upload_this_image_to_the_server'].'</p>';
					
						echo '<p><a href="'.$url_go_back.'">'.$lang['common']['go_back'].'</a></p>';
					
					}
					
				
				break;
			
			}
		
		break;
		
		case 20:
		
			//First, select form for choose an module, for now, products.
			
			echo '<h3>'.$lang['shop']['plugin_admin'].'</h3>';
			
			settype($_GET['element_choice'], 'string');
			
			$arr_elements_plugin=array($_GET['element_choice'], '', '', 'products', 'product', 'cart', 'cart');
			
			echo '<form method="get" action="'.make_fancy_url($base_url, 'admin', 'index', 'element_choice', array('IdModule' => $_GET['IdModule'], 'op' => 20)).'">';
			
			echo '<p>'.$lang['shop']['element_choice'].': '.SelectForm('element_choice', '', $arr_elements_plugin).' <input type="submit" value="'.$lang['common']['send'].'" /></p>';
			
			echo '</form>';
			
			//Now the form...
			
			$element_choice=$model['plugin_shop']->components['element']->check($_GET['element_choice']);
			
			if($element_choice!='')
			{
			
				$model['plugin_shop']->create_form();
				
				$model['plugin_shop']->forms['element']->form='HiddenForm';
				$model['plugin_shop']->forms['element']->SetForm($element_choice);
				
				$arr_plugins=array('', '', '');
				
				foreach($arr_plugin_list[$element_choice] as $plugin)
				{
				
					$arr_plugins[]=$plugin;
					$arr_plugins[]=$plugin;
					
				
				}
				
				$model['plugin_shop']->components['plugin']->arr_values=&$arr_plugin_list[$element_choice];
				
				$model['plugin_shop']->forms['plugin']->SetParameters($arr_plugins);
			
				$arr_fields=array('name', 'plugin');
				$arr_fields_edit=array('name', 'element', 'plugin');
				$url_options=make_fancy_url($base_url, 'admin', 'index', 'plugin_admin', array('op' => 20, 'IdModule' => $_GET['IdModule'], 'element_choice' => $element_choice));
			
				generate_admin_model_ng('plugin_shop', $arr_fields, $arr_fields_edit, $url_options, $options_func='PluginsOptionsListModel', 
				$where_sql='where element="'.$element_choice.'"', $arr_fields_form=array(), $type_list='Basic');
				
				//Now the order...
				
				echo '<p><a href="'.make_fancy_url($base_url, 'admin', 'index', 'plugin_admin', array('op' => 21, 'IdModule' => $_GET['IdModule'], 'element_choice' => $element_choice)).'">'.$lang['shop']['order_plugins'].'</a></p>';
			
			}
			
			
		
		break;
		
		case 21:
		
			$element_choice=$model['plugin_shop']->components['element']->check($_GET['element_choice']);
			
			if($element_choice!='')
			{
			
				echo '<h3>'.$lang['shop']['order_plugins'].'</h3>';
				
				GeneratePositionModel('plugin_shop', 'name', 'position', make_fancy_url($base_url, 'admin', 'index', 'plugin_admin', array('op' => 21, 'IdModule' => $_GET['IdModule'], 'element_choice' => $element_choice)), $where='where element="'.$element_choice.'"');
				
				echo '<p><a href="'.make_fancy_url($base_url, 'admin', 'index', 'plugin_admin', array('op' => 20, 'IdModule' => $_GET['IdModule'], 'element_choice' => $element_choice)).'">'.$lang['common']['go_back'].'</a></p>';
			
			}
		
		break;
		
		case 22:
		
			settype($_GET['IdProduct'], 'integer');
		
			echo '<h3>'.$lang['shop']['edit_plugin'].'</h3>';
			
			$element_choice=$model['plugin_shop']->components['element']->check($_GET['element_choice']);
			
			if($element_choice!='')
			{
			
				$plugin=@form_text($_GET['plugin']);
			
				if( in_array($plugin, $arr_plugin_list[$element_choice]) )
				{
				
					load_libraries(array($plugin), $base_path.'modules/shop/plugins/product/');
	
					$var_func=ucfirst($plugin).'Admin';
					
					if(function_exists($var_func))
					{
					
						$var_func($_GET['IdProduct']);
						
					}
				
				}
				
			
			}
			
		
		break;
		
		case 23:
		
			$element_choice=$model['plugin_shop']->components['element']->check($_GET['element_choice']);
			
			if($element_choice!='')
			{
			
				$plugin=@form_text($_GET['plugin']);
			
				if( in_array($plugin, $arr_plugin_list[$element_choice]) )
				{
				
					load_libraries(array($plugin), $base_path.'modules/shop/plugins/product/');
	
					$var_func=ucfirst($plugin).'AdminExternal';
					
					if(function_exists($var_func))
					{
					
						$var_func();
						
					}
				
				}
				
			
			}
		
		break;

	}

}


function CurrencyOptionsListModel($url_options, $model_name, $id)
{

	global $lang, $base_url;

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. make_fancy_url($base_url, 'admin', 'index', 'edit_currencies_change', array('IdModule' => $_GET['IdModule'], 'op' => 18, 'IdCurrency' => $id) ).'">'.$lang['shop']['modify_change_currencies'].'</a>';

	return $arr_options;

}

function ShopOptionsListModel($url_options, $model_name, $id)
{

	global $lang, $base_url;

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. make_fancy_url($base_url, 'admin', 'index', 'edit_cat_shop', array('IdModule' => $_GET['IdModule'], 'op' => 3, 'idcat' => $id) ).'">'.$lang['shop']['modify_products'].'</a>';

	$arr_options[]='<a href="'. make_fancy_url($base_url, 'admin', 'index', 'edit_cat_shop', array('IdModule' => $_GET['IdModule'], 'op' => 2, 'subcat' => $id) ).'">'.$lang['shop']['subcat_products'].'</a>';

	return $arr_options;

}

function ProductOptionsListModel($url_options, $model_name, $id, $arr_row_raw)
{

	global $lang, $base_url, $base_path, $arr_plugin_product_list;
	
	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. make_fancy_url($base_url, 'admin', 'index', 'edit_image_product', array('IdModule' => $_GET['IdModule'], 'op' => 14, 'IdProduct' => $id) ).'">'.$lang['shop']['edit_image_product'].'</a>';

	if($arr_row_raw['extra_options']=='standard_options.php')
	{

		$arr_options[]='<a href="'. make_fancy_url($base_url, 'admin', 'index', $lang['shop']['add__select_options_to_product'], array('IdModule' => $_GET['IdModule'], 'op' => 5, 'IdProduct' => $id) ).'">'.$lang['shop']['add__select_options_to_product'].'</a>';

	}
	
	//Add plugin options
	
	foreach($arr_plugin_product_list as $plugin)
	{
	
		//include($);
		
		load_libraries(array($plugin), $base_path.'modules/shop/plugins/product/');
	
		$var_func=ucfirst($plugin).'Link';
		
		if(function_exists($var_func))
		{
			
			$arr_options[]='<a href="'.make_fancy_url($base_url, 'admin', 'index', $var_func($id), array('IdModule' => $_GET['IdModule'], 'op' => 22, 'IdProduct' => $id, 'plugin' => $plugin, 'element_choice' => 'product') ).'">'.$var_func($id).'</a>';
			
		}
	
	}

	return $arr_options;

}

function TransportOptionsListModel($url_options, $model_name, $id)
{

	global $lang, $base_url;

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. make_fancy_url($base_url, 'admin', 'index', 'edit_price_transport', array('IdModule' => $_GET['IdModule'], 'op' => 9, 'IdTransport' => $id) ).'">'.$lang['shop']['add__select_prices_for_transport'].'</a>';

	return $arr_options;

}

function GroupShopOptionsListModel($url_options, $model_name, $id)
{

	global $lang, $base_url;

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. make_fancy_url($base_url, 'admin', 'index', 'edit_group_shop', array('IdModule' => $_GET['IdModule'], 'op' => 12, 'IdGroup_shop' => $id) ).'">'.$lang['shop']['add__user_to_group_shop'].'</a>';

	return $arr_options;

}

function BillOptionsListModel($url_options, $model_name, $id)
{

	global $lang, $base_url;

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. make_fancy_url($base_url, 'admin', 'index', 'obtain_bill', array('IdModule' => $_GET['IdModule'], 'op' => 16, 'IdOrder_shop' => $id) ).'">'.$lang['shop']['obtain_bill'].'</a>';

	return $arr_options;

}

function PluginsOptionsListModel($url_options, $model_name, $id, $arr_row)
{

	global $lang, $base_url, $arr_plugin_options;

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	
	if(isset($arr_plugin_options[$arr_row['plugin']]['admin_external']))
	{
	
		//$func_admin_plugin=$arr_row['plugin'].'AdminExternal';
		
		
		
		$arr_options[]='<a href="'.make_fancy_url($base_url, 'admin', 'index', $lang['shop']['admin_external_plugin'], array('IdModule' => $_GET['IdModule'], 'op' => 23, 'IdProduct' => $id, 'plugin' => $arr_row['plugin'], 'element_choice' => 'product') ).'">'.$lang['shop']['edit_plugin_external'].'</a>';
		
		//$func_admin_plugin();
	
	}

	return $arr_options;

}

function obtain_payment_form()
{

	global $base_path;

	$arr_code=array('');
	$arr_code_check=array();

	$dir=opendir($base_path.'modules/shop/payment');

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