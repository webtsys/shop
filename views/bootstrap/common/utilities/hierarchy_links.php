<?php

function Hierarchy_LinksView($arr_hierarchy, $folder_url, $ident_url, $idfield, $arr_parameters=array(), $last_link=0)
{
	
	
	$arr_final=array();
	
	$c=count($arr_hierarchy)-1;
	
	for($x=0;$x<$c;$x++)
	{
	
		$arr_id=&$arr_hierarchy[$x];
	
		$arr_tmp_param=$arr_parameters;
		
		$arr_tmp_param[$idfield]=$arr_id['id'];
		
		$arr_tmp_param[]=slugify($arr_id['name']);
	
		$arr_final[$x]='<a href="'.make_fancy_url(PhangoVar::$base_url, $folder_url, $ident_url, $arr_tmp_param).'">'.$arr_id['name'].'</a>';
	
	}
	
	switch($last_link)
	{
	
		default:
	
			$arr_final[$x]=$arr_hierarchy[$x]['name'];
		
		break;
		
		case 1:
		
			$arr_tmp_param=$arr_parameters;
		
			$arr_tmp_param[$idfield]=$arr_hierarchy[$x]['id'];
			
			$arr_tmp_param[]=slugify($arr_hierarchy[$x]['name']);
		
			$arr_final[$x]='<a href="'.make_fancy_url(PhangoVar::$base_url, $folder_url, $ident_url, $arr_tmp_param).'">'.$arr_hierarchy[$x]['name'].'</a>';
		
		break;
		
	}
	
	echo '<p>'.implode(' &gt; ', $arr_final).'</p>';

}

?>