<?php

function Hierarchy_Links_StandardView($arr_hierarchy, $url_fancy, $idfield, $arr_parameters=array(), $last_link=0, $name_home='Principal')
{
	$arr_hierarchy[0]['name']=$name_home;
	
	$arr_final=array();
	
	$c=count($arr_hierarchy)-1;
	
	for($x=0;$x<$c;$x++)
	{
	
		$arr_id=&$arr_hierarchy[$x];
	
		$arr_tmp_param=$arr_parameters;
		
		$arr_tmp_param[$idfield]=$arr_id['id'];
		
		//$arr_tmp_param[]=slugify($arr_id['name']);
	
		$arr_final[$x]='<a href="'.Routes::add_get_parameters($url_fancy, $arr_tmp_param).'">'.$arr_id['name'].'</a>';
	
	}
	
	switch($last_link)
	{
	
		default:
	
			$arr_final[$x]=$arr_hierarchy[$x]['name'];
		
		break;
		
		case 1:
		
			$arr_tmp_param=$arr_parameters;
		
			$arr_tmp_param[$idfield]=$arr_hierarchy[$x]['id'];
			
			$arr_tmp_param[]=Utils::slugify($arr_hierarchy[$x]['name']);
		
			$arr_final[$x]='<a href="'.Routes::add_get_parameters($url_fancy, $arr_tmp_param).'">'.$arr_hierarchy[$x]['name'].'</a>';
		
		break;
		
	}
	
	echo '<p>'.implode(' &gt; ', $arr_final);

}

?>