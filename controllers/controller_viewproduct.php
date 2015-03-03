<?php

class ViewproductSwitchClass extends ControllerSwitchClass 
{

	public function index($idproduct, $text_product)
	{

		//global $user_data, $model, $ip, $lang, $config_data, PhangoVar::$base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ConfigShop::$config_shop, $lang_taxes;

		$cont_index='';

		//Load page...

		load_model('shop');

		load_lang('shop');
		load_libraries(array('utilities/hierarchy_links'));
		load_libraries(array('config_shop'), PhangoVar::$base_path.'modules/shop/libraries/');

		settype($idproduct, 'integer');
		settype($_GET['img_big'], 'integer');

		//$idtax=ConfigShop::$config_shop['idtax'];
		
		PhangoVar::$model['product']->related_models=array('product_relationship' => array('idproduct', 'idcat_product'));
		
		$arr_product=PhangoVar::$model['product']->select_a_row($idproduct, array(), 0);
		
		$idcat_product=$arr_product['product_relationship_idcat_product'];
		
		$idproduct=$arr_product['IdProduct'];
		
		if($idproduct>0)
		{
		
			settype($idcat_product, 'integer');
			settype($idproduct, 'integer');
			
			$title=$arr_product['title'];
			$description=$arr_product['description'];
			
			$title=PhangoVar::$model['product']->components['title']->show_formatted($title);
			$description=PhangoVar::$model['product']->components['description']->show_formatted($description);

			list($view_only_mode)=PhangoVar::$model['cat_product']->select_a_row($idcat_product, array('view_only_mode'), false, true);
			
			$arr_product['view_only_mode']=$view_only_mode;
		
			//Prepare images
			
			$arr_product['images']=PhangoVar::$model['image_product']->select_to_array('where idproduct='.$idproduct.' order by principal DESC', array('photo'));
			
			if(count($arr_product['images'])==0)
			{
			
				$arr_product['images'][0]['photo']='default.jpg';
			
			}
			
			//Obtain plugins...
			
			$arr_plugin=array();
			
			$query=PhangoVar::$model['plugin_shop']->select('where element="product" order by position ASC', array('plugin'));
			
			while(list($plugin)=webtsys_fetch_row($query))
			{
				
				/*load_libraries(array($plugin), PhangoVar::$base_path.'modules/shop/plugins/product/');*/
			
				$func_plugin=ucfirst($plugin).'Show';
				
				$arr_plugin[$plugin]=$func_plugin;
			
			}
			
			$arr_product['plugins']=$arr_plugin;
			
			echo load_view(array('arr_product' => $arr_product), 'shop/viewproduct');
			
			

		}
		else
		{

			$title=$lang['shop']['no_exists_product'];
			
			echo load_view(array($lang['shop']['no_exists_product'], $lang['shop']['this_product_is_not_found']), 'content');

		}

		$cont_index=ob_get_contents();

		ob_clean();
		
		//Show links for categories

		echo load_view(array($title, $cont_index), 'home');

	}
		
}

?>
