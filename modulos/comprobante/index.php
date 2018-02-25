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
    if ($_SESSION["tipo_usuario"] == 'Contador de Escuela'){
    ?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-xs-6">
              
          <div class="box box-primary">
            <div class="box-header">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="doc_nec">
                <!-- acta, identificación-->
               
               
                <form action="javascript:void(0);">
                  <div class="form-group">                  
                    <div class="row">  
                    <div class="col-lg-6">
                    <label>Tipo</label>
                      <div class="input-group" id="recargado">
                      <?php
                      $obt_tdoc = "SELECT * FROM `tipo_comprobantes`";       
                      $res_tdoc = $mysqli->query($obt_tdoc);
                      ?>
                        <select class="form-control" name="id_archivo" id="id_archivo" required>
                        <option value="">Selecciona un tipo</option>
                        <?php
                        
                        while ($row_tdoc = $res_tdoc->fetch_array()) { 
                          ?>
                            <option value="<?php echo $row_tdoc[0] ?>"><?php echo $row_tdoc[1] ?></option>
                          <?php
                        }
                        ?>                        
                      </select>
                      </div><!-- /input-group -->                    
                    </div><!-- /.col-lg-6 --> 
                    <div class="col-lg-6">
                    <label>Descripción</label>
                      <div class="input-group">
                      <center>
                        <input type="text" placeholder="Descripción" name="desc" id="desc" class="form-control"/>
                      </center>
                      </div><!-- /input-group -->                      
                    </div><!-- /.col-lg-6 --> 
                    <div class="col-lg-12"></br>
                    </div>
                    <div class="col-lg-6">
                    <label>Archivo</label>
                      <div class="input-group">
                        <input id="archivo" type="file" class="file" name="archivo" data-show-preview="false" accept="application/pdf, image/*">
                      </div><!-- /input-group -->
                    </div><!-- /.col-lg-6 -->                 
                    <div class="col-lg-6">
                    <label><progress id="barra_de_progreso" value="0" max="100"></progress>  </label>
                      <div class="input-group">
                      <center>
                        <input type="submit" id="boton_subir" value="Subir" class="btn bg-purple btn-flat" style="width:240%" />
                      </center>
                      </div><!-- /input-group -->                    
                    </div><!-- /.col-lg-6 -->
                  </div><!-- /.row -->
                  </div><!-- /.form group -->
                   <!-- <div id="archivos_subidos"></div> -->


                  </form>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
            <div id="datos">
              <table class="table table-hover">
                <tbody>
                <tr>
                  <th>Archivo</th>
                  <th>Nombre</th>
                  <th>Descripción</th>
                  <th>Eliminar</th>
                </tr>
                <?php
                  $select = "SELECT `id_comprobante`,`url`,`descripcion` FROM `comprobantes` WHERE `id_mes` = $_SESSION[id_mes] AND `id_usuario` = $_SESSION[id_usuarios] AND `id_escuela` = $_SESSION[id_superior]";
                  $resul_select = $mysqli->query($select);
                  while($row = $resul_select->fetch_array()){
                ?>
                  <tr id="archivos_subidos">
                    <td style="width: 50px"><a target="_blank" href="../carga_archivos/archivos_subidos/<?php echo $row[1] ?>"> <img width="80%" src="../../dist/img/file.png"></a></td>
                    <td><?php echo $row[1] ?></td>
                    <td><?php echo $row[2] ?></td>
                    <td><button type="button" onClick="borrar(<?php echo $row[0] ?>)" class="btn btn-danger margin">Borrar</button></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              </table>
                </div>
                
                <script type="text/javascript">                
                var user;
                  function borrar(id){
                      user= id;
                      //alert(user);
                      var dataString2 = 'id_com='+ user;
                          $.ajax({
                              type: "POST",
                              url: "../carga_archivos/eliminar_archivo.php",
                              data: dataString2,
                              cache: false,
                              success: function(html){
                          location.href='index.php';
                          }
                          });

                    }
                </script>
            </div>

                  </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <div id="respuesta" class="alert"></div>


        </div>
        <!-- /.col -->

      <div class="col-xs-6">
<!-- COMPROBANTE DE ALUMNOS INSCRITOS-->
<div class="box box-warning">
            <div class="box-header">
            <h3 class="box-title">Alumnos inscritos</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="doc_nec">
                <!-- acta, identificación-->
                <?php
                  $slcIns = "SELECT `id_inscritos`,`blanco`,`alcanzado` FROM `inscritos` WHERE `id_mes` = $_SESSION[id_mes] AND `id_usuario` = $_SESSION[id_usuarios] AND `id_escuela` = $_SESSION[id_superior]";
                  $resul_slcIns = $mysqli->query($slcIns);
                  $row_slcIns = $resul_slcIns->fetch_array();
                  $filas = $resul_slcIns->num_rows;
                  if ($filas >= 1) {
                    $estado = "disabled";
                  }
                ?>
                <form method="post">
                  <div class="form-group">                  
                    <div class="row">  
                    <div class="col-lg-4">
                    <label>Blanco</label>
                      <div class="input-group">
                      <center>
                        <input type="number" placeholder="Blanco" name="blanco" id="blanco" class="form-control" <?php echo $estado ?>/>
                      </center>
                      </div><!-- /input-group -->                      
                    </div><!-- /.col-lg-6 --> 
                    <div class="col-lg-4">
                    <label>Alcanzado</label>
                      <div class="input-group">
                      <center>
                        <input type="number" placeholder="Alcanzado" name="alcanzado" id="alcanzado" class="form-control" <?php echo $estado ?>/>
                      </center>
                      </div><!-- /input-group -->                      
                    </div><!-- /.col-lg-6 -->                   
                    <div class="col-lg-4">
                    <label></label>
                      <div class="input-group">
                      <center>
                      <button type="submit" class="btn btn-primary" <?php echo $estado ?> >Guardar</button>
                      </center>
                      </div><!-- /input-group -->                    
                    </div><!-- /.col-lg-6 -->
                  </div><!-- /.row -->
                  </div><!-- /.form group -->
                   <!-- <div id="archivos_subidos"></div> -->


                  </form>
                  <?php
if (isset($_POST["blanco"])) {
    include "../carga_archivos/subir_archivo.php";
    $blanco = $_POST["blanco"];
    $alcanzado = $_POST["alcanzado"];
    $clasIns = new inscritos($blanco, $alcanzado);
    $clasIns->insertar();
}
?>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
            <div id="actDatos">
              <table class="table table-hover">
                <tbody>
                <tr>
                  <th>Blanco</th>
                  <th>Alcanzado</th>
                  <th>Eliminar</th>
                </tr>
                <?php
                if ($filas >= 1) {
                  ?>
                  <tr id="archivos_subidos">
                    <td><?php echo $row_slcIns[1] ?></td>
                    <td><?php echo $row_slcIns[2] ?></td>
                    <td><button type="button" onClick="borrarIns(<?php echo $row_slcIns[0] ?>)" class="btn btn-danger margin">Borrar</button></td>
                  </tr>
                  <?php
                }
                ?>
              </tbody>
              </table>
                </div>
                
                <script type="text/javascript">                
                var user;
                  function borrarIns(id){
                      user= id;
                      //alert(user);
                      var dataString2 = 'id_ins='+ user;
                          $.ajax({
                              type: "POST",
                              url: "../carga_archivos/eliminar_archivo.php",
                              data: dataString2,
                              cache: false,
                              success: function(html){
                          location.href='index.php';
                          }
                          });

                    }
                </script>
            </div>

                  </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

      </div><!-- /.col -->
      </div>
<script type="text/javascript">
function subirArchivos() {
      //alert("Hola");
  $("#archivo").upload('../carga_archivos/subir_archivo.php',
  {
      id_archivo: $("#id_archivo").val(),
      desc: $("#desc").val()
  },
  function(respuesta) {
      //Subida finalizada.
      $("#barra_de_progreso").val(0);
      if (respuesta === 1) {
          mostrarRespuesta('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4>  <i class="icon fa fa-check"></i>Subida Exitosa!</h4>El archivo se ha subido correctamente.', true);
          $("#id_archivo, #archivo, #desc").val('');
      } else {
          mostrarRespuesta('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Error!</h4>El archivo no se pudo subir.', false);
      }
        }, function(progreso, valor) {
      //Barra de progreso.
      $("#barra_de_progreso").val(valor);
  });
}


function mostrarRespuesta(mensaje, ok){
    datos();
    recargar();  
  $("#respuesta").removeClass('alert-success alert-dismissable').removeClass('alert-danger alert-dismissable').html(mensaje);
  if(ok){
      $("#respuesta").addClass('alert-success alert-dismissable');
  }else{
      $("#respuesta").addClass('alert-danger alert-dismissable');
  }
}

$(document).ready(function() {
    recargar();
    $("#boton_subir").on('click', function() {
      var verif = $("#id_archivo").val();
      //alert(verif);
      if (verif != null) {
    subirArchivos();
        }
  });
});

function recargar(){  
       /// Aqui podemos enviarle alguna variable a nuestro script PHP
    var variable_post="<?php echo $id_tmp ?>";
       /// Invocamos a nuestro script PHP
    $.post("ajax_select.php", { variable: variable_post }, function(data){
       /// Ponemos la respuesta de nuestro script en el DIV recargado
    $("#recargado").html(data);
    });         
}

function datos(){  
  var variable_post="comprobantes";
       /// Invocamos a nuestro script PHP
    $.post("ajax_datos.php", { variable: variable_post }, function(data){
       /// Ponemos la respuesta de nuestro script en el DIV recargado
    $("#datos").html(data);
    });         
}


</script>  
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
