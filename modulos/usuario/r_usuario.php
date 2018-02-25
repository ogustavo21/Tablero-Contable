<?php 
$atras = "../../";
  include($atras.'template/todo.php');
?>
<script type="text/javascript">
  $(document).ready(function()
    {
    $('#divEsc').hide();
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
        Registro
        <small>Usuarios para contadores</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">


    <?php

    if ($_SESSION["tipo_usuario"] == 'Financiero General'){
    ?>
      <div class="row">
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
                  <label for="exampleInputEmail1">RFC</label>
                  <input type="text" name="rfc" class="form-control" placeholder="RFC">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Nombre</label>
                  <input type="text" name="nombre" class="form-control" placeholder="Nombre">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Apellido paterno</label>
                  <input type="text" name="apaterno" class="form-control" placeholder="Apellido paterno">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Apellido materno</label>
                  <input type="text" name="amaterno" class="form-control" placeholder="Apellido materno">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Correo</label>
                  <input type="email" name="correo" class="form-control" placeholder="Correo">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Contraseña</label>
                  <input type="password" name="pass" class="form-control" placeholder="Contraseña">
                </div> 
               <div class="form-group">
                  <label for="exampleInputPassword1">Zona</label>                  
                  <select class="form-control" name="zona">
                  <?php
                    $lstZona = "SELECT `id_zona`, `zona` FROM `zona` WHERE `id_union` = 1 AND `status` = 1";
                    $res_lstZona = $mysqli->query($lstZona);
                    while ($row_reslstZona = $res_lstZona->fetch_array()) {                    
                  ?>
                    <option value="<?php echo $row_reslstZona["id_zona"] ?>"><?php echo $row_reslstZona["zona"] ?></option>
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
          </div><!--Cerrar el dov row-->
          <?php
        }


         if ( $_SESSION["tipo_usuario"] == 'Financiero de Zona'){
                    
    ?>
      <div class="row">
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
                  <label for="exampleInputEmail1">RFC</label>
                  <input type="text" name="rfc" class="form-control" placeholder="RFC">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Nombre</label>
                  <input type="text" name="nombre" class="form-control" placeholder="Nombre">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Apellido paterno</label>
                  <input type="text" name="apaterno" class="form-control" placeholder="Apellido paterno">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Apellido materno</label>
                  <input type="text" name="amaterno" class="form-control" placeholder="Apellido materno">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Correo</label>
                  <input type="email" name="correo" class="form-control" placeholder="Correo">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Contraseña</label>
                  <input type="password" name="pass" class="form-control" placeholder="Contraseña">
                </div> 
               <div class="form-group">

                                       <script type="text/javascript">
                      $(document).ready(function(){
                          $("#id_filantropica").change(function(){
                              var id_filantropica=$(this).val();
                              var dataString2 = 'id_filantropica='+ id_filantropica;
                              $.ajax({
                                  type: "POST",
                                  url: "ajax_link.php",
                                  data: dataString2,
                                  cache: false,
                              success: function(html){
                               $("#result").html(html);
                              }
                              });
                          });
                        });
                      </script>


                    <?php
                    $idusu=$_SESSION['id_usuarios'];
                       $Zona2 = "SELECT id_zona  FROM `usuarios`, zona, usuarios_escuelas WHERE id_usuarios=$_SESSION[id_usuarios] and usuarios_escuelas.id_usuario=usuarios.id_usuarios and zona.id_zona=usuarios_escuelas.id_org";
                  $res_lstZona1=$mysqli->query($Zona2);
                     $row_reslstZona1 = $res_lstZona1->fetch_array();
                      $esttt=$row_reslstZona1['id_zona']; 
                    ?>

                 
                      <label  for="exampleInputPassword1">Filantrópica</label> 
                  <select class="form-control" name="" id="id_filantropica">
                      <option selected disabled value="">Selecciona una Filantrópica</option>
                  <?php
                    $lstEscuela = "SELECT id_filantropica, nombre FROM filantropica WHERE id_zona=$esttt";
                    $res_lstEscuela = $mysqli->query($lstEscuela);
                    while ($row_reslstFila = $res_lstEscuela->fetch_array()) {                    
                  ?>
                    <option value="<?php echo $row_reslstFila["id_filantropica"] ?>"><?php echo $row_reslstFila["nombre"] ?></option>

                  <?php
                    }
                  ?>
                  </select>


                  <label for="exampleInputPassword1">Escuela</label> 
                   

                  <select class="form-control" name="escuela" id="result">  
                    <option selected disabled value="">Selecciona una Escuela</option>

                   </select>

                      <select  name="creador" style="visibility:hidden">
                        <option style="visibility:hidden"  value="<?php echo$_SESSION['id_usuarios']?>"></option>
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
if (isset($_POST["rfc"])) {
  include "cls_usuario.php";
  if ($_SESSION["tipo_usuario"] == 'Financiero General'){
    $rfc = $_POST["rfc"];
    $nombre = $_POST["nombre"];
    $apaterno = $_POST["apaterno"];
    $amaterno = $_POST["amaterno"];
    $correo = $_POST["correo"];
    $pass = $_POST["pass"];
    $zona = $_POST["zona"];
    $clasUsuario = new usuario($rfc, $nombre, $apaterno, $amaterno, $correo, $pass,$zona);
  }elseif($_SESSION["tipo_usuario"] == 'Financiero de Zona'){
    $rfc = $_POST["rfc"];
    $nombre = $_POST["nombre"];
    $apaterno = $_POST["apaterno"];
    $amaterno = $_POST["amaterno"];
    $correo = $_POST["correo"];
    $pass = $_POST["pass"];
    $escuela = $_POST["escuela"];
    $creador =$_POST["creador"];
     $clasUsuario = new usuario($rfc, $nombre, $apaterno, $amaterno, $correo, $pass, $escuela, $creador);
  }
    $clasUsuario->insertar();
}
?>