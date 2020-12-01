<?php 

	$host = 'de1tmi3t63foh7fa.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
	$user = 'p12ev49vux4k7zri';
	$password = 'xtz3w4dkb9gdxa3b';
	$db = 'dp099bssdbmvmsmx';


	$conection = @mysqli_connect($host,$user,$password,$db);
	if (!$conection) {
		echo "Error en la conexion";
	}
 ?>