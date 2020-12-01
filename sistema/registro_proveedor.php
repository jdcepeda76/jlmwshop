<?php 
	session_start(); 
	include "../conexion.php";
	if (!empty($_POST)) 
	{
		$alert='';
		if (empty($_POST['nit']) || empty($_POST['nombre']) || empty($_POST['telefono']))
		{
			$alert='<p class="msg_error">Todos los campos son requeridos</p>';
		}else{

			$nit = $_POST['nit'];
			$nombre = $_POST['nombre'];
			$telefono = $_POST['telefono'];
			$id_usuario = $_SESSION['idUser'];

			$result = 0;
			if (is_numeric($nit)) 
			{
				$query = mysqli_query($conection,"SELECT * FROM proveedores WHERE nit='$nit'");
				$result = mysqli_fetch_array($query);		
			}

			if ($result > 0) {
				$alert='<p class="msg_error">El NIT ya existe.</p>';
			}else{
				$query_insert = mysqli_query($conection,"INSERT INTO proveedores(nit,nombre,telefono,id_usuario) 
														VALUES('$nit','$nombre','$telefono',$id_usuario)");

				if ($query_insert) {
					$alert='<p class="msg_save">Registro correctamente</p>';
				}else{
					$alert='<p class="msg_error">Error al registrar proveedor.</p>';
				}
			}
		}
			mysqli_close($conection);
	}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro Proveedor</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1><i class="fas fa-user-tie"></i> Crear Proveedor</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<form action="" method="post">
				<label for="nit">NIT</label>
				<input type="number" name="nit" id="nit" placeholder="NIT">
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre">
				<label for="telefono">Telefono</label>
				<input type="number" name="telefono" id="telefono" placeholder="Telefono">
				<button type="submit" class="btn_save"><i class="fas fa-save"></i> Crear Proveedor</button>
			</form>

		</div>

	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>