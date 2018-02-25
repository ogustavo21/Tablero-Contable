<?php
$atras ="../";
include($atras."conex/connect.php");

$sql="UPDATE `comprobantes` SET `comprobante_al`= 1 WHERE `comprobante_al` = 0";	
$result=$mysqli->query($sql);

$sql="SELECT c.`id_comprobante`, c.`id_ejercicio`, m.`mes`, e.`nombre`, tp.`nombre`, c.`url` FROM `comprobantes` c INNER JOIN `mes` m ON c.`id_mes`= m.`id_mes` INNER JOIN `escuela` e ON c.`id_escuela` = e.`id_escuela` INNER JOIN `tipo_comprobantes` tp ON c.`id_tipo_comprobante` = tp.`id_tipo_comprobante` ORDER BY c.`id_comprobante` DESC limit 5";
$result=$mysqli->query($sql);

$response='';
$response = $response. '<ul class="menu">';
while($row=mysqli_fetch_array($result)) {
	if ($row[4] == "Pago de impuestos") {
		$color = "fuchsia";
	}
	if ($row[4] == "Pago de nómina") {
		$color = "green";
	}
	if ($row[4] == "Arqueo de caja") {
		$color = "aqua";
	}
	$response = $response. '<li>'.
                    '<a target="_blank" href="../carga_archivos/archivos_subidos/'. $row[5] .'">'.
                      '<p style="white-space: pre-line"><i class="fa fa-dot-circle-o text-'. $color .'"></i>   '. $row[4] .' de la escuela '. $row[3] .' a '. $row[2] .' '. $row[1] .
                    '</p></a>'.
                  '</li>';
}
                $response = $response. '</ul>';
if(!empty($response)) {
	print $response;
}


?>