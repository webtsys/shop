<?php

function ShopAdmin()
{

	//global $config_shop, $user_data, $arr_plugin_list, $arr_plugin_product_list;

	I18n::load_lang('shop');
	Webmodel::load_model('shop');
	Utils::load_config('shop');
	
	Utils::load_libraries(array('generate_admin_ng', 'admin/generate_admin_class', 'forms/selectmodelformbyorder', 'forms/selectmodelform', 'forms/textareabb', 'utilities/menu_selected', 'utilities/menu_barr_hierarchy', 'utilities/hierarchy_links'));

	settype($_GET['op'], 'string');

	$arr_link_options[1]=array('link' => set_admin_link( 'shop', array('op' => 1) ), 'text' => I18n::lang('shop', 'config_shop', 'Configuración de su tienda'));
	$arr_link_options[2]=array('link' => set_admin_link( 'shop', array('op' => 2) ), 'text' => I18n::lang('shop', 'products_categories', 'Categorías de productos'));
	$arr_link_options[3]=array('link' => set_admin_link( 'shop', array('op' => 3) ), 'text' => I18n::lang('shop', 'products', 'Productos'));
	//$arr_link_options[4]=array('link' => set_admin_link( 'standard_options_for_products', array('op' => 4) ), 'text' => I18n::lang('shop', 'standard_options_for_products', 'Opciones estandard para productos'));
	//$arr_link_options[6]=array('link' => set_admin_link( 'taxes', array('op' => 6) ), 'text' => I18n::lang('shop', 'taxes', 'Impuestos'));
	$arr_link_options[7]=array('link' => set_admin_link( 'shop', array('op' => 7) ), 'text' => I18n::lang('shop', 'transport', 'Transporte'));
	$arr_link_options[10]=array('link' => set_admin_link( 'shop', array('op' => 10) ), 'text' => I18n::lang('shop', 'gateways_payment', 'Pasarelas de pago'));
	//$arr_link_options[11]=array('link' => set_admin_link( 'discount_groups', array('op' => 11) ), 'text' => I18n::lang('shop', 'discount_groups', 'Grupos de descuento'));
	$arr_link_options[13]=array('link' => set_admin_link( 'shop', array('op' => 13) ), 'text' => I18n::lang('shop', 'orders', 'Pedidos'));
	$arr_link_options[15]=array('link' => set_admin_link( 'shop', array('op' => 15) ), 'text' => I18n::lang('shop', 'countries', 'Paises'));

	$arr_link_options[17]=array('link' => set_admin_link( 'shop', array('op' => 17) ), 'text' => I18n::lang('shop', 'currency', 'Moneda'));
	
	$arr_link_options[20]=array('link' => set_admin_link( 'shop', array('op' => 20) ), 'text' => I18n::lang('shop', 'plugins_shop', 'Plugins de tienda'));
	
	$arr_link_options[25]=array('link' => set_admin_link( 'shop', array('op' => 25) ), 'text' => I18n::lang('shop', 'admin_users', 'admin_users'));
	
	menu_selected($_GET['op'], $arr_link_options);
	
	/*
	?>
	<ul>
		<li><a href="<?php echo set_admin_link( 'config_shop', array('op' => 1) ); ?>"><?php echo I18n::lang('shop', 'config_shop', 'Configuración de su tienda'); ?></a></li>
		<li><a href="<?php echo set_admin_link( 'products_categories', array('op' => 2) ); ?>"><?php echo I18n::lang('shop', 'products_categories', 'Categorías de productos'); ?></a></li>
		<li><a href="<?php echo set_admin_link( 'standard_options_for_products', array('op' => 4) ); ?>"><?php echo I18n::lang('shop', 'standard_options_for_products', 'Opciones estandard para productos'); ?></a></li>
		<li><a href="<?php echo set_admin_link( 'taxes', array('op' => 6) ); ?>"><?php echo I18n::lang('shop', 'taxes', 'Impuestos'); ?></a></li>
		<li><a href="<?php echo set_admin_link( 'transport', array('op' => 7) ); ?>"><?php echo I18n::lang('shop', 'transport', 'Transporte'); ?></a></li>
		<li><a href="<?php echo set_admin_link( 'gateways_payment', array('op' => 10) ); ?>"><?php echo I18n::lang('shop', 'gateways_payment', 'Pasarelas de pago'); ?></a></li>
		<li><a href="<?php echo set_admin_link( 'discount_groups', array('op' => 11) ); ?>"><?php echo I18n::lang('shop', 'discount_groups', 'Grupos de descuento'); ?></a></li>
		<li><a href="<?php echo set_admin_link( 'orders', array('op' => 13) ); ?>"><?php echo I18n::lang('shop', 'orders', 'Pedidos'); ?></a></li>
		<li><a href="<?php echo set_admin_link( 'countries', array('op' => 15) ); ?>"><?php echo I18n::lang('shop', 'countries', 'Paises'); ?></a></li>
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

			echo '<h3>'.I18n::lang('shop', 'edit_config_shop', 'Editar configuración de tienda').'</h3>';

			Webmodel::$model['config_shop']->create_form();

			Webmodel::$model['config_shop']->set_enctype_binary();

			Webmodel::$model['config_shop']->forms['image_bill']->parameters=array('image_bill', '', '', 1, Webmodel::$model['config_shop']->components['image_bill']->url_path);

			Webmodel::$model['config_shop']->forms['title_shop']->parameters=array('title_shop', '', '', 'TextForm');
			Webmodel::$model['config_shop']->forms['description_shop']->parameters=array('description_shop', '', '', 'TextAreaBBForm');
			Webmodel::$model['config_shop']->forms['conditions']->parameters=array('conditions', '', '', 'TextAreaBBForm');

			Webmodel::$model['config_shop']->forms['idcurrency']->form='SelectModelForm';
			
			Webmodel::$model['config_shop']->forms['idcurrency']->parameters=array('idcurrency', '', '', 'currency', 'name', $where='order by name ASC');

			Webmodel::$model['config_shop']->func_update='Config';

			//labels

			Webmodel::$model['config_shop']->forms['image_bill']->label=I18n::lang('common', 'image', 'image');
			Webmodel::$model['config_shop']->forms['num_news']->label=I18n::lang('shop', 'num_news', 'Novedades');
			//Webmodel::$model['config_shop']->forms['yes_taxes']->label=I18n::lang('shop', 'yes_taxes', '¿Con impuestos incluidos?');
			//Webmodel::$model['config_shop']->forms['idtax']->label=I18n::lang('shop', 'taxes', 'Impuestos');
			//Webmodel::$model['config_shop']->forms['yes_transport']->label=I18n::lang('shop', 'yes_transport', '¿Necesitan transporte los productos?');
			//Webmodel::$model['config_shop']->forms['type_index']->label=I18n::lang('shop', 'type_index', 'Tipo de página principal');
			//Webmodel::$model['config_shop']->forms['explain_discounts_page']->label=I18n::lang('shop', 'explain_discounts_page', 'Página donde se definen los descuentos');
			Webmodel::$model['config_shop']->forms['conditions']->label=I18n::lang('shop', 'conditions', 'Terminos de venta');
			//Webmodel::$model['config_shop']->forms['ssl_url']->label=I18n::lang('shop', 'ssl_url', '¿Este dominio tiene SSL?. Elija sí, si su url base por defecto no tiene SSL');
			Webmodel::$model['config_shop']->forms['title_shop']->label=I18n::lang('shop', 'title_shop', 'Titulo de la tienda');
			Webmodel::$model['config_shop']->forms['description_shop']->label=I18n::lang('shop', 'description_shop', 'Descripción de la tienda');	
			Webmodel::$model['config_shop']->forms['head_bill']->label=I18n::lang('shop', 'head_bill', 'Cabecera de las facturas');
			Webmodel::$model['config_shop']->forms['num_begin_bill']->label=I18n::lang('shop', 'num_begin_bill', 'Número de comienzo de factura');
			Webmodel::$model['config_shop']->forms['elements_num_bill']->label=I18n::lang('shop', 'elements_num_bill', 'Número de elementos de la fáctura (si el número de factura sobrepasa estos elementos, tendrá un número de elementos de acuerdo a la cifra)');
			Webmodel::$model['config_shop']->forms['bill_data_shop']->label=I18n::lang('shop', 'bill_data_shop', 'Datos de su empresa que aparecerá en la fáctura');
			Webmodel::$model['config_shop']->forms['footer_bill']->label=I18n::lang('shop', 'footer_bill', 'Pie de la factura');
			Webmodel::$model['config_shop']->forms['idcurrency']->label=I18n::lang('shop', 'currency', 'Moneda');
			Webmodel::$model['config_shop']->forms['view_only_mode']->label=I18n::lang('shop', 'view_only_mode', 'Modo mostrador');

			$query=Webmodel::$model['config_shop']->select('limit 1', array(), 1);
			
			$result=Webmodel::$model['config_shop']->fetch_array($query);
			
			ModelForm::set_values_form($result, Webmodel::$model['config_shop']->forms, $show_error=0);

			//InsertModelForm('config_shop', set_admin_link( 'config_shop', array('op' => 1) ), set_admin_link( 'config_shop', array('op' => 1) ), array('title_shop', 'image_bill', 'view_only_mode', 'idcurrency', 'num_news', 'type_index', 'description_shop', 'conditions', 'head_bill', 'num_begin_bill', 'elements_num_bill', 'bill_data_shop', 'footer_bill'));
			
			$admin=new GenerateAdminClass('config_shop');
			
			$admin->set_url_post(set_admin_link('shop', array('op' => 1)));
			
			$admin->show_config_mode();

		break;

		case 2:

			settype($_GET['subcat'], 'integer');

			$query=Webmodel::$model['cat_product']->select('where IdCat_product='.$_GET['subcat'], array('title', 'subcat'));

			list($title, $parent)=Webmodel::$model['cat_product']->fetch_row($query);

			$title=Webmodel::$model['cat_product']->components['title']->show_formatted($title);

			$title=' - '.$title;

			if($title==' - ')
			{

				$title='';

			}
			
			$arr_hierarchy_links=hierarchy_links('cat_product', 'subcat', 'title', $_GET['subcat'], 0);
			
			//http://localhost/phango2/index.php/admin/shop/get/op/2/subcat/2
			
			$url_fancy=set_admin_link('shop', array('op' => 2));
			
			echo View::load_view(array($arr_hierarchy_links, $url_fancy, 'subcat', $arr_parameters=array(), $last_link=0), 'common/utilities/hierarchy_links_standard');
			
			//.' &gt; '.I18nField::show_formatted($arr_product['title'])
			//echo View::load_view(array($arr_hierarchy_links, 'admin', 'index', 'subcat', array(), 1), 'common/utilities/hierarchy_links');
			
			//Get view_only_mode from config_shop
			
			$query=Webmodel::$model['config_shop']->select('limit 1', array('view_only_mode'), 1);
			
			list($view_only_mode)=Webmodel::$model['config_shop']->fetch_row($query);

			echo '<h3>'.I18n::lang('shop', 'edit_categories_shop', 'Editar categorias de tienda').' '.$title.'</h3>';

			Webmodel::$model['cat_product']->create_form();
			
			Webmodel::$model['cat_product']->set_enctype_binary();

			Webmodel::$model['cat_product']->forms['subcat']->form='SelectModelFormByOrder';

			Webmodel::$model['cat_product']->forms['subcat']->parameters=array('subcat', '', $_GET['subcat'], 'cat_product', 'title', 'subcat', $where='');

			Webmodel::$model['cat_product']->forms['title']->label=I18n::lang('common', 'title', 'Title');
			Webmodel::$model['cat_product']->forms['subcat']->label=I18n::lang('shop', 'subcat', 'Elija categoría padre');
			Webmodel::$model['cat_product']->forms['description']->label=I18n::lang('shop', 'description', 'Descripción');
			Webmodel::$model['cat_product']->forms['description']->parameters=array('description', $class='', array(), $type_form='TextAreaBBForm');
			
			Webmodel::$model['cat_product']->forms['view_only_mode']->set_param_value_form($view_only_mode);
			Webmodel::$model['cat_product']->forms['view_only_mode']->label=I18n::lang('shop', 'view_only_mode', 'Modo mostrador');
			
			Webmodel::$model['cat_product']->forms['image_cat']->label=I18n::lang('common', 'image', 'image');
			Webmodel::$model['cat_product']->forms['image_cat']->parameters=array('image_cat', '', '', 1, Webmodel::$model['cat_product']->components['image_cat']->url_path);

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

				echo '<p><a href="'. set_admin_link( 'shop', array('op' => 2, 'subcat' => $parent) ).'">'.I18n::lang('common', 'go_back', 'Go back').'</a></p>';

			}
			else*/
			

		//break;
		
		case '2_5':
		
		if($_GET['op_edit']==0 && $_GET['op_action']==0)
		{
		
			//Order principal categories, util for various things.
			
			echo '<h3>'.I18n::lang('shop', 'order_cats', 'Ordenar categorías').'</h3>';
		
			//GeneratePositionModel('cat_product', 'title', 'position', set_admin_link( 'shop', array('op' => 2)), $where='');
			
			$admin->generate_position_model('title', 'position', set_admin_link( 'shop', array('op' => '2_5')), $admin->where_sql);
		
		}
		
		break;

		case 3:

			settype($_GET['idcat'], 'integer');
			settype($_GET['IdProduct'], 'integer');

			if($_GET['idcat']>0)
			{
			
				$arr_hierarchy_links=hierarchy_links('cat_product', 'subcat', 'title', $_GET['idcat'], 0);
			
				$url_fancy=set_admin_link('shop', array('op' => 3));
				
				echo View::load_view(array($arr_hierarchy_links, $url_fancy, 'idcat', $arr_parameters=array(), $last_link=0, 'Todos los productos'), 'common/utilities/hierarchy_links_standard');
			
			}
			
			$query=Webmodel::$model['cat_product']->select('where IdCat_product='.$_GET['idcat'], array('IdCat_product', 'title', 'subcat'));

			list($idcat, $title, $parent)=Webmodel::$model['cat_product']->fetch_row($query);
			
			settype($idcat, 'integer');
			
			if($idcat>0)
			{
			
				$title=Webmodel::$model['cat_product']->components['title']->show_formatted($title);
				
				//Obtain id's from product_relantionship
				
				$arr_id=Webmodel::$model['product_relationship']->select_a_field('where idcat_product='.$idcat, 'idproduct');
				
				$arr_id[]=0;
				
				$where_sql='where IdProduct IN ('.implode(',', $arr_id).')';
				
			
			}
			else
			{
			
				$title=I18n::lang('shop', 'no_category_defined', 'Categoría sin definir');
				$where_sql='';
				
				$cont_edit_product='';
			
			}

			echo '<h3>'.I18n::lang('shop', 'edit_products_from_category', 'Editar productos de la categoría').': '.$title.'</h3>';
			
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
			
			View::$header[]=ob_get_contents();
			
			ob_end_clean();
			
			echo '<p><strong>'.I18n::lang('shop', 'choose_category', 'Elegir categoría').'</strong>: '.SelectModelFormByOrder('idcat', '', $idcat, 'cat_product', 'title', 'subcat', $where='').'</p>';
			
			$arr_fields=array('referer', 'title');
			$arr_fields_edit=array( 'IdProduct', 'referer', 'title', 'description', 'description_short', 'price', 'special_offer', 'stock', 'date', 'about_order', 'weight', 'num_sold', 'cool' );
			
			$url_options=set_admin_link( 'shop', array('op' => 3, 'idcat' => $_GET['idcat']) );

			Webmodel::$model['product']->create_form();

			/*Webmodel::$model['product']->forms['idcat']->form='SelectModelFormByOrder';

			Webmodel::$model['product']->forms['idcat']->parameters=array('idcat', '', $_GET['idcat'], 'cat_product', 'title', 'subcat', $where='');*/

			$arr_options=array('', I18n::lang('common', 'any_option', 'any option'), '');
			$arr_options_check=array();

			$dir = opendir(PhangoVar::$base_path.'/modules/shop/options');

			while ($file = readdir($dir)) 
			{
				if(!preg_match('/^\./', $file))
				{

					$arr_options[]=ucfirst(str_replace('.php', '', $file));
					$arr_options[]=$file;
					$arr_options_check[]=$file;

				}
			}

			/*Webmodel::$model['product']->components['extra_options']->arr_values=&$arr_options_check;
			Webmodel::$model['product']->forms['extra_options']->SetParameters($arr_options);*/

			Webmodel::$model['product']->forms['description']->parameters=array('description', '', '', 'TextAreaBBForm');
			Webmodel::$model['product']->forms['description_short']->parameters=array('description_short', '', '', 'TextAreaBBForm');
			
			Webmodel::$model['product']->forms['stock']->set_param_value_form(1);

			//Labels for forms..

			Webmodel::$model['product']->forms['referer']->label=I18n::lang('shop', 'referer', 'Referencia');
			Webmodel::$model['product']->forms['title']->label=I18n::lang('common', 'title', 'Title');
			Webmodel::$model['product']->forms['description']->label=I18n::lang('common', 'description', 'description');
			Webmodel::$model['product']->forms['description_short']->label=I18n::lang('shop', 'description_short', 'Descripción breve del producto, útil para listados');
			//Webmodel::$model['product']->forms['idcat']->label=I18n::lang('shop', 'idcat', 'Categoría de tienda');
			Webmodel::$model['product']->forms['price']->label=I18n::lang('shop', 'price', 'Precio');
			Webmodel::$model['product']->forms['special_offer']->label=I18n::lang('shop', 'special_offer', 'Oferta especial');
			Webmodel::$model['product']->forms['stock']->label=I18n::lang('shop', 'stock', 'Stock');
			Webmodel::$model['product']->forms['date']->label=I18n::lang('common', 'date', 'date');
			Webmodel::$model['product']->forms['about_order']->label=I18n::lang('shop', 'about_order', 'Bajo pedido');
			//Webmodel::$model['product']->forms['extra_options']->label=I18n::lang('shop', 'extra_options', 'Opciones extra');
			Webmodel::$model['product']->forms['weight']->label=I18n::lang('shop', 'weight', 'Peso');
			Webmodel::$model['product']->forms['num_sold']->label=I18n::lang('shop', 'num_sold', 'Número de veces vendido');
			Webmodel::$model['product']->forms['cool']->label=I18n::lang('shop', 'cool', 'Recomendado');

			//Set enctype for this model...

			Webmodel::$model['product']->set_enctype_binary();
			
			//Load plugins for show links to ProductOptionsListModel
			
			/*$arr_plugin_product_list=array();
			
			$query=Webmodel::$model['plugin_shop']->select('where element="product" order by position ASC', array('plugin'));
			
			while(list($plugin)=webtsys_fetch_row($query))
			{
			
				$arr_plugin_product_list[]=$plugin;
				
			}*/
			
			$plugins=new PreparePluginClass('product');
			
			$plugins->obtain_list_plugins();
			
			$plugins->load_all_plugins();
			
			$admin=new GenerateAdminClass('product');
			
			$admin->arr_fields=&$arr_fields;
			$admin->arr_fields_edit=&$arr_fields_edit;
			$admin->set_url_post($url_options);
			$admin->options_func='ProductOptionsListModel';
			$admin->options_func_extra_args=array('plugins' => $plugins);
			$admin->where_sql=&$where_sql;
			
			$admin->show();
			

			//generate_admin_model_ng('product', $arr_fields, $arr_fields_edit, $url_options, $options_func='ProductOptionsListModel', $where_sql, $arr_fields_form=array(), $type_list='Basic');
			
			

			if($_GET['IdProduct']==0 && !isset($_GET['op_update']))
			{

				echo '<p><a href="'. set_admin_link( 'shop', array('op' => 3, 'idcat' => $parent) ).'">'.I18n::lang('common', 'go_back', 'Go back').'</a></p>';

			}

			

		break;

		case 4:

			echo '<h3>'.I18n::lang('shop', 'edit_options_for_product', 'Editar opciones para el producto').'</h3>';

			$url_options=set_admin_link( 'config_options_shop', array('op' => 4) );

			$arr_fields=array('title');
			$arr_fields_edit=array();

			Webmodel::$model['type_product_option']->create_form();

			Webmodel::$model['type_product_option']->forms['title']->label=I18n::lang('common', 'title', 'Title');
			Webmodel::$model['type_product_option']->forms['description']->label=I18n::lang('common', 'description', 'description');
			Webmodel::$model['type_product_option']->forms['question']->label=I18n::lang('shop', 'question', 'Pregunta para hacer al cliente sobre las opciones');
			Webmodel::$model['type_product_option']->forms['options']->label=I18n::lang('shop', 'options_product', 'Opciones (separadas por |)');
			Webmodel::$model['type_product_option']->forms['price']->label=I18n::lang('shop', 'price', 'Precio');

			generate_admin_model_ng('type_product_option', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

		break;

		case 5:

			settype($_GET['IdProduct'], 'integer');

			$query=Webmodel::$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('title', 'idcat'));

			list($title, $idcat)=Webmodel::$model['product']->fetch_row($query);

			echo '<h3>'.I18n::lang('shop', 'add_options_to_product', 'Añadir opciones al producto').': </h3>';

			$url_options=set_admin_link( 'add_options_to_product', array('op' => 5, 'IdProduct' => $_GET['IdProduct']) );

			$arr_fields=array('idtype');
			$arr_fields_edit=array('idtype', 'field_required', 'idproduct');

			Webmodel::$model['product_option']->create_form();

			Webmodel::$model['product_option']->forms['idproduct']->SetForm($_GET['IdProduct']);

			Webmodel::$model['product_option']->forms['idtype']->form='SelectModelForm';

			Webmodel::$model['product_option']->forms['idtype']->label=I18n::lang('shop', 'option_type', 'Tipo de opción');
			
			Webmodel::$model['product_option']->forms['idtype']->parameters=array('idtype', '', '', 'type_product_option', 'title', $where='');
			
			Webmodel::$model['product_option']->forms['field_required']->label=I18n::lang('shop', 'option_required', 'Opción requerida');

			generate_admin_model_ng('product_option', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

			if(!isset($_GET['op_edit']))
			{

				echo '<p><a href="'. set_admin_link( 'edit_product', array('op' => 3, 'idcat' => $idcat) ).'">'.I18n::lang('common', 'go_back', 'Go back').'</a></p>';
		
			}

		break;

		case 6:

			echo '<h3>'.I18n::lang('shop', 'edit_taxes', 'Editar impuestos').'</h3>';

			?>
			<p>
			<a href="<?php echo set_admin_link( 'zones_shop', array('op' => 8, 'type' => 1) ); ?>"><?php echo I18n::lang('shop', 'zones_taxes', 'Zonas de impuestos'); ?></a>
			</p>
			<?php

			$url_options=set_admin_link( 'edit_taxes', array('op' => 6) );

			$arr_fields=array('name');
			$arr_fields_edit=array();

			Webmodel::$model['taxes']->create_form();
		
			Webmodel::$model['taxes']->forms['country']->form='SelectModelForm';
	
			Webmodel::$model['taxes']->forms['name']->label=I18n::lang('common', 'name', 'name');
			Webmodel::$model['taxes']->forms['percent']->label=I18n::lang('shop', 'percent', 'Porcentaje');
			Webmodel::$model['taxes']->forms['country']->label=I18n::lang('shop', 'zone', 'Zona');
			
			Webmodel::$model['taxes']->forms['country']->parameters=array('country', '', '', 'zone_shop', 'name', $where='where type=1');

			generate_admin_model_ng('taxes', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

		break;

		case 7:

			echo '<h3>'.I18n::lang('shop', 'edit_transport', 'Editar transporte').'</h3>';

			?>
			<p>
			<a href="<?php echo set_admin_link( 'shop', array('op' => 8, 'type' => 0) ); ?>"><?php echo I18n::lang('shop', 'zones_transport', 'Zonas de transporte'); ?></a>
			</p>
			<?php

			$url_options=set_admin_link( 'shop', array('op' =>7) );

			$arr_fields=array('name');
			$arr_fields_edit=array();
	
			Webmodel::$model['transport']->create_form();
		
			Webmodel::$model['transport']->forms['country']->form='SelectModelForm';
			
			Webmodel::$model['transport']->forms['country']->parameters=array('country', '', '', 'zone_shop', 'name', $where='where type=0');
	
			Webmodel::$model['transport']->forms['name']->label=I18n::lang('common', 'name', 'name');
			Webmodel::$model['transport']->forms['country']->label=I18n::lang('shop', 'zone', 'Zona');
			Webmodel::$model['transport']->forms['type']->label=I18n::lang('shop', 'type_transport', 'Tipo de transporte');
			
			$arr_type_transport=array(0, I18n::lang('shop', 'type_by_weight', 'Por peso'), 0, 
			I18n::lang('shop', 'type_by_price', 'Por precio'), 1);
			
			Webmodel::$model['transport']->forms['type']->set_parameter_value($arr_type_transport);
			
			$admin= new GenerateAdminClass('transport');
			
			$admin->arr_fields=&$arr_fields;
			
			$admin->arr_fields_edit=&$arr_fields_edit;
			
			$admin->set_url_post($url_options);
			
			$admin->options_func='TransportOptionsListModel';
			
			$admin->show();

			//generate_admin_model_ng('transport', $arr_fields, $arr_fields_edit, $url_options, $options_func='TransportOptionsListModel', $where_sql='where IdTransport>0', $arr_fields_form=array(), $type_list='Basic');

		break;

		case 8:

			settype($_GET['type'], 'integer');

			$arr_type_zone[$_GET['type']]=I18n::lang('shop', 'countries_zones_transport', 'Areas de transporte');
			$arr_type_zone[0]=I18n::lang('shop', 'countries_zones_transport', 'Areas de transporte');
			$arr_type_zone[1]=I18n::lang('shop', 'countries_zones_taxes', 'Areas de impuestos');

			$sql_type_zone[$_GET['type']]='where type=0';
			$sql_type_zone[0]='where type=0';
			$sql_type_zone[1]='where type=1';

			$back_type_zone[$_GET['type']]=0;
			$back_type_zone[0]=7;
			$back_type_zone[1]=6;

			echo '<h3>'.I18n::lang('shop', 'countries_zones', 'Zonas').' - '.$arr_type_zone[$_GET['type']].'</h3>';

			$url_options=set_admin_link( 'shop', array('op' => 8, 'type' => $_GET['type']) );

			Webmodel::$model['zone_shop']->create_form();

			Webmodel::$model['zone_shop']->forms['type']->set_parameter_value($_GET['type']);

			/*foreach(PhangoVar::$arr_i18n as $lang_i18n)
			{

				Webmodel::$model['zone_shop']->forms['name_'.$lang_i18n]->label=I18n::lang('common', 'name', 'name').' '.$lang_i18n;

			}*/

			Webmodel::$model['zone_shop']->forms['name']->label=I18n::lang('common', 'name', 'name');

			Webmodel::$model['zone_shop']->forms['code']->label=I18n::lang('shop', 'country_code', 'Código de zona');
			
			Webmodel::$model['zone_shop']->forms['other_countries']->label=I18n::lang('shop', 'other_countries', 'Abarcar todos los países que no estén en ninguna otra zona');

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

			$go_back_text[0]=I18n::lang('shop', 'go_back_to_transport', 'Regresar a transporte');
			$go_back_text[1]=I18n::lang('shop', 'go_back_to_taxes', 'Regresar a impuestos');

			echo '<p><a href="'. set_admin_link( 'shop', array('op' => $back_type_zone[$_GET['type']]) ).'">'.$go_back_text[$_GET['type']].'</a></p>';

		break;

		case 9:
		
			Utils::load_libraries(array('config_shop'), PhangoVar::$base_path.'/modules/shop/libraries/');

			settype($_GET['IdTransport'], 'integer'); 

			$query=Webmodel::$model['transport']->select('where IdTransport='.$_GET['IdTransport'], array('name', 'type'));

			list($name_transport, $type)=Webmodel::$model['transport']->fetch_row($query);

			echo '<h3>'.I18n::lang('shop', 'price_transport_for', 'Precio de transporte para').': '.$name_transport.'</h3>';

			$url_options=set_admin_link( 'shop', array('op' => 9, 'IdTransport' => $_GET['IdTransport'] ) );
			
			if($type==0)
			{

				$arr_fields=array('price', 'weight');
				$arr_fields_edit=array();

				Webmodel::$model['price_transport']->create_form();
				
				Webmodel::$model['price_transport']->forms['idtransport']->set_param_value_form($_GET['IdTransport']);

				Webmodel::$model['price_transport']->forms['price']->label=I18n::lang('shop', 'price', 'Precio');
				Webmodel::$model['price_transport']->forms['weight']->label=I18n::lang('shop', 'weight', 'Peso');
				
				$admin=new GenerateAdminClass('price_transport');
				
				$admin->arr_fields=&$arr_fields;
				
				$admin->where_sql='where idtransport='.$_GET['IdTransport'];
				
				$admin->set_url_post($url_options);
				
				$admin->show();

				//generate_admin_model_ng('price_transport', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where idtransport='.$_GET['IdTransport'], $arr_fields_form=array(), $type_list='Basic');
				
			}
			else
			{
				
				$arr_fields=array('price', 'min_price');
				$arr_fields_edit=array();

				Webmodel::$model['price_transport_price']->create_form();
				
				Webmodel::$model['price_transport_price']->forms['idtransport']->SetForm($_GET['IdTransport']);

				Webmodel::$model['price_transport_price']->forms['price']->label=I18n::lang('shop', 'price', 'Precio');
				Webmodel::$model['price_transport_price']->forms['min_price']->label=I18n::lang('shop', 'min_price', 'Precio total de pedido');
				
				$admin=new GenerateAdminClass('price_transport');
				
				$admin->arr_fields=&$arr_fields;
				
				$admin->where_sql='where idtransport='.$_GET['IdTransport'];
				
				$admin->show();

				//generate_admin_model_ng('price_transport_price', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where idtransport='.$_GET['IdTransport'], $arr_fields_form=array(), $type_list='Basic');
			
			}

			if($_GET['op_edit']==0)
			{

				echo '<p><a href="'. set_admin_link( 'shop', array('op' => 7) ).'">'.I18n::lang('common', 'go_back', 'Go back').'</a></p>';

			}

		break;

		case 10:

			echo '<h3>'.I18n::lang('shop', 'gateways_payment', 'Pasarelas de pago').'</h3>';

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
			
			Webmodel::$model['payment_form']->create_form();
			//$this->arr_values=$arr_values;
			Webmodel::$model['payment_form']->components['code']->arr_values=&$arr_code_check;

			Webmodel::$model['payment_form']->forms['code']->set_parameter_value($arr_code);

			Webmodel::$model['payment_form']->forms['name']->label=I18n::lang('common', 'name', 'name');
			Webmodel::$model['payment_form']->forms['code']->label=I18n::lang('shop', 'code_payment', 'Código php');
			Webmodel::$model['payment_form']->forms['price_payment']->label=I18n::lang('shop', 'price_payment', 'Cargo del modo de pago');

			//generate_admin_model_ng('payment_form', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');
			
			$admin=new GenerateAdminClass('payment_form');
			
			$admin->arr_fields=&$arr_fields;
			
			$admin->set_url_post($url_options);
			
			$admin->show();

		break;

		case 11:

			echo '<h3>'.I18n::lang('shop', 'group_shop', 'Grupo').'</h3>';

			$url_options=set_admin_link( 'edit_group_shop', array('op' => 11) );

			$arr_fields=array('name');
			$arr_fields_edit=array();

			Webmodel::$model['group_shop']->create_form();

			Webmodel::$model['group_shop']->forms['name']->label=I18n::lang('common', 'name', 'name');
			Webmodel::$model['group_shop']->forms['discount']->label=I18n::lang('shop', 'discount', 'Descuento');
			Webmodel::$model['group_shop']->forms['taxes_for_group']->label=I18n::lang('shop', 'taxes_for_group', 'Descuentos en impuestos');
			Webmodel::$model['group_shop']->forms['transport_for_group']->label=I18n::lang('shop', 'transport_for_group', 'Descuento en portes');
			Webmodel::$model['group_shop']->forms['shipping_costs_for_group']->label=I18n::lang('shop', 'shipping_costs_for_group', 'Descuento en pasarela de pago');
		
			generate_admin_model_ng('group_shop', $arr_fields, $arr_fields_edit, $url_options, $options_func='GroupShopOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');

		break;

		case 12:


			settype($_GET['IdGroup_shop'], 'integer');

			Webmodel::$model['group_shop_users']->create_form();

			$url_options=set_admin_link( 'edit_group_shop', array('op' => 12) );

			Webmodel::$model['group_shop_users']->forms['group_shop']->SetForm($_GET['IdGroup_shop']);
			Webmodel::$model['group_shop_users']->forms['iduser']->label=I18n::lang('common', 'user', 'user');

			Webmodel::$model['group_shop_users']->forms['iduser']->form='SelectModelForm';
			
			Webmodel::$model['group_shop_users']->forms['iduser']->parameters=array('iduser', '', '', 'user', 'private_nick', $where='where iduser>0');

			generate_admin_model_ng('group_shop_users', array('iduser'), array(),  $url_options, $options_func='BasicOptionsListModel', $where_sql='');

			$url_back=set_admin_link( 'edit_group_shop', array('op' => 11) );

			if($_GET['op_edit']==0)
			{

			?>
				<p><a href="<?php echo $url_back; ?>"><?php echo I18n::lang('common', 'go_back', 'Go back'); ?></a></p>
			<?php

			}

		break;

		case 13:

			//?order_field=date_order&order_desc=1&search_word=&search_field=IdOrder_shop
			
			Utils::load_libraries(array('config_shop'), PhangoVar::$base_path.'/modules/shop/libraries/');
			
			echo '<h2>'.I18n::lang('shop', 'orders', 'Pedidos').'</h2>';

			if(!isset($_GET['order_field']))
			{

				$_GET['order_field']='date_order';
				$_GET['order_desc']=1;

			}

			Webmodel::$model['order_shop']->components['token']->required=0;
			//Webmodel::$model['order_shop']->components['payment_form']->required=0;
			
			settype($_GET['op_payment'], 'integer');
			
			$where_sql='';

			$arr_fields=array('name', 'last_name', 'email', 'total_price', 'payment_done', 'date_order');
			
			if($_GET['op_payment']==1)
			{
			
				unset($arr_fields[2]);
			
			}
			
			$arr_fields_edit=array('name', 'last_name', 'enterprise_name', 'email', 'nif', 'address', 'zip_code', 'city', 'region', 'country', 'phone', 'fax', 'name_transport', 'last_name_transport', 'address_transport', 'zip_code_transport', 'city_transport', 'region_transport', 'country_transport', 'phone_transport', 'date_order', 'observations', 'transport', 'name_payment', 'payment_done', 'total_price');
			
			$url_options=set_admin_link( 'shop', array('op' => 13, 'op_payment' => $_GET['op_payment']) );
	
			$arr_country=array('');

			$query=Webmodel::$model['country_shop']->select('', array('IdCountry_shop', 'name'));

			while(list($idcountry_shop, $name_country)=Webmodel::$model['country_shop']->fetch_row($query))
			{

				$arr_country[]=I18nField::show_formatted($name_country);
				$arr_country[]=$idcountry_shop;

			}

			Webmodel::$model['order_shop']->forms['country']->form='SelectForm';
			Webmodel::$model['order_shop']->forms['country']->set_parameter_value($arr_country);

			Webmodel::$model['order_shop']->forms['country_transport']->form='SelectForm';
			Webmodel::$model['order_shop']->forms['country_transport']->set_parameter_value($arr_country);

			Webmodel::$model['order_shop']->forms['name']->label=I18n::lang('common', 'name', 'name');
			Webmodel::$model['order_shop']->forms['last_name']->label=I18n::lang('common', 'last_name', 'Lastname');
			Webmodel::$model['order_shop']->forms['email']->label=I18n::lang('common', 'email', 'Email');
			Webmodel::$model['order_shop']->forms['total_price']->label=I18n::lang('shop', 'total_price', 'Precio total');
			Webmodel::$model['order_shop']->forms['payment_done']->label=I18n::lang('shop', 'make_payment', '¿Pagado?');
			Webmodel::$model['order_shop']->forms['date_order']->label=I18n::lang('common', 'date', 'date');

			//Zone_transport...

			$arr_transport=array('');

			$query=Webmodel::$model['transport']->select('', array('IdTransport', 'name'));

			while(list($idtransport, $name_transport)=Webmodel::$model['transport']->fetch_row($query))
			{

				$arr_transport[]=$name_transport;
				$arr_transport[]=$idtransport;

			}

			Webmodel::$model['order_shop']->forms['transport']->form='SelectForm';
			Webmodel::$model['order_shop']->forms['transport']->set_parameter_value($arr_transport);
			
			$arr_link_orders[0]=array('link' => set_admin_link( 'shop', array('op' => 13, 'op_payment' => 0) ), 'text' => I18n::lang('shop', 'payment_orders', 'Pedidos pagados'));
			
			$arr_link_orders[1]=array('link' => set_admin_link( 'shop', array('op' => 13, 'op_payment' => 1) ), 'text' => I18n::lang('shop', 'no_payment_orders', 'Pedidos no pagados'));
			
			menu_selected($_GET['op_payment'], $arr_link_orders, 1);
			
			switch($_GET['op_payment'])
			{
			
				default:
			
				echo '<h3>'.I18n::lang('shop', 'payment_orders', 'Pedidos pagados').'</h3>';

				//ListModel('order_shop', $arr_fields, $url_options, $options_func='BillOptionsListModel', $where_sql='where make_payment=1', $arr_fields_edit, 0);
				//($model_name, $arr_fields, $url_options, $options_func='BasicOptionsListModel', $options_func_extra_args=array(), $where_sql='', $arr_fields_form=array(), $type_list='Basic', $no_search=false, $yes_id=1, $yes_options=1, $extra_fields=array(), $separator_element='<br />', $simple_redirect=0)
				$list=new ListModelClass('order_shop', $arr_fields, $url_options, $options_func='BillOptionsListModel', $options_func_extra_args=array(), $where_sql='where payment_done=1', $arr_fields_edit, 0);
				
				$list->show();
				
				break;
				
				case 1:
			
				echo '<h3>'.I18n::lang('shop', 'no_payment_orders', 'Pedidos no pagados').'</h3>';

				//ListModel('order_shop', $arr_fields, $url_options, $options_func='BillOptionsListModel', $where_sql='where make_payment=0', $arr_fields_edit, 0);
				
				$list=new ListModelClass('order_shop', $arr_fields, $url_options, $options_func='BillOptionsListModel', $where_sql='where payment_done=0', $arr_fields_edit, 0);
				
				$list->show();
				
				break;
				
			}

		break;

		case 14:

			settype($_GET['IdProduct'], 'integer');

			$arr_relationship=Webmodel::$model['product_relationship']->select_a_row_where('where idproduct='.$_GET['IdProduct'].' limit 1', array('idcat_product'));
			
			$query=Webmodel::$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('title'));
			
			list($title)=Webmodel::$model['product']->fetch_row($query);
			
			$title=I18nField::show_formatted($title);

			echo '<h3>'.I18n::lang('shop', 'edit_image_product', 'Editar imagen de producto').' - '.$title.'</h3>';
			
			$url_add_images=set_admin_link( 'edit_image_product',array('op' => 19, 'IdProduct' => $_GET['IdProduct']) );
			
			echo '<p><a href="'.$url_add_images.'">'.I18n::lang('shop', 'add_new_images', 'Añadir nuevas imágenes').'</a></h3>';

			$url_options=set_admin_link( 'shop', array('op' => 14, 'IdProduct' => $_GET['IdProduct']) );

			$arr_fields=array('photo', 'principal');
			$arr_fields_edit=array('photo', 'idproduct', 'principal');

			Webmodel::$model['image_product']->create_form();

			Webmodel::$model['image_product']->forms['photo']->parameters=array('photo', '', '', 0, Webmodel::$model['image_product']->components['photo']->url_path);

			Webmodel::$model['image_product']->forms['idproduct']->form='HiddenForm';

			Webmodel::$model['image_product']->forms['idproduct']->set_param_value_form($_GET['IdProduct']);

			Webmodel::$model['image_product']->forms['photo']->label=I18n::lang('common', 'image', 'image');
			Webmodel::$model['image_product']->forms['principal']->label=I18n::lang('shop', 'principal_photo', 'Imagen principal');

			//order_field=photo&order_desc=1&search_word=&search_field=IdImage_product

			if(!isset($_GET['order_field']))
			{

				$_GET['order_field']='principal';
				$_GET['order_desc']=1;

			}
			Webmodel::$model['image_product']->set_enctype_binary();
			
			//generate_admin_model_ng('image_product', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where IdProduct='.$_GET['IdProduct'], $arr_fields_form=array(), $type_list='Basic');
			
			$admin=new GenerateAdminClass('image_product');
			
			$admin->arr_fields=&$arr_fields;
			$admin->arr_fields_edit=&$arr_fields_edit;
			
			$admin->set_url_post($url_options);
			
			$admin->where_sql='where idproduct='.$_GET['IdProduct'];
			
			$admin->show();

			if($_GET['op_action']==0 && $_GET['op_edit']==0)
			{

				echo '<p><a href="'. set_admin_link( 'shop', array('op' => 3, 'idcat' => $arr_relationship['idcat_product']) ).'">'.I18n::lang('common', 'go_back', 'Go back').'</a></p>';

			}

		break;

		case 15:

			echo '<h3>'.I18n::lang('shop', 'countries', 'Paises').'</h3>';

			$url_options=set_admin_link( 'shop', array('op' => 15) );

			$arr_fields=array('name');
			$arr_fields_edit=array('name', 'code', 'idzone_transport');

			Webmodel::$model['country_shop']->create_form();

			Webmodel::$model['country_shop']->forms['idzone_transport']->form='SelectModelForm';
			
			Webmodel::$model['country_shop']->forms['idzone_transport']->parameters=array('idzone_transport', '', '', 'zone_shop', 'name', $where='where type=0');

			/*Webmodel::$model['country_shop']->forms['idzone_taxes']->form='SelectModelForm';
			
			Webmodel::$model['country_shop']->forms['idzone_taxes']->parameters=array('idzone_taxes', '', '', 'zone_shop', 'name', $where='where type=1');*/

			/*foreach(PhangoVar::$arr_i18n as $lang_i18n)
			{

				Webmodel::$model['country_shop']->forms['name_'.$lang_i18n]->label=I18n::lang('common', 'name', 'name').' '.$lang_i18n;

			}*/

			Webmodel::$model['country_shop']->forms['name']->label=I18n::lang('common', 'name', 'name');

			Webmodel::$model['country_shop']->forms['code']->label=I18n::lang('shop', 'code_country', 'Código de país');
			//Webmodel::$model['country_shop']->forms['idzone_taxes']->label=I18n::lang('shop', 'idzone_taxes', 'Zona de impuestos');
			Webmodel::$model['country_shop']->forms['idzone_transport']->label=I18n::lang('shop', 'idzone_transport', 'Zonas de transporte');

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
			
			echo View::load_view(array(), 'shop/ordershop', 'shop');
			
			die;
		
			/*
			//Load pdf class...

			Utils::load_libraries(array('config_shop', 'fpdf/fpdf'), PhangoVar::$base_path.'/modules/shop/libraries/');
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

						$this->Image(Webmodel::$model['config_shop']->components['image_bill']->path.$config_shop['image_bill']);

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
				
					$text_address_client[]=iconv("UTF-8", "CP1252", I18n::lang('shop', 'client', 'Cliente').': '.$name_client);
					$text_address_client[]=iconv("UTF-8", "CP1252", I18n::lang('common', 'city', 'City').': '.$order_shop['city']);
					$text_address_client[]=iconv("UTF-8", "CP1252", I18n::lang('common', 'region', 'Region').': '.$order_shop['region'] );
					$text_address_client[]=iconv("UTF-8", "CP1252", I18n::lang('common', 'email', 'Email').': '.$order_shop['email'] );
					$text_address_client[]=iconv("UTF-8", "CP1252", I18n::lang('common', 'country', 'Country').': '.$order_shop['country'] );
					
					$text_address_client_other[]=iconv("UTF-8", "CP1252", I18n::lang('common', 'address', 'Address').': '.$order_shop['address'] );
					$text_address_client_other[]=iconv("UTF-8", "CP1252", I18n::lang('common', 'zip_code', 'Zip code').': '.$order_shop['zip_code'] );
					$text_address_client_other[]=iconv("UTF-8", "CP1252", I18n::lang('shop', 'fiscal_identity', 'Identidad fiscal').': '.$order_shop['nif'] );
					$text_address_client_other[]=iconv("UTF-8", "CP1252", I18n::lang('common', 'phone', 'Phone').': '.$order_shop['phone'] );
					$text_address_client_other[]=iconv("UTF-8", "CP1252", I18n::lang('common', 'fax', 'Fax').': '.$order_shop['fax'] );

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

					$this->Cell(90,9,I18n::lang('common', 'date', 'date').': '.$date,0,0,'LR');
					$this->Cell(95,9,iconv("UTF-8", "CP1252", I18n::lang('shop', 'num_bill', 'Número de factura')).': '.$num_bill,0,0,'LR');

					$this->Ln();

				}

				function TotalPrice($order_shop, $total_price)
				{

					global $lang;

					// Elemento descuento precio

					// 30 20 20

					$this->SetXY(10,220);

					$this->Cell(40,8,'', 0);
					$this->Cell(40,8,I18n::lang('shop', 'value', 'Valor'),1);
					$this->Cell(40,8,I18n::lang('shop', 'discount', 'Descuento'),1);
					$this->Cell(40,8,I18n::lang('shop', 'final_value', 'Valor final'),1);
					$this->Ln();
					//Here total price...

					//total_price | tax | tax_percent | tax_discount_percent | price_transport | transport_discount_percent | price_payment | payment_discount_percent | discount      | discount_percent | name_payment

					$this->Cell(40, 8, I18n::lang('shop', 'total_price', 'Precio total'), 1);
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

						$tax_name=iconv("UTF-8", "CP1252", I18n::lang('shop', 'transport_price', 'Portes'));

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

						$payment_name=iconv("UTF-8", "CP1252", I18n::lang('shop', 'shipping_costs', 'Cargos en pago'));

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
	
					$this->Cell(90,14, strtoupper(I18n::lang('shop', 'total', 'Total')).': '.$total,0);

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

			$query=Webmodel::$model['order_shop']->select('where IdOrder_shop='.$_GET['IdOrder_shop'], array(), 0);

			$arr_order=webtsys_fetch_array($query);
			
			settype($arr_order['IdOrder_shop'], 'integer');

			if($arr_order['IdOrder_shop']>0)
			{

				//Num invoice...
				// Columns titles

				$query=Webmodel::$model['country_shop']->select('where IdCountry_shop='.$arr_order['country'], array('name'), 1);

				list($arr_order['country'])=webtsys_fetch_row($query);

				$arr_order['country']=Webmodel::$model['country_shop']->components['name']->show_formatted($arr_order['country']);

				$header = array(iconv("UTF-8", "CP1252",I18n::lang('shop']->lang('referer', 'Referencia')), iconv("UTF-8", "CP1252",PhangoVar::$l_['shop']->lang('description', 'Descripción')), iconv("UTF-8", "CP1252", PhangoVar::$l_['shop']->lang('units', 'Unidades')), iconv("UTF-8", "CP1252", PhangoVar::$l_['shop']->lang('price', 'Precio')), iconv("UTF-8", "CP1252",PhangoVar::$l_['shop']->lang('discount', 'Descuento')), iconv("UTF-8", "CP1252",PhangoVar::$l_['shop', 'total', 'Total')) );

				//here query from cart_shop

				$cart_shop=array();

				$arr_units=array();

				$arr_product_total=array();

				$query=Webmodel::$model['cart_shop']->select('where token="'.$arr_order['token'].'"');

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
					
					$arr_product['product_title']=iconv("UTF-8", "CP1252", substr( Webmodel::$model['product']->components['title']->show_formatted( $arr_product['product_title'] ) , 0, 20).'[..]' );
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

			echo '<h3>'.I18n::lang('shop', 'currency', 'Moneda').'</h3>';

			$url_options=set_admin_link( 'shop', array('op' => 17) );

			$arr_fields=array('name', 'symbol');
			$arr_fields_edit=array('name', 'symbol');

			Webmodel::$model['currency']->create_form();

			Webmodel::$model['currency']->forms['name']->label=I18n::lang('common', 'name', 'name');
			Webmodel::$model['currency']->forms['symbol']->label=I18n::lang('shop', 'symbol', 'Símbolo');
		
			//generate_admin_model_ng('currency', $arr_fields, $arr_fields_edit, $url_options, $options_func='CurrencyOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic');
			$admin=new GenerateAdminClass('currency');
			
			$admin->arr_fields=&$arr_fields;
			$admin->arr_fields_edit=&$arr_fields_edit;
			
			$admin->set_url_post($url_options);
			
			$admin->options_func='CurrencyOptionsListModel';
			
			$admin->show();
			

		break;
	
		case 18:

			settype($_GET['IdCurrency'], 'integer');

			$query=Webmodel::$model['currency']->select('where IdCurrency='.$_GET['IdCurrency'], array('name'));

			list($name_currency)=Webmodel::$model['currency']->fetch_row($query);

			$name_currency=I18nField::show_formatted($name_currency);

			echo '<h3>'.I18n::lang('shop', 'modify_change_currencies', 'Modificar cambios entre monedas').': '.$name_currency.'</h3>';

			$url_options=set_admin_link( 'modify_change_currencies', array('op' => 18, 'IdCurrency' => $_GET['IdCurrency']) );

			$arr_fields=array('idcurrency_related');
			$arr_fields_edit=array();
			
			Webmodel::$model['currency_change']->components['idcurrency_related']->name_field_to_field='name';

			Webmodel::$model['currency_change']->create_form();

			Webmodel::$model['currency_change']->forms['idcurrency']->form='HiddenForm';
			Webmodel::$model['currency_change']->forms['idcurrency']->SetForm($_GET['IdCurrency']);

			Webmodel::$model['currency_change']->forms['idcurrency_related']->label=I18n::lang('shop', 'currency', 'Moneda');

			Webmodel::$model['currency_change']->forms['idcurrency_related']->form='SelectModelForm';
			
			Webmodel::$model['currency_change']->forms['idcurrency_related']->parameters=array('idcurrency_related', '', '', 'currency', 'name', $where='where IdCurrency!='.$_GET['IdCurrency']);

			Webmodel::$model['currency_change']->forms['change_value']->label=I18n::lang('shop', 'explain_change_value', 'Indique que valor es 1 unidad de la moneda elegida en relación a').' '.$name_currency;
		
			generate_admin_model_ng('currency_change', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where currency_change.idcurrency='.$_GET['IdCurrency'], $arr_fields_form=array(), $type_list='Basic');

			if($_GET['op_action']==0 && $_GET['op_edit']==0)
			{

				echo '<p><a href="'. set_admin_link( 'edit_currency', array('op' => 17) ).'">'.I18n::lang('common', 'go_back', 'Go back').'</a></p>';

			}

		break;
		
		case 19:
		
			settype($_GET['op_image'], 'integer');
			settype($_GET['IdProduct'], 'integer');
			
			//ew ImageField('photo', PhangoVar::$base_path.'application/media/shop/images/products/', PhangoVar::$base_url.'/media/shop/images/products', 'image', 1, array('small' => 45, 'mini' => 150, 'medium' => 300, 'preview' => 600));
			
			$arr_field['image_form1']=clone Webmodel::$model['image_product']->components['photo'];
			$arr_field['image_form1']->name_file='image_form1';
		
			$arr_form['image_form1']=new ModelForm('create_image', 'image_form1', 'ImageForm', I18n::lang('common', 'image', 'image').' 1', $arr_field['image_form1'], $required=1, $parameters='');
			
			/*$bool[1]=new BooleanField();
			
			$arr_form['principal1']=new ModelForm('create_image', 'principal1', 'SelectForm', I18n::lang('shop', 'principal_photo', 'Imagen principal').' 1', $bool[1], $required=0, $parameters=$bool[1]->get_parameters_default());*/
			
			for($x=2;$x<11;$x++)
			{
			
				$arr_field['image_form'.$x]=clone Webmodel::$model['image_product']->components['photo'];
				$arr_field['image_form'.$x]->name_file='image_form'.$x;
			
				$arr_form['image_form'.$x]=new ModelForm('create_image', 'image_form'.$x.'', 'ImageForm', I18n::lang('common', 'image', 'image').' '.$x, $arr_field['image_form'.$x], $required=0, $parameters='');
				
				/*$bool[$x]=new BooleanField();
				
				$arr_form['principal'.$x]=new ModelForm('create_image', 'principal'.$x, 'SelectForm', I18n::lang('shop', 'principal_photo', 'Imagen principal').' 1', $bool[$x], $required=0, $parameters=$bool[$x]->get_parameters_default());*/
			
			}
			
			switch($_GET['op_image'])
			{
			
				default:
				
					ob_start();
					
					$query=Webmodel::$model['product']->select('where IdProduct='.$_GET['IdProduct'], array('title'));
					
					list($title)=Webmodel::$model['product']->fetch_row($query);
					
					$title=I18nField::show_formatted($title);
				
					$url_post=set_admin_link( 'edit_image_product',array('op' => 19, 'IdProduct' => $_GET['IdProduct'], 'op_image' => 1) );
			
					echo View::load_view(array($arr_form, array(), $url_post, 'enctype="multipart/form-data"'), 'common/forms/updatemodelform');
					
					$cont_add=ob_get_contents();
					
					ob_end_clean();
					
					echo View::load_view(array(I18n::lang('shop', 'add_new_images', 'Añadir nuevas imágenes').' - '.$title, $cont_add), 'content');
					
					echo '<p><a href="'.$url_post=set_admin_link( 'edit_image_product',array('op' => 14, 'IdProduct' => $_GET['IdProduct']) ).'">'.I18n::lang('common', 'go_back', 'Go back').'</a></p>';
				
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
							
								Webmodel::$model['image_product']->insert(array('photo' => $arr_post[$file_name], 'principal' => 0, 'idproduct' => $_GET['IdProduct']));
							
							}
						
						}
					
					
					
						ob_end_clean();
						/*load_libraries(array('redirect'));
						die( redirect_webtsys( $url_post=set_admin_link( 'edit_image_product',array('op' => 14, 'IdProduct' => $_GET['IdProduct']) ), 
						I18n::lang('common', 'redirect', 'Redirect'), 
						I18n::lang('common', 'success', 'Success'), 
						I18n::lang('common', 'press_here_redirecting', 'Press here for redirecting') , $arr_block) );*/
						
						
						View::set_flash(I18n::lang('shop', 'success_image', 'Image modified successfully'));
						
						Routes::redirect( set_admin_link( 'edit_image_product',array('op' => 14, 'IdProduct' => $_GET['IdProduct']) ) );
						
						
					}
					else
					{
						
						$url_go_back=set_admin_link( 'edit_image_product',array('op' => 19, 'IdProduct' => $_GET['IdProduct']) );
						
						echo '<p>'.I18n::lang('common', 'error_cannot_upload_this_image_to_the_server', 'Error: Cannot upload this image to the server').'</p>';
					
						echo '<p><a href="'.$url_go_back.'">'.I18n::lang('common', 'go_back', 'Go back').'</a></p>';
					
					}
					
				
				break;
			
			}
		
		break;
		
		case 20:
		
			//First, select form for choose an module, for now, products.
			
			echo '<h3>'.I18n::lang('shop', 'plugin_admin', 'Administración de plugins').'</h3>';
			
			settype($_GET['element_choice'], 'string');
			
			$arr_elements_plugin=array($_GET['element_choice'], '', '', 'products', 'product', 'cart', 'cart');
			
			echo '<form method="get" action="'.set_admin_link( 'shop', array('op' => 20)).'">';
			
			echo '<p>'.I18n::lang('shop', 'element_choice', 'Elegir elemento')
			.': '.SelectForm('element_choice', '', $arr_elements_plugin).' <input type="submit" value="'.
			I18n::lang('common', 'send', 'Send').'" /></p>';
			
			echo '</form>';
			
			//Now the form...
			
			$element_choice=Webmodel::$model['plugin_shop']->components['element']->check($_GET['element_choice']);
			
			//settype($arr_plugin_list[$element_choice], 'array');
			
			if($element_choice!='')
			{
			
				Webmodel::$model['plugin_shop']->create_form();
// 				
				Webmodel::$model['plugin_shop']->forms['element']->form='HiddenForm';
				Webmodel::$model['plugin_shop']->forms['element']->set_parameter_value($element_choice);
				
				$arr_plugins=array('', '', '');
				
				/*foreach($arr_plugin_list[$element_choice] as $plugin)
				{
				
					$arr_plugins[]=$plugin;
					$arr_plugins[]=$plugin;
					
				
				}*/

				$arr_plugin_choice=array();
				
				$dir = opendir( PhangoVar::$base_path."/modules/shop/plugins" );

				while ( $plugin_dir = readdir( $dir ) )
				{
				
					if(!preg_match('/^\./', $plugin_dir))
					{
					
						$subdir=opendir(PhangoVar::$base_path."/modules/shop/plugins/".$plugin_dir);
						
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
				
				Webmodel::$model['plugin_shop']->components['plugin']->arr_values=&$arr_plugin_choice;
				
				Webmodel::$model['plugin_shop']->components['plugin']->restart_formatted();
				
				Webmodel::$model['plugin_shop']->forms['plugin']->parameters=array('plugin', '', $arr_plugins);
			
				Webmodel::$model['plugin_shop']->forms['name']->label=I18n::lang('common', 'name', 'name');
			
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
				
				echo '<p><a href="'.set_admin_link( 'shop', array('op' => 21, 'element_choice' => $element_choice)).'">'.I18n::lang('shop', 'order_plugins', 'Ordenar plugins').'</a></p>';
			
			}
			
			
		
		break;
		
		case 21:
		
			$element_choice=Webmodel::$model['plugin_shop']->components['element']->check($_GET['element_choice']);
			
			if($element_choice!='')
			{
			
				echo '<h3>'.I18n::lang('shop', 'order_plugins', 'Ordenar plugins').'</h3>';
				
				GeneratePositionModel('plugin_shop', 'name', 'position', set_admin_link( 'plugin_admin', array('op' => 21, 'element_choice' => $element_choice)), $where='where element="'.$element_choice.'"');
				
				echo '<p><a href="'.set_admin_link( 'plugin_admin', array('op' => 20, 'element_choice' => $element_choice)).'">'.I18n::lang('common', 'go_back', 'Go back').'</a></p>';
			
			}
		
		break;
		
		case 22:
		
			settype($_GET['IdProduct'], 'integer');
			settype($_GET['plugin'], 'string');
			
			$_GET['plugin']=basename(Utils::slugify($_GET['plugin']));
			
			$plugin=$_GET['plugin'];
			
			$plugins=new PreparePluginClass('product');
			
			$plugins->obtain_list_plugins();
			
			if(isset($plugins->arr_plugin_list[$plugin]))
			{
			
				echo '<h2>'.I18n::lang('shop', 'plugin_product_admin', 'plugin_product_admin').'</h2>';
				
				$plugins->load_plugin($plugin);
				
				$plugins->arr_class_plugin[$plugin]->admin_plugin_product();
			
			}
			
			
		
		break;
		
		case 23:
		
			$_GET['plugin']=basename(Utils::slugify($_GET['plugin']));
		
			$plugin=$_GET['plugin'];
		
			$plugins=new PreparePluginClass('product');
			
			$plugins->obtain_list_plugins();
			
			if(isset($plugins->arr_plugin_list[$plugin]))
			{
			
				echo '<h2>'.I18n::lang('shop', 'plugin_product_admin_home', 'plugin_product_admin_home').'</h2>';
				
				$plugins->load_plugin($plugin);
				
				$plugins->arr_class_plugin[$plugin]->admin_plugin();
			
			}
		
			/*$element_choice=Webmodel::$model['plugin_shop']->components['element']->check($_GET['element_choice']);
			
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
				
			
			}*/
		
		break;
		
		case 24:
		
			settype($_GET['idproduct'], 'integer');
			
			$product=Webmodel::$model['product']->select_a_row($_GET['idproduct'], array('title'), true);
		
			echo '<h3>'.I18n::lang('shop', 'change_shop_category', 'Cambiar categoría de tienda').' - '.I18nField::show_formatted($product['title']).'</h3>';
			
			$arr_fields=array('idcat_product');
			$arr_fields_edit=array();
			$url_options=set_admin_link( 'shop', array('op' => 24, 'idproduct' => $_GET['idproduct']));
			$url_back=set_admin_link( 'shop', array('op' => 3));
			
			Webmodel::$model['product_relationship']->components['idproduct']->form='HiddenForm';
			
			Webmodel::$model['product_relationship']->create_form();
			
			Webmodel::$model['product_relationship']->forms['idproduct']->form='HiddenForm';
			Webmodel::$model['product_relationship']->forms['idproduct']->set_param_value_form($_GET['idproduct']);
			
			Webmodel::$model['product_relationship']->forms['idcat_product']->label=I18n::lang('shop', 'category', 'Categoría');
			Webmodel::$model['product_relationship']->forms['idcat_product']->form='SelectModelFormByOrder';
			Webmodel::$model['product_relationship']->forms['idcat_product']->parameters=array('idcat_product', '', 0, 'cat_product', 'title', 'subcat', $where='');
			
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
			
				echo '<p><a href="'.$url_back.'">'.I18n::lang('common', 'go_back', 'Go back').'</a></p>';
				
			}
		
		break;
		
		case 25:
		
			echo '<h2>'.I18n::lang('shop', 'admin_users', 'admin_users').'</h2>';
		
			Webmodel::$model['user_shop']->components['token_client']->required=0;
			
			Webmodel::$model['user_shop']->components['token_recovery']->required=0;
			
			Webmodel::$model['user_shop']->components['password']->required=0;
		
			Webmodel::$model['user_shop']->create_form();
		
			Webmodel::$model['user_shop']->forms['country']->form='SelectModelForm';
			
			Webmodel::$model['user_shop']->forms['country']->parameters=array('country', '', '', 'country_shop', 'name', $where='');
			
			ConfigShop::$config_shop=Webmodel::$model['config_shop']->select_a_row_where('');
			
			$admin=new GenerateAdminClass('user_shop');
			
			$admin->arr_fields=array('email', 'name', 'last_name', 'region');
			
			$admin->arr_fields_edit=ConfigShop::$arr_fields_address;
			
			$admin->arr_fields_edit[]='email';
			
			$admin->arr_fields_edit[]='password';
			
			$url_post=set_admin_link('shop', array('op' => 25));
			
			$admin->set_url_post($url_post);
			
			if(ConfigShop::$config_shop['no_transport']==0)
			{
			
				$admin->options_func='UserOptionsListModel';
			
			}
			
			$admin->show();
		
		break;
		
		case 26:
		
			settype($_GET['IdUser_shop'], 'integer');
		
			echo '<h2>'.I18n::lang('shop', 'modify_address_transport_user', 'Modificar direcciones de transporte de usuario').'</h2>';
		
			$arr_menu[0]=array('module' => 'admin', 'controller' => 'index', 'text' => I18n::lang('user', 'admin_users', 'admin_users'), 'name_op' => 'op', 'params' => array('op' => 25, 'IdOrder_shop' => $_GET['IdUser_shop']));
		
			$arr_menu[1]=array('module' => 'admin', 'controller' => 'index', 'text' => I18n::lang('shop', 'admin_address_users', 'Administrar direcciones de usuario'), 'name_op' => 'op', 'params' => array('op' => 26, 'IdOrder_shop' => $_GET['IdUser_shop']));
		
			echo menu_barr_hierarchy_control($arr_menu);
		
			settype($_GET['IdUser_shop'], 'integer');
			
			Webmodel::$model['address_transport']->create_form();
		
			Webmodel::$model['address_transport']->forms['country_transport']->form='SelectModelForm';
			
			Webmodel::$model['address_transport']->forms['country_transport']->parameters=array('country_transport', '', '', 'country_shop', 'name', $where='');
		
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
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 26, 'IdUser_shop' => $id) ).'">'.I18n::lang('shop', 'modify_address_transport_user', 'Modificar direcciones de transporte de usuario').'</a>';

	return $arr_options;

}

function CurrencyOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 18, 'IdCurrency' => $id) ).'">'.I18n::lang('shop', 'modify_change_currencies', 'Modificar cambios entre monedas').'</a>';

	return $arr_options;

}

function ShopOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 3, 'idcat' => $id) ).'">'.I18n::lang('shop', 'modify_products', 'Modificar productos').'</a>';

	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 2, 'subcat' => $id) ).'">'.I18n::lang('shop', 'subcat_products', 'Subcategorías de productos').'</a>';	

	return $arr_options;

}

function ProductOptionsListModel($url_options, $model_name, $id, $arr_row_raw, $args)
{
	
	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 24, 'idproduct' => $id) ).'">'.I18n::lang('shop', 'edit_cat_product', 'Editar categorías de producto').'</a>';
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 14, 'IdProduct' => $id) ).'">'.I18n::lang('shop', 'edit_image_product', 'Editar imagen de producto').'</a>';
	
	$plugins=$args['plugins'];
	
	foreach($plugins->arr_class_plugin as $plugin => $arr_class)
	{
		
		$arr_options[]=$arr_class->admin_show_options($arr_row_raw);
	
	}

	return $arr_options;

}

function TransportOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 9, 'IdTransport' => $id) ).'">'.I18n::lang('shop', 'add__select_prices_for_transport', 'Añadir tabla de precios para el transporte').'</a>';

	return $arr_options;

}

function GroupShopOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 12, 'IdGroup_shop' => $id) ).'">'.I18n::lang('shop', 'add__user_to_group_shop', 'Añadir usuario a grupo de descuento').'</a>';

	return $arr_options;

}

function BillOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'. set_admin_link( 'shop', array('op' => 16, 'IdOrder_shop' => $id) ).'">'.I18n::lang('shop', 'obtain_bill', 'Obtener factura').'</a>';

	return $arr_options;

}

function PluginsOptionsListModel($url_options, $model_name, $id, $arr_row)
{

	//global $arr_plugin_options;

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	
	if(isset(ConfigShop::$arr_plugin_options[$arr_row['plugin']]['admin_external']))
	{
	
		//$func_admin_plugin=$arr_row['plugin'].'AdminExternal';
		
		
		
		$arr_options[]='<a href="'.set_admin_link( 'shop', array('op' => 23, 'plugin' => $arr_row['plugin'], 'element_choice' => $_GET['element_choice']) ).'">'.I18n::lang('shop', 'edit_plugin_external', 'Editar plugin externo').'</a>';
		
		//$func_admin_plugin();
	
	}

	return $arr_options;

}

function obtain_payment_form()
{

	$arr_code=array('');
	$arr_code_check=array();

	$dir=opendir(PhangoVar::$base_path.'/modules/shop/payment');

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