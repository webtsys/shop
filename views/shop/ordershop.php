<?php

function OrderShopView()
{

global $config_shop, $model;

?>
<!DOCTYPE html>
<html>
	<head>
		<style>
		table {
		
			border: solid 1px  #000;

		}
		</style>
	</head>
	<body>
		<table style="width:100%;">
			<tr>
				<td style="width:50%;">
					<img src="<?php echo $model['config_shop']->components['image_bill']->show_image_url($config_shop['image_bill']); ?>" border="0"/>
				</td>
				<td style="width:50%;">
					<?php echo $config_shop['bill_data_shop']; ?>
				</td>
			</tr>
		</table>
	</body>
</html>
<?php

}

?>