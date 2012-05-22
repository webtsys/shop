<?php

function List_BlockView($arr_prod, $arr_image)
{
	global $lang, $base_url;

	foreach($arr_prod as $idprod => $data_prod)
	{

		?>
		<p align="center"><strong><?php echo $data_prod['title']; ?></strong></p>
		<p align="center"><img src="<?php echo $arr_image[$idprod]; ?>" /></p>
		<p align="center"><?php echo $data_prod['description']; ?></p>
		<p align="center"><strong><a href="<?php echo make_fancy_url($base_url, 'shop', 'viewproduct', $data_prod['title'], array('IdProduct' => $idprod)); ?>"><?php echo $lang['shop']['see_more']; ?></a></strong></p>
		<?php

	}

}

?>