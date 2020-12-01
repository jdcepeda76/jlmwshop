<?php 
	session_start(); 
	if ($_SESSION['rol'] != 1) 
	{
		header("location: ./");	
	}
	include "../conexion.php";

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>LISTA FACTURAS</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<h1><i class="far fa-file-alt"></i> LISTA FACTURAS</h1>
		<a href="nueva_factura.php" class="btn_new btnNewFactura"><i class="fas fa-plus"></i> Nueva Factura</a>
		<form action="buscar_factura.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="No. Factura">
			<button type="submit" class="btn_search"><i class="fas fa-search"></i></button>
		</form>

		<div>
			<h4>Filtrar por fecha</h4>
			<form action="buscar_factura.php" method="get" class="form_search_date"> 
				<label>DE: </label>
				<input type="date" name="fecha_de" id="fecha_de" required>
				<label> A </label>
				<input type="date" name="fecha_a" id="fecha_a" required>
				<button type="submit" class="btn_view"><i class="fas fa-search"></i></button>
			</form>
		</div>

	<div class="containerTable">
		<table>
			<tr>
				<th>No</th>
				<th>Fecha / Hora</th>
				<th>Proveedor</th>
				<th>Administrador</th>
				<th>Estado</th>
				<th class="textright">Total Factura</th>
				<th class="textright">Acciones</th>
			</tr>

			<?php  

				//PAGINADOR
				$sql_registe = mysqli_query($conection,"SELECT COUNT(*) AS total_registro FROM factura_pedido WHERE estatus != 10");
				$result_register = mysqli_fetch_array($sql_registe);
				$total_registro = $result_register['total_registro'];

				$por_pagina = 15;

				if (empty($_GET['pagina'])) 
				{
					$pagina = 1;
				}else{
					$pagina = $_GET['pagina'];
				}

				$desde = ($pagina-1) * $por_pagina;
				$total_paginas = ceil($total_registro / $por_pagina);



				$query = mysqli_query($conection,"SELECT f.nofactura,f.fecha,f.totalfactura,f.id_proveedor,f.estatus,
														u.nombre as administrador,
														pr.nombre as proveedor
													FROM factura_pedido f
													INNER JOIN usuarios u
													ON f.usuario = u.id_usuario
													INNER JOIN proveedores pr
													ON f.id_proveedor = pr.id_proveedor
													WHERE f.estatus != 10
													ORDER BY f.fecha DESC LIMIT $desde,$por_pagina");
				mysqli_close($conection);
				$result = mysqli_num_rows($query);
				if ($result > 0) 
					{
						while ($data = mysqli_fetch_array($query)) {
							
							if ($data["estatus"] == 1) {
								$estado = '<span class="pagada">Pagada</span>';
							}else{
								$estado = '<span class="anulada">Anulada</span>';
							}

				?>						
					<tr id="row_<?php echo $data["nofactura"]; ?>">
						<td><?php echo $data["nofactura"]; ?></td>
						<td><?php echo $data["fecha"]; ?></td>
						<td><?php echo $data["proveedor"]; ?></td>
						<td><?php echo $data["administrador"]; ?></td>
						<td class="estado"><?php echo $estado; ?></td>
						<td class="textright totalfactura"><span>$.</span><?php echo $data["totalfactura"]; ?></td>
						<td>
							<div class="div_acciones">
								<div>
									<button class="btn_view view_factura" type="button" pr="<?php echo $data["id_proveedor"]; ?>" f="<?php echo $data["nofactura"]; ?>"><i class="fas fa-eye"></i></button>
								</div>
							<?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
									if ($data["estatus"] == 1) 
									{
						    ?>
							<div class="div_factura">
								<button class="btn_anular anular_factura" fac="<?php echo $data["nofactura"]; ?>"><i class="fas fa-ban"></i></button>
							</div>
							<?php    }else{ ?>
							<div class="div_factura">
								<button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>
							</div>
						<?php          		} 
								}  
						?>
							</div>
						</td>
					</tr>
			<?php 		

					}
				}		

			?>


			
		</table>
	</div>
		<div class="paginador">
			<ul>

				<?php 
				if ($pagina != 1) 
					{	
				 ?>
					<li><a href="?pagina=<?php echo 1; ?>"><i class="fas fa-step-backward"></i></a></li>
					<li><a href="?pagina=<?php echo $pagina-1; ?>"><i class="fas fa-backward"></i></a></li>
				<?php 
				}	
					for ($i=1; $i <= $total_paginas; $i++) {
						if ($i == $pagina) {
							echo '<li class="pageSelected">'.$i.'</li>';	
						}else{
							echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
						}
					}
					if ($pagina != $total_paginas) 
					{
				?>
					<li><a href="?pagina=<?php echo $pagina+1; ?>"><i class="fas fa-forward"></i></a></li>
					<li><a href="?pagina=<?php echo $total_paginas; ?>"><i class="fas fa-step-forward"></i></a></li>
				<?php } ?>	
			</ul>
		</div>

	</section>


	<?php include "includes/footer.php"; ?>
</body>
</html>