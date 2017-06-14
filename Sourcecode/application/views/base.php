<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta charset="UTF-8">
		<title>MoviesRating</title>

		<link rel="stylesheet" href="<?= base_url('assets/bootstrap-3.3.6/css/bootstrap.min.css'); ?>"/>
		<link rel="stylesheet" href="<?= base_url('assets/bootstrap-3.3.6/css/bootstrap-theme.min.css'); ?>"/>
		<link rel="stylesheet" href="<?= base_url('assets/bootstrap-tags-input-0.8.0/bootstrap-tagsinput.css'); ?>"/>
		<link rel="stylesheet" href="<?= base_url('assets/movies-rating/css/principal.css'); ?>"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<script src="<?= base_url('assets/jquery-2.2.4/jquery-2.2.4.min.js') ?>"></script>
	</head>
	<body>
		<?php $menuItem = isset($menuItem) ? $menuItem : '' ?>
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Alternar navegação</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?= base_url() ?>">MoviesRating</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li class="<?= strcasecmp($menuItem, 'usuarios') === 0 ? 'active' : '' ?>"><?= anchor('usuario', 'Usuários') ?></li>
						<li class="dropdown <?= strcasecmp($menuItem, 'filmes') === 0 ? 'active' : '' ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Filmes <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><?= anchor('filme', 'Filmes') ?></li>
								<li><?= anchor('filme/avaliar', 'Avaliação') ?></li>
								<li class="divider"></li>
								<li><?= anchor('filme/indicacao', 'Indicação') ?></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>

		<div class="row">
			<?= isset($title) ? ('<div class="row"><div class="page-title"><h3>' . $title . '</h3></div></div>') : '' ?>
			<?= isset($content) ? ('<div class="content">' . $content . '</div>') : '' ?>
		</div>

		<script src="<?= base_url('assets/bootstrap-3.3.6/js/bootstrap.min.js') ?>"></script>
		<script src="<?= base_url('assets/bootstrap-tags-input-0.8.0/bootstrap-tagsinput.min.js') ?>"></script>
	</body>
</html>