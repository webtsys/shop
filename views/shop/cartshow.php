<?php

function CartShowView($plugins, $arr_product_cart, $arr_price_base, $arr_price_base_total, $arr_price_filter, $yes_update)
{

	global $lang, $base_url;

	$fields=array($lang['shop']['referer'], $lang['common']['name'], $lang['shop']['num_products']);

		foreach($plugins->arr_plugin_list as $plugin)
		{
		
			$fields[]=$this->arr_plugins[$plugin]->name_plugin;
		
		}
		
		$fields[]=$lang['shop']['total_price'];
		
		$fields[]=$lang['common']['options'];
		
		//$fields[]=$lang['shop']['select_product'];

		$total=0;
		$total_units=0;
		
		up_table_config( $fields );
	
		foreach($arr_product_cart as $arr_product)
		{
		
			$arr_product['product_title']=I18nField::show_formatted($arr_product['product_title']);
		
			$price_last=$arr_price_filter[$arr_product['IdCart_shop']];
			
			$price_base=$arr_product['units'].' x '.MoneyField::currency_format($arr_price_base[$arr_product['IdCart_shop']]).' = '.MoneyField::currency_format($arr_price_base_total[$arr_product['IdCart_shop']]);
			
			$arr_product['price_product_last_txt']=MoneyField::currency_format($price_last);
		
			$total+=$price_last;
		
			$form_num_products=show_text_form($arr_product['IdCart_shop'], $arr_product['units']);
		
			$arr_row=array($arr_product['product_referer'], $arr_product['product_title'], $form_num_products);
			
			foreach($plugins->arr_plugin_list as $plugin)
			{
			
				$fields[]=$this->arr_plugins[$plugin]->show_plugin_applied($arr_product);
			
			}
			
			$arr_row[]=$arr_product['price_product_last_txt'];
			
			$arr_row[]='<a href="'.make_fancy_url($base_url, 'shop', 'cart', 'deleteproductfromcart', array('action' => 'delete', 'IdCart_shop' => $arr_product['IdCart_shop'])).'">'.$lang['common']['delete'].'</a>';
		
			middle_table_config($arr_row);
		
			$total_units+=$arr_product['units'];
		
		}
		
		middle_table_config(array('', '', $total_units, '<h2>'.MoneyField::currency_format($total).'</h2>', ''));
	
		down_table_config();
	
		//Plugins for added values text.
		if($yes_update==1)
		{
		
			?>
			<p><input type="submit" value="<?php echo $lang['shop']['modify_products']; ?>"/> <input type="button" value="<?php echo $lang['shop']['checkout_order']; ?>" /> </p>
			</form>
			<?php
		
		}
		
		//Plugins showed here that is applied to the prices.
		
		foreach($plugins->arr_plugin_list as $plugin)
		{
		
			echo $this->arr_plugins[$plugin]->plugin_applied();
		
		}

}

?>