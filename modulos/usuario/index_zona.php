<div class="row">
        <div class="col-xs-12">
      <a href="r_usuario.php"><button type="button" class="btn bg-purple btn-flat margin">Agregar usuario contador</button></a>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Contadores</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table  class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Correo</th>
                  <th>Tipo</th>
                  <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                <?php
                
                if($_SESSION["tipo_usuario"] == 'Financiero de Zona'){
                   $_SESSION[id_usuarios];
                 //$lst_user = "SELECT DISTINCT us.`id_usuarios`, us.`nombre`, us.`apaterno`, us.`amaterno`, us.`correo`, us.`status`, f.`nombre` FROM `usuarios` us INNER JOIN `filantropica` f ON ue.`id_org` = f.`id_filantropica` INNER JOIN `usuarios_escuelas` ue ON ue.`id_usuario` = $_SESSION[id_usuarios] AND f.`id_zona` = ue.`id_org` AND us.`id_tipo_usuario` = 3";
                 $lst_user = "SELECT DISTINCT * FROM `usuarios_escuelas`, escuela, usuarios, zona
                  WHERE usuarios.id_tipo_usuario=5 
                  and usuarios.id_usuarios=usuarios_escuelas.id_usuario
                  and usuarios_escuelas.id_org=escuela.id_escuela
                  and escuela.id_zona=zona.id_zona
                  and zona.id_zona=(SELECT zona.id_zona FROM `usuarios`, zona, usuarios_escuelas WHERE `id_usuarios`=$_SESSION[id_usuarios] and usuarios_escuelas.id_usuario=usuarios.id_usuarios and zona.id_zona=usuarios_escuelas.id_org) group by usuarios.id_usuarios";
                  $res_lst_user = $mysqli->query($lst_user);
            
                  $tipo = "Contador de Escuela";
                }
 

                  while ($row_resuser = $res_lst_user->fetch_array()) {                    
                  "esta";

                        
                   $t= $row_resuser['id_filantropica'];
                   
                ?>
                <tr>
                  <td><?php echo  $row_resuser["nombre"] ." ". $row_resuser["apaterno"] ." ". $row_resuser["amaterno"]; ?></td>

                  <td><?php echo $row_resuser["correo"] ?></td>
                  <td><?php echo $tipo ?></td>
                  <td><button type="button" onClick="abriresc(<?php echo $row_resuser[id_usuarios] ?>)" name="id_usuarios" class="btn bg-navy margin">Agregar Escuela</button>
                  <button type="button" onClick="abrir(<?php echo $row_resuser[id_usuarios] ?>)" name="id_usuarios" class="btn bg-navy margin">Modificar</button>
                  <?php
                    if ($row_resuser["20"] == 1) {
                      $checked = "checked";
                    }elseif ($row_resuser["20"] == 0) {
                      $checked = "";
                    }
                  ?>
                  <input id="toggle-event<?php echo $row_resuser[id_usuarios] ?>" <?php echo $checked ?> type="checkbox" data-toggle="toggle" data-onstyle="success">
                    <script>
                      $(function() {
                        $('#toggle-event<?php echo $row_resuser[id_usuarios] ?>').bootstrapToggle({
                          on: 'Activo',
                          off: 'Inactivo'
                        });

                        $('#toggle-event<?php echo $row_resuser[id_usuarios] ?>').change(function() {
                          var user = $(this).prop('checked');
                          if (user == true) {
                            document.location='cls_usuario.php?estatus=1&id=<?php echo $row_resuser[id_usuarios] ?>';
                          }else{
                            document.location='cls_usuario.php?estatus=0&id=<?php echo $row_resuser[id_usuarios] ?>';
                          }
                        })
                      })
                    </script>
                </td>
                </tr>

<?php 

                $escuelas = "SELECT escuela.nombre, usuarios_escuelas.id_org
                FROM `usuarios_escuelas`, escuela
                WHERE `id_usuario`=$row_resuser[1] and usuarios_escuelas.id_org=escuela.id_escuela";
                $res_escuelas = $mysqli->query($escuelas);
                 $res_escuelasN = $res_escuelas->num_rows;
                while ($row_escuela = $res_escuelas->fetch_array()) {
?>
                <tr>
                  <td colspan="3"><?php echo $row_escuela["nombre"] ?></td>
                  <td ><?php if ($res_escuelasN>1){?><button type="button" onClick="borraresc(<?php echo $row_resuser[id_usuarios] ?>,<?php echo $row_escuela["id_org"]?>)" name="id_usuarios" class="btn  btn-danger btn-sm">Borrar</button><?php }?></td>
                </tr>


                  <select  id="id_filantropica" style="visibility:hidden">
                        <option style="visibility:hidden"  value="<?php echo $row_resuser[id_filantropica]?>"></option>
                          </select> 
                          <select id="id_escuela" style="visibility:hidden">
                        <option style="visibility:hidden"  value="<?php echo $row_resuser['id_escuela']?>"></option>
                          </select> 

      <?php

                    }//fin while escuelas



                }//Cierra wuile de los datos
     ?>
                <script type="text/javascript">                
                var user;
                  function abrir(id){
                      user= id;
                       var id_fil=$("#id_filantropica").val();
                        var id_esc=$("#id_escuela").val();
                       //alert(user);
                      var dataString2 = '&id_usuarios='+ user +'&id_fil='+ id_fil +'&id_esc='+ id_esc;
                          $.ajax({
                              type: "POST",
                              url: "ajax_link.php",
                              data: dataString2,
                              cache: false,
                              success: function(html){
                          location.href='m_usuario.php';
                          }
                          });

                    }
                </script>
                <script type="text/javascript">                
                var user;
                  function abriresc(id){
                      user= id;
                      //alert(user);
                      var dataString2 = 'id_usuarios='+ user;
                          $.ajax({
                              type: "POST",
                              url: "ajax_link.php",
                              data: dataString2,
                              cache: false,
                              success: function(html){
                          location.href='esc_usuario.php';
                          }
                          });

                    }
                </script>
                <script type="text/javascript">
                  function borraresc(idu,idorg){
                      document.location='cls_usuario.php?borrar=1&iduser='+idu+'&idorg='+idorg;

                    }
                </script>
                </tbody>
                <tfoot>
                <tr>
                  
                  <th>Nombre</th>
                  <th>Correo</th>
                  <th>Tipo</th>
                  <th>Acción</th>

                      
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
 