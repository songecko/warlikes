<div id="contenedor" class="register">
	<img src="<?php echo $view['assets']->getUrl('images/urb.png')?>" width="150" style="padding: 15px" />
	<h2>¡Registrate y participá!</h2>
	<div class="mecanica">
		<ul>
			<li>
				<span class="paso">1</span>
				<p>Busca entre tus álbumes la foto <br>que más te identifica.</p>
			</li>
			<li>
				<span class="paso">2</span>
				<p style="float: left; margin-top: 12px">Selecciona la foto.</p>
			</li>
			<li>
				<span class="paso">3</span>
				<p style="float: left; margin-top: 12px">¡Descubre tu color ideal!</p>
			</li>
			<li>
				<a href="#" class="boton1 photoSelect" data-add-look-url="<?php echo $container->get('routing.generator')->generate('add_look') ?>" style="margin: 20px 0 0 90px"><span>Elegir fotos</span> <i class="fa fa-picture-o"></i></a>
			</li>
		</ul>
	</div>
	<img class="logo_jumbo" src="<?php echo $view['assets']->getUrl('images/jumbo.png') ?>" width="120" style="padding: 15px" />
	
</div>