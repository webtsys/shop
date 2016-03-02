<?php

use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaRouter\Controller;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaView\View;

function CartShowView($plugins, $arr_product_cart, $arr_price_base, $arr_price_base_total, $arr_price_filter, $yes_update, $method_text_form)
{

	
	ob_start();
	
	?>
	<script language="javascript">
	
	$(document).ready( function () {
	
		$('#checkout_order').click( function() {
		
			location.href='<?php echo Routes::make_simple_url('shop/cart/get_address'); ?>';
		
		});
		
	});
	
	</script>
	<?php
	
	View::$header[]=ob_get_contents();
	
	ob_end_clean();
	
	//Products plugin used in this view.
	
	$plugins_product=new PreparePluginClass('product');
		
	$plugins_product->obtain_list_plugins();

	$plugins_product->load_all_plugins();
	

	$fields=array(I18n::lang('shop', 'referer', 'Referencia'), I18n::lang('common', 'name', 'name'), I18n::lang('shop', 'num_products', 'Unidades'));
	
	/*if(count($arr_product_cart['details'])>0)
	{*/
	
		$fields[]=I18n::lang('shop', 'details', 'details');
	
	//}

	foreach($plugins->arr_plugin_list as $plugin)
	{
	
		$fields[]=$this->arr_plugins[$plugin]->name_plugin;
	
	}
	
	//here the plugins applied to this shit.
	
	$fields[]=I18n::lang('shop', 'total_price', 'Precio total');
	
	$set_options_func='no_set_options';
	
	/*if($yes_update==1)
	{
		$fields[]=I18n::lang('common', 'options', 'Options');
		
		$set_options_func='set_options';
		
	}*/
	
	//$fields[]=I18n::lang('shop', 'select_product', 'Seleccionar producto');

	$total=0;
	$total_units=0;
	
	//up_table_config( $fields );
	
	echo View::load_view([$fields], 'common/tables/headtable');
	
	$z=0;

	foreach($arr_product_cart as $arr_product)
	{
	
		$arr_product['product_title']=PhangoApp\PhaModels\CoreFields\I18nField::show_formatted($arr_product['product_title']);
	
		$price_last=$arr_price_filter[$arr_product['IdCart_shop']];
		
		$price_base=$arr_product['units'].' x '.ShopMoneyField::currency_format($arr_price_base[$arr_product['IdCart_shop']]).' = '.ShopMoneyField::currency_format($arr_price_base_total[$arr_product['IdCart_shop']]);
		
		$arr_product['price_product_last_txt']=ShopMoneyField::currency_format($price_last);
	
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
	
		//middle_table_config($arr_row);
		
		echo View::load_view([$arr_row], 'common/tables/middletable');
		
		$total_units+=$arr_product['units'];
	
		$z++;
	
	}
	
	$text_submit='';
	
	if($z>0)
	{
		//middle_table_config(array('', '', '', '', '<h2>'.MoneyField::currency_format($total).'</h2>'));
		
		echo View::load_view([array('', '', '', '', '<h2>'.ShopMoneyField::currency_format($total).'</h2>')], 'common/tables/middletable');
		
		$text_submit='<input type="submit" value="'.I18n::lang('shop', 'modify_products', 'Modificar productos').'"/> <input type="button" value="'.I18n::lang('shop', 'checkout_order', 'Pagar pedido').'" id="checkout_order" />';
	}
	else
	{
	
        echo View::load_view([array(I18n::lang('shop', 'no_products_in_index', 'No hay productos para mostrar')), array(' colspan='.count($fields))], 'common/tables/middletable');
	
		//middle_table_config(array(I18n::lang('shop', 'no_products_in_index', 'No hay productos para mostrar')), array(' colspan='.count($fields)));
	
	}

	echo View::load_view([], 'common/tables/bottomtable');

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


	$arr_options[]= '<a href="'.make_fancy_url(PhangoVar::$base_url, 'shop', 'cart', 'deleteproductfromcart', array('action' => 'delete', 'IdCart_shop' => $arr_product['IdCart_shop'])).'">'.I18n::lang('common', 'delete', 'Delete').'</a>';
	
	return $arr_options;

}

function no_set_options($arr_product, $arr_options)
{

	return $arr_options;

}

?>