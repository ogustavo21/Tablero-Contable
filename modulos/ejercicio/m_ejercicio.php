<?php 
$atras = "../../";
  include($atras.'template/todo.php');
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registro
        <small>Ejercicios</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if ($_SESSION["tipo_usuario"] == 'Financiero General'){
      $lstDEjercicio = "SELECT `id_ejercicio`, `nombre` FROM `ejercicio` WHERE `id_ejercicio` = $_SESSION[id_tmp]";
      $res_lstDEjercicio = $mysqli->query($lstDEjercicio);
      $row_reslstDEjercicio = $res_lstDEjercicio->fetch_array();
    ?>
      <!-- Small boxes (Stat box) -->
      <div class="col-md-2">
      </div>
      <div class="col-md-8">
      <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Formulario</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form method="post">
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Año de ejercicio</label>
                  <input type="text" class="form-control" name="nombre" value="<?php echo $row_reslstDEjercicio["id_ejercicio"] ?>" disabled>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Descripción</label>
                  <input type="text" class="form-control" name="nombre" value="<?php echo $row_reslstDEjercicio["nombre"] ?>" placeholder="Descripción">
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
            </form>
          </div>
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
<?php
if (isset($_POST["nombre"])) {
    include "cls_ejercicio.php";
    $ejercicio = $_POST["ejercicio"];
    $nombre = $_POST["nombre"];
    $clasZona = new ejercicio($ejercicio, $nombre);
    $clasZona->modificar();
}
?>