<div id="contenedor" class="look">
	<img src="<?php echo $view['assets']->getUrl('images/urb.png')?>" width="150" style="padding: 15px" />
	<h2>Look recomendado</h2>
	<img class="img_modelo" src="<?php echo $view['assets']->getUrl('images/'.$userLook['image'])?>" />
	<div class="resultado">
		<ul>
			<li>
				<span class="color" style="background: <?php echo $userLookColor ?>; padding-top: 45px; margin-bottom: 30px; font-size: 1.2em"><?php echo $userLook['name'] ?></span>
			</li>
			<li>
				<p><?php echo $userLook['description'] ?></p>
				<p><span class="tip">Tip:</span> <?php echo $userLook['tip'] ?></p>
			</li>
			<li>
				<a href="#" class="boton2 photoSelect" data-add-look-url="<?php echo $container->get('routing.generator')->generate('add_look') ?>"><span>Elegir otras fotos</span></a>
				<a href="#" class="boton2 fbShare"><span>Compartir</span></a>
			</li>
		</ul>
	</div>
	<img class="logo_jumbo" src="<?php echo $view['assets']->getUrl('images/jumbo.png')?>" width="120" style="padding: 15px" />
</div>