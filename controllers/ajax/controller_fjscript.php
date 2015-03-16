<?php

class FjscriptSwitchClass extends ControllerSwitchClass {

	function index()
	{

		//global $user_data, PhangoVar::$model, $ip, PhangoVar::$lang, $config_data, PhangoVar::$base_path, PhangoVar::$base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop;

		load_lang('shop');
		
		$original_base_url=PhangoVar::$base_url;
		
		load_libraries(array('config_shop', 'class_cart'), PhangoVar::$base_path.'modules/shop/libraries/');

		load_model('shop');

		$cart=new CartClass();

		$token=$cart->token;
		
		settype($_SERVER ['HTTP_REFERER'], 'string');

		if(!preg_match('/\/shop\//', $_SERVER ['HTTP_REFERER']))
		{
		
			PhangoVar::$base_url=$original_base_url;
		
		}
		
		?>
		
	function obtain_data_cart(mode)
	{

		
		jqxhr=$.ajax({
			url: "<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_ajax_obtaincart', array($token) ); ?>",
			type: "GET",
			dataType: "json",
			success: function(data){
				
				process_cart(data, mode);
			},
			error: function(data) {
			
				alert('Error obtaining products from cart');
			
			}
		});

	}

	function process_cart(data, mode)
	{
		
		if (mode == null)
		{
			mode = 0;
		}

		if(data['num_product']>0)
		{

			if(mode==0)
			{

				$("#cart_content").html('<?php echo PhangoVar::$lang['shop']['num_products']; ?>: '+data['num_product']+', <?php echo PhangoVar::$lang['shop']['price']; ?>: '+data['price_product']);

			}
			else
			{

				$("#cart_content_block").html('<?php echo PhangoVar::$lang['shop']['num_products']; ?>: '+data['num_product']+'<br /><?php echo PhangoVar::$lang['shop']['price']; ?>: '+data['price_product']);
				$("#cart_content_block_form").show();

			}

		}

	}

	function buy_product(idproduct)
	{
		
		//Check if is a product with options via ajax.
		//$('#sucess_buy_'+idproduct).html('<span class="error"><?php echo PhangoVar::$lang['shop']['success_buy']; ?></span>');

		$('#loading_buy_'+idproduct).fadeIn(1000);
		$('#buying_'+idproduct).fadeIn(1000);

		//Put gif in button.

		//$('.ship').html('<img src="<?php echo PhangoVar::$base_url; ?>/media/default/images/loading.gif" />');

		//Make other ajax for buying
		
		data_product=new Object;
		
		data_product['csrf_token']="<?php echo PhangoVar::$key_csrf; ?>";
		
		//data_product['IdProduct']=idproduct;
		
		$('#details').find(':input').each(function () {
			
			name=$(this).attr('name');
			
			data_product[name]=$(this).val();
			
			//alert(name);
			
		})
		
		$.ajax({
			url: "<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'cart_ajax_buy'); ?>/get/IdProduct/"+idproduct,
			type: "POST",
			data: data_product,
			dataType: "json",
			success: function(data){
				
				//Refresh cart if success...

				if(data['buy_return']==1)
				{

					//Update cart

					obtain_data_cart();

					if($('#cart_content_block')!=null)
					{

						obtain_data_cart(1);

					}
					
					$('#buying_'+idproduct).fadeOut(1000, function () {
					
						//$('#loading_buy_'+idproduct).fadeOut(2000);
						
						$('#sucess_buy_'+idproduct).fadeIn(1000, function () { 
						
						$('#sucess_buy_'+idproduct).fadeOut(1000);
						
						$('#loading_buy_'+idproduct).fadeOut(1000);
						
						} );

					});
					
					/*$('#sucess_buy_'+idproduct).fadeOut(1000);
					$('#sucess_buy_'+idproduct).fadeOut(1000);
					$('#loading_buy_'+idproduct).fadeOut(1000);*/

				}
				else
				{

					alert('<?php echo PhangoVar::$lang['shop']['error_buy_ajax']; ?> '+JSON.stringify(data));

					$('#sucess_buy_'+idproduct).html('<span class="error"><?php echo PhangoVar::$lang['shop']['error_buy_ajax']; ?></span>');

				}
				
				/*$('#sucess_buy_'+idproduct).fadeIn(500);

				$('#loading_buy_'+idproduct).fadeOut(2000);

				$('#sucess_buy_'+idproduct).delay(2000).fadeOut(500);*/

			},
			
			error: function(data){
			
				alert('<?php echo PhangoVar::$lang['shop']['error_buy_ajax']; ?> '+JSON.stringify(data));

				$('#sucess_buy_'+idproduct).html('<span class="error"><?php echo PhangoVar::$lang['shop']['error_buy_ajax']; ?></span>');
			
			},
		});
		
		
	}

	function return_https()
	{

		return "<?php echo PhangoVar::$base_url; ?>";

	}

		<?php

	}

}
	
?>
