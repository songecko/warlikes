<header id="header"></header>

<div class="page" id="photo">
	<header class="section-header">
		<h2>Se el menos votado y ganá!</h2>
		<p>Completá tus datos y subí tu foto de #entrecasa, Bombardeá con LIKES las fotos de los demás y sumá más chances de ganar. ¡Empezá a participar!</p>
	</header>
	<div class="clearfix">
		<article class="photo">
			<img class="big-thumb" src="<?php echo $view['router']->generate('imagine_resize', array('src' => $user['photo'], 'width' => '700', 'height' => '500'))?>" alt="" />
			<ul class="actions">
				<li class="dislike"><a href="<?php echo $view['router']->generate('user_photo_vote', array('id' => $user['id'])) ?>"><?php echo $user['votes'] ?></a></li>
				<li class="share"><a href="#" class="fbShare" data-title="Estoy participando en Like War. Ayúdame a que ésta foto tenga más likes que la mía. Ingresa y vota la foto.">Compartir</a></li>
			</ul>
		</article>
	</div>
</div>

<?php if($view['request']->getParameter('new')): ?>
<div class="modal">
	<div class="modal-content register-msj">
		<a href="#" class="modal-close">x</a>
		<div class="vertical-center">
			<p><strong>¡Ya estás participando!</strong><br /> Bombardeá con LIKES a los demás.</p>
		</div>
		<a href="<?php echo $view['router']->generate('gallery') ?>" class="button">Votalo</a>
	</div>
</div>
<?php endif; ?>