<?php
function BuyProduct()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop;

	I18n::load_lang('shop');
	Utils::load_libraries(array('config_shop', 'class_cart'), $base_path.'modules/shop/libraries/');

	Webmodel::load_model('shop');

	settype($_GET['IdProduct'], 'integer');

	//Only for buy products without options.
	
	Webmodel::$model['product']->related_models=array('product_relationship' => array('idproduct', 'idcat_product'));
	
	$query=Webmodel::$model['product']->select('where product.IdProduct='.$_GET['IdProduct'], array('IdProduct', 'price', 'special_offer','extra_options', 'stock', 'about_order'));
		
	list($idproduct, $price, $special_offer, $extra_options, $stock, $about_order, $idproduct_ref, $idcat_product)=Webmodel::$model['product']->fetch_row($query);
	
	if($stock==0 && $about_order==0)
	{
		echo json_encode(array());
	}
	else
	{
	
		$cart=new CartClass();
	
		settype($idproduct, 'integer');
		settype($idcat, 'integer');
		
		$query=Webmodel::$model['cat_product']->select('where IdCat_product='.$idcat, array('view_only_mode'));
		
		list($view_only_mode)=Webmodel::$model['product']->fetch_row($query);
		
		if($config_shop['view_only_mode']==0 && $view_only_mode==0 && $idproduct>0)
		{

			$buy_return=0;
			
			if($extra_options=='')
			{

				//No extra_options, add to cart...
				//($idproduct, $arr_details=array(), $price=0, $special_offer=0, $redirect=1)
				$buy_return=$cart->add_to_cart($idproduct, $arr_details=array(), $price, $special_offer, $redirect=0);

				

			}

			$jsondata['buy_return']=$buy_return;

			echo json_encode($jsondata);
		
		}
		else
		{
		
			echo json_encode(array());
		
		}
		
	}

}

?>