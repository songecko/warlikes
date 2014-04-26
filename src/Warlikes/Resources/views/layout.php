<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Jumbo URB</title>
		
		<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/normalize.css') ?>">
		<link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/font-awesome.min.css') ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/csphotoselector.css') ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/main.css') ?>">	
	</head>
	<body>
    	<?php echo $content ?>
    	
    	
	<!-- Markup for Carson Shold's Photo Selector -->
	<div id="CSPhotoSelector">
		<div class="CSPhotoSelector_dialog">
			<a href="#" id="CSPhotoSelector_buttonClose">x</a>
			<div class="CSPhotoSelector_form">
				<div class="CSPhotoSelector_header">
					<p>Elige desde tus fotos</p>
				</div>

				<div class="CSPhotoSelector_content CSAlbumSelector_wrapper">
					<p>Elegí uno de tus álbumes para seleccionar tu foto.</p>
					<div class="CSPhotoSelector_searchContainer CSPhotoSelector_clearfix">
						<div class="CSPhotoSelector_selectedCountContainer">Seleccionar un album</div>
					</div>
					<div class="CSPhotoSelector_photosContainer CSAlbum_container"></div>
				</div>

				<div class="CSPhotoSelector_content CSPhotoSelector_wrapper">
					<p>Seleccionar una foto/p>
					<div class="CSPhotoSelector_searchContainer CSPhotoSelector_clearfix">
						<div class="CSPhotoSelector_selectedCountContainer"><span class="CSPhotoSelector_selectedPhotoCount">0</span> / <span class="CSPhotoSelector_selectedPhotoCountMax">0</span> fotos seleccionadas</div>
						<a href="#" id="CSPhotoSelector_backToAlbums">Volver a los albumes</a>
					</div>
					<div class="CSPhotoSelector_photosContainer CSPhoto_container"></div>
				</div>

				<div id="CSPhotoSelector_loader"></div>


				<div class="CSPhotoSelector_footer CSPhotoSelector_clearfix">
					<a href="#" id="CSPhotoSelector_pagePrev" class="CSPhotoSelector_disabled">Anterior</a>
					<a href="#" id="CSPhotoSelector_pageNext">Siguiente</a>
					<div class="CSPhotoSelector_pageNumberContainer">
						Página <span id="CSPhotoSelector_pageNumber">1</span> / <span id="CSPhotoSelector_pageNumberTotal">1</span>
					</div>
					<a href="#" id="CSPhotoSelector_buttonOK">OK</a>
					<a href="#" id="CSPhotoSelector_buttonCancel">Cancelar</a>
				</div>
			</div>
		</div>
	</div>
	
    	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="<?php echo $view['assets']->getUrl('js/vendor/jquery.validate.min.js') ?>"></script>
		<script src="<?php echo $view['assets']->getUrl('js/vendor/csphotoselector.js') ?>"></script>
		<script src="<?php echo $view['assets']->getUrl('js/main.js') ?>"></script>
	</body>
</html>