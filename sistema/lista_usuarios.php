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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>LISTA USUARIOS</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<h1><i class="fas fa-users"></i> LISTA USUARIOS</h1>
		<a href="registro_usuario.php" class="btn_new btnNewUsuario"><i class="fas fa-user-plus"></i> Crear Usuario</a>
		<form action="buscar_usuario.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<button type="submit" class="btn_search"><i class="fas fa-search"></i></button>
		</form>
		<a href="reports/reporte_users.php" class="btn_excel"><i class="fas fa-file-excel"></i> Exportar</a>
		
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
				$sql_registe = mysqli_query($conection,"SELECT COUNT(*) AS total_registro FROM usuarios WHERE estatus = 1");
				$result_register = mysqli_fetch_array($sql_registe);
				$total_registro = $result_register['total_registro'];

				$por_pagina = 15;

				if (empty($_GET['pagina'])) 
				{
					$pagina = 1;
				}else{
					$pagina = $_GET['pagina'];
				}

				$desde = ($pagina-1) * $por_pagina;
				$total_paginas = ceil($total_registro / $por_pagina);



				$query = mysqli_query($conection,"SELECT u.id_usuario, u.Nombre, u.Apellido, u.Correo, u.Clave, u.Celular, u.Cedula, u.Direccion, r.id_rol FROM usuarios u INNER JOIN roles r ON u.id_rol= r.id_rol WHERE estatus = 1 ORDER BY id_usuario ASC LIMIT $desde,$por_pagina ");
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
							<a href="editar_usuario.php?id=<?php echo $data["id_usuario"]; ?>" class="link_edit"><i class="fas fa-edit"></i> EDITAR</a>

							<?php if ($data["id_usuario"] != 1) { ?>
							||
							<a href="eliminar_confirmar_usuario.php?id=<?php echo $data["id_usuario"]; ?>" class="link_delete"><i class="fas fa-trash-alt"></i> ELIMINAR</a>
							<?php } ?>
						</td>
					</tr>
			<?php 		

					 }
				}		

			?>


			
		</table>
		<form method="POST" name="uploadCsv" id="uploadcsv" enctype="multipart/form-data" class="form-horizontal">
			<fieldset>

				<!-- Form Name -->
				<legend>Subir CSV</legend>

				<!-- File Button -->
				<div class="form-group">
					<label class="col-md-4 control-label" for="filebutton">Cargar CSV</label>
					<div class="col-md-4">
						<input id="filebutton" name="archivo" accept=".csv" class="input-file" type="file">
					</div>
				</div>

				<!-- Button -->
				<div class="form-group">
					<label class="col-md-4 control-label" for="singlebutton">Subir archivo</label>
					<div class="col-md-4">
						<button id="singlebutton" name="subir" class="btn btn-success">Subir CSV</button>
					</div>
				</div>

			</fieldset>
		</form>
		<?php
		include "../conexion.php";

		if (isset($_REQUEST['subir'])) {
			$nombre = $_FILES['archivo']['name'];
			$tipo = $_FILES['archivo']['type'];
			$destino = "csv/$nombre";
			$res = copy($_FILES['archivo']['tmp_name'], $destino);
			if (file_exists($destino) == true) {
				$query = ("INSERT INTO usuarios (Nombre,Apellido,Correo,Clave,Celular,Cedula,Direccion,id_rol,estatus) VALUES ");
				$i = 0;
				$archivo = fopen($destino, 'r');
				while (($columna = fgetcsv($archivo)) != false) {
					if ($i > 0) {
						$query=$query. "('" . $columna[0] . "','" . $columna[1] . "','" . $columna[2] . "','" . $columna[3] . "','" . $columna[4] . "','" . $columna[5] . "','" . $columna[6] . "','" . $columna[7] . "','" . $columna[8] . "'),";
					}
					$i++;
				}
				$query = substr($query, 0, -1);
				$resultado = mysqli_query($conection, $query);
			}
		}
		?>
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