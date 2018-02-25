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
    if ($_SESSION["tipo_usuario"] == 'Financiero de Zona') {
      ?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
            <?php
              $lstFil = "SELECT f.*, z.`zona` FROM `filantropica` f INNER JOIN `zona` z ON f.`id_zona` = z.`id_zona` WHERE f.`id_zona` = $_SESSION[id_superior]";
              $res_lstFil = $mysqli->query($lstFil);
              $num_fil = $res_lstFil->num_rows;
            ?>
              <h3><?php echo $num_fil ?></h3>
             <?php echo $_SESSION['id_superior']?>
              <p>Filantrópicas</p>
            </div>
            <div class="icon">
              <i class="fa fa-map"></i>
            </div>
            <a href="../filantropica/" class="small-box-footer">Más detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
            <?php
              $lstEsc = "SELECT DISTINCT es.`id_escuela`, es.`nombre`, es.`status`, f.`nombre`, z.`zona` FROM `escuela` es INNER JOIN `filantropica` f ON es.`id_filantropica` = f.`id_filantropica` INNER JOIN `zona` z ON es.`id_zona` = z.`id_zona` AND f.`id_zona` = z.`id_zona` AND z.`id_zona` = $_SESSION[id_superior]";
              $res_lstEsc = $mysqli->query($lstEsc);
              $num_esc = $res_lstEsc->num_rows;
            ?>
              <h3><?php echo $num_esc ?></h3>

              <p>Escuelas</p>
            </div>
            <div class="icon">
              <i class="fa fa-map-o"></i>
            </div>
            <a href="../escuela" class="small-box-footer">Más detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
            <?php
              $lst_user = "SELECT DISTINCT us.`id_usuarios`, us.`nombre`, us.`apaterno`, us.`amaterno`, us.`correo`, us.`status`, f.`nombre` FROM `usuarios` us INNER JOIN `filantropica` f ON us.`id_superior` = f.`id_filantropica` AND f.`id_zona` = $_SESSION[id_superior] AND us.`id_tipo_usuario` = 3";
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
