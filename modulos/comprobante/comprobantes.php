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
        <small>Comprobantes</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if ($_SESSION["tipo_usuario"] == 'Financiero General'){
    ?>
<script type="text/javascript">
  //Mostrar los grupos obteniendo el id del trimestre
  $(document).ready(function(){
      $("#zona").change(function(){
          var zona=$(this).val();
          var dataString2 = 'zona='+ zona;
          $.ajax({
              type: "POST",
              url: "ajax_zona.php",
              data: dataString2,
              cache: false,
          success: function(html){
          $("#result").html(html);
          }
          });
      });
    });
  </script>
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class="col-xs-12">
        <div class="col-md-4">
          <div class="form-group">
            <select class="form-control" name="zona" id="zona">
    <?php
      $obt_Zona = "SELECT DISTINCT z.`id_zona`,z.`zona` FROM `zona` z INNER JOIN `escuela` e ON z.`id_zona` = e.`id_zona` INNER JOIN `comprobantes` c ON e.`id_escuela` = c.`id_escuela` WHERE z.`status` = 1 AND z.`id_union` = 1 AND c.`id_mes` = $_SESSION[id_mes]";       
      $res_Zona = $mysqli->query($obt_Zona);
      $num_zonas = $res_Zona->num_rows;
      if ($num_zonas == 0) {
    ?>
      <option value="">No existe ningun comprobante en el mes actual</option>
    <?php
      }else{
        ?>
        <option>Selecciona una zona</option>
      <?php
      while ($row_Zona = $res_Zona->fetch_array()) { 
      ?>
        <option value="<?php echo $row_Zona["id_zona"] ?>"><?php echo $row_Zona["zona"] ?></option>
      <?php
        }
      }
    ?>
            </select>
          </div>
          </div>
          </div>
        <div class="col-xs-6">

          <div class="box box-primary" id="result">
            <div class="box-header">
            <h3 class="box-title">Zona </h3>
            </div>
            <div class="box-body">


            </div>
            <!-- /.box-body -->
          </div>
        </div>
        <!-- /.col -->

      <div class="col-xs-6">
        <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Resultado</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="resultado_info">


            </div>
            <!-- /.box-body -->
        </div>
          <!-- /.box -->
      </div><!-- /.col -->

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
