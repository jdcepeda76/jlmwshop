<?php 
	session_start(); 
	if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) 
	{
		header("location: ./");	
	}
	include "../conexion.php";
	if (!empty($_POST)) 
	{
		if (empty($_POST['id_proveedor'])) {
			header("location: lista_proveedor.php");
			mysqli_close($conection);
		}

		$idproveedor = $_POST['id_proveedor'];



		$query_delete = mysqli_query($conection,"UPDATE proveedores SET estatus = 0 WHERE id_proveedor =$idproveedor ");
		mysqli_close($conection);
		if ($query_delete) {
			header("location: lista_proveedor.php");
		}else{
			echo "Error al eliminar";
		}
	}

	if (empty($_REQUEST['id'])) 
	{
		header("location: lista_proveedor.php");	
		mysqli_close($conection);
	}else{
		
		$idproveedor = $_REQUEST['id']; 

		$query = mysqli_query($conection,"SELECT * FROM proveedores WHERE id_proveedor = $idproveedor");
		mysqli_close($conection);
		$result = mysqli_num_rows($query);

		if ($result > 0) 
		{
			while ($data = mysqli_fetch_array($query)) {
				$nit = $data['nit'];
				$nombre = $data['nombre'];
				$telefono = $data['telefono'];
			}
		}else{
			header("location: lista_proveedor.php");	
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
			<h2>¿Está seguro de eliminar el proveedor?</h2>
			<p>NIT: <span><?php echo $nit; ?></span></p>
			<p>Nombre: <span><?php echo $nombre; ?></span></p>
			<p>Telefono: <span><?php echo $telefono; ?></span></p>

			<form method="post" action="">
				<input type="hidden" name="id_proveedor" value="<?php echo $idproveedor; ?>">
				<a href="lista_proveedor.php" class="btn_cancel"><i class="fas fa-ban"></i> Cancelar</a>
				<button type="submit" class="btn_ok"><i class="fas fa-trash-alt"></i> Eliminar</button>
			</form>
		</div>
	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>