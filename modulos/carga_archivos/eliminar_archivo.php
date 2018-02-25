<?php
/**
* 
* @author Beyda Mariana Trejo Román <1130032@unav.edu.mx>
* @copyright 2016-2017 Área de Desarrollo UNAV 
* @version 1.0
*
*Aquí se elimina el archivo que se manda en la siguiente variable
*@var string $archivo aqui se manda el nombre del archivo a borrar
*/
require("../../conex/connect.php");

if (isset($_POST["id_com"])) {
$id = $_POST["id_com"];

$select = "SELECT `url` FROM `comprobantes` WHERE `id_comprobante` = $id";
$resul_select = $mysqli->query($select);
$row = $resul_select->fetch_array();

if (file_exists("archivos_subidos/$row[0]")) {
  unlink("archivos_subidos/$row[0]");
  echo $v2 = 1;
}else {
  echo $v2 = 0;
}
if ($v2 == 1) {
$updtM = "DELETE FROM `comprobantes` WHERE `id_comprobante` = $id";
$updtM = $mysqli->query($updtM);
if ($mysqli->error) {
echo "<script>if(confirm('Algunos datos no se pudieron actualizar')){ 
document.location='/modulos/comprobantes';}
else{ alert('Operacion Cancelada'); 
}</script>";
}else{
echo "<script> document.location='/modulos/comprobantes/'; </script>";
}
}
}

if (isset($_POST["id_ins"])) {
$id = $_POST["id_ins"];

$updtM = "DELETE FROM `inscritos` WHERE `id_inscritos` = $id";
$updtM = $mysqli->query($updtM);
if ($mysqli->error) {
echo "<script>if(confirm('Algunos datos no se pudieron actualizar')){ 
document.location='/modulos/comprobante';}
else{ alert('Operacion Cancelada'); 
}</script>";
}else{
echo "<script> document.location='/modulos/comprobante/'; </script>";
}

}
?>
