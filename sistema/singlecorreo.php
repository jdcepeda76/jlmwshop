<?php 
	session_start(); 
	
	include "../conexion.php";
	if (!empty($_POST)) 
	{
		$alert='';
		if (empty($_POST['de']) || empty($_POST['para']))
		{
			$alert='<p class="msg_error">Todos los campos son requeridos</p>';
		}else{

			$de = $_POST['de'];
			$para = $_POST['para'];
			$asunto = $_POST['asunto'];


				if ($para['para'] > 0)  {
					$alert='<p class="msg_save">Envio correctamente</p>';
				}else{
					$alert='<p class="msg_error">Error al envio.</p>';
				}
			}
			mysqli_close($conection);
	}

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Nuevo Correo</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1><i class="fas fa-envelope-open-text"></i> Nuevo correo</h1>
			<hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<form action="includes/singlemail.php" name="enviar" method="post">

				<label for="para">Para</label>
                <input type="text" name="email" id="email" placeholder="Email">

				<label for="asunto">Asunto</label>
                <input type="text" name="asunto" id="asunto" placeholder="Asunto">
                <label for="asunto">Mensaje</label>
				<textarea name="mensaje" id="mensaje" cols="54" rows="15"></textarea>
				
				<button type="submit" class="btn_save" name="enviar"><i class="fas fa-paper-plane"></i> Enviar correo</button>
			</form>

		</div>

	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>