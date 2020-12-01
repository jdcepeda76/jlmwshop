<?php 
	session_start(); 
	include "../conexion.php";

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>LISTA PROVEEDORES</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<?php 
			$busqueda = strtolower($_REQUEST['busqueda']);
			if (empty($busqueda)) {
				header("location: lista_proveedor.php");
				mysqli_close($conection);
			}

		 ?>
		<h1>LISTA PROVEEDORES</h1>
		<a href="registro_proveedor.php" class="btn_new btnNewProveedor">CREAR PROVEEDOR</a>


		<form action="buscar_proveedor.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
			<input type="submit" value="Buscar" class="btn_search">
		</form>
	<div class="containerTable">
		<table>
			<tr>
				<th>ID</th>
				<th>NIT</th>
				<th>NOMBRE</th>
				<th>TELEFONO</th>
				<th>ACCIONES</th>
			</tr>

			<?php  

			$sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM proveedores 
					WHERE ( id_proveedor LIKE '%$busqueda%'OR 
							nit LIKE '%$busqueda%' OR 
							nombre LIKE '%$busqueda%' OR 
							telefono LIKE '%$busqueda%')
							AND estatus = 1");
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



				$query = mysqli_query($conection,"SELECT * FROM proveedores 
												  WHERE ( id_proveedor LIKE '%$busqueda%'OR 
														nit LIKE '%$busqueda%' OR 
														nombre LIKE '%$busqueda%' OR 
														telefono LIKE '%$busqueda%')
												   		AND
												  		estatus = 1 ORDER BY id_proveedor ASC LIMIT $desde,$por_pagina ");
				mysqli_close($conection);
				$result = mysqli_num_rows($query);
				if ($result > 0) 
					{
						while ($data = mysqli_fetch_array($query)) {
										
				?>						
					<tr>
						<td><?php echo $data["id_proveedor"]; ?></td>
						<td><?php echo $data["nit"]; ?></td>
						<td><?php echo $data["nombre"]; ?></td>
						<td><?php echo $data["telefono"]; ?></td>
						<td>
							<a href="editar_proveedor.php?id=<?php echo $data["id_proveedor"]; ?>" class="link_edit">EDITAR</a>

							<?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 ) { ?>
							||
							<a href="eliminar_confirmar_proveedor.php?id=<?php echo $data["id_proveedor"]; ?>" class="link_delete">ELIMINAR</a>
							<?php } ?>
						</td>
					</tr>
			<?php 		

					 }
				}		

			?>


			
		</table>
	</div>
		<?php 
		if ($total_registro != 0) 
			{
		 ?>
		<div class="paginador">
			<ul>

				<?php 
				if ($pagina != 1) 
					{	
				 ?>
					<li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|<</a></li>
					<li><a href="?pagina=<?php echo $pagina-1; ?>&busqueda=<?php echo $busqueda; ?>"><<</a></li>
				<?php 
				}	
					for ($i=1; $i <= $total_paginas; $i++) {
						if ($i == $pagina) {
							echo '<li class="pageSelected">'.$i.'</li>';	
						}else{
							echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
						}
					}
					if ($pagina != $total_paginas) 
					{
				?>
					<li><a href="?pagina=<?php echo $pagina+1; ?>&busqueda=<?php echo $busqueda; ?>">>></a></li>
					<li><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?>">>|</a></li>
				<?php } ?>	
			</ul>
		</div>
	<?php } ?>

	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>