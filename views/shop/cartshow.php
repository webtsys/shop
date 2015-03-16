<?php

function CartShowView($plugins, $arr_product_cart, $arr_price_base, $arr_price_base_total, $arr_price_filter, $yes_update, $method_text_form)
{

	
	ob_start();
	
	?>
	<script language="javascript">
	
	$(document).ready( function () {
	
		$('#checkout_order').click( function() {
		
			location.href='<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_get_address'); ?>';
		
		});
		
	});
	
	</script>
	<?php
	
	PhangoVar::$arr_cache_header[]=ob_get_contents();
	
	ob_end_clean();
	
	//Products plugin used in this view.
	
	$plugins_product=new PreparePluginClass('product');
		
	$plugins_product->obtain_list_plugins();

	$plugins_product->load_all_plugins();
	

	$fields=array(PhangoVar::$lang['shop']['referer'], PhangoVar::$lang['common']['name'], PhangoVar::$lang['shop']['num_products']);
	
	/*if(count($arr_product_cart['details'])>0)
	{*/
	
		$fields[]=PhangoVar::$lang['shop']['details'];
	
	//}

	foreach($plugins->arr_plugin_list as $plugin)
	{
	
		$fields[]=$this->arr_plugins[$plugin]->name_plugin;
	
	}
	
	//here the plugins applied to this shit.
	
	$fields[]=PhangoVar::$lang['shop']['total_price'];
	
	$set_options_func='no_set_options';
	
	/*if($yes_update==1)
	{
		$fields[]=PhangoVar::$lang['common']['options'];
		
		$set_options_func='set_options';
		
	}*/
	
	//$fields[]=PhangoVar::$lang['shop']['select_product'];

	$total=0;
	$total_units=0;
	
	up_table_config( $fields );
	
	$z=0;

	foreach($arr_product_cart as $arr_product)
	{
	
		$arr_product['product_title']=I18nField::show_formatted($arr_product['product_title']);
	
		$price_last=$arr_price_filter[$arr_product['IdCart_shop']];
		
		$price_base=$arr_product['units'].' x '.MoneyField::currency_format($arr_price_base[$arr_product['IdCart_shop']]).' = '.MoneyField::currency_format($arr_price_base_total[$arr_product['IdCart_shop']]);
		
		$arr_product['price_product_last_txt']=MoneyField::currency_format($price_last);
	
		$total+=$price_last;
	
		$form_num_products=$method_text_form($arr_product['IdCart_shop'], $arr_product['units']);
	
		$arr_row=array($arr_product['product_referer'], $arr_product['product_title'], $form_num_products);
		
		foreach($plugins->arr_plugin_list as $plugin)
		{
		
			$fields[]=$this->arr_plugins[$plugin]->show_plugin_applied($arr_product);
		
		}
		
		//Here the plugins applied to products.
		
		//a:1:{s:14:"characteristic";a:2:{s:19:"characteristic_name";a:1:{i:0;a:1:{i:0;s:14:"Número de pie";}}s:21:"characteristic_option";a:1:{i:0;a:1:{i:0;s:2:"35";}}}} 
		
		$arr_details=unserialize($arr_product['details']);
		
		if(count($arr_details)>0)
		{
		
			$arr_row[]=$arr_details['characteristic']['characteristic_name'][0][0].': '.$arr_details['characteristic']['characteristic_option'][0][0];
			
		}
		else
		{
		
			$arr_row[]='';
		
		}
		
		$arr_row[]=$arr_product['price_product_last_txt'];
		
		$set_options_func($arr_product, $arr_row);
	
		middle_table_config($arr_row);
		
		$total_units+=$arr_product['units'];
	
		$z++;
	
	}
	
	$text_submit='';
	
	if($z>0)
	{
		middle_table_config(array('', '', '', '', '<h2>'.MoneyField::currency_format($total).'</h2>'));
		
		$text_submit='<input type="submit" value="'.PhangoVar::$lang['shop']['modify_products'].'"/> <input type="button" value="'.PhangoVar::$lang['shop']['checkout_order'].'" id="checkout_order" />';
	}
	else
	{
	
		middle_table_config(array(PhangoVar::$lang['shop']['no_products_in_index']), array(' colspan='.count($fields)));
	
	}

	down_table_config();

	//Plugins for added values text.
	if($yes_update==1)
	{
	
		?>
		<p><?php echo $text_submit; ?></p>
		</form>
		<?php
	
	}
	
	//Plugins showed here that is applied to the prices.
	
	foreach($plugins->arr_plugin_list as $plugin)
	{
	
		echo $this->arr_plugins[$plugin]->plugin_applied();
	
	}

}

function set_options($arr_product, $arr_options)
{


	$arr_options[]= '<a href="'.make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'deleteproductfromcart', array('action' => 'delete', 'IdCart_shop' => $arr_product['IdCart_shop'])).'">'.PhangoVar::$lang['common']['delete'].'</a>';
	
	return $arr_options;

}

function no_set_options($arr_product, $arr_options)
{

	return $arr_options;

}

?>