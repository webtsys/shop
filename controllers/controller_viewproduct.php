<?php

use PhangoApp\PhaRouter\Controller;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaView\View;
use PhangoApp\PhaModels\CoreFields\I18nField;

class ViewProductController extends Controller
{

	public function home($idproduct)
	{

		//global $user_data, $model, $ip, $lang, $config_data, PhangoVar::$base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ConfigShop::$config_shop, $lang_taxes;

		$cont_index='';

		//Load page...

		Webmodel::load_model('vendor/phangoapp/shop/models/models_shop');

		I18n::load_lang('phangoapp/shop');
		//load_libraries(array('utilities/hierarchy_links'));
		Utils::load_libraries(array('config_shop'), 'vendor/phangoapp/shop/libraries');

		settype($idproduct, 'integer');
		settype($_GET['img_big'], 'integer');

		//$idtax=ConfigShop::$config_shop['idtax'];
		
		Webmodel::$model['product']->related_models=array('product_relationship' => array('idproduct', 'idcat_product'));
		
		$arr_product=Webmodel::$model['product']->select_a_row($idproduct, array(), 0);
		
		$idcat_product=$arr_product['product_relationship_idcat_product'];
		
		$idproduct=$arr_product['IdProduct'];
		
		if($idproduct>0)
		{
		
			settype($idcat_product, 'integer');
			settype($idproduct, 'integer');
			
			$title=$arr_product['title'];
			$description=$arr_product['description'];
			
			$title=Webmodel::$model['product']->components['title']->show_formatted($title);
			$description=Webmodel::$model['product']->components['description']->show_formatted($description);
            
			$arr_cat=Webmodel::$model['cat_product']->select_a_row($idcat_product, ['view_only_mode'], false, true);
			
			$view_only_mode=$arr_cat['view_only_mode'];
			
			$arr_product['view_only_mode']=$view_only_mode;
		
			//Prepare images
			
			Webmodel::$model['image_product']->set_conditions(['where idproduct=?', [$idproduct]]);
			
			//order by principal DESC';
			
			Webmodel::$model['image_product']->set_order(['principal' =>1 ]);
			
			$arr_product['images']=Webmodel::$model['image_product']->select_to_array(array('photo'));
			
			if(count($arr_product['images'])==0)
			{
			
				$arr_product['images'][0]['photo']='default.jpg';
			
			}
			
			//Obtain plugins...
			
			$arr_plugin=array();
			
			Webmodel::$model['plugin_shop']->conditions='where element="product"';
			
			//'order by position ASC'
			
			Webmodel::$model['plugin_shop']->set_order(['position' => 0]);
			
			$query=Webmodel::$model['plugin_shop']->select(array('plugin'));
			
			while(list($plugin)=Webmodel::$model['plugin_shop']->fetch_row($query))
			{
				
			
				$func_plugin=ucfirst($plugin).'Show';
				
				$arr_plugin[$plugin]=$func_plugin;
			
			}
			
			$arr_product['plugins']=$arr_plugin;
			
			echo View::load_view(array('arr_product' => $arr_product), 'shop/viewproduct');
			
			

		}
		else
		{

			$title=I18n::lang('shop', 'no_exists_product', 'Product not exists');
			
			echo View::load_view(array(I18n::lang('shop', 'no_exists_product', 'Product not exists'), I18n::lang('shop', 'this_product_is_not_found', 'This product is not found')), 'content');

		}
        
		$cont_index=ob_get_contents();
        
		ob_clean();
		
		//Show links for categories

		echo View::load_view(array($title, $cont_index), 'home');

	}
		
}

?>
