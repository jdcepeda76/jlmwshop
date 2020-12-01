<?php 
	session_start(); 
	if ($_SESSION['rol'] != 1) 
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
	<title>LISTA PRODUCTOS</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<?php 

			$busqueda = '';
			if (empty($_REQUEST['busqueda'])) {
				header("location: lista_producto.php");
			}
			if (!empty($_REQUEST['busqueda'])) {
				$busqueda = strtolower($_REQUEST['busqueda']);
				$where = " ( Id_producto LIKE '%$busqueda%'OR producto LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR tela LIKE '%$busqueda%')
							AND estatus = 1";
				$buscar = 'busqueda='.$busqueda;
			}

		?>
		<h1><i class="fas fa-tshirt"></i> LISTA PRODUCTOS</h1>
		<a href="registro_producto.php" class="btn_new btnNewProducto"><i class="fas fa-plus"></i> CREAR PRODUCTO</a>
		<form action="buscar_producto.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<button type="submit" class="btn_search"><i class="fas fa-search"></i></button>
		</form>
	<div class="containerTable">
		<table>
			<tr>
				<th>ID</th>
				<th>NOMBRE</th>
				<th>PROVEEDOR</th>
				<th>PRECIO</th>
				<th>CANTIDAD</th>
				<th>TELA</th>
				<th>TALLA</th>
				<th>FOTO</th>
				<th>ACCIONES</th>
			</tr>

			<?php  

				//PAGINADOR
				$sql_registe = mysqli_query($conection,"SELECT COUNT(*) AS total_registro FROM productos 
					WHERE  $where");
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



				$query = mysqli_query($conection,"SELECT p.Id_producto, p.producto, pr.nombre, p.precio, p.cantidad, te.tela, ta.codigo, p.foto
													 FROM productos p
													 INNER JOIN proveedores pr
													 ON p.nombre = pr.id_proveedor
													 INNER JOIN tipo_tela te
													 ON p.tela = te.id_tela
													 INNER JOIN tallas ta
													 ON p.codigo = ta.id_talla
													 WHERE 
													 (p.Id_producto LIKE '%$busqueda%' OR
													  p.producto LIKE '%$busqueda%' OR
													  pr.nombre LIKE '%$busqueda%' OR 
													  te.tela LIKE '%$busqueda%')
													 AND p.estatus = 1 ORDER BY p.Id_producto DESC LIMIT $desde,$por_pagina ");
				mysqli_close($conection);
				$result = mysqli_num_rows($query);
				if ($result > 0) 
					{
						while ($data = mysqli_fetch_array($query)) {
							if ($data['foto'] != 'img_producto.png') 
							{
								$foto = 'img/uploads/'.$data['foto'];
							}else{
								$foto = 'img/'.$data['foto'];
							}
				?>						
					<tr class="row<?php echo $data["Id_producto"]; ?>">
						<td><?php echo $data["Id_producto"]; ?></td>
						<td><?php echo $data["producto"]; ?></td>
						<td><?php echo $data["nombre"]; ?></td>
						<td class="celPrecio"><?php echo $data["precio"]; ?></td>
						<td class="celCantidad"><?php echo $data["cantidad"]; ?></td>
						<td><?php echo $data["tela"]; ?></td>
						<td><?php echo $data["codigo"]; ?></td>
						<td class="img_producto"><img src="<?php echo $foto; ?>" alt="<?php echo $data["nombre"]; ?>"></td>
						<?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
						<td>
							<a href="#" product="<?php echo $data["Id_producto"]; ?>" class="link_add add_product"><i class="fas fa-plus"></i> Agregar</a>
							||
							<a href="editar_producto.php?id=<?php echo $data["Id_producto"]; ?>" class="link_edit"><i class="fas fa-edit"></i> Editar</a> 
							||
							<a href="#" product="<?php echo $data["Id_producto"]; ?>" class="link_delete del_product"><i class="fas fa-trash-alt"></i> Eliminar</a>
						</td>
						<?php } ?>
					</tr>
			<?php 		

					 }
				}		

			?>


			
		</table>
	</div>
		<?php
		 if ($total_paginas != 0) { 
		?>
		<div class="paginador">
			<ul>

				<?php 
				if ($pagina != 1) 
					{	
				 ?>
					<li><a href="?pagina=<?php echo 1; ?>&<?php echo $buscar; ?>"><i class="fas fa-step-backward"></i></a></li>
					<li><a href="?pagina=<?php echo $pagina-1; ?>&<?php echo $buscar; ?>"><i class="fas fa-backward"></i></a></li>
				<?php 
				}	
					for ($i=1; $i <= $total_paginas; $i++) {
						if ($i == $pagina) {
							echo '<li class="pageSelected">'.$i.'</li>';	
						}else{
							echo '<li><a href="?pagina='.$i.'&'.$buscar.'">'.$i.'</a></li>';
						}
					}
					if ($pagina != $total_paginas) 
					{
				?>
					<li><a href="?pagina=<?php echo $pagina+1; ?>&<?php echo $buscar; ?>"><i class="fas fa-forward"></i></a></li>
					<li><a href="?pagina=<?php echo $total_paginas; ?>&<?php echo $buscar; ?>"><i class="fas fa-step-forward"></i></a></li>
				<?php } ?>	
			</ul>
		</div>
	<?php } ?>

	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>