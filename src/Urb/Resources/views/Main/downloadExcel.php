<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
	<table>
		<tr>
			<th>Nombre</th>
			<th>Cédula</th> 
			<th>Email</th>
			<th>Teléfono</th>
			<th>Fecha de nacimiento</th>
			<th>Producto</th>
			<th>N° de Factura</th>
			<th>Monto</th>
			<th>Tienda</th>
			<th>Quiere recibir email?</th>
		</tr>
		<?php foreach ($registers as $register): ?>
		<tr>
			<td><?php echo $register["name"] ?></td>
			<td><?php echo $register["cedula"] ?></td>
			<td><?php echo $register["email"] ?></td>
			<td><?php echo $register["phone"] ?></td>
			<td><?php echo date('d/m/Y', strtotime($register["birthdate"])) ?></td>
			<td><?php echo $register["product"] ?></td>
			<td><?php echo $register["factura"] ?></td>
			<td><?php echo $register["monto"] ?></td>
			<td><?php echo $register["tienda"] ?></td>
			<td><?php echo ($register["newsletter"]==1)?"SI":"NO";  ?></td>
		</tr>
		<?php endforeach;  ?>  	  		 
	</table>
</body>  
</html>