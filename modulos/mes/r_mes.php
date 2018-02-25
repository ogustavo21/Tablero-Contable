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
        <small>Mes</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if ($_SESSION["tipo_usuario"] == 'Financiero General'){
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
                  <input type="text" class="form-control" name="mes" placeholder="Nombre del mes">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Fecha de inicio</label>
                  <input type="date" class="form-control" name="f_inicio">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Fecha de fin</label>
                  <input type="date" class="form-control" name="f_final">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Fecha límite</label>
                  <input type="date" class="form-control" name="f_limite">
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
    $clasZona->insertar();
}
?>