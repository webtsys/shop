<?php

class ObtaincartSwitchClass extends ControllerSwitchClass {

	function index()
	{

		//global $user_data, $model, $ip, PhangoVar::$lang, $config_data, PhangoVar::$base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop;

		load_lang('shop');
		load_libraries(array('config_shop', 'class_cart'), PhangoVar::$base_path.'modules/shop/libraries/');

		load_model('shop');

		$cart=new CartClass();
		
		list($num_product, $total_price_product)=$cart->obtain_simple_cart();

		$jsondata['num_product']=$num_product;

		//Add here plugins for taxes, etc...

		/*$idtax=$config_shop['idtax'];

		$total_price_product+=calculate_taxes($idtax, $total_price_product);

		$text_taxes=add_text_taxes($idtax);*/
		
		$jsondata['price_product']=MoneyField::currency_format($total_price_product);//number_format($total_price_product, 2);

		echo json_encode($jsondata);

		

	}

}

?>