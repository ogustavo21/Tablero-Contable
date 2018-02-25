<?php 
$atras = "../../";
  include($atras.'template/todo.php');
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Página
        <small>Principal</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if ($_SESSION["tipo_usuario"] == 'Financiero General'){
    ?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
            <?php
              $lstEjer = "SELECT * FROM `ejercicio`";
              $res_lstEjer = $mysqli->query($lstEjer);
              $num_ejer = $res_lstEjer->num_rows;
            ?>
              <h3><?php echo $num_ejer ?></h3>

              <p>Ejercicios</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar-plus-o"></i>
            </div>
            <a href="../ejercicio/" class="small-box-footer">Más detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
            <?php
              $lstMes = "SELECT * FROM `mes`";
              $res_lstMes = $mysqli->query($lstMes);
              $num_mes = $res_lstMes->num_rows;
            ?>
              <h3><?php echo $num_mes ?></h3>

              <p>Meses</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar-plus-o"></i>
            </div>
            <a href="../mes" class="small-box-footer">Más detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
            <?php
              $lstZona = "SELECT `id_zona`, `zona`, `direc`, `status` FROM `zona` WHERE `id_union` = 1";
              $res_lstZona = $mysqli->query($lstZona);
              $num_zona = $res_lstZona->num_rows;
            ?>
              <h3><?php echo $num_zona ?></h3>

              <p>Zonas</p>
            </div>
            <div class="icon">
              <i class="fa fa-map"></i>
            </div>
            <a href="../zona" class="small-box-footer">Más detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
            <?php
              $lst_user = "SELECT DISTINCT us.`id_usuarios`, us.`nombre`, us.`apaterno`, us.`amaterno`, us.`correo`, us.`status` FROM `usuarios` us WHERE us.`id_tipo_usuario` = 4 ORDER BY us.`nombre` ASC";
              $res_lst_user = $mysqli->query($lst_user);
              $num_usuario = $res_lst_user->num_rows;
            ?>
              <h3><?php echo $num_usuario ?></h3>

              <p>Usuarios</p>
            </div>
            <div class="icon">
              <i class="fa fa-user-plus"></i>
            </div>
            <a href="../usuario" class="small-box-footer">Más detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
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
