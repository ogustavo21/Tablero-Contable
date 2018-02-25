<?php 
session_start(); //Inicia session
$atras = "";
if (isset($_SESSION['id_usuarios'])){
if ($_SESSION["tipo_usuario"] == 'Financiero General') {
  header("location: /modulos/principal/union.php");
}
if ($_SESSION["tipo_usuario"] == 'Financiero de Zona') {
  header("location: /modulos/principal/zona.php");
}
if ($_SESSION["tipo_usuario"] == 'Financiero de Filantropica') {
  header("location: /modulos/principal/index.php");
}
if ($_SESSION["tipo_usuario"] == 'Contador de Escuela') {
  header("location: /modulos/principal/index.php");
}

}//Se cierra la comprobación de existir sesión
else{

include($atras.'template/libs.php');
?>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>TABLERO </b>SEA</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Iniciar Sesión</p>

    <form action="conex/control.php" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="login" placeholder="Usuario">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="pass" placeholder="Contraseña">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-6">
          <div class="checkbox icheck">
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Iniciar sesión</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<?php 
  include($atras.'template/script.php');
?>
</body>
</html>
<?php
}//Se cierra el else de que no existe ninguna sesión
?>