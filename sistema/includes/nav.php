<nav class="viewMenu">
			<ul>
				<li><a href="index.php"><i class="fas fa-home"></i> Inicio</a></li>
				<?php 
					if ($_SESSION['rol'] == 1) { 
					 ?>
				<li class="principal">	
					<a href="#"><i class="fas fa-users"></i> Usuarios <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="registro_usuario.php"><i class="fas fa-user-plus"></i> Nuevo Usuario</a></li>
						<li><a href="lista_usuarios.php"><i class="fas fa-users"></i> Lista de Usuarios</a></li>
					</ul>
				</li>
		
				<li class="principal">
					
					<a href="#"><i class="fas fa-building"></i> Proveedor <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="registro_proveedor.php"><i class="fas fa-user-tie"></i> Nuevo Proveedor</a></li>
						<li><a href="lista_proveedor.php"><i class="fas fa-building"></i> Lista de Proveedor</a></li>
					</ul>
				</li>
			
				<li class="principal">
					<a href="#"><i class="fas fa-cubes"></i> Pedidos <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="registro_pedido.php"><i class="fas fa-layer-group"></i> Nuevo Pedido</a></li>
						<li><a href="lista_pedido.php"><i class="fas fa-cubes"></i> Lista de Pedidos</a></li>
					</ul>
				</li>
			<?php } ?>
				<li class="principal">
					<a href="#"><i class="fas fa-tshirt"></i> Productos <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						<?php 
							if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { 
					 	?>
						<li><a href="registro_producto.php"><i class="fas fa-plus"></i> Nuevo Producto</a></li>
					<?php } ?>
						<li><a href="lista_producto.php"><i class="fas fa-tshirt"></i> Lista de Productos</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#"><i class="far fa-file-alt"></i> Facturas <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="nueva_factura.php"><i class="fas fa-plus"></i> Nuevo Factura</a></li>
						<li><a href="facturas.php"><i class="far fa-file-alt"></i> Facturas</a></li>
					</ul>
				</li>
				<li class="principal">
					<?php 
					if ($_SESSION['rol'] == 1) { 
					 ?>
					<a href="#"><i class="fas fa-envelope"></i> Correo <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="singlecorreo.php">Nuevo correo</a></li>
						<li><a href="correosmasivos.php">Enviar correo masivo</a></li>
					</ul>
				</li>
				<?php } ?>
			</ul>
		</nav>