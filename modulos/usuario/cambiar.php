<?php 
$atras = "../../";
  include($atras.'template/todo.php');
?>
<script type="text/javascript">
  $(document).ready(function()
    {
    $("#tipo_usuario").click(function () {
      var valor = $("#tipo_usuario").val();
      if (valor == "5") {
        $('#divEsc').show();
      }else{
        $('#divEsc').hide();
      }
    });
  });
</script>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Modificar
        <small>Contraseña</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

    <?php
    if ($_SESSION["tipo_usuario"] == 'Financiero General' || $_SESSION["tipo_usuario"] == 'Financiero de Zona' || $_SESSION["tipo_usuario"] == 'Financiero de Filantropica' || $_SESSION["tipo_usuario"] == 'Contador de Escuela'){
      $lstDatos = "SELECT `correo` FROM `usuarios` WHERE `id_usuarios` = $_SESSION[id_usuarios]";
      $res_lstDatos = $mysqli->query($lstDatos);
      $row_reslstDatos = $res_lstDatos->fetch_array();
    ?>
      <div class="row">
      <!-- Small boxes (Stat box) -->
      <div class="col-md-2">
      </div>
      <div class="col-md-8">
      <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Cambio de contraseña</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form method="post">
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Correo</label>
                  <input type="email" name="correo" class="form-control" value="<?php echo $row_reslstDatos["correo"] ?>" disabled>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Contraseña</label>
                  <input type="password" name="pass1" class="form-control" placeholder="Contraseña" required>
                </div>                
                <div class="form-group">
                  <label for="exampleInputPassword1">Confirmar contraseña</label>
                  <input type="password" name="pass2" class="form-control" placeholder="Confirmar contraseña" required>
                </div>  
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
            </form>
          </div>
          </div>
          </div><!--Cerrar el dov row-->
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
if (isset($_POST["pass2"])) {
  include "cls_usuario.php";

    $pass1 = $_POST["pass1"];
    $pass2 = $_POST["pass2"];
    if ($pass1 == $pass2) {
      $pass = md5($pass1);
      $clasUsuario = new usuario(0,0,0,0,0,$pass,0,0,0);
    }
    $clasUsuario->cambiar();
}
?>