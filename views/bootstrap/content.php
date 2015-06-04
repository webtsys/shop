<?php

function ContentView($title, $content)
{

?>

	<div class="container-fluid">
		<h2 class="title bg-primary">
			<?php echo $title; ?>
		</div>
		<div class="cont container-fluid">
			<?php echo $content; ?>
		</div>
	</div>

<?php

}

?>