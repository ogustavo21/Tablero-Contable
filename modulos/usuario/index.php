<?php 
$atras = "../../";
  include($atras.'template/todo.php');
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista
        <small>Usuarios</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if ($_SESSION["tipo_usuario"] == 'Financiero General'){
      include('index_union.php');
    }
    elseif ($_SESSION["tipo_usuario"] == 'Financiero de Zona'){
      include('index_zona.php');
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
