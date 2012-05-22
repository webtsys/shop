<?php

//Obtenemos array de producto...

settype($_GET['idcart_shop'], 'integer');

$query=$model['cart_shop']->select('where IdCart_shop='.$_GET['idcart_shop'], array('details'));

$arr_product=webtsys_fetch_array($query);

$product_data=unserialize($arr_product['details']);
print_r($product_data);
switch($_GET['action'])
{

default:

	?>
	<form method="post" action="buy.php?IdProduct=<?php echo $_GET['IdProduct']; ?>&amp;action=1">
	<p>Nombre de dominio (Extensiones v&aacute;lidas: .com .net. org): <input type="text" name="ident" value="<?php echo $product_data['ident']; ?>" /><input type="hidden" name="idcart_shop" value="<?php echo $_GET['idcart_shop']; ?>" /></p>
	<p>Ver si dominio est&aacute; disponible: <input type="submit" value="Ver"/></p>
	</form>
	
	<?php

break;

case 1:

	$_POST['ident']=@form_text($_POST['ident']);
	settype($_POST['idcart_shop'], 'integer');

	//Hacer una funcion que se llame validate domain.
	
	if($_POST['ident']!='')
	{

		add_cart( array( 'ident' => $_POST['ident'] ) , $price, $special_offer);

	}
	else
	{

		echo '<p>El dominio no tiene un formato adecuado</p><a href="javascript:history.back();">Pulse aqu&iacute; y vuelva a intentarlo</a>';

	}

break;

}