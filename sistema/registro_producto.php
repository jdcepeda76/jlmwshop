<?php 
	session_start(); 
	
	include "../conexion.php";
	if (!empty($_POST)) 
	{
		$alert='';
		if (empty($_POST['id_producto']) || empty($_POST['id_proveedor']) || empty($_POST['precio']) || $_POST['precio'] <= 0 || empty($_POST['cantidad']) || $_POST['cantidad'] <= 0 || empty($_POST['id_tela']) || empty($_POST['id_talla']))
		{
			$alert='<p class="msg_error">Todos los campos son requeridos</p>';
		}else{

			$producto = $_POST['id_producto'];
			$nombre = $_POST['nombre'];
			$proveedor = $_POST['id_proveedor'];
			$precio = $_POST['precio'];
			$cantidad = $_POST['cantidad'];
			$id_tela = $_POST['id_tela'];
			$id_talla = $_POST['id_talla'];
			$id_usuario = $_SESSION['idUser'];

			$foto = $_FILES['foto'];
			$nombre_foto = $foto['name'];
			$type = $foto['type'];
			$url_temp = $foto['tmp_name'];
			$imgProducto = 'img_producto.png';

			if ($nombre_foto != '') {
				$destino = 'img/uploads/';
				$img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
				$imgProducto = $img_nombre.'.jpg';
				$src = $destino.$imgProducto;
			}



				$query_insert = mysqli_query($conection,"INSERT INTO productos(producto,nombre,precio,cantidad,tela,codigo,id_usuario,foto) 
														VALUES('$nombre','$proveedor','$precio','$cantidad','$id_tela','$id_talla','$id_usuario','$imgProducto')");

				if ($query_insert) {
					if ($nombre_foto != '') {
						move_uploaded_file($url_temp, $src);
					}
					$alert='<p class="msg_save">Producto registrado correctamente</p>';
				}else{
					$alert='<p class="msg_error">Error al registrar el producto.</p>';
				}
			}
	}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro Producto</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1><i class="fas fa-plus"></i> Crear Producto</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<form action="" method="post" enctype="multipart/form-data">
				<label for="id_producto">Producto</label>

				<?php 
					$query_producto = mysqli_query($conection,"SELECT Id_producto, producto FROM productos WHERE estatus = 1 ORDER BY producto ASC ");
					$result_producto = mysqli_num_rows($query_producto);
				?>

				<select name="id_producto" id="id_producto">

					<?php 
						if ($result_producto > 0) {
							while ($producto = mysqli_fetch_array($query_producto)) {
								
					?>
						<option value="<?php echo $producto['Id_producto']; ?>"><?php echo $producto['producto']; ?></option>
					<?php 
							}
						}
					 ?>
					
				</select>
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre">
				<label for="id_proveedor">Proveedor</label>

				<?php 
					$query_proveedor = mysqli_query($conection,"SELECT id_proveedor, nombre FROM proveedores WHERE estatus = 1 ORDER BY nombre ASC ");
					$result_proveedor = mysqli_num_rows($query_proveedor);
				?>

				<select name="id_proveedor" id="id_proveedor">

					<?php 
						if ($result_proveedor > 0) {
							while ($proveedor = mysqli_fetch_array($query_proveedor)) {
								
					?>
						<option value="<?php echo $proveedor['id_proveedor']; ?>"><?php echo $proveedor['nombre']; ?></option>
					<?php 
							}
						}
					 ?>
					
				</select>
				<label for="precio">Precio</label>
				<input type="number" name="precio" id="precio" placeholder="Precio">
				<label for="cantidad">Cantidad</label>
				<input type="number" name="cantidad" id="cantidad" placeholder="Cantidad">
				
				<label for="id_tela">Tela</label>

				<?php 
					$query_tela = mysqli_query($conection,"SELECT id_tela, tela FROM tipo_tela ORDER BY tela ASC ");
					$result_tela = mysqli_num_rows($query_tela);
				?>

				<select name="id_tela" id="id_tela">

					<?php 
						if ($result_tela > 0) {
							while ($tela = mysqli_fetch_array($query_tela)) {
								
					?>
						<option value="<?php echo $tela['id_tela']; ?>"><?php echo $tela['tela']; ?></option>
					<?php 
							}
						}
					 ?>
				</select>

				<label for="id_talla">Talla</label>

				<?php 
					$query_talla = mysqli_query($conection,"SELECT id_talla, codigo FROM tallas ORDER BY codigo ASC ");
					$result_talla = mysqli_num_rows($query_talla);
					mysqli_close($conection);
				?>

				<select name="id_talla" id="id_talla">

					<?php 
						if ($result_talla > 0) {
							while ($talla = mysqli_fetch_array($query_talla)) {
								
					?>
						<option value="<?php echo $talla['id_talla']; ?>"><?php echo $talla['codigo']; ?></option>
					<?php 
							}
						}
					 ?>
				</select>
				<div class="photo">
					<label for="foto">Foto</label>
				        <div class="prevPhoto">
				        <span class="delPhoto notBlock">X</span>
				        <label for="foto"></label>
				        </div>
				        <div class="upimg">
				        <input type="file" name="foto" id="foto">
				        </div>
				        <div id="form_alert"></div>
				</div>

				<button type="submit" class="btn_save"><i class="fas fa-save"></i> Crear Producto</button>
			</form>

		</div>

	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>