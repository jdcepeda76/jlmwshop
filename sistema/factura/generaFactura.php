<?php

	//print_r($_REQUEST);
	//exit;
	//echo base64_encode('2');
	//exit;
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}

	include "../../conexion.php";
	require_once '../pdf/vendor/autoload.php';
	use Dompdf\Dompdf;

	if(empty($_REQUEST['pr']) || empty($_REQUEST['f']))
	{
		echo "No es posible generar la factura.";
	}else{
		$proveedor = $_REQUEST['pr'];
		$noFactura = $_REQUEST['f'];
		$anulada = '';

		$query_config   = mysqli_query($conection,"SELECT * FROM configuracion");
		$result_config  = mysqli_num_rows($query_config);
		if($result_config > 0){
			$configuracion = mysqli_fetch_assoc($query_config);
		}


		$query = mysqli_query($conection,"SELECT f.nofactura, DATE_FORMAT(f.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(f.fecha,'%H:%i:%s') as  hora, f.id_proveedor, f.estatus,
												 v.nombre as vendedor,
												 pr.nit, pr.nombre, pr.telefono
											FROM factura_pedido f
											INNER JOIN usuarios v
											ON f.usuario = v.id_usuario
											INNER JOIN proveedores pr
											ON f.id_proveedor = pr.id_proveedor
											WHERE f.nofactura = $noFactura AND f.id_proveedor = $proveedor  AND f.estatus != 10 ");

		$result = mysqli_num_rows($query);
		if($result > 0){

			$factura = mysqli_fetch_assoc($query);
			$no_factura = $factura['nofactura'];

			if($factura['estatus'] == 2){
				$anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
			}

			$query_productos = mysqli_query($conection,"SELECT p.producto,dt.cantidad,dt.precio,(dt.cantidad * dt.precio) as precio_total
														FROM factura_pedido f
														INNER JOIN detalle_factura dt
														ON f.nofactura = dt.nofactura
														INNER JOIN productos p
														ON dt.Id_producto = p.Id_producto
														WHERE f.nofactura = $no_factura ");
			$result_detalle = mysqli_num_rows($query_productos);

			ob_start();
		    include(dirname('__FILE__').'/factura.php');
		    $html = ob_get_clean();

			// instantiate and use the dompdf class
			$dompdf = new Dompdf();

			$dompdf->loadHtml($html);
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('letter', 'portrait');
			// Render the HTML as PDF
			$dompdf->render();
			//limpiar el buffer
			ob_get_clean();
			// Output the generated PDF to Browser
			$dompdf->stream('factura_'.$noFactura.'.pdf',array('Attachment'=>0));
			exit;
		}
	}

?>