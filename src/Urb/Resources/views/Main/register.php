<div id="contenedor" class="register">
	<img src="<?php echo $view['assets']->getUrl('images/urb.png')?>" width="150" style="padding: 15px" />
	<h2>¡Registrate y participá!</h2>
	<p class="texto_registro">Llega el frío y es hora de renovar tu look pero, ¿sabés cuál es tu color ideal para esta temporada? ¡Nosotros te ayudamos! Elige tu foto y te mostraremos cual es el tono ideal para vos. Registrate para empezar. </p>
	<form action="<?php echo $container->get('routing.generator')->generate('register') ?>" method="post" id="registerForm">
		<ul class="registro">
			<li>
				<label>Nombre</label>
				<input type="text" name="register[name]" id="register_name"
					title="Debes completar tu nombre."
					data-rule-required="true"
				 />
			</li>
			<li>
				<label>Apellido</label>
				<input type="text" name="register[last_name]" id="register_last_name"
					title="Debes completar tu apellido."
					data-rule-required="true" 
				/>
			</li>
			<li>
				<label>DNI</label>
				<input type="text" name="register[dni]" id="register_dni"
					title="Debes completar tu DNI."
					data-rule-required="true" 
				/>
			</li>
			<li>
				<label>E-mail</label>
				<input type="text" name="register[email]" id="register_email"
					title="Debes completar tu email correctamente."
					data-rule-required="true"
					data-rule-email="true" 
				/>
			</li>
			<li>
				<label>Fecha de nacimiento</label>
				<input type="text" name="register[birth_date][day]" id="register_birth_date_day" class="fecha" maxlength="2"
					title="Debes completar el día de tu fecha de nacimiento."
					data-rule-required="true" 
				/>
				<input type="text" name="register[birth_date][month]" id="register_birth_date_month" class="fecha" maxlength="2"
					title="Debes completar el mes de tu fecha de nacimiento."
					data-rule-required="true" 
				/>
				<input type="text" name="register[birth_date][year]" id="register_birth_date_year" class="fecha" maxlength="4"
					title="Debes completar el año de tu fecha de nacimiento."
					data-rule-required="true" 
				/>
			</li>
			<li style="text-align: left; padding-left: 210px">
				<input style="width: auto;" name="register[terms]" type="checkbox"
					title="Debes aceptar las bases y condiciones."
					data-rule-required="true" 
				/> 
				Acepto bases y condiciones
			</li>
			<li style="text-align: left; padding-left: 210px">
				<input style="width: auto;" type="checkbox" name="register[newsletter]" /> Quiero recibir información sobre Jumbo.
			</li>
			<li>
				<button type="submit" class="boton1" style="float:right; width: 230px; margin-top: 20px"><span>Continuar</span> <i class="fa  fa-arrow-circle-right"></i></button>
			</li>
		</ul>
	</form>
	<img class="logo_jumbo" src="<?php echo $view['assets']->getUrl('images/jumbo.png')?>" width="120" style="padding: 15px" />
</div>