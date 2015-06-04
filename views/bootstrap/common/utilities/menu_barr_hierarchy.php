<?php

function LinkHierarchyView($url_menu, $title_menu)
{

	echo '<a href="'.$url_menu.'">'.$title_menu.'</a>';

}

function NoLinkHierarchyView($title_menu)
{

	echo $title_menu;

}

function MenuHierarchyView($arr_final_menu)
{

	echo implode(' &gt;&gt; ', $arr_final_menu);

}


?>