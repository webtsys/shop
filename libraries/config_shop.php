<?php

//Load shop config...

//global $arr_currency, $arr_change_currency;

Webmodel::load_model('shop');

//$query=Webmodel::$model['config_shop']->select('', array(), 1);

ConfigShop::$config_shop=Webmodel::$model['config_shop']->select_a_row_where('');

ConfigShop::$config_shop['title_shop']=Webmodel::$model['config_shop']->components['title_shop']->show_formatted(ConfigShop::$config_shop['title_shop']);
ConfigShop::$config_shop['conditions']=Webmodel::$model['config_shop']->components['conditions']->show_formatted(ConfigShop::$config_shop['conditions']);

//Prepare cookie_shop token

/*if(!isset($_COOKIE['webtsys_shop']))
{

	$token=sha1(uniqid(rand(), true));

	setcookie  ( 'webtsys_shop', $token, 0, $cookie_path);

}*/

/*if(ConfigShop::$config_shop['ssl_url']==1)
{
	
	$base_url=str_replace('http://', 'https://', $base_url);

}*/

//Load taxes
/*
$arr_taxes[0]=0;
$lang_taxes[0]='';
$arr_currency=array();
$arr_change_currency=array();

$query=Webmodel::$model['taxes']->select('', array(Webmodel::$model['taxes']->idmodel, 'name', 'percent') );

while(list($idtaxes, $name, $percent)=webtsys_fetch_row($query))
{

	$arr_taxes[$idtaxes]=$percent;
	$lang_taxes[$idtaxes]=$name;

}
*/
//Load currencies...

$query=Webmodel::$model['currency']->select('', array(Webmodel::$model['currency']->idmodel, 'symbol') );

while(list($idcurrency, $symbol_currency)=Webmodel::$model['currency']->fetch_row($query))
{

	ConfigShop::$arr_currency[$idcurrency]=$symbol_currency;

}

$query=Webmodel::$model['currency_change']->select('', array('idcurrency', 'idcurrency_related', 'change_value') , 1);

while(list($idcurrency, $idcurrency_related, $change_value)=Webmodel::$model['currency_change']->fetch_row($query))
{

	ConfigShop::$arr_change_currency[$idcurrency][$idcurrency_related]=$change_value;

}

settype($_SESSION['idcurrency'], 'integer');

if(!isset(ConfigShop::$arr_currency[$_SESSION['idcurrency']]))
{

	$_SESSION['idcurrency']=ConfigShop::$config_shop['idcurrency'];

}

//Taxes functions...

//ConfigShop::$config_shop['yes_taxes']==1 && 

/*$arr_func_taxes['add_taxes'][0]='no_add_taxes';
$arr_func_taxes['add_taxes'][1]='add_taxes';

$arr_func_taxes['add_taxes'][0]='no_add_taxes';
$arr_func_taxes['add_taxes'][1]='add_taxes';*/
/*
if(ConfigShop::$config_shop['idtax']==0)
{

	ConfigShop::$config_shop['yes_taxes']=0;

}

function yes_add_taxes($idtaxes)
{

	global $arr_taxes;

	return $arr_taxes[$idtaxes];

}

function yes_add_text_taxes($idtaxes)
{

	global $lang_taxes, $lang, $arr_taxes;

	return $lang_taxes[$idtaxes].' '.$arr_taxes[$idtaxes].'% '.$lang['shop']['included'];

}

function yes_calculate_taxes($idtax, $price)
{

	global $arr_taxes;
	
	$add_tax=$price*($arr_taxes[$idtax]/100);

	return $add_tax;

}


function yes_add_text_taxes_final($price, $idtax)
{

	global $lang, $arr_taxes;
	
	if($idtax>0)
	{

		$sum_tax=calculate_taxes($idtax, $price);

		$price+=$sum_tax;

		$text_taxes=add_text_taxes($idtax, $price);

		$text_total_price='<strong>'.$lang['shop']['total_price_with_taxes'].'</strong>: '.MoneyField::currency_format($price);

		return array($text_total_price, $text_taxes, $sum_tax);

	}

	return array($lang['shop']['the_country_selected_is_different_to_default_dont_have_taxes'], '', 0);

}

function yes_name_field_taxes($fields)
{

	global $lang;

	$fields[]=$lang['shop']['taxes'];
	$fields[]=$lang['shop']['price_with_taxes'];

	return $fields;

}

function yes_add_field_taxes($fields, $price, $idtax, $sum_tax)
{

	global $lang_taxes, $arr_taxes;

	$fields[]=$lang_taxes[$idtax].' '.$arr_taxes[$idtax].'%<br />'.MoneyField::currency_format($sum_tax);
	$fields[]=MoneyField::currency_format($price);

	return $fields;

}

//if(ConfigShop::$config_shop['yes_taxes']==0)
	
//Function used when yes_taxes=false

function no_calculate_taxes($idtax, $price)
{

	return 0;

}

function no_add_taxes($idtaxes)
{

	return 0;

}

function no_add_text_taxes($idtaxes)
{
	
	global $lang_taxes, $lang, ConfigShop::$config_shop;

	if(ConfigShop::$config_shop['idtax']>0)
	{

		return $lang['shop']['taxes_no_included'];

	}

}

function no_add_text_taxes_final($price, $idtax)
{

	global ConfigShop::$config_shop, $lang;

	if(ConfigShop::$config_shop['idtax']>0)
	{
		
		//return $lang['shop']['taxes_no_included'];
		return array($lang['shop']['taxes_no_included'], '', 0);

	}

}

function no_name_field_taxes($fields)
{

	return $fields;

}

function no_add_field_taxes($fields, $price, $idtax, $sum_tax)
{


	return $fields;

}

//Funcitons for calculate taxes automatically.

function calculate_taxes($idtax, $price)
{
	global ConfigShop::$config_shop;

	$func_calculate_taxes[0]='no_calculate_taxes';
	$func_calculate_taxes[1]='yes_calculate_taxes';

	return $func_calculate_taxes[ConfigShop::$config_shop['yes_taxes']]($idtax, $price);

}

function add_taxes($idtaxes)
{
	
	global ConfigShop::$config_shop;

	$func_add_taxes[0]='no_add_taxes';
	$func_add_taxes[1]='yes_add_taxes';

	return $func_add_taxes[ConfigShop::$config_shop['yes_taxes']]($idtaxes);

}

function add_text_taxes($idtaxes)
{
	
	global ConfigShop::$config_shop;

	$func_add_text_taxes[0]='no_add_text_taxes';
	$func_add_text_taxes[1]='yes_add_text_taxes';

	return $func_add_text_taxes[ConfigShop::$config_shop['yes_taxes']]($idtaxes);

}

function add_text_taxes_final($price, $idtax)
{

	global ConfigShop::$config_shop;

	$func_add_text_taxes_final[0]='no_add_text_taxes_final';
	$func_add_text_taxes_final[1]='yes_add_text_taxes_final';

	return $func_add_text_taxes_final[ConfigShop::$config_shop['yes_taxes']]($price, $idtax);

}

function name_field_taxes($fields)
{

	global ConfigShop::$config_shop;

	$func_name_field_taxes[0]='no_name_field_taxes';
	$func_name_field_taxes[1]='yes_name_field_taxes';

	return $func_name_field_taxes[ConfigShop::$config_shop['yes_taxes']]($fields);

}

function add_field_taxes($fields, $price, $idtax, $sum_tax)
{

	global ConfigShop::$config_shop;

	$func_add_field_taxes[0]='no_add_field_taxes';
	$func_add_field_taxes[1]='yes_add_field_taxes';

	return $func_add_field_taxes[ConfigShop::$config_shop['yes_taxes']]($fields, $price, $idtax, $sum_tax);

}


//Apply discounts...

function apply_discount($group_shop_discount, $total_sum)
{
	
	$discounts=obtain_discount($group_shop_discount, $total_sum);//($total_sum/$division);

	$total_sum-=$discounts;

	return $total_sum;

}

function obtain_discount($group_shop_discount, $total_sum)
{

	$discounts=0;
	
	if($group_shop_discount>0)
	{

		$division=100/$group_shop_discount;
		
		$discounts=($total_sum/$division);

	}

	return $discounts;

}

function calculate_raw_taxes($percent_tax, $price)
{

	global $arr_taxes;

	$add_tax=$price*($percent_tax/100);

	return $add_tax;

}

function calculate_num_bill($idorder_shop)
{

	global ConfigShop::$config_shop;

	settype($idorder_shop, 'string');

	$num_elements_num_bill=strlen($idorder_shop);
	$num_bill_tmp='';

	if($num_elements_num_bill<ConfigShop::$config_shop['elements_num_bill'])
	{

		$count_elements_num_bill=ConfigShop::$config_shop['elements_num_bill']-$num_elements_num_bill;

		for($x=0;$x<$count_elements_num_bill;$x++)
		{

			$num_bill_tmp.='0';

		}

	}

	$num_bill_tmp.=$idorder_shop;

	$num_bill=ConfigShop::$config_shop['head_bill'].$num_bill_tmp;

	return $num_bill;

}

function add_cart($arr_details=array(), $price=0, $special_offer=0, $redirect=1)
{

	global PhangoVar::$model, $base_path, $base_url, $arr_block, $cookie_path, $lang;
	
	settype($_POST['IdCart_shop'], 'integer');

	$redirect_url=make_fancy_url($base_url, 'shop', 'cart', 'add_cart', array() );
	
	settype($_COOKIE['webtsys_shop'], 'string');

	$token=$_COOKIE['webtsys_shop'];

	$query=Webmodel::$model['cart_shop']->select('where token="'.sha1($token).'"');

	$arr_cart=webtsys_fetch_array($query);

	settype($arr_cart['IdCart_shop'], 'integer');

	if($arr_cart['IdCart_shop']==0)
	{

		$token=sha1(uniqid(rand(), true));

		setcookie  ( 'webtsys_shop', $token, 0, $cookie_path);

	}

	if($special_offer>0)
	{

		$price=$special_offer;
	
	}
	
	if($_POST['IdCart_shop']>0 && Webmodel::$model['cart_shop']->select_count('where cart_shop.IdCart_shop='.$_POST['IdCart_shop'], 'IdCart_shop'))
	{
		
		if(!Webmodel::$model['cart_shop']->update( array('details' => $arr_details, 'time' => time(), 'price_product' => $price) , 'where token = "'.sha1($token).'" and IdCart_shop='.$_POST['IdCart_shop'].'  and idproduct ='. $_GET['IdProduct']))
		{

			return 0;

		}
		

	}
	else
	{
		
		if(!Webmodel::$model['cart_shop']->insert( array('token' => sha1($token), 'idproduct' => $_GET['IdProduct'], 'details' => $arr_details, 'time' => time(), 'price_product' => $price) ))
		{

			return 0;

		}

	}
	
	if($redirect==1)
	{

		ob_end_clean();
		
		load_libraries(array('redirect'));
		die( redirect_webtsys( $redirect_url, $lang['common']['redirect'], $lang['common']['success'], $lang['common']['press_here_redirecting'] , $arr_block) );

	}
	else
	{

		return 1;

	}

}
*/

?>