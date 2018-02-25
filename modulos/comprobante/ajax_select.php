<?php
session_start();
include_once("../../conex/connect.php");


$selectt = "SELECT distinct * FROM `tipo_comprobantes` WHERE `tipo_comprobantes`.`id_tipo_comprobante` NOT IN (SELECT `id_tipo_comprobante` FROM `comprobantes` WHERE `id_usuario` = $_SESSION[id_usuarios] and `id_escuela`=$_SESSION[id_superior] and `id_mes` = $_SESSION[id_mes] and `tipo_comprobantes`.`id_tipo_comprobante` = `comprobantes`.`id_tipo_comprobante`)";
$result = $mysqli->query($selectt) or die ($mysqli->error);
$filas = $result->num_rows;

if($filas > 0){

echo '<div class="input-group" id="recargado">';
echo '<select class="form-control" name="id_archivo" id="id_archivo" required>';
echo '<option selected disabled value="">Selecciona un tipo</option>';

while($row = mysqli_fetch_array($result)){
$id_tipo_comprobante=$row['id_tipo_comprobante'];
$nombre=$row['nombre'];
echo '<option value="'.$id_tipo_comprobante.'">'. $nombre .'</option>';

}

 }else{

echo '<option selected disabled value="">No hay más tipos</option>';
}
echo '</select>';
echo '</div>';

?>