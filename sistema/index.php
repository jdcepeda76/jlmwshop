<?php 
	session_start();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>JLM || WORKSHOP</title>
</head>
<body>
	<?php
		 include "includes/header.php"; 
		 include "../conexion.php";

		 //DATOS EMPRESA
		 $nit = '';
		 $nombre = '';
		 $telefono = '';
		 $email = '';
		 $direcion = '';

		 $query_empresa = mysqli_query($conection,"SELECT * FROM configuracion");
		 $row_empresa = mysqli_num_rows($query_empresa);
		 if ($row_empresa > 0) {
		 	while ($arrInfoEmpresa = mysqli_fetch_assoc($query_empresa)) {
		 		$nit = $arrInfoEmpresa['nit'];
		 		$nombre = $arrInfoEmpresa['nombre'];
		 		$telefono = $arrInfoEmpresa['telefono'];
		 		$email = $arrInfoEmpresa['email'];
		 		$direcion = $arrInfoEmpresa['direccion'];
		 	}
		 }




		 $query_dash = mysqli_query($conection,"CALL dataDashBoard();");
		 $result_dash = mysqli_num_rows($query_dash);
		 if ($result_dash > 0) {
		 	$data_dash = mysqli_fetch_assoc($query_dash);
		 	mysqli_close($conection);
		 }
	?>
	<section id="container">
		<div class="divContainer">
			<div>
				<h1 class="titlePanelControl">PANEL DE CONTROL</h1>
			</div>
			<div class="dashboard">
				<a href="lista_usuarios.php">
					<i class="fas fa-users"></i>
					<p>
						<strong>USUARIOS</strong><br>
						<span><?= $data_dash['usuarios'] ?></span>
					</p>
				</a>

				<a href="lista_proveedor.php">
					<i class="fas fa-user-tie"></i>
					<p>
						<strong>PROVEEDORES</strong><br>
						<span><?= $data_dash['proveedores'] ?></span>
					</p>
				</a>

				<a href="lista_pedido.php">
					<i class="fas fa-cubes"></i>
					<p>
						<strong>PEDIDOS</strong><br>
						<span><?= $data_dash['pedidos'] ?></span>
					</p>
				</a>

				<a href="lista_producto.php">
					<i class="fas fa-tshirt"></i>
					<p>
						<strong>PRODUCTOS</strong><br>
						<span><?= $data_dash['productos'] ?></span>
					</p>
				</a>

				<a href="facturas.php">
					<i class="far fa-file-alt"></i>
					<p>
						<strong>FACTURAS</strong><br>
						<span><?= $data_dash['factura_pedido'] ?></span>
					</p>
				</a>
			</div>
		</div>

		<div class="divInfoSistema">
			<div>
				<h1 class="titlePanelControl">CONFIGURACIÓN</h1>
			</div>
			<div class="containerPerfil">
				<div class="containerDataUser">
					<div class="logoUser">
						<img src="img/logoUser.png">
					</div>
					<div class="divDataUser">
						<h4>Informacion Personal</h4>

						<div>
							<label>Nombre:</label>  <span><?= $_SESSION['nombre']; ?></span>
						</div>
						<div>
							<label>Apellido:</label>  <span><?= $_SESSION['apellido']; ?></span>
						</div>
						<div>
							<label>Correo:</label>  <span><?= $_SESSION['correo']; ?></span>
						</div>

						<h4>Datos Usuario</h4>
						<div>
							<label>Rol:</label>  <span><?= $_SESSION['rol']; ?></span>
						</div>
						<div>
							<label>Usuario:</label>  <span><?= $_SESSION['nombre']; ?></span>
						</div>

						<h4>Cambiar Contraseña</h4>
						<form action="" method="post" name="frmChangePass" id="frmChangePass">
							<div>
								<input type="password" name="txtPassUser" id="txtPassUser" placeholder="Contraseña Actual" required>
							</div>
							<div>
								<input class="newPass" type="password" name="txtNewPassUser" id="txtNewPassUser" placeholder="Nueva Contraseña" required>
							</div>
							<div>
								<input class="newPass" type="password" name="txtPassConfirm" id="txtPassConfirm" placeholder="Confirmar Contraseña" required>
							</div>
							<div class="alertChangePass" style="display: none;">
							</div>
							<div>
								<button type="submit" class="btn_save btnChangesPass"><i class="fas fa-key"></i> Cambiar Contraseña</button>
							</div>
						</form>
					</div>
				</div>

				<?php if ($_SESSION['rol'] == 1){ ?>
				<div class="containerDataEmpresa">
					<div class="logoEmpresa">
						<img src="img/logoEmpresa.png">
					</div>
					<h4>Datos de la Empresa</h4>

					<form action="" method="post" name="frmEmpresa" id="frmEmpresa">
						<input type="hidden" name="action" value="updateDetaEmpresa">

						<div>
							<label>Nit:</label><input type="text" name="txtNit" id="txtNit" placeholder="NIT" value="<?= $nit; ?>" required>
						</div>
						<div>
							<label>Nombre:</label><input type="text" name="txtNombre" id="txtNombre" placeholder="Nombre" value="<?= $nombre; ?>" required>
						</div>
						<div>
							<label>Telefono:</label><input type="number" name="txtTelEmpresa" id="txtTelEmpresa" placeholder="Telefono" value="<?= $telefono; ?>" required>
						</div>
						<div>
							<label>Email:</label><input type="email" name="txtEmailEmpresa" id="txtEmailEmpresa" placeholder="Email" value="<?= $email; ?>" required>
						</div>
						<div>
							<label>Direccion:</label><input type="text" name="txtDireccion" id="txtDireccion" placeholder="Direccion" value="<?= $direcion; ?>" required>
						</div>
						<div class="alertFormEmpresa" style="display: none;"></div>
						<div>
							<button type="submit" class="btn_save btnChangesPass"><i class="far fa-save fa-lg"></i> Guardas Datos</button>
						</div>
					</form>
				</div>
			<?php } ?>
			</div>
		</div>

	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>