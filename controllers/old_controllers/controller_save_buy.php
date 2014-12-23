<?php
function Save_Buy()
{


	settype($_POST['idproduct'], 'array');

	foreach($_POST['idproduct'] as $id => $num_prods)
	{
		settype($id, 'integer');
		settype($num_prods, 'integer');

		$_SESSION['products'][$id]=$num_prods;

	}	

}

?>