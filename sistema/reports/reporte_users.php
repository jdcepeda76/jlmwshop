<?php
require ('../../conexion.php');
header("Content-Type:application/xls");
header('Content-Disposition: attachment; filename="UsuariosJLM.xls"');

$sql= "SELECT u.id_usuario, u.Nombre, u.Apellido, u.Correo, u.Clave, u.Celular, u.Cedula, u.Direccion, r.id_rol FROM usuarios u INNER JOIN roles r ON u.id_rol= r.id_rol WHERE estatus = 1 ORDER BY id_usuario";
$resultado= $conection->query($sql);


?>

<meta charset="utf-8">


<table border="1">
	<h2>REPORTE - LISTADO DE USUARIOS DEL SISTEMA</h2>
	<h3>JLM WorkShop</h3>
	<p>Generado el dia: <?php echo "" . date("d/m/Y") . " a las " . date("h:i a"); ?></p>

	<tr>	
		<th style="background-color: #008868;"><h3>ID</h3></th>
		<th style="background-color: #008868;"><h3>Cedula</h3></th>
		<th style="background-color: #008868;"><h3>Nombre</h3></th>
		<th style="background-color: #008868;"><h3>Apellido</h3></th>
		<th style="background-color: #008868;"><h3>E-mail</h3></th>
		<th style="background-color: #008868;"><h3>N° Celular</h3></th>
		<th style="background-color: #008868;"><h3>Dirección</h3></th>
		<th style="background-color: #008868;"><h3>ID Rol</h3></th>
		<th style="background-color: #008868;"><h3>Contraseña</h3></th>
	</tr>
	<?php
		while($row=mysqli_fetch_assoc($resultado)){
			?>
			<tr>
				<td><?php echo $row['id_usuario'] ?></td>
				<td><?php echo $row['Cedula'] ?></td>
				<td><?php echo $row['Nombre'] ?></td>
				<td><?php echo $row['Apellido'] ?></td>
				<td><?php echo $row['Correo'] ?></td>
				<td><?php echo $row['Celular'] ?></td>
				<td><?php echo $row['Direccion'] ?></td>
				<td><?php echo $row['id_rol'] ?></td>
				<td><?php echo $row['Clave'] ?></td>
			</tr>
			
			<?php

		}

	?>
</table>