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
    if ($_SESSION["tipo_usuario"] == 'Contador de Escuela'){

        $estadomes = "SELECT `status`FROM mes WHERE  id_mes=$_SESSION[id_mes] ";
       $resultadoestado= $mysqli->query($estadomes);
       $arrayresult = $resultadoestado->fetch_array();
    $estado =  $arrayresult['status'];


     $obt_diasfaltantes = "SELECT f_limite FROM mes WHERE f_limite >=(SELECT CURRENT_DATE() FROM `mes`where status=1) and id_mes=$_SESSION[id_mes] and id_ejercicio=$_SESSION[id_ejercicio]";     
    $res_diasfalt = $mysqli->query($obt_diasfaltantes);
     while ($row_balance2 = $res_diasfalt->fetch_array()) { 
         $tmp_dias = $row_balance2['f_limite'];
                    
        }
               
     $resultadodia = count($tmp_dias);
     
    $obt_ER = "SELECT `id_edo_res` FROM `edo_res` WHERE `id_escuela` = $_SESSION[id_superior] AND `id_mes`=$_SESSION[id_mes]";     
    $res_ER = $mysqli->query($obt_ER);
    $num_ER = $res_ER->num_rows;
    ?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
      <?php 
    if ($num_ER == 0 ) {
       if ($estado==1 || $resultadodia >0) {
        
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
        $obt_edoResul = "SELECT * FROM `edo_res_detalle_temp` WHERE `id_usuario`=$_SESSION[id_usuarios] AND `id_mes`=$_SESSION[id_mes]";
        /*COMPROBACIÓN DE QUE ESTE BIEN EL FORMATO DEL EXCEL AL SUBIR*/
        $obt_cant = "SELECT `concepto`, SUM(`presupuestom`)  FROM `edo_res_detalle_temp` WHERE `id_usuario` IN ($_SESSION[id_usuarios], 0) AND `id_mes` IN ($_SESSION[id_mes], 0) GROUP BY `concepto` ORDER BY `id_edo_resultados`";
        $res_cant = $mysqli->query($obt_cant);
        $num_cant = $res_cant->num_rows;
         if ($num_cant == 101) {
          $boton = '<button class="btn bg-blue margin sweet-4 pull-right" onclick="confirmar()">Confirmar subida</button>';
        }else{
          $boton = "<h3 style=color:red>Existe un error en la escritura en los siguientes conceptos:</h3>";
          $n=0;
          while ($row_cant = $res_cant->fetch_array()) { 
            if ($n > 100) {
              $boton .= "<p>$row_cant[concepto]</p>";
            }
            $n++;
          }
        }
         }}//Cierra $num_ER 
        else{
          $obt_edoResul = "SELECT erd.* FROM `edo_res_detalle` erd INNER JOIN `edo_res`er ON erd.`id_edo_res` = er.`id_edo_res` WHERE er.`id_escuela`=$_SESSION[id_superior] AND er.`id_mes`=$_SESSION[id_mes]";
          }?>
        <div class="col-xs-12">
         
 <?php 
  $res_edoResul = $mysqli->query($obt_edoResul);
  $num_edoResul = $res_edoResul->num_rows;
  if ($num_edoResul > 0) {
?>  
          <div class="box box-primary">
            <div class="box-header">
            <h3 class="box-title">Resultados</h3>
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
                  <tr><th colspan="7"><?php echo $row_slcInfo['filantropica'] ?></th></tr>
                  <tr><th colspan="7"><?php echo $row_slcInfo['escuela'] ?></th></tr>
                  <tr><th colspan="7">Estado de Resultados Comparativo</th></tr>
                  <tr><th colspan="7"><?php echo "Del ". strftime("%d de %B", strtotime($row_slcFecha['f_inicio'])) ." al ". strftime('%d de %B de %Y', strtotime($row_slcFecha['f_final'])) ?></th></tr>
                  <tr><th colspan="7" height="20px"></th></tr>
                  <tr>
                    <th></th>
                    <th colspan="3" id="verde"><i>Información mensual</i></th>
                    <th colspan="3" id="naranja"><i>Información acumulada</i></th>
                  </tr>
                  <tr>
                    <th>Concepto</th>
                    <th id="verde">Presupuesto</th>
                    <th id="verde">Real</th>
                    <th id="verde">Variación</th>
                    <th id="naranja">Presupuesto</th>
                    <th id="naranja">Real</th>
                    <th id="naranja">Variación</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="7"><u><strong>INGRESOS OPERATIVOS</strong></u></td>
                  </tr>
                  <tr>
                    <td colspan="7" height="20px"></td>
                  </tr>
                  <tr>
                    <td colspan="7"><u><strong>Ingresos educativos</strong></u></td>
                  </tr>
    <?php
      while ($row_edoResul = $res_edoResul->fetch_array()) { 
        $concepto=""; $presupuestom=""; $realm=""; $variacionm=""; $presupuestoa=""; $reala=""; $variaciona="";
        $CC = $row_edoResul['concepto'];
        if ($CC == 'Enseñanza' || $CC == "Ingresos por Colegiaturas no Recibidos" || $CC == 'Otros Ingresos (Transferencias, Financieros, Reclamos)' || $CC == 'Impuestos Estatales' || $CC == 'Teléfono') {
          $presupuestom = 'style="border-bottom:1px solid black;"';
          $realm=$presupuestom; $variacionm=$presupuestom; $presupuestoa=$presupuestom; $reala=$presupuestom; $variaciona=$presupuestom;
        }

        if ($CC == ' Total Ingresos Educativos Netos' || $CC == ' Total de Otros Ingresos' || $CC == 'TOTAL DE INGRESOS OPERATIVOS' || $CC == ' Total Sueldos y Prestaciones' || $CC == ' Total Gastos de Administración y Generales'|| $CC == 'GRAN TOTAL DE GASTOS') {
          $presupuestom = 'style="font-weight: bold;"';
          $realm=$presupuestom; $variacionm=$presupuestom; $presupuestoa=$presupuestom; $reala=$presupuestom; $variaciona=$presupuestom; $concepto = $presupuestom;
        }

        if ($CC == 'Sub Total Ingresos Educativos') {
          $concepto = 'style="font-weight: bold;"';
        }

        if ($CC == 'Otros Ingresos' || $CC == 'EGRESOS' || $CC == 'Gastos por Sueldos y Prestaciones' || $CC == 'Gastos de Administración y Generales' || $CC == ';') {
          $concepto = 'style="font-weight: bold;text-decoration: underline;"';
          $row_edoResul['presupuestom'] = ""; $row_edoResul['realm']=""; $row_edoResul['variacionm']=""; $row_edoResul['presupuestoa']=""; $row_edoResul['reala']=""; $row_edoResul['variaciona']="";
          if ($CC == ';') {
            $row_edoResul['concepto'] = "";
            $concepto .= 'height = "10px"';
          }
        }
        if ($CC == 'UTILIDAD O (PERDIDA) OPERATIVA ') {
          $presupuestom = 'style="border-bottom: medium double black;font-weight: bold;"';
          $realm=$presupuestom; $variacionm=$presupuestom; $presupuestoa=$presupuestom; $reala=$presupuestom; $variaciona=$presupuestom;
          $concepto = $presupuestom;
        }
    ?>
              <tr>
                    <td <?php echo $concepto ?>><?php echo $row_edoResul['concepto'] ?></td>
                    <div <?php echo $numeros ?>>
                    <td <?php echo $presupuestom ?>><?php echo $row_edoResul['presupuestom'] ?></td>
                    <td <?php echo $realm ?>><?php echo $row_edoResul['realm'] ?></td>
                    <td <?php echo $variacionm ?>><?php echo $row_edoResul['variacionm'] ?></td>
                    <td <?php echo $presupuestoa ?>><?php echo $row_edoResul['presupuestoa'] ?></td>
                    <td <?php echo $reala ?>><?php echo $row_edoResul['reala'] ?></td>
                    <td <?php echo $variaciona ?>><?php echo $row_edoResul['variaciona'] ?></td>
                    </div>
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
              <?php echo $boton; ?>
            </div>
          </div>
          <!-- /.box -->
          <?php }//Cierra el if de que exista algún registro en el estado de resultados ?>


        </div>
        <!-- /.col -->
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
