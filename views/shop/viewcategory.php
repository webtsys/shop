<?php

use PhangoApp\PhaLibs\ParentLinks;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaView\View;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaI18n\I18n;

function ViewCategoryView($idcat_product, $arr_cat, $arr_product, $arr_photo, $total_elements)
{

//global PhangoVar::$lang, ConfigShop::$config_shop, PhangoVar::$base_url, PhangoVar::$model;

/*$arr_hierarchy_links=hierarchy_links('cat_product', 'subcat', 'title', $idcat_product);

echo load_view(array($arr_hierarchy_links, 'shop', 'viewcategory', 'IdCat_product', array(), 0), 'common/utilities/hierarchy_links');*/

//($url, $model_name, $parentfield_name, $field_name, $idmodel, $last_link=0, $arr_parameters=[])

$url=Routes::make_simple_url('shop/viewcategory');

$parentlinks=new ParentLinks($url, 'cat_product', 'subcat', 'title', $idcat_product);

echo $parentlinks->show();

ob_start();

?>



<?php

View::$header[]=ob_get_contents();

ob_end_clean();

$title_category=$arr_cat['title'];
$num_news=ConfigShop::$config_shop['num_news'];

ob_start();
/*
?>
<form method="get" action="<?php echo make_fancy_url(PhangoVar::$base_url, 'shop', 'viewcategory', array(0, slugify($title_category))); ?>">
	<p>
	<?php

	echo I18n::lang('shop', 'select_category_shop', 'Seleccionar categoría').': '.SelectModelFormByOrder('IdCat_product', '', $idcat_product, 'cat_product', 'title', 'subcat', $where='');

	?>
	<input type="submit" value="<?php echo I18n::lang('shop', 'choose_category', 'Elegir categoría'); ?>"/>
	</p>
<form>
<?php
*/

$ob_get_search=ob_get_contents();

ob_end_clean();

//echo load_view(array($title_category, $arr_cat['description'].$ob_get_search), 'content');

//echo $search_product;

$z=count($arr_product);

?>
<h1><?php echo $arr_cat['title']; ?></h1>
<div class="last_news">
    <?php
    
    foreach($arr_product as $product)
    {
    
        //echo $product['date'].'<p>';
        ?>
        <div class="column">
        <?php
        
        echo View::load_view([$product, $arr_photo[$product['IdProduct']]], 'shop/productlist');
    
        ?>
        </div>
        <?php
    
    }
    ?>
</div>
<?php
/*
$z=0;

foreach($arr_product as $key_prod => $product)
{
	
	$idproduct=$product['IdProduct'];
	$title_product=I18nField::show_formatted($product['title']);

	
	$price_real=number_format($product['price'], 2);

	$price=MoneyField::currency_format($price_real);

	if($product['special_offer']>0)
	{

		$offer=$product['special_offer'];
		
		$price= '<strong>'.I18n::lang('shop', 'offer', 'Oferta').'</strong> <span style="text-decoration: line-through;">'.MoneyField::currency_format($price_real).' </span> -> '.MoneyField::currency_format($offer);

	}
	
	$stock=$product['stock'];
	$stock_txt='';
	
	$arr_stock[0]=I18n::lang('shop', 'no_stock', 'Sin stock');
	$arr_stock[1]=I18n::lang('shop', 'in_stock', 'En stock');

	if($product['about_order']==0)
	{

		$stock_txt=$arr_stock[$stock];

	}
	else
	{

		$stock_txt=I18n::lang('shop', 'served_on_request', 'Servido bajo pedido');

	}
	
	?>
		
	</div>
	<?php
	
		$z++;

	}
	
	*/
	
	if($z==0)
	{

		echo '<p>'.I18n::lang('shop', 'no_products_in_category', 'Todavía no hay ningún producto en esta categoría').'</p>';

	}
	else
	{

		
		$url_next=Routes::make_simple_url('shop/viewcategory', array($idcat_product, Utils::slugify($title_category)) );
		
		echo '<p style="clear:both;">'.I18n::lang('common', 'pages', 'Pages').': '.PhangoApp\PhaUtils\Pages::show( $_GET['begin_page'], $total_elements, $num_news, $url_next).'</p>';

	}

}

?>