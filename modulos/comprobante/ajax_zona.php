<?php
session_start();
require("../../conex/connect.php");
$zona=$_POST['zona'];

$obt_Zona = "SELECT `zona` FROM `zona` WHERE `id_zona` = $zona";       
$res_Zona = $mysqli->query($obt_Zona);
$row_Zona = $res_Zona->fetch_array();
?>
<div class="box-header">
  <h3 class="box-title"><?php echo $row_Zona["zona"] ?> </h3>
  </div>
  <?php
    $obt_obtFil = "SELECT DISTINCT f.`id_filantropica`,f.`nombre` FROM `filantropica` f INNER JOIN `escuela` e ON f.`id_filantropica` = e.`id_filantropica` INNER JOIN `comprobantes` c ON e.`id_escuela` = c.`id_escuela` AND c.`id_mes` = $_SESSION[id_mes] WHERE f.`status` = 1 AND f.`id_zona` = $zona";       
    $res_obtFil = $mysqli->query($obt_obtFil);
    while ($row_obtFil = $res_obtFil->fetch_array()) { 
  ?>
  <div class="box-header with-border">
  <small><?php echo $row_obtFil["nombre"] ?></small>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <table id="example1" class="table table-bordered table-striped">
      <thead>
      <tr>
        <th>Nombre</th>
        <th>Acción</th>
      </tr>
      </thead>
      <tbody>
      <?php
        $lstEsc = "SELECT DISTINCT e.`id_escuela`,e.`nombre` FROM `escuela` e INNER JOIN `comprobantes` c ON e.`id_escuela` = c.`id_escuela` WHERE e.`status` = 1 AND e.`id_filantropica` = $row_obtFil[id_filantropica] AND c.`id_mes` = $_SESSION[id_mes]";
        $res_lstEsc = $mysqli->query($lstEsc);
        while ($row_res_lstEsc = $res_lstEsc->fetch_array()) {                    
      ?>
      <tr>
        <td><?php echo $row_res_lstEsc["nombre"] ?></td>
        <td>
          <button type="button" id="info" onClick="info(<?php echo $row_res_lstEsc["id_escuela"] ?>)" class="btn bg-navy margin">Ver</button>
        </td>
      </tr>
      <?php
        }
      ?>
      </tbody>
      <tfoot>
      <tr>
        <th>Nombre</th>
        <th>Acción</th>
      </tr>
      <script type="text/javascript">
          function info(id){
              //alert(info);
              var dataString2 = 'info='+ id;
                  $.ajax({
                      type: "POST",
                      url: "ajax_info.php",
                      data: dataString2,
                      cache: false,
                      success: function(html){
                    $("#resultado_info").html(html);
                  }
                  });

            }
        </script>
      </tfoot>
    </table>
  </div>
  <?php
}//Cierra el while de las filantrópicas
?>
  <!-- /.box-body -->