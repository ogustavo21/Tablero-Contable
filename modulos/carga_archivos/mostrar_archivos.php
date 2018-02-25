<?php
/**
* 
* @author Beyda Mariana Trejo Román <1130032@unav.edu.mx>
* @copyright 2016-2017 Área de Desarrollo UNAV 
* @version 1.0
*
*Aquí se muestran cada uno de los archivos por empleado en la siguiente variable
*@var string $_SESSION['id_datosper'] aqui se manda el id del empleado
*@var array $archivos aqui se mandan los nombre de los archivos encontrados
*/
session_start();
$directorio_escaneado = scandir('archivos_subidos');
$archivos = array();
$n = 0;
	require("../../conex/connect.php");
	$mysqli->set_charset("utf8");
   	$select = "SELECT c.`url`, tc.`nombre`,c.`descripcion` FROM `comprobantes` c INNER JOIN `tipo_comprobantes` tc ON c.`id_tipo_comprobante` = tc.`id_tipo_comprobante` WHERE c.`id_mes` = $_SESSION[id_mes] AND c.`id_usuario` = $_SESSION[id_usuarios]";
    $resul_select = $mysqli->query($select);
    while ( $row = $resul_select->fetch_array()) {
    	foreach ($directorio_escaneado as $item) {
		    if ($item != '.' and $item != '..' and $item == $row[0]) {
		        $archivos[] = $item;
		        $_SESSION["hi".$n] = $row[2];
		        $n++;
		    }
		}
    }
    
echo json_encode($archivos);
?>
