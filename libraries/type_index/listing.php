<?php

global $arr_cache_jscript;

$cont_index='';

settype($_GET['IdCat_product'], 'integer');

//Load page...
load_lang('shop');
load_model('shop');
load_libraries(array('generate_admin_ng', 'forms/selectmodelformbyorder'));

function BuyOptionsListModel($url_options, $model_name, $id)
{

	global $lang;

	return array();

}

function BuyField($model_idmodel)
{

	global $model;
	
	settype($_SESSION['products'], 'array');

	settype($_SESSION['products'][$model_idmodel], 'integer');

	return TextForm('idproduct['.$model_idmodel.']', '', $_SESSION['products'][$model_idmodel]);

}

if($user_data['IdUser']>0)
{
	settype($_GET['IdCat_product'], 'integer');
	
	?>
	<form method="get" action="<?php echo make_fancy_url($base_url, 'shop', 'index', 'index', array()); ?>">
	<p><?php echo $lang['shop']['choose_cat']; ?>: <?php echo SelectModelFormByOrder('IdCat_product', '', $_GET['IdCat_product'], 'cat_product', 'title', 'subcat', $where=''); ?> <input type="submit" value="<?php echo $lang['shop']['choose_cat']; ?>"/></p>
	</form>
	<?php

	$where_sql='where idcat='.$_GET['IdCat_product'];
	$url_options=make_fancy_url($base_url, 'shop', 'index', 'buy_product', array('IdCat_product' => $_GET['IdCat_product']) );

	$model['product']->create_form();
	$model['product']->forms['title']->label=$lang['common']['title'];
	$model['product']->forms['referer']->label=$lang['shop']['referer'];

	list($where_sql, $arr_where_sql, $location, $arr_order)=SearchInField('product', array('title', 'referer'), $where_sql, $url_options, false);

	?>

	<form method="post" action="<?php echo make_fancy_url($base_url, 'shop', 'multiple_buy', 'buy_product', array() ); ?>" name="form_buy">
	<?php set_csrf_key(); ?>
	<p><input type="submit" value="<?php echo $lang['shop']['go_products_to_cart']; ?>" /></p>
	<?php

	if($_GET['IdCat_product']==0)
	{

		$where_sql='where 1=1';

	}

	if($arr_where_sql!='' && $_GET['IdCat_product']==0)
	{

		$where_sql.=' AND ';

	}

	$num_elements=25;
	
	$where_sql.=$arr_where_sql.' order by '.$location.$_GET['order_field'].' '.$arr_order[$_GET['order_desc']].' limit '.$_GET['begin_page'].', '.$num_elements;

	$total_elements=$model['product']->select_count($where_sql, 'IdProduct');

	$url_options_link=make_fancy_url($base_url, 'shop', 'index', 'pagination_shop', array('order_field' => $_GET['order_field'], 'order_desc' => $_GET['order_desc'], 'search_word' => $_GET['search_word'], 'search_field' => $_GET['search_field'], 'IdCat_product' => $_GET['IdCat_product']));

	$pages=pages( $_GET['begin_page'], $total_elements, $num_elements, $url_options_link, $initial_num_pages=20, $variable='begin_page', $label='', $func_jscript='send_results_shopping();');

	//Discounts

	$text_discounts='';
	$z=0;
	$discounts=0;

	$query=$model['group_shop_users']->select('where group_shop_users.iduser='.$user_data['IdUser']);
	
	while($arr_group=webtsys_fetch_array($query))
	{

		$division=100/$arr_group['group_shop_discount'];
		$discounts+=$division;//+=($total_sum/$division);
		$arr_group['group_shop_name']=$model['group_shop']->components['name']->show_formatted($arr_group['group_shop_name']);

		$text_discounts.=$arr_group['group_shop_name'].' ';
		$z++;

		echo '<p><strong>'.$lang['shop']['apply_discounts'].'</strong>: '.$arr_group['group_shop_name'].'</p>';
	}


	//Load products...

	$arr_products=array();

	$count_extra_options=0;
	
	up_table_config(array($lang['shop']['referer'], $lang['common']['name'], $lang['common']['image'], $lang['shop']['price'], $lang['shop']['buy_product_units']));

	$query=$model['product']->select($where_sql, array('IdProduct', 'referer', 'title', 'price', 'special_offer', 'extra_options'));

	while(list($idproduct, $referer, $title,  $price, $special_offer, $extra_options)=webtsys_fetch_row($query))
	{

		if($special_offer>0)
		{

			$price=$special_offer;

		}

		$price_text=MoneyField::currency_format($price);

		if($discounts>0)
		{

			$new_price=MoneyField::currency_format( ($price-($price/$discounts)) );

			$price_text='<span style="text-decoration: line-through;">'.MoneyField::currency_format($price).'</span> '.$new_price.'';

		}
		//$query=$model['image

		$image_p='';//'<img src="'.$base_url.'/shop/images/products/mini_'.$photo1.'" />';

		//middle_table_config(array($referer, $title, $image_p, $price_text.' &euro; '.$text_discounts, BuyField($idproduct)));

		$arr_products[$idproduct]=array($referer, $title, 'image_p' => $image_p, $price_text.' '.$text_discounts, BuyField($idproduct));

		if($extra_options!='')
		{

			$count_extra_options++;

		}

	}

	foreach($arr_products as $idproduct => $arr_product)
	{

		$query=$model['image_product']->select('where idproduct='.$idproduct.' and principal=1 limit 1', array('photo'));

		list($image_photo)=webtsys_fetch_row($query);

		$arr_product['image_p']='<img src="'.$model['image_product']->components['photo']->url_path.'/mini_'.$image_photo.'">';

		$arr_product[1]=$model['product']->components['title']->show_formatted($arr_product[1]);
		
		middle_table_config($arr_product);

	}

	down_table_config();

	if($count_extra_options>0)
	{

		echo '<p><span class="error">'.$lang['shop']['warning_products_with_options'].'</span></p>';

	}

	pages_table($pages);

	?>
	<br clear="left" />
	<p><input type="submit" value="<?php echo $lang['shop']['go_products_to_cart']; ?>" /></p>
	</form>
	<?php

	$arr_cache_jscript[]='jquery.min.js';

	?>
	<script language="javascript">
		//send_results_shopping();
		
		function send_results_shopping()
		{
			var x=0;
			
			var num_elements=document.forms['form_buy'].elements.length;
			arr_elements=new Object();
			
			for(x=1;x<num_elements-1;x++)
			{
				name_field=document.forms['form_buy'].elements[x].name;
				arr_elements[name_field]=document.forms['form_buy'].elements[x].value;
				
			}
			
			$.post("<?php echo make_fancy_url($base_url, 'shop', 'save_buy', 'recuperate_buying', array() ); ?>",  arr_elements, function(data) { return true; }, "text");

			return true;

		}
		
	</script>

	<?php
}
else
{

	die( header('Location: '.make_fancy_url($base_url, 'user', 'index', 'buy_product', array('register_page' => 'shop') ) ) );

}


?>
