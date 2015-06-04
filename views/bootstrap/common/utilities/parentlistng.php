<?php

function ParentListNgView($idfather, $arr_list_father, $id_ul='', $class_ul='', $name_ul='')
{

	echo '<ul id="'.$id_ul.'" class="'.$class_ul.'" name="'.$name_ul.'">'."\n";
	
	foreach($arr_list_father[$idfather] as $id => $arr_list)
	{
	
		echo '<li>'.$arr_list;
		
		if(isset($arr_list_father[$id]))
		{
		
			ParentListNgView($id, $arr_list_father);
		
		}
		
		echo '</li>'."\n";
	
	}
	
	echo '</ul>'."\n";

}

?>