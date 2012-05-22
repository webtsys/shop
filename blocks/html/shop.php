<?php

global $base_url, $lang, $model, $arr_taxes, $lang_taxes, $config_shop;

load_lang('shop');
load_model('shop');
load_libraries(array('config_shop'), $base_path.'modules/shop/libraries/');

?>
<script language="Javascript">
	obtain_data_cart(1);
</script>
<div id="cart_content_block"><?php echo $lang['shop']['empty_cart']; ?></div>
<p>
	<form action="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'cart', array()); ?>" name="cart_content_block_form" id="cart_content_block_form" style="display:none;">
		<?php set_csrf_key(); ?>
		<input type="submit" value="<?php echo $lang['shop']['buy']; ?>" />
	</form>
</p>
<?php



/*
if(!isset($_COOKIE['webtsys_shop']))
{

	echo $lang['shop']['empty_cart'];

}
else
{
	
	$arr_id=array(0);

	$query=webtsys_query('select idproduct from cart_shop where token="'.sha1($_COOKIE['webtsys_shop']).'"');
	
	while(list($idproduct)=webtsys_fetch_row($query))
	{
		
		settype($arr_id[$idproduct], 'integer');

		$arr_id[$idproduct]++;

	}
	
	$total_sum=0;

	$idtax=$config_shop['idtax'];

	$query=$model['product']->select('where IdProduct IN ('.implode(',', array_keys($arr_id) ).')', array('IdProduct', 'title', 'price', 'special_offer'), 1);
	
	while( list($idproduct, $title, $price, $offer)=webtsys_fetch_row($query) )
	{
		

		if($offer>0)
		{

			$price=$offer;

		}

		$sum_tax=calculate_taxes($idtax, $price);

		$text_taxes=add_text_taxes($idtax);

		$price+=$sum_tax;
		$price*=$arr_id[$idproduct];
		
		$title=$model['product']->components['title']->show_formatted($title);
		
		$title.=' ';
		
		echo '<a href="'.make_fancy_url($base_url, 'shop', 'viewproduct', $title, array('IdProduct' => $idproduct) ).'"><strong>'.$arr_id[$idproduct].' x '.substr($title, 0,  10).'[..]'."</strong></a> - ".MoneyField::currency_format($price)."<br />\n";


		$total_sum+=$price;
		

	}

	$total_sum=MoneyField::currency_format($total_sum);

	if($total_sum>0)
	{

		echo '<p><strong>'.$lang['shop']['total'].': </strong>'.$total_sum.'<br />'.$text_taxes.'</p>';
		?>
		<p><form action="<?php echo make_fancy_url($base_url, 'shop', 'cart', 'cart', array()); ?>"><?php set_csrf_key(); ?><input type="submit" value="<?php echo $lang['shop']['buy']; ?>" /></form></p>
		<?php

	}
	else
	{

		echo $lang['shop']['empty_cart'];

	}

}
*/
?>
