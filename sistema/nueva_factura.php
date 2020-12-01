<?php 
	session_start();
	include "../conexion.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<?php include "includes/scripts.php"; ?>
	<title>Nueva Factura</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	
	<section id="container">
		<div class="title_page">
			   <h1><i class="fas fa-cube"></i> Nueva Factura</h1>
		</div>
		<div class="datos_proveedor">
			<div class="action_proveedor">
			<h4>Datos del Proveedor</h4>
			<a href="#" class="btn_new btn_new_proveedor btnNewProveedor"><i class="fas fa-plus"></i> Nuevo Proveedor</a>
		</div>
		<form name="form_new_proveedor_factura" id="form_new_proveedor_factura" class="datos">
			<input type="hidden" name="action" value="addProveedor" >
			<input type="hidden" name="id_proveedor" id="id_proveedor" value="" required>
			<div class="wd30">
				<label>Nit</label>
				<input type="text" name="nit_proveedor" id="nit_proveedor">
			</div>
			<div class="wd30">
				<label>Nombre</label>
				<input type="text" name="nombre_proveedor" id="nombre_proveedor" disabled required>
			</div>
			<div class="wd30">
				<label>Telefono</label>
				<input type="number" name="telefono_proveedor" id="telefono_proveedor" disabled required>
			</div>
			<div id="div_registro_proveedor" class="wd100">
				<button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i> Guardar</button>
			</div>
		</form>
	</div>
	<div class="datos_pedido">
		<h4>Datos Pedidos</h4>
		<div class="datos">
			<div class="wd50">
				<label>Administrador</label>
				<p><?php echo $_SESSION['nombre']; ?></p>
			</div>
			<div class="wd50">
				<label>Acciones</label>
				<div id="acciones_pedido">
					<a href="#" class="btn_ok textcenter" id="btn_anular_pedido"><i class="fas fa-ban"></i> Anular</a>
					<a href="#" class="btn_new textcenter" id="btn_facturar_pedido" style="display: none;"><i class="far fa-edit"></i> Procesar</a>
				</div>
			</div>
		</div>
	</div>
<div class="containerTable">
	
	<table class="tbl_pedido">
		<thead>
			<tr>
				<th width="100px">ID</th>
				<th>Producto</th>
				<th>Existencia</th>
				<th width="100px">Cantidad</th>
				<th class="textright">Precio</th>
				<th class="textright">Precio Total</th>
				<th> Accion</th>
			</tr>
			<tr>
				<td><input type="text" name="txt_Id_producto" id="txt_Id_producto"></td>
				<td id="txt_producto">-</td>
				<td id="txt_cantidad">-</td>
				<td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
				<td id="txt_precio" class="textright">0.00</td>
				<td id="txt_precio_total" class="textright">0.00</td>
				<td> <a href="#" id="add_producto_pedido" class="link_add"><i class="fas fa-plus"></i> Agregar</a></td>
			</tr>
			<tr>
				<td>ID</td>
				<th colspan="2">Producto</th>
				<th>Cantidad</th>
				<th class="textright">Precio</th>
				<th class="textright">Precio Total</th>
				<th> Accion</th>
			</tr>
		</thead>
		<tbody id="detalle_pedido">
			<!--CONTENIDO AJAX -->
		</tbody>
		<tfoot id="detalle_totales">
			<!-- CONTENIDO AJAX -->
		</tfoot>
	</table>
</div>		
	</section>

	<?php include "includes/footer.php"; ?>

	<script type="text/javascript">
		$(document).ready(function(){
			var usuarioid = '<?php echo $_SESSION['idUser']; ?>';
			serchForDetalle(usuarioid);
		});
	</script>
</body>
</html>