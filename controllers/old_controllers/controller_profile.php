<?php

function Profile()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop, $lang_taxes, $language;

	$cont_index='';

	$arr_block='';

	$arr_block=select_view(array('shop'));

	load_lang('shop');
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');
	
	ob_start();

	if($user_data['IdUser']>0)
	{

		//Last products buying...

		$arr_last_order=array();
		$arr_last_products=array();

		$query=$model['order_shop']->select('where iduser='.$user_data['IdUser'].' order by date_order DESC limit 6', array('token'));

		while(list($token_order)=webtsys_fetch_row($query))
		{

			$arr_last_order[]=$token_order;

		}

		$query=$model['cart_shop']->select('where token IN ("'.implode('", "', $arr_last_order).'") order by time ASC limit 6');

		while($arr_product=webtsys_fetch_array($query))
		{

			$arr_last_products[$arr_product['idproduct']]=$model['product']->components['title']->show_formatted($arr_product['product_title']);

		}

		//Billing

		$query=webtsys_query('select SUM(total_price) from order_shop where iduser='.$user_data['IdUser']);

		list($total_bill)=webtsys_fetch_row($query);
		
		settype($total_bill, 'integer');

		//Discount level...

		$query=$model['group_shop_users']->select('where group_shop_users.iduser='.$user_data['IdUser'].' order by group_shop.discount DESC');

		$arr_group_discount=webtsys_fetch_array($query);

		settype($arr_group_discount['group_shop_discount'], 'integer');

		$query=$model['group_shop']->select('where discount>'.$arr_group_discount['group_shop_discount'].' order by discount ASC limit 1', array('name', 'discount'));
		
		$arr_next_group_discount=webtsys_fetch_array($query);

		//Transport data..

		$query=$model['dir_transport']->select('where iduser='.$user_data['IdUser']);

		$arr_dir_transport=webtsys_fetch_array($query);
	
		settype($arr_dir_transport['IdDir_transport'], 'integer');
		settype($arr_dir_transport['country_transport'], 'integer');

		$query=$model['country_shop']->select('where IdCountry_shop='.$arr_dir_transport['country_transport'], array('name'), 1);

		list($arr_dir_transport['country_transport'])=webtsys_fetch_row($query);

		$arr_dir_transport['country_transport']=$model['country_shop']->components['name']->show_formatted($arr_dir_transport['country_transport']);
	
		//Show view
		
		echo load_view(array($arr_last_products, $total_bill, $arr_group_discount, $arr_next_group_discount, $arr_dir_transport), 'shop/profileshop');

	}

	$cont_index.=ob_get_contents();

	ob_end_clean();

	echo load_view(array($config_shop['title_shop'], $cont_index, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, ''), $arr_block);

}

?>