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

			$idproveedor = $_POST['id'];
			$nit     	 = $_POST['nit'];
			$nombre 	 = $_POST['nombre'];
			$telefono  	 = $_POST['telefono'];

		

			$query = mysqli_query($conection,"SELECT * FROM proveedores
												WHERE (nit='$nit' AND id_proveedor != $idproveedor)");

				$result = mysqli_fetch_array($query);
			//	$result = count($result);
		


			if ($result > 0){
				$alert='<p class="msg_error">El NIT ya existe.</p>';
			}else{

				$sql_update = mysqli_query($conection,"UPDATE proveedores
													   SET nit = $nit, nombre ='$nombre', telefono='$telefono'
													   WHERE id_proveedor= $idproveedor");
				
				if ($sql_update) 
				{
					$alert='<p class="msg_save">Proveedor actualizado correctamente</p>';
				}else{
					$alert='<p class="msg_error">Error al actualizar Proveedor.</p>';
				}
			}
		}
	}

	//MOSTRAR DATOS
	
	if (empty($_REQUEST['id'])) 
	{
		header('location: lista_proveedor.php');	
		mysqli_close($conection);
	}
	$idproveedor = $_REQUEST['id'];

	$sql = mysqli_query($conection,"SELECT * FROM proveedores WHERE id_proveedor= $idproveedor and estatus = 1");
	mysqli_close($conection);
	$result_sql = mysqli_num_rows($sql);

	if ($result_sql == 0) 
	{
		header('location: lista_proveedor.php');	
	}else{

		while ($data = mysqli_fetch_array($sql)) {
			
			$idproveedor = $data['id_proveedor'];
			$nit 		 = $data['nit'];
			$nombre      = $data['nombre'];
			$telefono    = $data['telefono'];
			
		}
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Proveedor</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1><i class="fas fa-edit"></i> Editar Proveedor</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<form action="" method="post">
				<input type="hidden" name="id" value="<?php echo $idproveedor; ?>">
				<label for="nit">NIT</label>
				<input type="number" name="nit" id="nit" placeholder="NIT" value="<?php echo $nit; ?>">
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>">
				<label for="telefono">Telefono</label>
				<input type="number" name="telefono" id="telefono" placeholder="Telefono" value="<?php echo $telefono; ?>">
				<button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar Proveedor</button>
			</form>

		</div>

	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>