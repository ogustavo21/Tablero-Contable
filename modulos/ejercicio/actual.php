<?php 
$atras = "../../";
  include($atras.'template/todo.php');
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Seleccionar
        <small>Ejercicio</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if ($_SESSION["tipo_usuario"] == 'Contador de Escuela'){
    ?>
      <!-- Small boxes (Stat box) -->
      <div class="col-md-1">
      </div>
      <div class="col-md-10">
      <div class="box box-primary">





<!-- *****************************************/.ajax ejercicio con mes******************************** -->

  <script type="text/javascript">
  $(document).ready(function(){
      $("#ejercicio").change(function(){
          var ejercicio=$(this).val();
          var dataString2 = 'ejercicio='+ ejercicio;
          $.ajax({
              type: "POST",
              url: "ajax_link.php",
              data: dataString2,
              cache: false,
          success: function(html){
          $("#mes").html(html);
          }
          });
      });
    });
  </script>
            <div class="box-header with-border">
              <h3 class="box-title">Selección</h3>
            </div>






<!-- *****************************************/.ajax id escuela selecionada******************************** -->

  <script type="text/javascript">
  $(document).ready(function(){
      $("#id_org").change(function(){
          var id_escuela=$(this).val();
          var dataString2 = 'id_org='+ id_escuela;
          $.ajax({
              type: "POST",
              url: "../escuela/ajax_link2.php",
              data: dataString2,
              cache: false,
          success: function(html){
           $("#resultado").html(html);
          }
          });
      });
    });
  </script>

            <!-- /.box-header -->
            <!-- form start -->
              <div class="box-body">

          <div class="col-xs-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Seleccione la Escuela</label>
                  <select class="form-control" id="id_org" >
                    <option><?php echo  "Elegida: ". $_SESSION["escuela"]; ?></option>
                  <?php
                    $lstMes = "SELECT usuarios_escuelas.id_org, escuela.nombre 
                    from usuarios_escuelas 
                    INNER JOIN escuela ON usuarios_escuelas.id_org=escuela.id_escuela 
                    WHERE usuarios_escuelas.id_usuario=$_SESSION[id_usuarios]";
                    $res_lstMes = $mysqli->query($lstMes);
                  while ($row_res_lstEjer = $res_lstMes->fetch_array()) { 
                    ?>
                    <option value="<?php echo $row_res_lstEjer["id_org"] ?>"><? echo $row_res_lstEjer["nombre"] ?></option>
                    
                    <?
                  }
                  ?>
                  </select>

                
                </div>
</div>
<div id="resultado"></div>
   </div>


            <!-- /.box-header -->
            <!-- form start -->
              <div class="box-body">
              <div class="col-xs-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Año de ejercicio</label>
                  <select class="form-control" id="ejercicio">
                    <option><?php echo $_SESSION["id_ejercicio"] ?></option>
                  <?php
                  $lstEjer = "SELECT `id_ejercicio` FROM `ejercicio`";
                  $res_lstEjer = $mysqli->query($lstEjer);
                  while ($row_res_lstEjer = $res_lstEjer->fetch_array()) { 
                    ?>
                    <option><? echo $row_res_lstEjer["id_ejercicio"] ?></option>
                    <?
                  }
                  ?>
                  </select>
                </div>
                </div>

              <div class="col-xs-6">
                <div class="form-group">
                  <label for="exampleInputPassword1">Mes</label>
                  <select class="form-control" id="mes">
                  <?php
                    $lstMes = "SELECT `mes` FROM `mes` WHERE `id_mes` = $_SESSION[id_mes]";
                    $res_lstMes = $mysqli->query($lstMes);
                    $row_res_lstMes = $res_lstMes->fetch_array();
                  ?>
                    <option value="<?php echo $_SESSION["id_mes"] ?>"><? echo $row_res_lstMes["mes"] ?></option>
                  </select>
                </div>
              </div>
              </div>
              <!-- /.box-body -->
          </div>
          </div>





<script type="text/javascript">
  $(document).ready(function(){
      $("#mes").change(function(){
      var mes=$(this).val();
      var dataString2 = 'mes='+ mes;
      $.ajax({
          type: "POST",
          url: "ajax_link.php",
          data: dataString2,
          cache: false,
      success: function(html){
      $("#resultado").html(html);
      }
      });
  });
    });

  </script>

                        <div id="resultado"></div>
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