<?php 
	include "../conexion.php";

	session_start();
//	print_r($_POST); exit;

	if (!empty($_POST)) {
		
		//extraer datos del producto
		if ($_POST['action'] == 'infoProducto') 
		{
			$producto_id = $_POST['producto'];

			$query = mysqli_query($conection,"SELECT Id_producto,producto,cantidad,precio FROM productos
												WHERE Id_producto = $producto_id AND estatus = 1");
			mysqli_close($conection);

			$result = mysqli_num_rows($query);
			if ($result > 0) {
				$data = mysqli_fetch_assoc($query);
				echo json_encode($data,JSON_UNESCAPED_UNICODE);
				exit;
			}
			echo 'error';
			exit;
		}

		//AGREGAR PRODUCTOS A ENTRADA
		if ($_POST['action'] == 'addProduct') 
		{
			if (!empty($_POST['cantidad']) || !empty($_POST['precio']) || !empty($_POST['producto_id']) ) 
					{
						$precio	     = $_POST['precio'];
						$cantidad    = $_POST['cantidad'];
						$producto_id = $_POST['producto_id'];
						$usuario_id = $_SESSION['idUser'];

						$query_insert = mysqli_query($conection,"INSERT INTO entradas(Id_producto,
																						cantidad,
																						precio,
																						id_usuario) 
																				VALUES($producto_id,
																						$cantidad,
																						$precio,
																						$usuario_id)"); 
						if ($query_insert) {
							//Ejecutar procedimiento almacenado
							$query_upd = mysqli_query($conection,"CALL actualizar_precio_producto ($precio,$cantidad,$producto_id)");
							$result_pro = mysqli_num_rows($query_upd);
							if ($result_pro > 0) {
								$data = mysqli_fetch_assoc($query_upd);
								$data['producto_id'] = $producto_id;
								echo json_encode($data,JSON_UNESCAPED_UNICODE);
								exit;
							}else{
								echo 'error';
							}
							mysqli_close($conection);
						}else{
							echo 'error';
						}
						exit;
					}	
		}


		//Eliminar producto
		if ($_POST['action'] == 'delProduct') 
		{

			if (empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])) {
				echo "Error";
			}else{
				$idproducto = $_POST['producto_id'];
				$query_delete = mysqli_query($conection,"UPDATE productos SET estatus = 0 WHERE Id_producto =$idproducto ");
				mysqli_close($conection);
				if ($query_delete) {
					echo 'Ok';
				}else{
					echo "Error al eliminar";
				}
			}
			echo 'error';
			exit;
		}


		//Buscar pedido
		if($_POST['action'] == 'searchProveedor') 
		{	
			if (!empty($_POST['proveedor'])) {
				$nit = $_POST['proveedor'];
				$query = mysqli_query($conection,"SELECT * FROM proveedores WHERE nit LIKE '$nit' AND estatus = 1");
				mysqli_close($conection);
				$result = mysqli_num_rows($query);

				$data = '';
				if ($result > 0) {
					$data = mysqli_fetch_assoc($query);
				}else{
					$data = 0;
				}
				echo json_encode($data,JSON_UNESCAPED_UNICODE);
			}
			exit;
		}


		//Registrar proveedor - factura
		if($_POST['action'] == 'addProveedor') 
		{
			$nit = $_POST['nit_proveedor'];
			$nombre = $_POST['nombre_proveedor'];
			$telefono = $_POST['telefono_proveedor'];
			$usuario_id = $_SESSION['idUser'];

			$query_insert = mysqli_query($conection,"INSERT INTO proveedores(nit,nombre,telefono,id_usuario) 
														VALUES('$nit','$nombre','$telefono',$usuario_id)");
			if ($query_insert) {
				$id_proveedor = mysqli_insert_id($conection);
				$msg = $id_proveedor;
			}else{
				$msg = 'error';
			}
			mysqli_close($conection);
			echo $msg;
			exit;
		}

		//Agregar producto al detalle temporal
		if($_POST['action'] == 'addProductoDetalle'){
			if (empty($_POST['producto']) || empty($_POST['cantidad'])) {
				echo 'error';
			}else{
				$Id_producto = $_POST['producto'];
				$cantidad = $_POST['cantidad'];
				$token = md5($_SESSION['idUser']);

				$query_iva = mysqli_query($conection,"SELECT iva FROM configuracion");
				$result_iva = mysqli_num_rows($query_iva);

				$quey_detalle_temp  = mysqli_query($conection,"CALL add_detalle_temp($Id_producto,$cantidad,'$token')");
				$result = mysqli_num_rows($quey_detalle_temp);

				$detalleTabla = '';
				$sub_total 	  = 0;
				$iva          = 0;
				$total        = 0;
				$arrayData    = array();

				if ($result > 0) { 
					if ($result_iva > 0) {
						$info_iva = mysqli_fetch_assoc($query_iva);
						$iva = $info_iva['iva'];
					}

					while ($data = mysqli_fetch_assoc($quey_detalle_temp)) {
						$precioTotal = round($data['cantidad'] * $data['precio'], 2);
						$sub_total   = round($sub_total + $precioTotal, 2);
						$total       = round($total + $precioTotal, 2);

						$detalleTabla .= '<tr>
											<td>'.$data['Id_producto'].'</td>
											<td colspan="2">'.$data['producto'].'</td>
											<td class="textcenter">'.$data['cantidad'].'</td>
											<td class="textright">'.$data['precio'].'</td>
											<td class="textright">'.$precioTotal.'</td>
											<td class="">
												<a class="link_delete" href="#" onclick="event.preventDefault();
												del_product_detalle('.$data['correctivo'].');"><i class="far fa-trash-alt"></i></a>
											</td>
										</tr>';
					}

					$impuesto = round($sub_total * ($iva / 100), 2);
					$tl_sniva = round($sub_total - $impuesto, 2);
					$total    = round($tl_sniva + $impuesto, 2);

					$detalleTotales = '<tr>
											<td colspan="5" class="textright">Subtotal</td>
											<td class="textright">'.$tl_sniva.'</td>
									   </tr>
										<tr>
											<td colspan="5" class="textright">IVA ('.$iva.'%)</td>
											<td class="textright">'.$impuesto.'</td>
										</tr>
										<tr>
											<td colspan="5" class="textright">Total</td>
											<td class="textright">'.$total.'</td>
										</tr>';

					$arrayData['detalle'] = $detalleTabla;
					$arrayData['totales'] = $detalleTotales;

					echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
				}else{
					echo 'error';
				}
				mysqli_close($conection);
			}
			exit;
		} 


		//Extrae datos del detalle temp
		if($_POST['action'] == 'serchForDetalle'){
			if (empty($_POST['user'])) 
			{
				echo 'error';
			}else{
				$token = md5($_SESSION['idUser']);

				$query = mysqli_query($conection,"SELECT tmp.correctivo,
														 tmp.token_user,
														 tmp.cantidad,
														 tmp.precio,
														 p.Id_producto,
														 p.producto
													FROM datalle_temp tmp
													INNER JOIN productos p
													ON tmp.Id_producto = p.Id_producto
													WHERE token_user = '$token' ");

				$result = mysqli_num_rows($query);

				$query_iva = mysqli_query($conection,"SELECT iva FROM configuracion");
				$result_iva = mysqli_num_rows($query_iva);


				$detalleTabla = '';
				$sub_total 	  = 0;
				$iva          = 0;
				$total        = 0;
				$arrayData    = array();

				if ($result > 0) { 
					if ($result_iva > 0) {
						$info_iva = mysqli_fetch_assoc($query_iva);
						$iva = $info_iva['iva'];
					}

					while ($data = mysqli_fetch_assoc($query)) {
						$precioTotal = round($data['cantidad'] * $data['precio'], 2);
						$sub_total   = round($sub_total + $precioTotal, 2);
						$total       = round($total + $precioTotal, 2);

						$detalleTabla .= '<tr>
											<td>'.$data['Id_producto'].'</td>
											<td colspan="2">'.$data['producto'].'</td>
											<td class="textcenter">'.$data['cantidad'].'</td>
											<td class="textright">'.$data['precio'].'</td>
											<td class="textright">'.$precioTotal.'</td>
											<td class="">
												<a class="link_delete" href="#" onclick="event.preventDefault();
												del_product_detalle('.$data['correctivo'].');"><i class="far fa-trash-alt"></i></a>
											</td>
										</tr>';
					}

					$impuesto = round($sub_total * ($iva / 100), 2);
					$tl_sniva = round($sub_total - $impuesto, 2);
					$total    = round($tl_sniva + $impuesto, 2);

					$detalleTotales = '<tr>
											<td colspan="5" class="textright">Subtotal</td>
											<td class="textright">'.$tl_sniva.'</td>
									   </tr>
										<tr>
											<td colspan="5" class="textright">IVA ('.$iva.'%)</td>
											<td class="textright">'.$impuesto.'</td>
										</tr>
										<tr>
											<td colspan="5" class="textright">Total</td>
											<td class="textright">'.$total.'</td>
										</tr>';

					$arrayData['detalle'] = $detalleTabla;
					$arrayData['totales'] = $detalleTotales;

					echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
				}else{
					echo 'error';
				}
				mysqli_close($conection);
			}
			exit;
		} 



		if($_POST['action'] == 'del_product_detalle'){
		
			if (empty($_POST['id_detalle'])) 
			{
				echo 'error';
			}else{
				$id_detalle = $_POST['id_detalle'];
				$token 		= md5($_SESSION['idUser']);

				$query_iva = mysqli_query($conection,"SELECT iva FROM configuracion");
				$result_iva = mysqli_num_rows($query_iva);

				$query_detalle_temp = mysqli_query($conection,"CALL del_detalle_temp($id_detalle,'$token')");
				$result = mysqli_num_rows($query_detalle_temp);


				$detalleTabla = '';
				$sub_total 	  = 0;
				$iva          = 0;
				$total        = 0;
				$arrayData    = array();

				if ($result > 0) { 
					if ($result_iva > 0) {
						$info_iva = mysqli_fetch_assoc($query_iva);
						$iva = $info_iva['iva'];
					}

					while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
						$precioTotal = round($data['cantidad'] * $data['precio'], 2);
						$sub_total   = round($sub_total + $precioTotal, 2);
						$total       = round($total + $precioTotal, 2);

						$detalleTabla .= '<tr>
											<td>'.$data['Id_producto'].'</td>
											<td colspan="2">'.$data['producto'].'</td>
											<td class="textcenter">'.$data['cantidad'].'</td>
											<td class="textright">'.$data['precio'].'</td>
											<td class="textright">'.$precioTotal.'</td>
											<td class="">
												<a class="link_delete" href="#" onclick="event.preventDefault();
												del_product_detalle('.$data['correctivo'].');"><i class="far fa-trash-alt"></i></a>
											</td>
										</tr>';
					}

					$impuesto = round($sub_total * ($iva / 100), 2);
					$tl_sniva = round($sub_total - $impuesto, 2);
					$total    = round($tl_sniva + $impuesto, 2);

					$detalleTotales = '<tr>
											<td colspan="5" class="textright">Subtotal</td>
											<td class="textright">'.$tl_sniva.'</td>
									   </tr>
										<tr>
											<td colspan="5" class="textright">IVA ('.$iva.'%)</td>
											<td class="textright">'.$impuesto.'</td>
										</tr>
										<tr>
											<td colspan="5" class="textright">Total</td>
											<td class="textright">'.$total.'</td>
										</tr>';

					$arrayData['detalle'] = $detalleTabla;
					$arrayData['totales'] = $detalleTotales;

					echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
				}else{
					echo 'error';
				}
				mysqli_close($conection);
			}
			exit;
		}


		//Anular Pedido
		if ($_POST['action'] == 'anularPedido') {
			
			$token  = md5($_SESSION['idUser']);
			$query_del = mysqli_query($conection,"DELETE FROM datalle_temp WHERE  token_user = '$token' ");
			mysqli_close($conection);
			if ($query_del) {
				echo 'ok';
			}else{
				echo 'error';
			}
			exit;
		}


		//Procesar Pedido
		if ($_POST['action'] == 'procesarPedido') {

			if (empty($_POST['id_proveedor'])) {
				$id_proveedor = 11;
			}else{
				$id_proveedor = $_POST['id_proveedor'];
			}
			
			$token   = md5($_SESSION['idUser']);
			$usuario   = ($_SESSION['idUser']);
			
			$query = mysqli_query($conection,"SELECT * FROM datalle_temp WHERE token_user = '$token'");
			$result = mysqli_num_rows($query);

			if ($result > 0) 
			{
				$quey_procesar = mysqli_query($conection,"CALL procesar_pedido($usuario,$id_proveedor,'$token')");
				$result_detalle = mysqli_num_rows($quey_procesar);

				if ($result_detalle > 0) {
						$data = mysqli_fetch_assoc($quey_procesar);
						echo json_encode($data,JSON_UNESCAPED_UNICODE);
					}else{
						echo "error";
					}
			}else{
				echo "error";
			}
			mysqli_close($conection);
			exit;
		}


		//Info factura
		if ($_POST['action'] == 'infoFactura'){
			if (!empty($_POST['nofactura'])) {
					
				$nofactura = $_POST['nofactura'];

				$query = mysqli_query($conection,"SELECT * FROM factura_pedido WHERE nofactura = '$nofactura' AND estatus = 1");
				mysqli_close($conection);

				$result = mysqli_num_rows($query);
				if ($result > 0) {
					
					$data = mysqli_fetch_assoc($query);
					echo json_encode($data,JSON_UNESCAPED_UNICODE);
					exit;
				}
			}
			echo "error";
			exit; 
		}

		//Anular Factura
		if ($_POST['action'] == 'anularFactura') {
			
			if (!empty($_POST['noFactura'])) {
				$noFactura = $_POST['noFactura'];

				$quey_anular = mysqli_query($conection,"CALL anular_factura($noFactura)");
				mysqli_close($conection);
				$result = mysqli_num_rows($quey_anular);
				if ($result > 0) {
					$data = mysqli_fetch_assoc($quey_anular);
					echo json_encode($data,JSON_UNESCAPED_UNICODE);
					exit;
				}
			}
			echo "error";
			exit;
		}


		//CAMBIAR CONTRASEÑA
		if ($_POST['action'] == 'changePassword') {
			
			if (!empty($_POST['passActual']) && !empty($_POST['passNuevo'])) 
			{
				$password = ($_POST['passActual']);
				$newPass = ($_POST['passNuevo']);
				$idUser = $_SESSION['idUser'];

				$code = '';
				$msg = '';
				$arrData = array();

				$query_user = mysqli_query($conection,"SELECT * FROM usuarios
														   WHERE Clave = '$password' AND id_usuario = $idUser ");
				$result = mysqli_num_rows($query_user);
				if ($result > 0) {
					$query_update = mysqli_query($conection,"UPDATE usuarios SET Clave = '$newPass' WHERE id_usuario = $idUser ");
					mysqli_close($conection);
					if ($query_update) {
						$code = '00';
						$msg = "Se cambio la contraseña con exito";
					}else{
						$code = '2';
						$msg = "No es posible cambiar su contraseña";
					}
				}else{
					$code = '1';
					$msg = "La contraseña actual es incorrecta";
				}
				$arrData = array('cod' => $code, 'msg' => $msg);
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);

			}else{
				echo "error";
			}
		}


		//Actualizar datos empresa

		if ($_POST['action'] == 'updateDetaEmpresa') {
			if (empty($_POST['txtNit']) || empty($_POST['txtNombre']) || empty($_POST['txtTelEmpresa']) || empty($_POST['txtEmailEmpresa']) || empty($_POST['txtDireccion']) ) 
			{
				$code = '1';
				$msg = "Todos los campos son requeridos";
			}else{
				$intNit    = intval($_POST['txtNit']);
		        $strNombre   = $_POST['txtNombre'];
		        $intTelefono   =  intval($_POST['txtTelEmpresa']);
		        $strEmail    = $_POST['txtEmailEmpresa'];
		        $strDireccion    = $_POST['txtDireccion'];

		        $queryUpd = mysqli_query($conection,"UPDATE configuracion 
		        										SET nit = $intNit,
		        											nombre = '$strNombre',
		        											telefono = $intTelefono,
		        											email = '$strEmail',
		        											direccion = '$strDireccion'
		        										WHERE id = 1 ");

		        mysqli_close($conection);
		        if ($queryUpd) {
		        	$code = '00';
		        	$msg = "Datos actualizados correctamente";
		        }else{
		        	$code = '2';
		        	$msg = "Error al actualizar los datos";
		        }
			}

			$arrData = array('cod' => $code, 'msg' => $msg);
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			exit;
		}

	}
	exit;
?>