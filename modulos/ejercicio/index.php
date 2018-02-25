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
        <small>Ejercicio</small>
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
      <a href="r_ejercicio.php"><button type="button" class="btn bg-purple btn-flat margin">Agregar ejercicio</button></a>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Ejercicios</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Año</th>
                  <th>Descripción</th>
                  <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $lstEjer = "SELECT * FROM `ejercicio`";
                  $res_lstEjer = $mysqli->query($lstEjer);
                  while ($row_res_lstEjer = $res_lstEjer->fetch_array()) {                    
                ?>
                <tr>
                  <td><?php echo $row_res_lstEjer["id_ejercicio"]; ?></td>
                  <td><?php echo $row_res_lstEjer["nombre"] ?></td>
                  <td><button type="button" onClick="abrir(<?php echo $row_res_lstEjer[id_ejercicio] ?>)" name="id_ejercicio" class="btn bg-navy margin">Modificar</button>
                  <?php
                    if ($row_res_lstEjer["status"] == 1) {
                      $checked = "checked";
                    }elseif ($row_res_lstEjer["status"] == 0) {
                      $checked = "";
                    }
                  ?>
                  <input id="toggle-event<?php echo $row_res_lstEjer[id_ejercicio] ?>" <?php echo $checked ?> type="checkbox" data-toggle="toggle" data-onstyle="success">
<script>
  $(function() {
    $('#toggle-event<?php echo $row_res_lstEjer[id_ejercicio] ?>').bootstrapToggle({
      on: 'Activo',
      off: 'Inactivo'
    });

    $('#toggle-event<?php echo $row_res_lstEjer[id_ejercicio] ?>').change(function() {
      var user = $(this).prop('checked');
      if (user == true) {
        document.location='cls_ejercicio.php?estatus=1&id=<?php echo $row_res_lstEjer[id_ejercicio] ?>';
      }else{
        document.location='cls_ejercicio.php?estatus=0&id=<?php echo $row_res_lstEjer[id_ejercicio] ?>';
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
                      var dataString2 = 'id_ejercicio='+ user;
                          $.ajax({
                              type: "POST",
                              url: "ajax_link.php",
                              data: dataString2,
                              cache: false,
                              success: function(html){
                          location.href='m_ejercicio.php';
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
                  <th>Año</th>
                  <th>Descripción</th>
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
