<?php 
	session_start(); 
	if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) 
	{
		header("location: ./");	
	}
	include "../conexion.php";
	if (!empty($_POST)) 
	{
		if (empty($_POST['idpedido'])) {
			header("location: lista_pedido.php");
			mysqli_close($conection);
		}

		$idpedido = $_POST['idpedido'];



		$query_delete = mysqli_query($conection,"UPDATE pedidos SET estatus = 0 WHERE id_pedido =$idpedido ");
		mysqli_close($conection);
		if ($query_delete) {
			header("location: lista_pedido.php");
		}else{
			echo "Error al eliminar";
		}
	}



	if (empty($_REQUEST['id'])) 
	{
		header("location: lista_pedido.php");	
		mysqli_close($conection);
	}else{
		
		$idpedido = $_REQUEST['id']; 

		$query = mysqli_query($conection,"SELECT * FROM pedidos WHERE id_pedido = $idpedido");
		mysqli_close($conection);
		$result = mysqli_num_rows($query);

		if ($result > 0) 
		{
			while ($data = mysqli_fetch_array($query)) {
				$nombre = $data['nombre'];
				$total = $data['total'];
			}
		}else{
			header("location: lista_pedido.php");	
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
			<i class="fas fa-cubes fa-7x" style="color: #e66262"></i>
			<br>
			<br>
			<h2>¿Está seguro de eliminar el pedido?</h2>
			<p>Nombre: <span><?php echo $nombre; ?></span></p>
			<p>Total: <span><?php echo $total; ?></span></p>

			<form method="post" action="">
				<input type="hidden" name="idpedido" value="<?php echo $idpedido; ?>">
				<a href="lista_pedido.php" class="btn_cancel"><i class="fas fa-ban"></i> Cancelar</a>
				<button type="submit" class="btn_ok"><i class="fas fa-trash-alt"></i> Eliminar</button>
			</form>
		</div>
	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>