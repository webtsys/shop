<?php

use PhangoApp\PhaRouter\Controller;
use PhangoApp\PhaView\View;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaModels\Webmodel;

class IndexController extends Controller {

	public function home()
	{
        $m=Webmodel::$m;
	
        Webmodel::load_model('vendor/phangoapp/shop/models/models_shop');
	
        Utils::load_libraries(array('config_shop'), 'vendor/phangoapp/shop/libraries/');
	
        $m->product->set_order(['date' => 1]);
        
        $m->product->set_limit([ConfigShop::$config_shop['num_news']]);
	
        $arr_product=$m->product->select_to_array();
        
        $arr_id=array_keys($arr_product);
        
        foreach($arr_id as $idproduct)
        {
        
            $arr_photo[$idproduct]='default_image.jpg';
        
        }
       
        $arr_q=array_fill(0, count($arr_id), '?');
        
        $arr_id[]=1;
       
        $m->image_product->set_conditions(['where idproduct IN ('.implode(',', $arr_q).') and principal=?', $arr_id]);
        
        $query=$m->image_product->select(['photo', 'idproduct'], true);
        
        while(list($photo, $idproduct)=$m->image_product->fetch_row($query))
        {
            $arr_photo[$idproduct]=$photo;
        }
        
        $content=View::load_view(['title' => ConfigShop::$config_shop['title_shop'], 'products' => $arr_product, 'photo' => $arr_photo], 'shop/homeshop');
	
		echo View::load_theme(I18n::lang('shop', 'home_shop', 'Últimas novedades'), $content);
	
	}

}

?>