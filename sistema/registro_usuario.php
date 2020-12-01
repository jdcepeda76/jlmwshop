<?php 
	session_start(); 
	if ($_SESSION['rol'] != 1) 
	{
		header("location: ./");	
	}
	include "../conexion.php";
	if (!empty($_POST)) 
	{
		$alert='';
		if (empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['correo']) || empty($_POST['clave']) || empty($_POST['celular']) || empty($_POST['cedula']) || empty($_POST['direccion']) || empty($_POST['rol']))
		{
			$alert='<p class="msg_error">Todos los campos son requeridos</p>';
		}else{

			$nombre = $_POST['nombre'];
			$apellido = $_POST['apellido'];
			$correo = $_POST['correo'];
			$clave = $_POST['clave'];
			$celular = $_POST['celular'];
			$cedula = $_POST['cedula'];
			$direccion = $_POST['direccion'];
			$rol = $_POST['rol'];


			$query = mysqli_query($conection,"SELECT * FROM usuarios WHERE Cedula='$cedula' OR Correo='$correo'");
			mysqli_close($conection);
			$result = mysqli_fetch_array($query);

			if ($result > 0) {
				$alert='<p class="msg_error">La cédula o el correo ya existe.</p>';
			}else{
				include "../conexion.php";
				$query_insert = mysqli_query($conection,"INSERT INTO usuarios(Nombre,Apellido,Correo,Clave,Celular,Cedula,Direccion,id_rol) 
														VALUES('$nombre','$apellido','$correo','$clave','$celular','$cedula','$direccion','$rol')");
				if ($query_insert) {
					$alert='<p class="msg_save">Registro correctamente</p>';
				}else{
					$alert='<p class="msg_error">Error al registrar usuario.</p>';
				}
			}
		}
	}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro Usuario</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1><i class="fas fa-user-plus"></i> Crear Usuario</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<form action="" method="post">
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre">
				<label for="apellido">Apellido</label>
				<input type="text" name="apellido" id="apellido" placeholder="Apellido">
				<label for="correo">Correo</label>
				<input type="email" name="correo" id="correo" placeholder="Correo">
				<label for="clave">Clave</label>
				<input type="password" name="clave" id="clave" placeholder="Clave">
				<label for="celular">Celular</label>
				<input type="number" name="celular" id="celular" placeholder="Celular">
				<label for="cedula">Cedula</label>
				<input type="number" name="cedula" id="cedula" placeholder="Cedula">
				<label for="direccion">Direccion</label>
				<input type="text" name="direccion" id="direccion" placeholder="Direccion">
				<label for="rol">Rol</label>
				<?php 
					include "../conexion.php";
					$query_rol = mysqli_query($conection,"SELECT * FROM roles");
					mysqli_close($conection);
					$result_rol = mysqli_num_rows($query_rol);

				?>
				<select name="rol" id="rol">

					<?php 
						if ($result_rol > 0) 
						{
							while ($rol = mysqli_fetch_array($query_rol)) {
					?>
							<option value="<?php echo $rol["id_rol"]; ?>"><?php echo $rol["rol"]; ?></option>
					<?php 
							}	
						}
					 ?>
				</select>
				<button type="submit" class="btn_save"><i class="fas fa-save"></i> Crear Usuario</button>
			</form>

		</div>

	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>