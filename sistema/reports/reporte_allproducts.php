<?php
require ('../../conexion.php');
header("Content-Type:application/xls");
header('Content-Disposition: attachment; filename="ProductosJLM.xls"');

$sql= "SELECT p.Id_producto, p.producto, pr.nombre, p.precio, p.cantidad, te.tela, ta.codigo, p.dateadd FROM productos p INNER JOIN proveedores pr ON p.nombre=pr.id_proveedor INNER JOIN tipo_tela te ON p.tela=te.id_tela INNER JOIN tallas ta ON p.codigo=ta.id_talla ORDER BY Id_producto";
$resultado= $conection->query($sql);


?>

<meta charset="utf-8">

<table border="1">
	<h2>REPORTE - LISTADO DE PRODUCTOS</h2>
	<h3>JLM WorkShop</h3>
	<p>Generado el dia: <?php echo "" . date("d/m/Y") . " a las " . date("h:i a"); ?></p>

	<tr>	
		<th style="background-color: #008868;"><h3>ID</h3></th>
		<th style="background-color: #008868;"><h3>Nombre</h3></th>
		<th style="background-color: #008868;"><h3>Proveedor</h3></th>
		<th style="background-color: #008868;"><h3>Precio</h3></th>
		<th style="background-color: #008868;"><h3>Cantidad</h3></th>
		<th style="background-color: #008868;"><h3>Tela</h3></th>
		<th style="background-color: #008868;"><h3>Talla</h3></th>
		<th style="background-color: #008868;"><h3>Fecha Añadido</h3></th>
	</tr>
	<?php
		while($row=mysqli_fetch_assoc($resultado)){
			?>
			<tr>
				<td><?php echo $row['Id_producto'] ?></td>
				<td><?php echo $row['producto'] ?></td>
				<td><?php echo $row['nombre'] ?></td>
				<td><?php echo $row['precio'] ?></td>
				<td><?php echo $row['cantidad'] ?></td>
				<td><?php echo $row['tela'] ?></td>
				<td><?php echo $row['codigo'] ?></td>
				<td><?php echo $row['dateadd'] ?></td>
			</tr>
			
			<?php

		}

	?>
</table>