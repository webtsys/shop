<?php
function Functions_Jscript()
{

	global $user_data, $model, $ip, $lang, $config_data, $base_path, $base_url, $cookie_path, $arr_block, $prefix_key, $block_title, $block_content, $block_urls, $block_type, $block_id, $config_data, $config_shop;

	load_lang('shop');
	
	$original_base_url=$base_url;
	
	load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');

	load_model('shop');

	settype($_COOKIE['webtsys_shop'], 'string');

	$token=$_COOKIE['webtsys_shop'];

	if(!preg_match('/\/shop\//', $_SERVER ['HTTP_REFERER']))
	{
	
		$base_url=$original_base_url;
	
	}
	
	?>
	
function obtain_data_cart(mode)
{

	
	jqxhr=$.ajax({
		url: "<?php echo make_fancy_url($base_url, 'shop/ajax', 'obtaincart', 'obtaincart', array('token' => $token) ); ?>",
		type: "GET",
		dataType: "json",
		success: function(data){
			
			process_cart(data, mode);
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

			$("#cart_content").html('<?php echo $lang['shop']['num_products']; ?>: '+data['num_product']+', <?php echo $lang['shop']['price']; ?>: '+data['price_product']);

		}
		else
		{

			$("#cart_content_block").html('<?php echo $lang['shop']['num_products']; ?>: '+data['num_product']+'<br /><?php echo $lang['shop']['price']; ?>: '+data['price_product']);
			$("#cart_content_block_form").show();

		}

	}

}

function buy_product(idproduct)
{

	//Check if is a product with options via ajax.
	//$('#sucess_buy_'+idproduct).html('<span class="error"><?php echo $lang['shop']['success_buy']; ?></span>');

	$('#loading_buy_'+idproduct).fadeIn(1000);
	$('#buying_'+idproduct).fadeIn(1000);

	$.ajax({
		url: "<?php echo make_fancy_url($base_url, 'shop/ajax', 'checkoptionsproduct', 'checkoptionsproduct', array() ); ?>",
		type: "GET",
		data: "IdProduct="+idproduct,
		dataType: "json",
		success: function(data){

			if(data['check_options_product']==0)
			{

				//Put gif in button.

				//$('.ship').html('<img src="<?php echo $base_url; ?>/media/default/images/loading.gif" />');

				//Make other ajax for buying

				$.ajax({
					url: "<?php echo make_fancy_url($base_url, 'shop/ajax', 'buyproduct', 'buyproduct' ); ?>",
					type: "GET",
					data: "IdProduct="+idproduct,
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

							alert('<?php echo $lang['shop']['error_buy_ajax']; ?>');

							$('#sucess_buy_'+idproduct).html('<span class="error"><?php echo $lang['shop']['error_buy_ajax']; ?></span>');

						}
						
						/*$('#sucess_buy_'+idproduct).fadeIn(500);

						$('#loading_buy_'+idproduct).fadeOut(2000);

						$('#sucess_buy_'+idproduct).delay(2000).fadeOut(500);*/

					},
					
					error: function(data){
					
						alert('<?php echo $lang['shop']['error_buy_ajax']; ?> '+JSON.stringify(data));

						$('#sucess_buy_'+idproduct).html('<span class="error"><?php echo $lang['shop']['error_buy_ajax']; ?></span>');
					
					},
				});


			}
			else
			{

				redirect_url='<?php echo make_fancy_url($base_url, 'shop', 'buy', 'buy_product' , array('IdProduct' => 'idproduct')); ?>';

				redirect_url=redirect_url.replace('idproduct', idproduct);

				location.href=redirect_url;

			}

		}

	

	});
	
	
}

function return_https()
{

	return "<?php echo $base_url; ?>";

}

	<?php

}

?>
