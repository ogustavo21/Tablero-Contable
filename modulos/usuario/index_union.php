<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <a href="r_usuario.php"><button type="button" class="btn bg-purple btn-flat margin">Agregar usuario contador</button></a>
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Contadores de Zona</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Zona</th>
            <th>Acción</th>
          </tr>
          </thead>
          <tbody>
          <?php
          $lst_user = "SELECT DISTINCT us.`id_usuarios`, us.`nombre`, us.`apaterno`, us.`amaterno`, us.`correo`, us.`status`, zona.zona
FROM `usuarios` us, usuarios_escuelas,zona
WHERE us.`id_tipo_usuario` = 4 and us.id_usuarios=usuarios_escuelas.id_usuario and usuarios_escuelas.id_org=zona.id_zona 
ORDER BY us.`nombre` ASC";
            $res_lst_user = $mysqli->query($lst_user);
            while ($row_resuser = $res_lst_user->fetch_array()) {
          ?>
          <tr>
            <td><?php echo $row_resuser["nombre"] ." ". $row_resuser["apaterno"] ." ". $row_resuser["amaterno"]; ?></td>
            <td><?php echo $row_resuser["correo"] ?></td>
            <td><?php echo $row_resuser["zona"] ?></td>
            <td><button type="button" onClick="abrir(<?php echo $row_resuser[id_usuarios] ?>)" name="id_usuarios" class="btn bg-navy margin">Modificar</button>
            <?php
              if ($row_resuser["status"] == 1) {
                $checked = "checked";
              }elseif ($row_resuser["status"] == 0) {
                $checked = "";
              }
            ?>
            <input id="toggle-event<?php echo $row_resuser[id_usuarios] ?>" <?php echo $checked ?> type="checkbox" data-toggle="toggle" data-onstyle="success">
<script>
$(function() {
$('#toggle-event<?php echo $row_resuser[id_usuarios] ?>').bootstrapToggle({
on: 'Activo',
off: 'Inactivo'
});

$('#toggle-event<?php echo $row_resuser[id_usuarios] ?>').change(function() {
var user = $(this).prop('checked');
if (user == true) {
  document.location='cls_usuario.php?estatus=1&id=<?php echo $row_resuser[id_usuarios] ?>';
}else{
  document.location='cls_usuario.php?estatus=0&id=<?php echo $row_resuser[id_usuarios] ?>';
}
})
})
</script>
          </td>
          </tr>
          
            <?php
          }//Cierra wuile de los datos
          ?>
          <script type="text/javascript">                
          var user;
            function abrir(id){
              user= id;
              //alert(user);
              var dataString2 = 'id_usuarios='+ user;
                  $.ajax({
                      type: "POST",
                      url: "ajax_link.php",
                      data: dataString2,
                      cache: false,
                      success: function(html){
                  location.href='m_usuario.php';
                  }
                  });
              }
          </script>
          </tbody>
          <tfoot>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Zona</th>
            <th>Acción</th>
          </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>