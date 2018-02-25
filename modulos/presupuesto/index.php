<?php 
$atras = "../../";
  include($atras.'template/todo.php');
?>
<style type="text/css">
th{
  text-align: center;
}

#activo, #pasivo{
  text-align: right;
}
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Presupuesto
        <small>General</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    /*echo "idsuperior=";
    echo $_SESSION['id_superior'];
    echo "idmes=";
   echo $_SESSION['id_mes'];
   echo "año";
      echo $_SESSION['id_ejercicio'];
    echo "idusuario=";
     echo $_SESSION['id_usuarios'];*/

    if ($_SESSION["tipo_usuario"] == 'Contador de Escuela'){
    $obt_balance = "SELECT `id_presupuesto` FROM `presupuesto` WHERE `id_escuela` = $_SESSION[id_superior] AND `id_ejercicio`=$_SESSION[id_ejercicio]";     
    $res_balance = $mysqli->query($obt_balance);
    $num_balance = $res_balance->num_rows;


    $obt_mesagosto = "select * from mes WHERE MONTH(CURRENT_DATE)=11";     
    $res_mesagost = $mysqli->query($obt_mesagosto);
    $num_mes =  $res_mesagost->num_rows;
       $mesinicial=  $num_mes ;

    ?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
      <?php 
    if ($num_balance == 0) {
      if($mesinicial>0 ) {
     ?>
        <div class="col-xs-4">
          <div class="box box-primary">
            <div class="box-header">
            <h3 class="box-title">Subir archivo</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="doc_nec">
                <!-- acta, identificación-->
                <form name="frmcargararchivo" action="procesar.php" method="post" enctype="multipart/form-data">
                  <div class="form-group">                  
                    <div class="row">  
                    <div class="col-lg-12">
                    <label>Archivo</label>
                      <div class="input-group">
                        <input id="archivo" type="file" class="file" name="excel" data-show-preview="false" accept=".csv" required="true">
                      </div><!-- /input-group -->
                    </div><!-- /.col-lg-6 --> 
                  </div><!-- /.row -->
                  </div><!-- /.form group -->
                   <!-- <div id="archivos_subidos"></div> -->
                   <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Subir archivo</button>
              </div>
                </form>
                  </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <?php
        $obt_balance = "SELECT * FROM `presupuesto_detalle_temp` WHERE `id_usuario`=$_SESSION[id_usuarios] AND `id_mes`=$_SESSION[id_mes]";
        $res_balance2 = $mysqli->query($obt_balance);
        while ($row_balance2 = $res_balance2->fetch_array()) { 
          $tmp_activo[] = $row_balance2['concepto_presupuesto'];
          //$tmp_pasivo[] = $row_balance2['concepto_pasivo'];          
        }
        $obt_cor = "SELECT * FROM `presupuesto_detalle_temp` WHERE `id_usuario` = 0 AND `id_mes` = 0";
        $res_cor = $mysqli->query($obt_cor);
        while($row_cor = $res_cor->fetch_array()){          
          $cor_activo[] = $row_cor['concepto_presupuesto'];
         // $cor_pasivo[] = $row_cor['concepto_pasivo'];     
        }
        /*COMPROBACIÓN DE QUE ESTE BIEN EL FORMATO DEL EXCEL AL SUBIR*/
        $count_tmp_activo = count($tmp_activo);
        $boton = "";
          for ($i=0; $i < $count_tmp_activo; $i++) { 
            if ($tmp_activo[$i] != $cor_activo[$i]) {
              $boton .= "<p>$tmp_activo[$i] por $cor_activo[$i]</p>";
            }
           /* if ($tmp_pasivo[$i] != $cor_pasivo[$i]) {
              $boton .= "<p>$tmp_pasivo[$i] por $cor_pasivo[$i]</p>";
            }*/
          }
          if ($boton == "") {
            $boton = '<button class="btn bg-blue margin sweet-4 pull-right" onclick="confirmar()">Confirmar subida</button>';
          }else{
            $titulo = "<h3 style=color:red>Existe un error en la escritura en los siguientes conceptos:</h3>";
          }

        // <a href="procesar.php?subir=1"><button type="submit" class="btn btn-primary pull-right">Confirmar subida</button></a>
        }else{



         echo '<div style="position: center; top: 55px;right: 10px;">
                                <div class="alert alert-danger" role="alert">
                              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                              <strong>¡Error!</strong> No existe presupuesto en el Ejercicio selecionado. | &nbsp; Nota: Solo en el mes de <i><b>Agosto</b></i> podrá subir el presupuesto .
                                </div>
                                </div>';


       }


      }else{
          $obt_balance = "SELECT pd.* FROM `presupuesto_detalle` pd INNER JOIN `presupuesto` p ON pd.`id_presupuesto` = p.`id_presupuesto` AND p.`id_escuela` = $_SESSION[id_superior] AND  p.`id_ejercicio`=$_SESSION[id_ejercicio]";
         }


  $res_balance = $mysqli->query($obt_balance);
  $num_balance = $res_balance->num_rows;
  if ($num_balance > 0) {
?>
         <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header">
            <h3 class="box-title">Presupuesto por Escuela</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <table width="100%">
              <?php 
                $slcInfo = "SELECT es.`nombre` escuela, f.`nombre` filantropica FROM `escuela` es INNER JOIN `filantropica` f ON es.`id_filantropica` = f.`id_filantropica` WHERE `id_escuela` = $_SESSION[id_superior]";
                $sql_slcInfo = $mysqli->query($slcInfo);
                $row_slcInfo = $sql_slcInfo->fetch_array();
                $slcFecha = "SELECT `f_inicio`,`f_final` FROM `mes` WHERE `id_mes` = $_SESSION[id_mes]";
                $sql_slcFecha = $mysqli->query($slcFecha);
                $row_slcFecha = $sql_slcFecha->fetch_array();
                setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
              ?>
                <thead>
                  <tr><th colspan="5"><?php echo $row_slcInfo['filantropica'] ?></th></tr>
                  <tr><th colspan="5"><?php echo $row_slcInfo['escuela'] ?></th></tr>
                  <tr><th colspan="5">Estado de Situación Financiera</th></tr>
                  <tr style="border-bottom: medium double black;"><th colspan="5"><?php echo "Al ". strftime('%d de %B de %Y', strtotime($row_slcFecha['f_final'])) ?></th></tr>
                  <tr><th colspan="5" height="20px"></th></tr>
                  <tr>
                   <th colspan="2">ANUAL</th>
                    <th width="60px"></th>
                  <th colspan="2">MENSUAL</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="font-weight: bold;">CUENTAS ANUALES</td>
                    <td></td>
                    <td></td>
                    <td style="font-weight: bold;">CUENTAS MENSUALES</td>
                    <td></td>
                  </tr>
    <?php
      while ($row_balance = $res_balance->fetch_array()) {
       
        $concepto_activo="";
        $anual=""; 
        $concepto_pasivo=""; 
        $mensual="";
        $con_presupuesto = $row_balance['concepto_presupuesto'];   
        $a = $row_balance['anual'];
        $m = $row_balance['mensual'];



        if (preg_match("/Impuestos por Acreditar\b/", $con_presupuesto) || preg_match("/Depreciación Acumulada de Eq. Varios\b/", $con_presupuesto) || preg_match("/Pagos Anticipados a Largo Plazo\b/", $con_presupuesto)) {
          $anual = 'style="border-bottom:1px solid black;"';
           $mensual = 'style="border-bottom:1px solid black;"';
        }
        if (preg_match("/Servicios Educativos Anticipados\b/", $a) || preg_match("/Anticipo de Clientes a Largo Plazo\b/", $a)) {
          $anual = 'style="border-bottom:1px solid black;"';
          $mensual = 'style="border-bottom:1px solid black;"';
        }

        if (preg_match("/SUB TOTAL\b/", $con_presupuesto) || preg_match("/TOTAL INGRESOS\b/", $con_presupuesto) || preg_match("/TOTAL SUELDOS Y SALARIOS\b/", $con_presupuesto,$m)  || preg_match("/TOTAL GASTOS ADMINISTRATIVOS\b/", $con_presupuesto) || preg_match("/SUPERAVIT O (DEFICIT)\b/", $con_presupuesto)) {
          $concepto_activo='style="font-weight: bold;"';
          $anual = $concepto_activo;
          $mensual= $concepto_activo;
        }

        if (preg_match("/SUPERAVIT O (DEFICIT)\b/", $con_presupuesto)) {
          $concepto_pasivo='style="font-weight: bold;"';
          $mensual = $concepto_pasivo;
          $anual = $concepto_pasivo;

        }

        if (preg_match("/Total de PASIVO\b/", $a) || preg_match("/Total de PATRIMONIO\b/", $a)) {
          $concepto_pasivo='style="font-weight: bold;"';
          $mensual = 'style="font-weight: bold;border-bottom: medium double black;border-top:1px solid black;"';
        }
        
 
        if (preg_match("/TOTAL GASTOS ADMINISTRATIVOS\b/", $con_presupuesto)  || preg_match("/TOTAL SUELDOS Y SALARIOS\b/",  $con_presupuesto) || preg_match("/TOTAL INGRESOS\b/", $con_presupuesto)) {
          $concepto_activo='style="font-weight: bold;"';
          $anual = 'style="font-weight: bold;border-bottom: medium double black;border-top:1px solid black;"';
          $mensual= 'style="font-weight: bold;border-bottom: medium double black;border-top:1px solid black;"';
        }


 

        if (preg_match("/Activo Fijo\b/", $con_presupuesto) && !preg_match("/Total de Activo Fijo\b/", $con_presupuesto)) {
         $concepto_activo='style="font-weight: bold;"';
         $row_balance['activo'] = "";
        }
        if (preg_match("/Pasivo Diferido\b/", $a) && !preg_match("/Total de Pasivo Diferido\b/", $a)) {
         $concepto_pasivo='style="font-weight: bold;"';
         $row_balance['pasivo'] = "";
        }
        
        if ($a == "PATRIMONIO") {
         $concepto_pasivo='style="font-weight: bold;text-align:center;"';
         $row_balance['pasivo'] = "";
        }
        if (preg_match("/Activo Diferido\b/", $con_presupuesto) && !preg_match("/Total de Activo Diferido\b/", $con_presupuesto)) {
         $concepto_activo='style="font-weight: bold;text-align:center;"';
         $row_balance['activo'] = "";
        }
        if (preg_match("/Total de Pasivo Diferido\b/", $a)) {
         $concepto_pasivo='style="font-weight: bold;"';
         $mensual = $concepto_pasivo;
        }

        
        if ($con_presupuesto == ";" || $con_presupuesto == "-") {
          $row_balance['activo']="";
          $row_balance['concepto_activo']="";
          $concepto_activo ='height="20px"';
        }
        if ($a == "") {
          $row_balance['pasivo']="";
        }
        if ($row_balance['activo'] < 0) {
          $anual = 'style="color: red"';
        }
        if ($row_balance['pasivo'] < 0) {
          $mensual = 'style="color: red"';
        }
    ?>
              <tr>
                <td <?php echo $concepto_activo ?>><?php echo $row_balance['concepto_presupuesto'] ?></td>
                <td id="activo" <?php echo $anual ?>><?php echo $row_balance['anual'] ?></td>
                <td></td>
                   <td <?php echo $concepto_activo ?>><?php echo $row_balance['concepto_presupuesto'] ?></td>
                <td id="pasivo" <?php echo $mensual ?>><?php echo $row_balance['mensual'] ?></td>
              </tr>
    <?php
      }//Cierra while de 
    ?>
                </tbody>
              </table>
              <script type="text/javascript">
      function confirmar(){
        swal({
          title: "Confirmar envio",
          text: "Clic en si para continuar y enviarlo",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: 'btn-primary',
          confirmButtonText: 'Si, continuar',
          closeOnConfirm: false,
          //closeOnCancel: false
        },
        function(){
          window.location.href = "procesar.php?subir=1";
        });
      };
    </script>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <?php 
              echo $titulo;
              echo $boton; ?>
            </div>
          </div>
          <!-- /.box -->
        </div>
<?php }//Cierra if de $num_balance ?>
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
