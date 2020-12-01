<?php 
	session_start(); 
	if ($_SESSION['rol'] != 1) 
	{
		header("location: ./");	
	}
	include "../conexion.php";
	if (!empty($_POST)) 
	{
		if ($_POST['id_usuario'] == 1) {
			header("location: lista_usuarios.php");
			mysqli_close($conection);
			exit;
		}

		$idusuario = $_POST['id_usuario'];

	//	$query_delete = mysqli_query($conection,"DELETE FROM usuarios WHERE id_usuario =$idusuario ");

		$query_delete = mysqli_query($conection,"UPDATE usuarios SET estatus = 0 WHERE id_usuario =$idusuario ");
		mysqli_close($conection);
		if ($query_delete) {
			header("location: lista_usuarios.php");
		}else{
			echo "Error al eliminar";
		}
	}

	if (empty($_REQUEST['id']) || $_REQUEST['id'] == 1) 
	{
		header("location: lista_usuarios.php");	
		mysqli_close($conection);
	}else{
		
		$idusuario = $_REQUEST['id']; 

		$query = mysqli_query($conection,"SELECT u.Nombre, u.Correo, r.id_rol 
										FROM usuarios u 
										INNER JOIN roles r ON u.id_rol = r.id_rol 
										WHERE u.id_usuario = $idusuario");
		mysqli_close($conection);
		$result = mysqli_num_rows($query);

		if ($result > 0) 
		{
			while ($data = mysqli_fetch_array($query)) {
				$nombre = $data['Nombre'];
				$correo = $data['Correo'];
				$rol = $data['id_rol'];
			}
		}else{
			header("location: lista_usuarios.php");	
		}
	}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>JLM || WORKSHOP</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="data_delete">
			<i class="fas fa-user-times fa-7x" style="color: #e66262"></i>
			<br>
			<br>
			<h2>¿Está seguro de eliminar el usuario?</h2>
			<p>Nombre: <span><?php echo $nombre; ?></span></p>
			<p>Email: <span><?php echo $correo; ?></span></p>
			<p>Rol: <span><?php echo $rol; ?></span></p>

			<form method="post" action="">
				<input type="hidden" name="id_usuario" value="<?php echo $idusuario; ?>">
				<a href="lista_usuarios.php" class="btn_cancel"><i class="fas fa-ban"></i> Cancelar</a>
				<button type="submit" class="btn_ok"><i class="fas fa-trash-alt"></i> Eliminar</button>
			</form>
		</div>
	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>