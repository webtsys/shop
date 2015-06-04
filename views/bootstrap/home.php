<?php

function HomeView($title, $content)
{

?>
<!DOCTYPE html>
<html>
	<head>
	<title><?php echo $title; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php 
		View::$css[]='bootstrap.min.css';
		View::$css[]='style.css';
		
		View::$js[]='jquery.min.js';
		View::$js[]='bootstrap.min.js';
		
		echo View::loadJS();
		echo View::loadCSS();
		echo View::loadHeader();
	?>
	</head>
	<body>
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
				<a class="navbar-brand" href="#"><?php echo PhangoVar::$portal_name; ?></a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Menu<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#">Action</a></li>
						<li><a href="#">Another action</a></li>
						<li><a href="#">Something else here</a></li>
						<li class="divider"></li>
						<li><a href="#">Separated link</a></li>
						<li class="divider"></li>
						<li><a href="#">One more separated link</a></li>
					</ul>
					</li>
				</ul>
				
				
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
		<div class="container-fluid">
		
			<?php echo $content; ?>
		</div>
	</body>
</html>

<?php

}

?>