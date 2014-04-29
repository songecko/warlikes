<article>
	<a href="<?php echo $view['router']->generate('user_photo', array('id' => $user['id'])) ?>" class="caption">Bombardeá a los demás <strong>¡Votálo!</strong></a>
	<img class="thumb" src="<?php echo $view['router']->generate('imagine_resize', array('src' => $user['photo'], 'width' => '220', 'height' => '220')) ?>" alt="" />
	<ul class="actions">
		<li class="dislike"><a href="<?php echo $view['router']->generate('user_photo_vote', array('id' => $user['id'])) ?>"><?php echo $user['votes'] ?></a></li>
		<li class="share"><a href="#" class="fbShare" data-title="Estoy participando en Like War. Ayúdame a que ésta foto tenga más likes que la mía. Ingresa y vota la foto.">Compartir</a></li>
	</ul>
</article>