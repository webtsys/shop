<?php

function ProfileShopView($arr_last_products, $total_billing, $arr_group_discount, $arr_next_group_discount, $arr_dir_transport)
{

	global $lang, $base_url, $model, $config_shop; 

	?>
	<div class="title"><?php echo $lang['shop']['profile_shop']; ?></div>
	<div class="cont">
		<p><?php echo $lang['shop']['explain_profile_shop']; ?></p>
		<h3><?php echo $lang['shop']['last_buy_products']; ?></h3>
		<ul>
		<?php
		$z=0;
		foreach($arr_last_products as $idproduct => $name_product)
		{

			?>
			<li><a href="<?php echo make_fancy_url($base_url, 'shop', 'viewproduct', 'view_'.$name_product, array('IdProduct' => $idproduct)); ?>"><?php echo $name_product; ?></a></li>
			<?php

			$z++;

		}

		?>
		</ul>
		<?php
		if($z==0)
		{

			?>
			
			<?php

		}

		?>
		<h3><?php echo $lang['shop']['total_billing']; ?></h3>
		<ul>
			<li><?php echo MoneyField::currency_format($total_billing); ?></li>
		</ul>
		<h3><?php echo $lang['shop']['discount_level']; ?></h3>
		<?php

		if($arr_group_discount['group_shop_discount']!=0)
		{

		?>
		<ul>
			<li><?php echo $arr_group_discount['group_shop_name']; ?></li>
		</ul>
		<h3><?php echo $lang['shop']['discount_next_level']; ?></h3>
		<?php 

		if($arr_next_group_discount['name']!='')
		{

		?>
		<ul>
			<li><?php echo $arr_next_group_discount['name']; ?></li>
		</ul>
		<?php
		}
		else
		{
		?>
		<p><?php echo $lang['shop']['no_next_level']; ?></p>
		<?php
		}
		?>
		<p><a href="<?php echo make_fancy_url($base_url, 'pages', 'index', 'explain_discounts', array('IdPage' => $config_shop['explain_discounts_page']) ); ?>"><?php echo $lang['shop']['press_for_read_conditions_for_discounts']; ?></a></p>

		<?php
		}
		else
		{
		?>
		<p><?php echo $lang['shop']['no_in_discount_group']; ?></p>
		<?php
		}
		?>

		<h3><?php echo $lang['shop']['dir_transport']; ?></h3>
		<?php
		if($arr_dir_transport['IdDir_transport']!=0)
		{
			?>
			<ul>
			<?php
			
			if($arr_dir_transport['enterprise_name_transport']!='')
			{
			?>
				<li><?php echo $arr_dir_transport['enterprise_name_transport']; ?></li>
			<?php
			}
			else
			{
			?>
				<li><?php echo $arr_dir_transport['name_transport']; ?> <?php echo $arr_dir_transport['last_name_transport']; ?></li>
			<?php
			}

		
		
			?>
			<li><?php echo $arr_dir_transport['address_transport']; ?></li>
			<li><?php echo $arr_dir_transport['zip_code_transport']; ?> <?php echo $arr_dir_transport['city_transport']; ?> (<?php echo $arr_dir_transport['region_transport']; ?>)</li>
			<li><?php echo $arr_dir_transport['country_transport']; ?></li>
			</ul>
		<?php
			
		}
		else
		{
		?>
		<p><?php echo $lang['shop']['no_dir_transport_for_now']; ?></p>
		<?php
		}
		?>

	</div>
	<?php

}


?>