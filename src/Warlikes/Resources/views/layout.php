<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8" />
		<title>Guerra de Likes</title>
		<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,700italic,700,400italic" rel="stylesheet" type="text/css">
		<link href="<?php echo $view['assets']->getUrl('css/main.css') ?>"  rel="stylesheet" type="text/css" media="screen" />
	</head>
	<body>
		<div id="page">
			<nav id="nav">
				<ul>
					<li class="nav-home<?php echo $view['router']->onRoute('homepage')?' current':'' ?>"><a href="<?php echo $view['router']->generate('homepage') ?>"><em>Inicio</em> <span></span></a></li>
					<li class="nav-gallery<?php echo $view['router']->onRoute('gallery')?' current':'' ?>"><a href="<?php echo $view['router']->generate('gallery') ?>"><em>Galería</em> <span></span></a></li>
					<li class="nav-ranking<?php echo $view['router']->onRoute('ranking')?' current':'' ?>"><a href="<?php echo $view['router']->generate('ranking') ?>"><em>Ranking</em> <span></span></a></li>
					<li class="nav-share"><a href="#" class="fbShare" data-title="Estoy participandp del Like War. Competí por una GiftCard de $10.000  e increíbles premios."><em>Compartir</em> <span></span></a></li>
				</ul>
			</nav>
			
    		<?php echo $content ?>
    		    		
			<div id="alertModal" class="modal hide">
				<div class="modal-content register-msj">
					<a href="#" class="modal-close">x</a>
					<div class="vertical-center">
						<p><strong class="title">¡Debes completar todos los datos!</strong></p>
					</div>
				</div>
			</div>

			<footer id="footer" class="clearfix">
				<a href="<?php echo $view['router']->generate('terms') ?>" class="button">Bases y condiciones</a>
				<p>Si sos uno de los ganadores y tenés tarjeta CENCOSUD, te llevás una giftcard extra de <strong>$3.000!!!</strong></p>
				<div class="logos clearfix">
					<a href="#" id="cencosud">CENCOSUD</a>
					<span id="easy">Easy</span>
				</div>
			</footer>
		</div>
		<!--/#page-->
	
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js?ver=1.11.0"></script>
		<script src="<?php echo $view['assets']->getUrl('js/vendor/jquery.validate.min.js') ?>"></script>
		<script src="<?php echo $view['assets']->getUrl('js/main.js') ?>"></script>
	</body>
</html>