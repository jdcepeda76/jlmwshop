<?php 
	session_start(); 
	if ($_SESSION['rol'] != 1 AND $_SESSION['rol'] != 2) 
	{
		header("location: ./");	
	}
	include "../conexion.php";
	if (!empty($_POST)) 
	{
		$alert='';
		if (empty($_POST['nombre']) || empty($_POST['ingreso']) || empty($_POST['salida']) || empty($_POST['total']) || empty($_POST['proveedor']))
		{
			$alert='<p class="msg_error">Todos los campos son requeridos</p>';
		}else{

			$idpedido 	 = $_POST['id'];
			$nombre      = $_POST['nombre'];
			$ingreso 	 = $_POST['ingreso'];
			$salida  	 = $_POST['salida'];
			$total  	 = $_POST['total'];
			$proveedor = $_POST['proveedor'];

		

			$sql_update = mysqli_query($conection,"UPDATE pedidos
												   SET nombre = '$nombre', f_ingreso= '$ingreso', f_salida='$salida', total = $total, id_proveedor = $proveedor
												   WHERE id_pedido= $idpedido");
			
			if ($sql_update) 
			{
				$alert='<p class="msg_save">Pedido actualizado correctamente</p>';
			}else{
				$alert='<p class="msg_error">Error al actualizar el Pedido.</p>';
			}
		}
	}

	//MOSTRAR DATOS
	
	if (empty($_REQUEST['id'])) 
	{
		header('location: lista_pedido.php');	
		mysqli_close($conection);
	}
	$idpedido = $_REQUEST['id'];

	$sql = mysqli_query($conection,"SELECT * FROM pedidos WHERE id_pedido= $idpedido and estatus = 1");
	mysqli_close($conection);
	$result_sql = mysqli_num_rows($sql);

	if ($result_sql == 0) 
	{
		header('location: lista_pedido.php');	
	}else{

		while ($data = mysqli_fetch_array($sql)) {
			
			$idpedido 	    = $data['id_pedido'];
			$nombre 	    = $data['nombre'];
			$ingreso 	    = $data['f_ingreso'];
			$salida         = $data['f_salida'];
			$total          = $data['total'];
			$idproveedor    = $data['id_proveedor'];
		}
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Pedido</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1><i class="fas fa-edit"></i> Editar Pedido</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<form action="" method="post">
				<input type="hidden" name="id" value="<?php echo $idpedido; ?>">
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>">
				<label for="ingreso">Ingreso</label>
				<input type="date" name="ingreso" id="ingreso" placeholder="Ingreso" value="<?php echo $ingreso; ?>">
				<label for="salida">Salida</label>
				<input type="date" name="salida" id="salida" placeholder="Salida" value="<?php echo $salida; ?>">
				<label for="total">Total</label>
				<input type="number" name="total" id="total" placeholder="Total" value="<?php echo $total; ?>">
				<label for="proveedor">Proveedor</label>
				<?php 
					include "../conexion.php";
					$query_ped = mysqli_query($conection,"SELECT * FROM proveedores");
					mysqli_close($conection);
					$result_ped = mysqli_num_rows($query_ped);

				?>
				<select name="proveedor" id="proveedor">

					<?php 
						if ($result_ped > 0) 
						{
							while ($proveedor = mysqli_fetch_array($query_ped)) {
					?>
							<option value="<?php echo $proveedor["id_proveedor"]; ?>"><?php echo $proveedor["nombre"]; ?></option>
					<?php 
							}	
						}
					 ?>
				</select>
				<button type="submit" class="btn_save"><i class="fas fa-edit"></i> Editar Pedido</button>
			</form>
		</div>

	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>