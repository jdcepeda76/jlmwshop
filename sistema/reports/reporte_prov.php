<?php
require ('../../conexion.php');
header("Content-Type:application/xls");
header('Content-Disposition: attachment; filename="ProveedoresJLM.xls"');

$sql= "SELECT * FROM proveedores WHERE estatus = 1 ORDER BY id_proveedor";
$resultado= $conection->query($sql);


?>

<meta charset="utf-8">


<table border="1">
	<h2>REPORTE - LISTADO DE PROVEEDORES DEL SISTEMA</h2>
	<h3>JLM WorkShop</h3>
	<p>Generado el dia: <?php echo "" . date("d/m/Y") . " a las " . date("h:i a"); ?></p>

	<tr>	
		<th style="background-color: #008868;"><h3>ID</h3></th>
		<th style="background-color: #008868;"><h3>NIT</h3></th>
		<th style="background-color: #008868;"><h3>Nombre</h3></th>
		<th style="background-color: #008868;"><h3>N° Teléfono</h3></th>
		<th style="background-color: #008868;"><h3>Fecha Añadido</h3></th>
	</tr>
	<?php
		while($row=mysqli_fetch_assoc($resultado)){
			?>
			<tr>
				<td><?php echo $row['id_proveedor'] ?></td>
				<td><?php echo $row['nit'] ?></td>
				<td><?php echo $row['nombre'] ?></td>
				<td><?php echo $row['telefono'] ?></td>
				<td><?php echo $row['dateadd'] ?></td>
			</tr>
			
			<?php

		}

	?>
</table>