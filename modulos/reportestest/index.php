<?php 
$atras = "../../";
  include($atras.'template/todo.php');
?>
<style type="text/css">
th{
  text-align: center;
}

#verde{
  background-color: #4bae4f;
}

#naranja{
  background-color: #fe9700;
}
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Estado
        <small>de Resultados</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

    <?php
    if ($_SESSION["tipo_usuario"] == 'Financiero General'){
      ?>
    <div class="row">
       <div class="col-xs-12">
         <div class="box box-primary">
  <!-- *****************************************/.ajax ejercicio con mes******************************** -->

    <script type="text/javascript">
    $(document).ready(function(){
        $("#ejercicio").change(function(){
            var ejercicio=$(this).val();
            var dataString2 = 'ej1='+ ejercicio;
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

       
      $('#mes').change(function(){
          $('#tipo').prop('selectedIndex',0);
          $('#zona').prop('selectedIndex',0);
          $('#escuela').prop('selectedIndex',0);
      });

      $('#ejercicio').change(function(){
          $('#tipo').prop('selectedIndex',0);
          $('#zona').prop('selectedIndex',0);
          $('#escuela').prop('selectedIndex',0);
      });

      $('#tipo').change(function(){
          $('#escuela').prop('selectedIndex',0);
      });
    });
    </script>
              <div class="box-header with-border">
                <h3 class="box-title">Selección</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
                <div class="box-body">
                <div class="col-xs-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Año de ejercicio</label>
                    <select class="form-control" id="ejercicio">
                      <option>Selecciona una opción</option>
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


                  <!-- te imprime el id del ajax con el mes ya sacado de la consulta de ajax link normal-->
                <div class="col-xs-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Més</label>
                    <select class="form-control" id="mes">
                    </select>
                  </div>
                </div>

                <div class="col-xs-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Tipo</label>
                    <select class="form-control" id="tipo">
                      <option>Selecciona una opción</option>
                      <option>Estado de resultados</option>
                      <option>Balance general</option>
                      <option>Presupuesto</option>
                    </select>
                  </div>
                </div>

                <div class="col-xs-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Zona</label>
                    <select class="form-control" id="zona">
                    
                    </select>
                  </div>
                </div>

                <div class="col-xs-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Escuela</label>
                    <select class="form-control" id="escuela">
                    <option></option>
                    </select>
                  </div>
                </div>
                </div>
                <!-- /.box-body -->
            </div>
            <div id="resultado"></div>
       </div>
    </div>
    <?php
  }
    if ($_SESSION["tipo_usuario"] == 'Financiero de Zona'){
      ?>
    <div class="row">
       <div class="col-xs-12">
         <div class="box box-primary">
  <!-- *****************************************/.ajax ejercicio con mes******************************** -->

    <script type="text/javascript">
    $(document).ready(function(){
        $("#ejercicio").change(function(){
            var ejercicio=$(this).val();
            var dataString2 = 'ej1='+ ejercicio;
            $.ajax({
                type: "POST",
                url: "ajax_link_zona.php",
                data: dataString2,
                cache: false,
            success: function(html){
            $("#mes").html(html);
            }
            });
        });

        $("#tipo").change(function(){
        var tipo=$(this).val();
        var mes=$("#mes").val();
        var dataString2 = 't1='+ tipo +'&t2='+ mes;
        $.ajax({
            type: "POST",
            url: "ajax_link_zona.php",
            data: dataString2,
            cache: false,
        success: function(html){
        $("#escuela").html(html);
        }
        });
      });


        $("#escuela").change(function(){
        var tipo=$("#tipo").val();
        var escuela=$(this).val();
        var mes=$("#mes").val();
        var dataString2 = 'e1='+ tipo +'&e2='+ escuela +'&e3='+ mes;
        $.ajax({
            type: "POST",
            url: "ajax_link_zona.php",
            data: dataString2,
            cache: false,
        success: function(html){
        $("#resultado").html(html);
        }
        });
      });

      $('#mes').change(function(){
          $('#tipo').prop('selectedIndex',0);
          $('#escuela').prop('selectedIndex',0);
      });

      $('#ejercicio').change(function(){
          $('#tipo').prop('selectedIndex',0);
          $('#escuela').prop('selectedIndex',0);
      });

      $('#tipo').change(function(){
          $('#escuela').prop('selectedIndex',0);
      });
    });
    </script>
              <div class="box-header with-border">
                <h3 class="box-title">Selección</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
                <div class="box-body">
                <div class="col-xs-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Año de ejercicio</label>
                    <select class="form-control" id="ejercicio">
                      <option>Selecciona una opción</option>
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

                <div class="col-xs-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Més</label>
                    <select class="form-control" id="mes">
                    </select>
                  </div>
                </div>

                <div class="col-xs-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Tipo</label>
                    <select class="form-control" id="tipo">
                      <option>Selecciona una opción</option>
                      <option>Estado de resultados</option>
                      <option>Balance general</option>

                    </select>
                  </div>
                </div>

                <div class="col-xs-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Escuela</label>
                    <select class="form-control" id="escuela">
                    <option></option>
                    </select>
                  </div>
                </div>
                </div>
                <!-- /.box-body -->
            </div>
            <div id="resultado"></div>
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
