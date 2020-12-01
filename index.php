<?php 

	$alert = '';
	session_start();
	if (!empty($_SESSION['active'])) 
	{
		header('location: sistema/');
	}else{

	if (!empty($_POST))
	{
		if(empty($_POST['usuario']) || empty($_POST['clave'])) 
		{
			$alert = 'Ingrese su usuario y su clave';

		}else{

			require_once "conexion.php";

			$user = mysqli_real_escape_string($conection,$_POST['usuario']);
			$pass = mysqli_real_escape_string($conection,$_POST['clave']);

			$query = mysqli_query($conection,"SELECT * FROM usuarios WHERE Nombre= '$user' AND Clave='$pass'");
			mysqli_close($conection);
			$result = mysqli_num_rows($query);

			if ($result > 0) 
			{
				$data = mysqli_fetch_array($query);

				$_SESSION['active'] = true;
				$_SESSION['idUser'] = $data['id_usuario'];
				$_SESSION['nombre'] = $data['Nombre'];
				$_SESSION['apellido'] = $data['Apellido'];
				$_SESSION['correo'] = $data['Correo'];
				$_SESSION['clave'] = $data['Clave'];
				$_SESSION['celular'] = $data['Celular'];
				$_SESSION['cedula'] = $data['Cedula'];
				$_SESSION['direccion'] = $data['Direccion'];
				$_SESSION['rol'] = $data['id_rol'];

				header('location: sistema/');
			}else{
				$alert = 'El usuario o contraseña son incorrectos';
				session_destroy();
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>LOGIN | JLM</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<section id="container">
		<form action="" method="POST">
			<h3>¡Bienvenido!</h3>
			<img src="./img/logo_white_alt.png" alt="Logo" style="width: 200px;">

			<input type="text" name="usuario" placeholder="Usuario">
			<input type="password" name="clave" placeholder="Contraseña">
			<div class="alert"><?php echo isset($alert) ? $alert : '' ?></div>
			<input type="submit" value="Iniciar Sesión">
		</form>
	</section>
</body>
</html>