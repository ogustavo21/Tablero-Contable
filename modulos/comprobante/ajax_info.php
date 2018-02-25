<?php
session_start();
require("../../conex/connect.php");
$info=$_POST['info'];
?>
<div class="box-header with-border">
  <small>Comprobantes</small>
</div>
<table class="table table-hover">
  <tbody>
  <tr>
    <th>Archivo</th>
    <th>Nombre</th>
    <th>Descripción</th>
  </tr>
  <?php
    $select = "SELECT `id_comprobante`,`url`,`descripcion` FROM `comprobantes` WHERE `id_mes` = $_SESSION[id_mes] AND `id_escuela` = $info";
    $resul_select = $mysqli->query($select);
    while($row = $resul_select->fetch_array()){
  ?>
    <tr id="archivos_subidos">
      <td style="width: 50px"><a target="_blank" href="../carga_archivos/archivos_subidos/<?php echo $row[1] ?>"> <img width="80%" src="../../dist/img/file.png"></a></td>
      <td><?php echo $row[1] ?></td>
      <td><?php echo $row[2] ?></td>
    </tr>
  <?php
  }
  ?>
</tbody>
</table>
 <?php
  $slcIns = "SELECT `id_inscritos`,`blanco`,`alcanzado` FROM `inscritos` WHERE `id_mes` = $_SESSION[id_mes] AND `id_escuela` = $info";
  $resul_slcIns = $mysqli->query($slcIns);
  $row_slcIns = $resul_slcIns->fetch_array();
  $filas = $resul_slcIns->num_rows; 
if ($filas >= 1) {
  ?>
<div class="box-header with-border">
  <small>Alumnos inscritos</small>
</div>
<table class="table table-hover">
  <tbody>
  <tr>
    <th>Blanco</th>
    <th>Alcanzado</th>
  </tr>
    <tr id="archivos_subidos">
      <td><?php echo $row_slcIns[1] ?></td>
      <td><?php echo $row_slcIns[2] ?></td>
    </tr>
</tbody>
</table>
    <?php
  }
  ?>