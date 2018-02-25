<?php

require("../../conex/connect.php");
$filantropica=$_POST['filantropica'];


echo $query = "SELECT `id_escuela`, `nombre` FROM `escuela` WHERE `id_filantropica` = $filantropica";
$result = $mysqli->query($query) or die ($mysqli->error);
$filas = $result->num_rows;

if($filas > 0){

while($row = mysqli_fetch_array($result)){
$id_escuela=$row['id_escuela'];
$nombre=$row['nombre'];
echo '<option value="'.$id_escuela.'">'. $nombre .'</option>';

}

 }else{

echo '<option selected disabled value="">No existen escuelas</option>';
}

?>