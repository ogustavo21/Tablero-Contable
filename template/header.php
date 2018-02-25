
  <header class="main-header">
    <!-- Logo -->
    <div class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">SEA</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img style=" max-width: 100%; height: 50%;" src="../../dist/img/sea/Logo_SEA.png"></span>
    </div>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
        <?php
        if ($_SESSION["tipo_usuario"] == 'Financiero General') {
        ?>
<script type="text/javascript">

  function myFunction() {
    $.ajax({
      url: "/template/ver_notif.php",
      type: "POST",
      processData:false,
      success: function(data){
        $("#notification-count").hide();          
        $("#notification-latest").show();$("#notification-latest").html(data);
      },
      error: function(){}           
    });
   }
   
   $(document).ready(function() {
    $('body').click(function(e){
      if ( e.target.id != 'notification-icon'){
        $("#notification-latest").hide();
      }
    });
  });
     
  </script>
  <script type="text/javascript">
$(document).ready(function() {  
  function contador(){
    
        var dataString2 = 'id_com=2';
            $.ajax({
                type: "POST",
                url: "/template/contador.php",
                data: dataString2,
                cache: false,
                success: function(html){
                  if (html > 0) {
                  $("#notification-count").show();
                  $("#notification-count").html(html);
                }
            }
            });
  }

  setInterval(contador, 60000);
});
</script>

    <?php
      $count=0;
      $sql2="SELECT c.`id_comprobante` FROM `comprobantes` c INNER JOIN `mes` m ON c.`id_mes`= m.`id_mes` INNER JOIN `escuela` e ON c.`id_escuela` = e.`id_escuela` INNER JOIN `tipo_comprobantes` tp ON c.`id_tipo_comprobante` = tp.`id_tipo_comprobante` WHERE c.`comprobante_al` = 0";
      $result=$mysqli->query($sql2);
      $count=$result->num_rows;
    ?>
          <li class="dropdown notifications-menu">
            <a onclick="myFunction()" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <div id="notification-count" class="label label-warning"><?php if($count>0) { echo $count; } ?></div>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Tu tienes <?php echo $count ?> notificaciones</li>
              <li>
                <div id="notification-latest"></div>
              </li>
              <li class="footer"><!--<a href="<?php echo $atras ?>#">Ver todas</a>--></li>
            </ul>
          </li>
        <?php
      }
      ?>
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $atras ?>dist/img/avatar5.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION["tipo_usuario"] ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo $atras ?>dist/img/avatar5.png" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION["tipo_usuario"] ?>
                  <small><?php echo $_SESSION["nombre"] ?></small>
                </p>
              </li>
              
              
                  <!-- Menu Footer-->
                  <li class="user-footer">
                  <div class="pull-left">
                      <a href="<?php echo $atras ?>modulos/usuario/cambiar.php" class="btn btn-default btn-flat">Contraseña</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo $atras ?>template/exit.php" class="btn btn-default btn-flat">Salir</a>
                    </div>
                  </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>