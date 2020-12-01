<?php 
	session_start(); 
	if ($_SESSION['rol'] != 1 AND $_SESSION['rol'] != 2) 
	{
		header("location: ./");	
	}
	include "../conexion.php";

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>LISTA PEDIDOS</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<h1><i class="fas fa-cubes"></i> LISTA PEDIDOS</h1>
		<a href="registro_pedido.php" class="btn_new btnNewPedido"><i class="fas fa-layer-group"></i> Crear Pedido</a>
		<form action="buscar_pedido.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<button type="submit" class="btn_search"><i class="fas fa-search"></i></button>
		</form>
		<a href="reports/reporte_pedidos.php" class="btn_excel"><i class="fas fa-file-excel"></i> Exportar</a>
	<div class="containerTable">
		
		<table>
			<tr>
				<th>ID</th>
				<th>TIPO</th>
				<th>INGRESO</th>
				<th>SALIDA</th>
				<th>TOTAL</th>
				<th>PROVEEDOR</th>
				<th>ACCIONES</th>
			</tr>

			<?php  

				//PAGINADOR
				$sql_registe = mysqli_query($conection,"SELECT COUNT(*) AS total_registro FROM pedidos WHERE estatus = 1");
				$result_register = mysqli_fetch_array($sql_registe);
				$total_registro = $result_register['total_registro'];

				$por_pagina = 4;

				if (empty($_GET['pagina'])) 
				{
					$pagina = 1;
				}else{
					$pagina = $_GET['pagina'];
				}

				$desde = ($pagina-1) * $por_pagina;
				$total_paginas = ceil($total_registro / $por_pagina);



				$query = mysqli_query($conection,"SELECT * FROM pedidos WHERE estatus = 1 ORDER BY id_pedido ASC LIMIT $desde,$por_pagina ");
				mysqli_close($conection);
				$result = mysqli_num_rows($query);
				if ($result > 0) 
					{
						while ($data = mysqli_fetch_array($query)) {
										
				?>						
					<tr>
						<td><?php echo $data["id_pedido"]; ?></td>
						<td><?php echo $data["nombre"]; ?></td>
						<td><?php echo $data["f_ingreso"]; ?></td>
						<td><?php echo $data["f_salida"]; ?></td>
						<td><?php echo $data["total"]; ?></td>
						<td><?php echo $data["id_proveedor"]; ?></td>
						<td>
							<a href="editar_pedido.php?id=<?php echo $data["id_pedido"]; ?>" class="link_edit"><i class="fas fa-edit"></i> EDITAR</a> 

							<?php if ($_SESSION['rol'] == 1) { ?>
							||
							<a href="eliminar_confirmar_pedido.php?id=<?php echo $data["id_pedido"]; ?>" class="link_delete"><i class="fas fa-trash-alt"></i> ELIMINAR</a>
							<?php } ?>
						</td>
					</tr>
			<?php 		

					 }
				}		

			?>


			
		</table>
	</div>
		<div class="paginador">
			<ul>

				<?php 
				if ($pagina != 1) 
					{	
				 ?>
					<li><a href="?pagina=<?php echo 1; ?>"><i class="fas fa-step-backward"></i></a></li>
					<li><a href="?pagina=<?php echo $pagina-1; ?>"><i class="fas fa-backward"></i></a></li>
				<?php 
				}	
					for ($i=1; $i <= $total_paginas; $i++) {
						if ($i == $pagina) {
							echo '<li class="pageSelected">'.$i.'</li>';	
						}else{
							echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
						}
					}
					if ($pagina != $total_paginas) 
					{
				?>
					<li><a href="?pagina=<?php echo $pagina+1; ?>"><i class="fas fa-forward"></i></a></li>
					<li><a href="?pagina=<?php echo $total_paginas; ?>"><i class="fas fa-step-forward"></i></a></li>
				<?php } ?>	
			</ul>
		</div>

	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>