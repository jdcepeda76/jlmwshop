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

			$idUsuario = $_POST['id'];
			$nombre = $_POST['nombre'];
			$apellido = $_POST['apellido'];
			$correo = $_POST['correo'];
			$clave = $_POST['clave'];
			$celular = $_POST['celular'];
			$cedula = $_POST['cedula'];
			$direccion = $_POST['direccion']; 
			$rol = $_POST['rol'];

			$query = mysqli_query($conection,"SELECT * FROM usuarios 
												WHERE (Cedula='$cedula' AND id_usuario != $idUsuario) 
												OR (Correo='$correo' AND id_usuario != $idUsuario) ");
			$result = mysqli_fetch_array($query);
			//$result = count($result);

			if ($result > 0){
				$alert='<p class="msg_error">La cédula o el correo ya existe.</p>';
			}else{

				if (empty($_POST['clave'])) 
				{
					$sql_update = mysqli_query($conection,"UPDATE usuarios
															SET Nombre = '$nombre', Apellido ='$apellido', Correo='$correo', Celular='$celular', Cedula='$cedula', Direccion='$direccion', id_rol='$rol'
															WHERE id_usuario= $idUsuario");
				}else{
					$sql_update = mysqli_query($conection,"UPDATE usuarios
															SET Nombre = '$nombre', Apellido ='$apellido', Correo='$correo', Clave='$clave', Celular='$celular', Cedula='$cedula', Direccion='$direccion', id_rol='$rol'
															WHERE id_usuario= $idUsuario"); 
				}

				
				if ($sql_update) {
					$alert='<p class="msg_save">Usuario actualizado correctamente</p>';
				}else{
					$alert='<p class="msg_error">Error al actualizar usuario.</p>';
				}
			}
		}
	}

	//MOSTRAR DATOS
	
	if (empty($_REQUEST['id'])) 
	{
		header('location: lista_usuarios.php');	
		mysqli_close($conection);
	}
	$iduser = $_REQUEST['id'];

	$sql = mysqli_query($conection,"SELECT u.id_usuario, u.Nombre, u.Apellido, u.Correo, u.Clave, u.Celular, u.Cedula, u.Direccion, (u.id_rol) as id_rol, (r.rol) as roles FROM usuarios u INNER JOIN roles r on u.id_rol = r.id_rol WHERE id_usuario= $iduser and estatus = 1");
	mysqli_close($conection);
	$result_sql = mysqli_num_rows($sql);

	if ($result_sql == 0) 
	{
		header('location: lista_usuarios.php');	
	}else{

		$option ='';

		while ($data = mysqli_fetch_array($sql)) {
			
			$iduser = $data['id_usuario'];
			$nombre = $data['Nombre'];
			$apellido = $data['Apellido'];
			$correo = $data['Correo'];
			$clave = $data['Clave'];
			$celular = $data['Celular'];
			$cedula = $data['Cedula'];
			$direccion = $data['Direccion'];
			$idrol = $data['id_rol'];
			$roles = $data['roles'];

			if ($idrol == 1) {
				$option = '<option value="'.$idrol.'" select>'.$roles.'</option>';
			}else if ($idrol == 2) {
				$option = '<option value="'.$idrol.'" select>'.$roles.'</option>';
			}else if ($idrol == 3) {
				$option = '<option value="'.$idrol.'" select>'.$roles.'</option>';
			}
		}
	}


?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Usuario</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1><i class="fas fa-edit"></i> Editar Usuario</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<form action="" method="post">
				<input type="hidden" name="id" value="<?php echo $iduser; ?>">
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>">
				<label for="apellido">Apellido</label>
				<input type="text" name="apellido" id="apellido" placeholder="Apellido" value="<?php echo $apellido; ?>">
				<label for="correo">Correo</label>
				<input type="email" name="correo" id="correo" placeholder="Correo" value="<?php echo $correo; ?>">
				<label for="clave">Clave</label>
				<input type="password" name="clave" id="clave" placeholder="Clave" value="<?php echo $clave; ?>">
				<label for="celular">Celular</label>
				<input type="number" name="celular" id="celular" placeholder="Celular" value="<?php echo $celular; ?>">
				<label for="cedula">Cedula</label>
				<input type="number" name="cedula" id="cedula" placeholder="Cedula" value="<?php echo $cedula; ?>">
				<label for="direccion">Direccion</label>
				<input type="text" name="direccion" id="direccion" placeholder="Direccion" value="<?php echo $direccion; ?>">
				<label for="rol">Rol</label>
				<?php 
					include "../conexion.php";
					$query_rol = mysqli_query($conection,"SELECT * FROM roles");
					mysqli_close($conection);
					$result_rol = mysqli_num_rows($query_rol);

				?>
				<select name="rol" id="rol" class="notItemOne">

					<?php 

						echo $option;
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
				<button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar Usuario</button>
			</form>

		</div>

	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>