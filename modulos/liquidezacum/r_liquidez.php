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
        <small>Escuelas</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if ($_SESSION["tipo_usuario"] == 'Financiero de Zona'){
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
                  <label for="exampleInputEmail1">RFC </label>
                  <input type="text" class="form-control" name="rfc"  placeholder="RFC" required>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Nombre de la escuela </label>
                  <input type="text" class="form-control"  required name="nombre" placeholder="Nombre de la escuela">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Dirección </label>
                  <input type="text" class="form-control"  required name="direccion" placeholder="Dirección">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Teléfono </label>
                  <input type="number" class="form-control"  required name="telefono" placeholder="Teléfono">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Correo </label>
                  <input type="email" class="form-control"  required name="correo" placeholder="Correo">
                </div>               
                <div class="form-group">
                  <label for="exampleInputPassword1">Filantrópica</label>               
                  <select class="form-control" name="filantropica">
                    <option value="">Seleccciona una Filantrópica</option>
                  <?php
                    $lstFil = "SELECT f.`id_filantropica`, f.`nombre` FROM `filantropica` f INNER JOIN `usuarios_escuelas` ue ON `id_usuario`=$_SESSION[id_usuarios] AND f.`id_zona` = ue.`id_org` AND f.`status` = 1";
                    $res_lstFil = $mysqli->query($lstFil);
                    while ($row_reslstFil = $res_lstFil->fetch_array()) {                    
                  ?>
                    <option value="<?php echo $row_reslstFil["id_filantropica"] ?>"><?php echo $row_reslstFil['nombre'] ?></option>
                  <?php
                    }
                  ?>
                  </select>
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
    include "cls_escuela.php";
    $rfc = $_POST["rfc"];
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];
    $filantropica = $_POST["filantropica"];
    $clasEscuela = new escuela($rfc, $nombre, $direccion, $telefono, $correo, $filantropica);
    $clasEscuela->insertar();
}
?>