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
        <small>Meses</small>
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
      <a href="r_mes.php"><button type="button" class="btn bg-purple btn-flat margin">Agregar un mes</button></a>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Meses</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Año</th>
                  <th>Mes</th>
                  <th>Fecha</th>
                  <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $lstMes = "SELECT * FROM `mes` ORDER by f_inicio, id_ejercicio";
                  $res_lstMes = $mysqli->query($lstMes);
                  while ($row_res_lstMes = $res_lstMes->fetch_array()) { 
                  $date_inicio = date("d-m-Y", strtotime($row_res_lstMes["f_inicio"]));
                  $date_fin = date("d-m-Y", strtotime($row_res_lstMes["f_final"]));                   
                ?>
                <tr>
                  <td><?php echo $row_res_lstMes["id_ejercicio"]; ?></td>
                  <td><?php echo $row_res_lstMes["mes"] ?></td>
                  <td><?php echo $date_inicio ." al ". $date_fin ?></td>
                  <td><button type="button" onClick="abrir(<?php echo $row_res_lstMes[id_mes] ?>)" name="id_mes" class="btn bg-navy margin">Modificar</button>
                  <?php
                    if ($row_res_lstMes["status"] == 1) {
                      $checked = "checked";
                    }elseif ($row_res_lstMes["status"] == 0) {
                      $checked = "";
                    }
                  ?>
                  <input id="toggle-event<?php echo $row_res_lstMes[id_mes] ?>" <?php echo $checked ?> type="checkbox" data-toggle="toggle" data-onstyle="success">
<script>
  $(function() {
    $('#toggle-event<?php echo $row_res_lstMes[id_mes] ?>').bootstrapToggle({
      on: 'Activo',
      off: 'Inactivo'
    });

    $('#toggle-event<?php echo $row_res_lstMes[id_mes] ?>').change(function() {
      var user = $(this).prop('checked');
      if (user == true) {
        document.location='cls_mes.php?estatus=1&id=<?php echo $row_res_lstMes[id_mes] ?>';
      }else{
        document.location='cls_mes.php?estatus=0&id=<?php echo $row_res_lstMes[id_mes] ?>';
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
                      var dataString2 = 'id_mes='+ user;
                          $.ajax({
                              type: "POST",
                              url: "ajax_link.php",
                              data: dataString2,
                              cache: false,
                              success: function(html){
                          location.href='m_mes.php';
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
                  <th>Mes</th>
                  <th>Fecha</th>
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
