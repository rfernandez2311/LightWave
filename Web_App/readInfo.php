<?php

include "dbconnect.php";

$consulta = "select * from Live_Info";

$result = $connection->query($consulta);

$usuarios = array();

while($fila = $result->fetch_array()){
	$usuarios[] = $fila;
	//$usuarios[] = array_map('utf8_encode', $fila);
}

echo json_encode($usuarios);

$result->close();
?>
