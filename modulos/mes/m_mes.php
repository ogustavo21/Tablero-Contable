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
      $lstMes = "SELECT `mes`, `f_inicio`, `f_final`, `f_limite`, `id_ejercicio` FROM `mes` WHERE `id_mes` = $_SESSION[id_tmp]";
      $res_lstMes = $mysqli->query($lstMes);
      $row_reslstMes = $res_lstMes->fetch_array();
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
                  <label for="exampleInputEmail1">Mes</label>
                  <input type="text" class="form-control" name="mes" value="<?php echo $row_reslstMes["mes"] ?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Fecha de inicio</label>
                  <input type="date" class="form-control" name="f_inicio" value="<?php echo $row_reslstMes["f_inicio"] ?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Fecha de fin</label>
                  <input type="date" class="form-control" name="f_final" value="<?php echo $row_reslstMes["f_final"] ?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Fecha limite</label>
                  <input type="date" class="form-control" name="f_limite" value="<?php echo $row_reslstMes["f_limite"] ?>">
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
if (isset($_POST["mes"])) {
    include "cls_mes.php";
    $mes = $_POST["mes"];
    $f_inicio = $_POST["f_inicio"];
    $f_final = $_POST["f_final"];
    $f_limite = $_POST["f_limite"];
    $clasZona = new mes($mes, $f_inicio, $f_final, $f_limite);
    $clasZona->modificar();
}
?>