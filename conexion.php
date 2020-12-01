<?php 

	$host = 'de1tmi3t63foh7fa.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
	$user = 'de1tmi3t63foh7fa.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
	$password = 'xtz3w4dkb9gdxa3b';
	$db = 'dp099bssdbmvmsmx';


	$conection = @mysqli_connect($host,$user,$password,$db);
	if (!$conection) {
		echo "Error en la conexion";
	}
 ?>