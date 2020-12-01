<?php
require ('../../conexion.php');
header("Content-Type:application/xls");
header('Content-Disposition: attachment; filename="PedidosJLM.xls"');

$sql= "SELECT * FROM pedidos WHERE estatus = 1 ORDER BY id_pedido";
$resultado= $conection->query($sql);


?>

<meta charset="utf-8">


<table border="1">
	<h2>REPORTE - LISTADO DE PEDIDOS DEL SISTEMA</h2>
	<h3>JLM WorkShop</h3>
	<p>Generado el dia: <?php echo "" . date("d/m/Y") . " a las " . date("h:i a"); ?></p>

	<tr>	
		<th style="background-color: #008868;"><h3>ID</h3></th>
		<th style="background-color: #008868;"><h3>Nombre</h3></th>
		<th style="background-color: #008868;"><h3>Fecha Ingreso</h3></th>
		<th style="background-color: #008868;"><h3>Fecha salida</h3></th>
		<th style="background-color: #008868;"><h3>Total</h3></th>
	</tr>
	<?php
		while($row=mysqli_fetch_assoc($resultado)){
			?>
			<tr>
				<td><?php echo $row['id_pedido'] ?></td>
				<td><?php echo $row['nombre'] ?></td>
				<td><?php echo $row['f_ingreso'] ?></td>
				<td><?php echo $row['f_salida'] ?></td>
				<td><?php echo $row['total'] ?></td>
			</tr>
			
			<?php

		}

	?>
</table>