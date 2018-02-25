  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $atras ?>dist/img/avatar5.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $_SESSION["id_tipo_usuario"] ?></p>
          <a><i class="fa fa-caret-right"></i> <?php echo $_SESSION["nombre"] ?></a>
        </div>
      </div>
      <?php
      if ($_SESSION["id_tipo_usuario"] = 'Financiero General') {
        ?>
          
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MENÚ</li>
        <li class="active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>INICIO</span>
            
          </a>
        </li>
        <li><a href="../zona/"><i class="fa fa-circle-o"></i> <span>Zonas</span></a></li>
        <li><a href="../usuario/"><i class="fa fa-circle-o"></i> <span>Usuarios</span></a></li>        
      </ul>

      <?php
        }//Se cierra la opción de usuario FINANCIERO GENERAL
      ?>

      <?php
      if ($_SESSION["id_tipo_usuario"] = 'Financiero de Zona') {
        ?>
          
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MENÚ</li>
        <li class="active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>INICIO</span>
            
          </a>
        </li>
        <li><a href="../zona/"><i class="fa fa-circle-o"></i> <span>Zonas</span></a></li>
        <li><a href="../usuario/"><i class="fa fa-circle-o"></i> <span>Usuarios</span></a></li>        
      </ul>

      <?php
        }//Se cierra la opción de usuario FINANCIERO GENERAL
      ?>
    </section>
    <!-- /.sidebar -->
  </aside>