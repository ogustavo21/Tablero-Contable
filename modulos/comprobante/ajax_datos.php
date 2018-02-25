<?php
session_start();
include_once("../../conex/connect.php");
?>
<table class="table table-hover">
<tbody>
<tr>
  <th>Archivo</th>
  <th>Nombre</th>
  <th>Descripción</th>
  <th>Eliminar</th>
</tr>
<?php
  $select = "SELECT `id_comprobante`,`url`,`descripcion` FROM `comprobantes` WHERE `id_mes` = $_SESSION[id_mes] AND `id_usuario` = $_SESSION[id_usuarios] AND `id_escuela` = $_SESSION[id_superior]";
  $resul_select = $mysqli->query($select);
  while($row = $resul_select->fetch_array()){
?>
  <tr id="archivos_subidos">
    <td style="width: 50px"><a target="_blank" href="../carga_archivos/archivos_subidos/<?php echo $row[1] ?>"> <img width="80%" src="../../dist/img/file.png"></a></td>
    <td><?php echo $row[1] ?></td>
    <td><?php echo $row[2] ?></td>
    <td><button type="button" onClick="borrar(<?php echo $row[0] ?>)" class="btn btn-danger margin">Borrar</button></td>
  </tr>
<?php
}
?>

<script type="text/javascript">             
                var user;
                  function borrar(id){
                      user= id;
                      //alert(user);
                      var dataString2 = 'id_com='+ user;
                          $.ajax({
                              type: "POST",
                              url: "../carga_archivos/eliminar_archivo.php",
                              data: dataString2,
                              cache: false,
                              success: function(html){
                          location.href='index.php';
                          }
                          });

                    }
                </script>
</tbody></table>