<?php 
	session_start(); 
	
	include "../conexion.php";
	if (!empty($_POST)) 
	{
		$alert='';
		if (empty($_POST['nombre']) || empty($_POST['ingreso']) || empty($_POST['salida']) || empty($_POST['total']) || empty($_POST['proveedor']))
		{
			$alert='<p class="msg_error">Todos los campos son requeridos</p>';
		}else{

			$nombre = $_POST['nombre'];
			$ingreso = $_POST['ingreso'];
			$salida = $_POST['salida'];
			$total = $_POST['total'];
			$id_usuario = $_SESSION['idUser'];
			$proveedor = $_POST['proveedor'];

				
				$query_insert = mysqli_query($conection,"INSERT INTO pedidos(nombre,f_ingreso,f_salida,total,id_usuario,id_proveedor) 
														VALUES('$nombre','$ingreso','$salida','$total','$id_usuario','$proveedor')");

				if ($query_insert) {
					$alert='<p class="msg_save">Registro correctamente</p>';
				}else{
					$alert='<p class="msg_error">Error al registrar insumo.</p>';
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
	<title>Registro Pedido</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1><i class="fas fa-layer-group"></i> Crear Pedido</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<form action="" method="post">
				<label for="nombre">Tipo</label>
				<input type="text" name="nombre" id="nombre" placeholder="Tipo">
				<label for="ingreso">Ingreso</label>
				<input type="date" name="ingreso" id="ingreso" placeholder="Ingreso">
				<label for="salida">Salida</label>
				<input type="date" name="salida" id="salida" placeholder="Salida">
				<label for="total">Total Camisas</label>
				<input type="number" name="total" id="total" placeholder="Camisas">
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
				<button type="submit" class="btn_save"><i class="fas fa-save"></i> Crear Pedido</button>
			</form>

		</div>

	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>