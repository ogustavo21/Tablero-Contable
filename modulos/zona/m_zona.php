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
        <small>Zonas</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if ($_SESSION["tipo_usuario"] == 'Financiero General'){
      $lstDZona = "SELECT `zona`, `direc` FROM `zona` WHERE `id_zona` = $_SESSION[id_tmp]";
      $res_lstDZona = $mysqli->query($lstDZona);
      $row_reslstDZona = $res_lstDZona->fetch_array();
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
                  <label for="exampleInputEmail1">Nombre de zona</label>
                  <input type="text" class="form-control" name="nombre" value="<?php echo $row_reslstDZona["zona"] ?>" placeholder="Nombre de zona">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Dirección</label>
                  <input type="text" class="form-control" name="dir" value="<?php echo $row_reslstDZona["direc"] ?>" placeholder="Dirección">
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
    include "cls_zona.php";
    $nombre = $_POST["nombre"];
    $dir = $_POST["dir"];
    $clasZona = new zona($nombre, $dir);
    $clasZona->modificar();
}
?>