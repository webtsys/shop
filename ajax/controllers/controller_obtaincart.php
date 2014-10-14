<?php
function ObtainCart()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop;

	load_lang('shop');
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');

	load_model('shop');

	settype($_COOKIE['webtsys_shop'], 'string');

	$token=sha1($_COOKIE['webtsys_shop']);

	$num_product=0;
	$total_price_product=0;
	
	$query=$model['cart_shop']->select('where token="'.$token.'"', array('idproduct', 'price_product'));
	
	while(list($idproduct, $price_product)=webtsys_fetch_row($query))
	{
		
		$num_product++;

		$total_price_product+=$price_product;

	}

	$jsondata['num_product']=$num_product;

	//Add here plugins for taxes, etc...

	/*$idtax=$config_shop['idtax'];

	$total_price_product+=calculate_taxes($idtax, $total_price_product);

	$text_taxes=add_text_taxes($idtax);*/
	
	$jsondata['price_product']=MoneyField::currency_format($total_price_product);//number_format($total_price_product, 2);

	echo json_encode($jsondata);

	

}

?>