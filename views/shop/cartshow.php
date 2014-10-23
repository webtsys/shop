<?php

function CartShowView($plugins, $arr_product_cart, $arr_price_base, $arr_price_base_total, $arr_price_filter, $yes_update)
{

	global $lang;

	$fields=array($lang['shop']['referer'], $lang['common']['name'], $lang['shop']['num_products']);

		foreach($plugins->arr_plugin_list as $plugin)
		{
		
			$fields[]=$this->arr_plugins[$plugin]->name_plugin;
		
		}
		
		$fields[]=$lang['shop']['total_price'];
		
		//$fields[]=$lang['shop']['select_product'];

		$total=0;
		
		up_table_config( $fields );
		
		$z=0;
	
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
		
			middle_table_config($arr_row);
		
			$z++;
		
		}
		
		if($z>0)
		{
			middle_table_config(array('', '', '', '<h2>'.MoneyField::currency_format($total).'</h2>'));
		}
		else
		{
		
			middle_table_config(array($lang['shop']['no_products_in_index']), array(' colspan='.count($fields)));
		
		}
		
		down_table_config();
	
		//Plugins for added values text.
		if($yes_update==1)
		{
		
			?>
			<p><input type="submit" value="<?php echo $lang['shop']['modify_products']; ?>"/></p>
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