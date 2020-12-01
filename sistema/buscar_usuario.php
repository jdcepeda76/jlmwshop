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
	<title>LISTA USUARIOS</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<?php 
			$busqueda = strtolower($_REQUEST['busqueda']);
			if (empty($busqueda)) {
				header("location: lista_usuarios.php");
				mysqli_close($conection);
			}

		 ?>
		<h1>LISTA USUARIOS</h1>
		<a href="registro_usuario.php" class="btn_new btnNewUsuario">Crear Usuario</a>


		<form action="buscar_usuario.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

		<div class="containerTable">
	<div class="containerTable">		
		<table>
			<tr>
				<th>ID</th>
				<th>NOMBRE</th>
				<th>APELLIDO</th>
				<th>EMAIL</th>
				<th>CLAVE</th>
				<th>CELULAR</th>
				<th>CEDULA</th>
				<th>DIRECCION</th>
				<th>ROL</th>
				<th>ACCIONES</th>
			</tr>

			<?php  

				//PAGINADOR
				$rol ='';
				if ($busqueda == 'administrador') 
				{
					$rol = " OR id_rol LIKE '%1%' ";

				}else if ($busqueda == 'empleado') {
					$rol = " OR id_rol LIKE '%2%' ";

				}else if ($busqueda == 'proveedor') {
					$rol = " OR id_rol LIKE '%3%' ";
				}
				$sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM usuarios 
					WHERE ( id_usuario LIKE '%$busqueda%'OR 
							Nombre LIKE '%$busqueda%' OR 
							Apellido LIKE '%$busqueda%' OR 
							Correo LIKE '%$busqueda%' OR 
							Clave LIKE '%$busqueda%' OR 
							Celular LIKE '%$busqueda%' OR 
							Cedula LIKE '%$busqueda%' OR 
							Direccion LIKE '%$busqueda%'
							$rol )
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



				$query = mysqli_query($conection,"SELECT u.id_usuario, u.Nombre, u.Apellido, u.Correo, u.Clave, u.Celular, u.Cedula, u.Direccion, r.id_rol FROM usuarios u 
												  INNER JOIN roles r ON u.id_rol= r.id_rol 
												  WHERE ( u.id_usuario LIKE '%$busqueda%'OR 
														u.Nombre LIKE '%$busqueda%' OR 
														u.Apellido LIKE '%$busqueda%' OR 
														u.Correo LIKE '%$busqueda%' OR 
														u.Clave LIKE '%$busqueda%' OR 
														u.Celular LIKE '%$busqueda%' OR 
														u.Cedula LIKE '%$busqueda%' OR 
														u.Direccion LIKE '%$busqueda%' OR   
												   		r.id_rol LIKE '%$busqueda%')
												   		AND
												  		estatus = 1 ORDER BY id_usuario ASC LIMIT $desde,$por_pagina ");
				mysqli_close($conection);
				$result = mysqli_num_rows($query);
				if ($result > 0) 
					{
						while ($data = mysqli_fetch_array($query)) {
										
				?>						
					<tr>
						<td><?php echo $data["id_usuario"]; ?></td>
						<td><?php echo $data["Nombre"]; ?></td>
						<td><?php echo $data["Apellido"]; ?></td>
						<td><?php echo $data["Correo"]; ?></td>
						<td><?php echo $data["Clave"]; ?></td>
						<td><?php echo $data["Celular"]; ?></td>
						<td><?php echo $data["Cedula"]; ?></td>
						<td><?php echo $data["Direccion"]; ?></td>
						<td><?php echo $data["id_rol"]; ?></td>
						<td>
							<a href="editar_usuario.php?id=<?php echo $data["id_usuario"]; ?>" class="link_edit">EDITAR</a>

							<?php if ($data["id_usuario"] != 1) { ?>
							||
							<a href="eliminar_confirmar_usuario.php?id=<?php echo $data["id_usuario"]; ?>" class="link_delete">ELIMINAR</a>
							<?php } ?>
						</td>
					</tr>
			<?php 		

					 }
				}		

			?>


			
		</table>
	</div>
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