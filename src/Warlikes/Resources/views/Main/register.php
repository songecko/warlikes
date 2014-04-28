<header id="header"></header>
	
<div class="page" id="register">
	<header class="section-header">
		<h2>Registrate y participá</h2>
		<p>Completa los datos y subí tu foto de #Entrecasa.<br /> Bombardeá con LIKES las fotos de los demás y sumá más chances de ganar.</p>
	</header>
	<form action="<?php echo $view['router']->generate('register') ?>" method="post" id="register-form" class="clearfix" enctype="multipart/form-data">
		<div class="form-item clearfix">
			<label class="label" for="register_name">Nombre</label>
			<input type="text" name="register[name]" id="register_name" class="input"
				title="Debes completar tu nombre correctamente."
				data-rule-required="true"
			/>
		</div>
		<div class="form-item clearfix">
			<label class="label" for="register_last_name">Apellido</label>
			<input type="text" name="register[last_name]" id="register_last_name" class="input"
				title="Debes completar tu apellido correctamente."
				data-rule-required="true"
			/>
		</div>
		<div class="form-item clearfix">
			<label class="label" for="register_dni">DNI</label>
			<input type="number" name="register[dni]" id="register_dni" class="input"
				title="Debes completar tu DNI correctamente."
				data-rule-required="true"
			/>
		</div>
		<div class="form-item clearfix">
			<label class="label" for="register_email">E-mail</label>
			<input type="email" name="register[email]" id="register_email" class="input"
				title="Debes completar tu email correctamente."
				data-rule-required="true"
				data-rule-email="true"  
			/>
		</div>
		<div class="form-item form-item-wide clearfix">
			<label class="label">Fecha de nacimiento</label>
			<div class="group clearfix">
				<select name="register[birth_date][day]" class="select"
					title="Debes completar el día de tu fecha de nacimiento."
					data-rule-required="true" 
				>
					<?php for($day=1; $day<=31; $day++): ?>
					<option value="<?php echo str_pad($day, 2, '0', STR_PAD_LEFT) ?>"><?php echo str_pad($day, 2, '0', STR_PAD_LEFT) ?></option>
					<?php endfor;?>
				</select>
				<select name="register[birth_date][month]" class="select"
					title="Debes completar el mes de tu fecha de nacimiento."
					data-rule-required="true" 
				>
					<option value="01">Enero</option>
					<option value="02">Febrero</option>
					<option value="03">Marzo</option>
					<option value="04">Abril</option>
					<option value="05">Mayo</option>
					<option value="06">Junio</option>
					<option value="07">Julio</option>
					<option value="08">Agosto</option>
					<option value="09">Septiembre</option>
					<option value="10">Octubre</option>
					<option value="11">Noviembre</option>
					<option value="12">Diciembre</option>
				</select>
				<select name="register[birth_date][year]" class="select"
					title="Debes completar el año de tu fecha de nacimiento."
					data-rule-required="true" 
				>
					<?php for ($year=1940; $year <= 1997; $year++): ?>
					<option value="<?php echo $year ?>"><?php echo $year ?></option>
					<?php endfor; ?>
				</select>
			</div>
		</div>
		<div class="form-item clearfix">
			<label class="label" for="register_photo"><strong>Subí tu foto</strong></label>
			<div class="group clearfix">
				<input type="file" name="register[photo]" id="register_photo"
					title="Debes seleccionar una foto para participar."
					data-rule-required="true"  
				/>
			</div>
		</div>
		<div class="form-item form-item-special clearfix">
			<label class="label">Soy cliente de Tarjeta Cencosud</label>
			<div class="group clearfix">
				<label><input type="radio" name="register[has_cencosud]" value="yes" checked="" /> Sí &nbsp;</label>
				<label><input type="radio" name="register[has_cencosud]" value="no" /> No</label>
			</div>
		</div>
		<div class="left">
			<label class="small-label"><input type="checkbox" name="register[terms]"
				title="Debes aceptar las bases y condiciones."
				data-rule-required="true"  
			/> Acepto las <a href="<?php echo $view['router']->generate('terms') ?>">bases y condiciones</a></label>
			<label class="small-label"><input type="checkbox" name="register[newsletter_easy]" /> Quiero recibir información y ofertas de Easy</label>
			<label class="small-label"><input type="checkbox" name="register[newsletter_cencosud]" /> Quiero recibir información y ofertas de Tarjeta Cencosud</label>
		</div>
		<div class="right">
			<input type="submit" name="submit" class="button" value="Enviar" />
		</div>
	</form>
</div>