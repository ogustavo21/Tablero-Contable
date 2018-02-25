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
          <p><?php echo $_SESSION["tipo_usuario"] ?></p>
          <a><i class="fa fa-caret-right"></i> <?php echo $_SESSION["nombre"] ?></a>
        </div>
      </div>
      <?php
      if ($_SESSION["tipo_usuario"] == 'Financiero General') {
        ?>
          
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MENÚ</li>
        <li class="active treeview">
          <a href="../principal/union.php">
            <i class="fa fa-dashboard"></i> <span>INICIO</span>
            
          </a>
        </li>
        <li><a href="../ejercicio/"><i class="fa fa-circle-o"></i> <span>Ejercicio</span></a></li>
        <li><a href="../mes/"><i class="fa fa-circle-o"></i> <span>Mes</span></a></li>   
        <li><a href="../organizacion/"><i class="fa fa-circle-o"></i> <span>Organización</span></a></li>
        <li><a href="../zona/"><i class="fa fa-circle-o"></i> <span>Zonas</span></a></li>
        <li><a href="../usuario/"><i class="fa fa-circle-o"></i> <span>Usuarios</span></a></li>   
        <li><a href="../comprobante/comprobantes.php"><i class="fa fa-circle-o"></i> <span>Comprobantes</span></a></li> 
        <li><a href="../reportes/"><i class="fa fa-circle-o"></i> <span>Reportes</span></a></li>    
       <!--   <li><a href="../reportestest/"><i class="fa fa-circle-o"></i> <span>Prueba de reportes</span></a></li>  -->  
          <!-- <li><a href="../liquidezacum/general.php"><i class="fa fa-circle-o"></i> <span>Liquidez</span></a></li>     -->
      </ul>
      </ul>

      <?php
        }//Se cierra la opción de usuario FINANCIERO GENERAL

      if ($_SESSION["tipo_usuario"] == 'Financiero de Zona') {
        ?>
          
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MENÚ</li>
        <li class="active treeview">
          <a href="../principal/zona.php">
            <i class="fa fa-dashboard"></i> <span>INICIO</span>
            
          </a>
        </li>
        <li><a href="../filantropica/"><i class="fa fa-circle-o"></i> <span>Filatrópicas</span></a></li>        
        <li><a href="../escuela/"><i class="fa fa-circle-o"></i> <span>Escuelas</span></a></li>        
        <li><a href="../usuario/"><i class="fa fa-circle-o"></i> <span>Usuarios</span></a></li>   
        <li><a href="../reportes/"><i class="fa fa-circle-o"></i> <span>Reportes</span></a></li>         
      </ul>

      <?php
        }//Se cierra la opción de usuario FINANCIERO ZONA

       if ($_SESSION["tipo_usuario"] == 'Contador de Escuela') {
        ?>
          
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MENÚ</li>
        <li class="active treeview">
          <a href="../principal/escuela.php">
            <i class="fa fa-dashboard"></i> <span>INICIO</span>
          </a>
        </li>
        <li><a href="../ejercicio/actual.php"><i class="fa fa-circle-o"></i> <span>Ejercicio</span></a></li>
        <li><a href="../comprobante"><i class="fa fa-circle-o"></i> <span>Comprobantes</span></a></li>
        <li><a href="../estado_resultados"><i class="fa fa-circle-o"></i> <span>Estado de resultados</span></a></li>
      <!--  <li><a href="../balance"><i class="fa fa-circle-o"></i> <span>Balance general</span></a></li>-->
         <li><a href="../balance"><i class="fa fa-circle-o"></i> <span>Balance General</span></a></li>
              <li><a href="../presupuesto"><i class="fa fa-circle-o"></i> <span>Presupuesto</span></a></li>
              <li><a href="../liquidezacum"><i class="fa fa-circle-o"></i> <span>Liquidez Acumulada</span></a></li>
      </ul>

      <?php
        }//Se cierra la opción de usuario FINANCIERO ESCUELA
      ?>
    </section>
    <!-- /.sidebar -->
  </aside>