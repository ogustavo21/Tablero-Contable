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
        <small>Zonas</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if ($_SESSION["tipo_usuario"] == 'Financiero General'){
    ?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-xs-12">
      <a href="r_zona.php"><button type="button" class="btn bg-purple btn-flat margin">Agregar zona</button></a>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Zonas</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Dirección</th>
                  <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $lstZona = "SELECT `id_zona`, `zona`, `direc`, `status` FROM `zona` WHERE `id_union` = 1";
                  $res_lstZona = $mysqli->query($lstZona);
                  while ($row_res_lstZona = $res_lstZona->fetch_array()) {                    
                ?>
                <tr>
                  <td><?php echo $row_res_lstZona["zona"]; ?></td>
                  <td><?php echo $row_res_lstZona["direc"] ?></td>
                  <td><button type="button" onClick="abrir(<?php echo $row_res_lstZona[id_zona] ?>)" name="id_zona" class="btn bg-navy margin">Modificar</button>
                  <?php
                    if ($row_res_lstZona["status"] == 1) {
                      $checked = "checked";
                    }elseif ($row_res_lstZona["status"] == 0) {
                      $checked = "";
                    }
                  ?>
                  <input id="toggle-event<?php echo $row_res_lstZona[id_zona] ?>" <?php echo $checked ?> type="checkbox" data-toggle="toggle" data-onstyle="success">
<script>
  $(function() {
    $('#toggle-event<?php echo $row_res_lstZona[id_zona] ?>').bootstrapToggle({ //Cambia el nombre por defecto por el que se desee
      on: 'Activo',
      off: 'Inactivo'
    });

    $('#toggle-event<?php echo $row_res_lstZona[id_zona] ?>').change(function() {
      var user = $(this).prop('checked'); //Guarda el valor de toggle para realizar la acción y cambiar su estatus
      if (user == true) {
        document.location='cls_zona.php?estatus=1&id=<?php echo $row_res_lstZona[id_zona] ?>';
      }else{
        document.location='cls_zona.php?estatus=0&id=<?php echo $row_res_lstZona[id_zona] ?>';
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
                      var dataString2 = 'id_zona='+ user;
                          $.ajax({
                              type: "POST",
                              url: "ajax_link.php",
                              data: dataString2,
                              cache: false,
                              success: function(html){
                          location.href='m_zona.php';
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
                  <th>Correo</th>
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
