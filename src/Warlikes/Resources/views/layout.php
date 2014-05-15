<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8" />
		<meta property="og:image" content="https://secure-server.com.ar/<?php echo $view['assets']->getUrl('images/fbBrand.png') ?>" />
		<title>Guerra de Likes</title>
		<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,700italic,700,400italic" rel="stylesheet" type="text/css">
		<link href="<?php echo $view['assets']->getUrl('css/main.css') ?>"  rel="stylesheet" type="text/css" media="screen" />
	</head>
	<body>
		<div id="page">
			<?php if(!$view['router']->onRoute(array('like'))): ?>
			<nav id="nav">
				<ul>
					<li class="nav-home<?php echo $view['router']->onRoute('homepage')?' current':'' ?>"><a href="<?php echo $view['router']->generate('homepage') ?>"><em>Inicio</em> <span></span></a></li>
					<li class="nav-gallery<?php echo $view['router']->onRoute('gallery')?' current':'' ?>"><a href="<?php echo $view['router']->generate('gallery') ?>"><em>Galería</em> <span></span></a></li>
					<li class="nav-ranking<?php echo $view['router']->onRoute('ranking')?' current':'' ?>"><a href="<?php echo $view['router']->generate('ranking') ?>"><em>Ranking</em> <span></span></a></li>
					<li class="nav-share"><a href="#" class="fbShare" data-title="Estoy participandp del Like War. Competí por una GiftCard de $10.000  e increíbles premios."><em>Compartir</em> <span></span></a></li>
				</ul>
			</nav>
			<?php endif; ?>
			
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
					<a href="https://www.facebook.com/TarjetaCencosudArgentina?fref=ts" target="_blank" id="cencosud">CENCOSUD</a>
					<span id="easy">Easy</span>
				</div>
			</footer>
		</div>
		<!--/#page-->
	
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js?ver=1.11.0"></script>
		<script src="<?php echo $view['assets']->getUrl('js/vendor/jquery.validate.min.js') ?>"></script>
		<script src="<?php echo $view['assets']->getUrl('js/main.js') ?>"></script>
		<script>
    dataLayer = [];
 </script>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-5WN6CN"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5WN6CN');</script>
<!-- End Google Tag Manager -->
	</body>
</html>