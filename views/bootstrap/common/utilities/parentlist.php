<?php

function ParentListView($model_name, $arr_cat, $arr_list_father, $idfather, $url_cat, $arr_perm=array())
{

	$idfield=Webmodel::$model[$model_name]->idmodel;
	
	$arr_hidden[0]='';
	$arr_hidden[1]='';
	
	settype($_GET[$idfield], 'integer');
	
	$end_ul='';
	
	echo '<div id="list_ul">';
	
	if($idfather==0)
	{
	
		$first_url[$_GET[$idfield]]='<ul><li><a href="'.$url_cat.'">'.I18n::lang('common', 'home', 'Home').'</a><ul>';
		$first_url[0]='<ul><li><strong>'.I18n::lang('common', 'home', 'Home').'</strong></li><ul>';
		
		echo $first_url[$_GET[$idfield]];
		
		$end_ul= '</ul></ul>';
	}
	
	settype($arr_list_father[$idfather], 'array');
	
	foreach($arr_list_father[$idfather] as $idcat)
	{
		
		settype($arr_perm[$idcat], 'integer');
		
		$url_blog=Routes::addGetParameters($url_cat, array($idfield => $idcat) );
		
		$arr_hidden[$arr_perm[$idcat]]='<span class="error">'.$arr_cat[$idcat].'</span>';
		$arr_hidden[0]='<a href="'.$url_blog.'">'.$arr_cat[$idcat].'</a>';
		$arr_hidden[1]='<span class="error">'.$arr_cat[$idcat].'</span>';
		
		$arr_url[$idcat]=$arr_hidden[$arr_perm[$idcat]];

		$arr_url[$_GET[$idfield]]=$arr_cat[$idcat];
		
		echo '<li id="cat_blog'.$idcat.'"><b>'.$arr_url[$idcat].'</b>'."\n";

		//Here the blogs from category..

		echo '</li>';
		echo '<ul>';
			if(isset($arr_list_father[$idcat]))
			{
				
				//recursive_list($model_name, $arr_cat, $arr_list_father, $idcat, $url_cat, $arr_perm);
				//echo load_view(array($model_name, $arr_cat, $arr_list_father, $idfather, $url_cat, $arr_perm=array()), 'parentlist');
				ParentListView($model_name, $arr_cat, $arr_list_father, $idfather, $url_cat, $arr_perm);
			}
		echo '</ul>';

	}
	
	echo $end_ul;
	
	echo '</div>';

}

?>