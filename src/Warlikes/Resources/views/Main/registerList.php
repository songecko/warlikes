<div class="container">
	<div class="logo">
		<img src="../images/logo.png"/>
	</div> 
	<div class="panel panel-default">
	 	<div class="panel-body">
		 	<ul class="nav nav-pills">
		 		<li><a href="<?php echo $container->get('routing.generator')->generate('register_list') ?>">Registros</a></li>
				<li><a href="<?php echo $container->get('routing.generator')->generate('download_excel') ?>">Descargar Excel</a></li>
				<li><a href="<?php echo $container->get('routing.generator')->generate('generate_winners') ?>">Generar ganadores</a></li>
			</ul>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">Registros</div>
		<table class="table table-condensed table-bordered table-hover">
			<tr>
				<th>#</th>
				<th>Nombre</th>
				<th>Cédula</th> 
				<th>Email</th>
				<th>Teléfono</th>
				<th>Fecha de nacimiento</th>
				<th>Producto</th>
				<th>N° de Factura</th>
				<th>Monto</th>
				<th>Chances</th>
				<th>Tienda</th>
				<th>Recibir mail?</th>
				<th></th>
			</tr>
			<?php $i = 0; ?>
			<?php foreach ($registers as $register): $i++; ?>
			<tr<?php echo ($register['is_winner'] == 1)?'  class="success"':''?>>
				<td><?php echo $i; ?></td>
				<td><?php echo $register["name"] ?></td>
				<td><?php echo $register["cedula"] ?></td>
				<td><?php echo $register["email"] ?></td>
				<td><?php echo $register["phone"] ?></td>
				<td><?php echo date('d/m/Y', strtotime($register["birthdate"])) ?></td>
				<td><?php echo $register["product"] ?></td>
				<td><?php echo $register["factura"] ?></td>
				<td><?php echo $register["monto"] ?></td>
				<td><?php echo ceil($register["monto"]/200) ?></td>
				<td><?php echo $register["tienda"] ?></td>
				<td>
					<?php if($register["newsletter"] == 1): ?>
					<span class="label label-success">Sí</span>
					<?php else: ?>
					<span class="label label-danger">No</span>
					<?php endif; ?>
				</td>
				<td>
					<a href="<?php echo $container->get('routing.generator')->generate('delete_register', array('id' => $register['id'])) ?>" 
						onclick="return confirm('Estas seguro?')">
						<span class="glyphicon glyphicon-remove"></span>
					</a>
				</td>
			</tr>
			<?php endforeach;  ?>  		 
		</table>        
	</div>
</div>