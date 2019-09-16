<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="<?= '../'.$link_css; ?>">
		<link rel="icon" type="image/png" href="<?= '../'.$link_fav; ?>" />
		<link rel="stylesheet" type="text/css" href="<?= $link_css_1; ?>">
	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
			<a class="navbar-brand" href="#"><?= $api_sub; ?></a>
		</nav>
		<div id="form_post">
			<br>
			<h3><?= $api_title; ?></h3>
			<hr>
			<form method="POST" class="form_api">
				<div class="form-group">
					<label for="util_api"><?= $util_name; ?></label>
					<input type="text" class="form-control" id="util_api" name="util_api" placeholder="<?= $util_name; ?>">
				</div>
				<div class="form-group">
					<label for="pass_api"><?= $mdp; ?></label>
					<input type="password" class="form-control" id="pass_api" name="pass_api" placeholder="<?= $mdp; ?>">
				</div>
				<br>
				<button type="submit" class="btn btn-primary" name="sub_api"><?= $connection; ?></button>
			</form>
			<?php
			if (isset($erreur) && !empty($erreur)) {
				?><font color="red"><?= $erreur; ?></font><?php
			}
			?>
		</div>
	</body>
</html>