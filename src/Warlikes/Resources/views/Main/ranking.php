<header id="header"></header>

<div class="page" id="ranking">
	<header class="section-header">
		<h2>Ranking</h2>
		<p>Bombardeá con likes al resto y sumá posibilidades de ganar. ¡Sumales likes para poder ganar!</p>
	</header>
	<?php echo $view->render('Main/Partial/user_photos.php', array('pager' => $pager, 'pagination' => $pagination)) ?>
</div>