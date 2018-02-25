<?php 
$atras = "../../";
  include($atras.'template/todo.php');
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista
        <small>Escuelas</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if ($_SESSION["tipo_usuario"] == 'Financiero de Zona'){
    ?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-xs-12">
      <a href="r_escuela.php"><button type="button" class="btn bg-purple btn-flat margin">Agregar escuela</button></a>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Escuela</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Zona</th>
                  <th>Filantrópica</th>
                  <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $lstEsc = "SELECT DISTINCT es.`id_escuela`, es.`nombre`, es.`status`, f.`nombre`, z.`zona` FROM `escuela` es INNER JOIN `filantropica` f ON es.`id_filantropica` = f.`id_filantropica` INNER JOIN `zona` z ON es.`id_zona` = z.`id_zona` AND f.`id_zona` = z.`id_zona` INNER JOIN `usuarios_escuelas` ue ON `id_usuario`=$_SESSION[id_usuarios] AND z.`id_zona` = ue.`id_org`";
                  $res_lstEsc = $mysqli->query($lstEsc);
                  while ($row_res_lstEsc = $res_lstEsc->fetch_array()) {                    
                ?>
                <tr>
                  <td><?php echo $row_res_lstEsc["1"]; ?></td>
                  <td><?php echo $row_res_lstEsc["zona"] ?></td>
                  <td><?php echo $row_res_lstEsc["3"] ?></td>
                  <td><button type="button" onClick="abrir(<?php echo $row_res_lstEsc[id_escuela] ?>)" name="id_escuela" class="btn bg-navy margin">Modificar</button>
                  <?php
                    if ($row_res_lstEsc["status"] == 1) {
                      $checked = "checked";
                    }elseif ($row_res_lstEsc["status"] == 0) {
                      $checked = "";
                    }
                  ?>
                  <input id="toggle-event<?php echo $row_res_lstEsc[id_escuela] ?>" <?php echo $checked ?> type="checkbox" data-toggle="toggle" data-onstyle="success">
<script>
  $(function() {
    $('#toggle-event<?php echo $row_res_lstEsc[id_escuela] ?>').bootstrapToggle({ //Cambia el nombre por defecto por el que se desee
      on: 'Activo',
      off: 'Inactivo'
    });

    $('#toggle-event<?php echo $row_res_lstEsc[id_escuela] ?>').change(function() {
      var user = $(this).prop('checked'); //Guarda el valor de toggle para realizar la acción y cambiar su estatus
      if (user == true) {
        document.location='cls_escuela.php?estatus=1&id=<?php echo $row_res_lstEsc[id_escuela] ?>';
      }else{
        document.location='cls_escuela.php?estatus=0&id=<?php echo $row_res_lstEsc[id_escuela] ?>';
      }
    })
  })
</script>
                </td>
                </tr>
                
                <script type="text/javascript">                
                var user;
                  function abrir(id){
                      user= id;
                      //alert(user);
                      var dataString2 = 'id_escuela='+ user;
                          $.ajax({
                              type: "POST",
                              url: "ajax_link.php",
                              data: dataString2,
                              cache: false,
                              success: function(html){
                          location.href='m_escuela.php';
                          }
                          });

                    }
                </script>
                <?php
                  }
                ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Nombre</th>
                  <th>Zona</th>
                  <th>Filantrópica</th>
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
      <?php
    }
    ?>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
</div>
<!-- ./wrapper -->
<?php 
  include($atras.'template/footer.php');
  include($atras.'template/script.php');
?>

</body>
</html>
