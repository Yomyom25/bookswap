<?php
$host = "localhost";
$user = "root";
$contrasena = "";
$bd = "bookswap";

// $host = "localhost";
// $user = "jyanmx_yom";
// $contrasena = "g_8!yu(,8(R3";
// $bd = "jyanmx_taller";

$conectar = mysqli_connect($host, $user, $contrasena, $bd);

if (!$conectar) {
	echo "No se pudo conectar a la base de datos";
}
