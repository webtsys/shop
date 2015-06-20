<?php

function SearchFormView($arr_search_field, $arr_order_field, $arr_order_select, $url_options)
{

	$form_search='<form method="get" action="'.Routes::add_get_parameters( $url_options, array() ).'">';
	$form_search.=Utils::set_csrf_key();
	$form_search.=I18n::lang('common', 'order_by', 'Order by').': '.SelectForm('order_field', '', $arr_order_field)
	.' '.I18n::lang('common', 'in_order', 'By order').': '.SelectForm('order_desc', '', $arr_order_select);

	$arr_order_field[0]=$_GET['search_field'];

	$form_search.='<p>'.I18n::lang('common', 'search', 'Search').': '.TextForm('search_word', '', $_GET['search_word']).' '
	.I18n::lang('common', 'search_by', 'Search by')
	.': '.SelectForm('search_field', '', $arr_search_field).'</p><p><input type="submit" value="'.
	I18n::lang('common', 'send', 'Send').
	'"/> <input type="button" value="'.I18n::lang('common', 'reset', 'Reset')
	.'" onclick="javascript:location.href=\''.$url_options.'\'"/>';

	$form_search.='</form></p>';
	
	echo View::load_view(array(I18n::lang('common', 'order_and_search', 'Order and search'), $form_search), 'content');

}

?>