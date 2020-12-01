	<?php 

	if (empty($_SESSION['active'])) 
	{
		header('location: ../');
	}
	 ?>
	<header>
		<div class="header">
			<a href="#" class="btnMenu"><i class="fas fa-bars"></i></a>
			<h1><img src="./img/logo_white_alt.png" alt="Logo" style="width: 150px; padding: 13px 5px 5px 5px;"></h1>
			<div class="optionsBar">
				<span class="user"><?php echo $_SESSION['nombre'].' - ' .$_SESSION['rol']; ?></span>
				<img class="photouser" src="img/user.png" alt="Usuario">
				<a href="salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
			</div>
		</div>
		<?php include "nav.php"; ?>
	</header>

	<div class="modal">
		<div class="bodyModal">
		</div>	
	</div>