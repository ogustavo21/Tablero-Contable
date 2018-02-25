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
        <small>Organización</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if ($_SESSION["tipo_usuario"] == 'Financiero General'){
    ?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
      <?php
        $lstZona = "SELECT `id_zona`,`zona` FROM `zona` WHERE `id_union` = 1 AND `status` = 1";//ARREGLAR EL ID UNION PARA QUE PUEDA SER ADMINISTRADO POR VARIAS UNIONES DINAMICO, INNERJOIN
        $res_lstZona = $mysqli->query($lstZona);
        while ($row_res_lstZona = $res_lstZona->fetch_array()) {                    
      ?>
        <div class="col-xs-6">
              
          <div class="box box-info collapsed-box">
            <div class="box-header"> 
              <h3 class="box-title"><?php echo $row_res_lstZona["zona"]; ?></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
          <?php
            $obt_obtFil = "SELECT DISTINCT `id_filantropica`,`nombre` FROM `filantropica` WHERE `status` = 1 AND `id_zona` = $row_res_lstZona[id_zona]";     
            $res_obtFil = $mysqli->query($obt_obtFil);
            while ($row_obtFil = $res_obtFil->fetch_array()) { 
          ?>
          <div class="box-header with-border">
          <small><?php echo $row_obtFil["nombre"] ?></small>
          </div>
            <div class="box-body">
              <table class="table table-hover">
                <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Dirección</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $lstEsc = "SELECT DISTINCT `id_escuela`,`nombre`,`direccion` FROM `escuela` WHERE `status` = 1 AND `id_filantropica` = $row_obtFil[id_filantropica]";
                  $res_lstEsc = $mysqli->query($lstEsc);
                  while ($row_res_lstEsc = $res_lstEsc->fetch_array()) {                    
                ?>
                <tr>
                  <td><?php echo $row_res_lstEsc["nombre"] ?></td>
                  <td><?php echo $row_res_lstEsc["direccion"] ?></td>
                </tr>
                <?php
                  }//row_res_lstEsc
                ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Nombre</th>
                  <th>Dirección</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <?php 
              }//row_obtFil
             ?>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <?php
          } //row_res_lstZona
        ?>
      </div>
      <!-- /.row -->
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
