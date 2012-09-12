<?php

global $base_url, $lang;

load_libraries(array('i18n_fields'));

$query=webtsys_query('select IdCat_product, title, subcat from cat_product order by subcat ASC');

$arr_cat=array();

$arr_cat[0]=array();
//http://localhost/phangofm/index.php/shop/show/viewcategory/viewcategory/?IdCat_product=1&csrf_token=bc24ffaf6dd55be07423bf37bdc24d65d5f7b275_e07e5858a717530561f6fd8f120ddb8da70024fc&order_field=title&order_desc=0&search_word=pepe&search_field=title
?>
<?php

while(list($idcat, $title, $subcat)=webtsys_fetch_row($query))
{

	$arr_cat[$subcat][$idcat]=I18nField::show_formatted($title);

}
?>
<form method="get" action="<?php echo make_fancy_url($base_url, 'shop', 'viewcategory', 'viewcategory'); ?>">
<strong><?php echo $lang['common']['category']; ?></strong>
<?php set_csrf_key(); ?>
<br />
<select name="IdCat_product">
<?php

foreach($arr_cat[0] as $idcat => $value)
{

	echo "<option value=\"$idcat\">".$value."</option>";
	$c=count(@$arr_cat[$idcat]);
	if($c>0)
	{
		?>
		
		<?php
		foreach($arr_cat[$idcat] as $sub_idcat => $sub_value)
		{

			echo "<option value=\"$sub_idcat\">&nbsp;&nbsp;&nbsp;".$sub_value."</option>";

		}
		?>
		
		<?php
	}
	$z++;
}

?>

</select>
<br />
<strong><?php echo $lang['common']['search']; ?></strong> <br />
<input type="text" name="search_word" size="15"/>
<input type="hidden" name="search_field" value="title" />
<input type="hidden" name="order_field" value="title" />
<input type="hidden" name="order_desc" value="0" />
<br />
<input type="submit" value="Buscar" />
</form>
