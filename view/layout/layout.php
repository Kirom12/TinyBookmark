<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="<?php echo $page['description'] ?>">
	<meta name="keywords" content="<?php echo join(',', $page['tags']) ?>">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php echo $page['title'] ?></title>
	<link rel="stylesheet" href="static/css/normalize.css">
	<!-- <link rel="stylesheet" href="static/css/bootstrap.min.css"> -->
	<link rel="stylesheet" href="static/css/main.css">

	<script src="static/js/jquery.min.js"></script>
</head>
<body>
	<div id="main">
		<div class="container">
			<?php echo $content; ?>
		</div>
	</div>
</body>
</html>