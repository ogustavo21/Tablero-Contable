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
        Liquidez
        <small>General</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
     //echo $_SESSION["id_usuarios"];
    if ($_SESSION["tipo_usuario"] =='Financiero General'){/**/
       
      
    ?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
      <?php 
    
        /*EXTRAE TODOS LOS ACTIVOS CORRIENETES Y PASIVOS CORRIENTES DE TODAS LAS ESCUELAS*/
        /*
          $obt_balance = "SELECT sum(activo) as tactivo, SUM(pasivo) as tpasivo   from balance_detalle WHERE concepto_activo LIKE '%Total de Activo Corriente%' OR concepto_pasivo  LIKE '%Total de Pasivo Corriente%'";
       */
          /*EXTRAE SOLO CONCEPTO PASIVO*/
        /*  $tpasivocorr ="SELECT concepto_pasivo, SUM(pasivo) as tpasivo   from balance_detalle INNER JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_pasivo  LIKE '%Total de Pasivo Corriente%' AND balance.id_escuela=3 AND balance.id_ejercicio=2017";


           $tactivocorr ="SELECT concepto_activo, SUM(activo) as tactivo   from balance_detalle INNER JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE '%Total de Activo Corriente%' AND balance.id_escuela=3 AND balance.id_ejercicio=2017";*/
 
 /*

  $res_tpasivocorr = $mysqli->query($tpasivocorr);
  $res_tactivocorr = $mysqli->query($tactivocorr);
  $num_balance = $res_balance->num_rows;
   /**/
?>

         <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header">
            <h3 class="box-title">test</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <table width="100%">
              <?php 
                $slcInfo = "SELECT es.`nombre` escuela, f.`nombre` filantropica FROM `escuela` es INNER JOIN `filantropica` f ON es.`id_filantropica` = f.`id_filantropica`  WHERE `id_escuela` = 3";
                $sql_slcInfo = $mysqli->query($slcInfo);
                $row_slcInfo = $sql_slcInfo->fetch_array();
                $slcFecha = "SELECT `f_inicio`,`f_final` FROM `mes` WHERE `id_mes` = $_SESSION[id_mes]";//arreglar esto
                $sql_slcFecha = $mysqli->query($slcFecha);
                $row_slcFecha = $sql_slcFecha->fetch_array();
                setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
              ?>
                <thead>
                  <tr><th colspan="5"><?php echo $row_slcInfo['filantropica'] ?></th></tr>
                  <tr><th colspan="5"><?php echo $row_slcInfo['escuela'] ?></th></tr>
                  <tr><th colspan="5">Informe Financiero Comparativo de Capital Operativo y Liquidez</th></tr>
                  <tr style="border-bottom: medium double black;"><th colspan="5"><?php echo "Al ". strftime('%d de %B de %Y', strtotime($row_slcFecha['f_final'])) ?></th></tr>
                  <tr><th colspan="5" height="20px"></th> </tr>
                  <th colspan="5">CAPITAL OPERATIVO</th>
                 <tr>
                    
                     <th colspan="1"> </th>
                      
                    <th width="60px"></th>
                    <th colspan="2"> </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="font-weight: bold;">Conceptos Financieros</td>
               
                    <td></td>
                    <td style="font-weight: bold; text-align: left;">Año Actual</td>
                     <td style="font-weight: bold;">Año Anterior</td>
                     <td style="font-weight: bold;">%</td>
                    <td></td>
                  </tr>
    <?php

        $concepto_activo=""; 
        $activo=""; 
        $concepto_pasivo=""; 
        $pasivo="";
        $ca = $row_balance['concepto_pasivo'];
        $cp = $row_balance['tpasivo'];
 

        if (preg_match("/Impuestos por Acreditar\b/", $ca) || preg_match("/Depreciación Acumulada de Eq. Varios\b/", $ca) || preg_match("/Pagos Anticipados a Largo Plazo\b/", $ca)) {
          $activo = 'style="border-bottom:1px solid black;"';
        }
        if (preg_match("/Servicios Educativos Anticipados\b/", $cp) || preg_match("/Anticipo de Clientes a Largo Plazo\b/", $cp)) {
          $pasivo = 'style="border-bottom:1px solid black;"';
        }

        if (preg_match("/Total de Activo Corriente\b/", $ca) || preg_match("/Total de Activo Fijo\b/", $ca) || preg_match("/Total de Activo Diferido\b/", $ca)) {
          $concepto_activo='style="font-weight: bold;"';
          $activo = $concepto_activo;
        }

        if (preg_match("/Total de Pasivo Corriente\b/", $cp)) {
          $concepto_pasivo='style="font-weight: bold;"';
          $pasivo = $concepto_pasivo;
        }

        if (preg_match("/Total de PASIVO\b/", $cp) || preg_match("/Total de PATRIMONIO\b/", $cp)) {
          $concepto_pasivo='style="font-weight: bold;"';
          $pasivo = 'style="font-weight: bold;border-bottom: medium double black;border-top:1px solid black;"';
        }
        if (preg_match("/de ACTIVO\b/", $ca)) {
          $concepto_activo='style="font-weight: bold;"';
          $activo = 'style="font-weight: bold;border-bottom: medium double black;border-top:1px solid black;"';
        }
        if (preg_match("/Activo Fijo\b/", $ca) && !preg_match("/Total de Activo Fijo\b/", $ca)) {
         $concepto_activo='style="font-weight: bold;"';
         $row_balance['activo'] = "";
        }
        if (preg_match("/Pasivo Diferido\b/", $cp) && !preg_match("/Total de Pasivo Diferido\b/", $cp)) {
         $concepto_pasivo='style="font-weight: bold;"';
         $row_balance['pasivo'] = "";
        }
        if ($cp == "PATRIMONIO") {
         $concepto_pasivo='style="font-weight: bold;text-align:center;"';
         $row_balance['pasivo'] = "";
        }
        if (preg_match("/Activo Diferido\b/", $ca) && !preg_match("/Total de Activo Diferido\b/", $ca)) {
         $concepto_activo='style="font-weight: bold;text-align:center;"';
         $row_balance['activo'] = "";
        }
        if (preg_match("/Total de Pasivo Diferido\b/", $cp)) {
         $concepto_pasivo='style="font-weight: bold;"';
         $pasivo = $concepto_pasivo;
        }

        if ($ca == ";" || $ca == "-") {
          $row_balance['activo']="";
          $row_balance['concepto_activo']="";
          $concepto_activo ='height="20px"';
        }
        if ($cp == "") {
          $row_balance['pasivo']="";
        }
        if ($row_balance['activo'] < 0) {
          $activo = 'style="color: red"';
        }
        if ($row_balance['pasivo'] < 0) {
          $pasivo = 'style="color: red"';
        }  ?>

 <?php


 

  $sql1 = "SELECT concepto_activo, SUM(activo) as tactivo   from balance_detalle INNER JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE '%Total de Activo Corriente%' AND balance.id_escuela=41 AND balance.id_ejercicio=2016";
   $resultadoestado1= $mysqli->query($sql1);
       $arrayresult1 = $resultadoestado1->fetch_array();
       $arrayresult1['concepto_activo'];
     $arrayresult1['tactivo'];

  $sql2 = "SELECT concepto_pasivo, SUM(pasivo) as tpasivo   from balance_detalle INNER JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_pasivo  LIKE '%Total de Pasivo Corriente%' AND balance.id_escuela=41 AND balance.id_ejercicio=2016";
  $resultadoestado2= $mysqli->query($sql2);
       $arrayresult2 = $resultadoestado2->fetch_array();
     $arrayresult2['concepto_pasivo'];
      $arrayresult2['tpasivo'];
      $tot=  $arrayresult2['tpasivo']+  $arrayresult1['tactivo'];


//suma de fondocajachica, caja otros ingresos, bancos
      $cajabancos = "SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Fondo de Caja Chica%' AND balance.id_escuela=41 AND balance.id_ejercicio=2016 and balance.id_mes=15 union SELECT   activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Caja Otros Ingresos%' AND balance.id_escuela=41 AND balance.id_ejercicio=2016 and balance.id_mes=15 UNION SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Bancos%' AND balance.id_escuela=41 AND balance.id_ejercicio=2016 and balance.id_mes=15";
  $resultadoestado3= $mysqli->query($cajabancos);
  $total = 0; 
      while ($arrayresult3 = $resultadoestado3->fetch_row()) {
      $total = $total +  $arrayresult3[0];
        }

  $inversion = "SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Inversiones%' AND balance.id_escuela=41 AND balance.id_ejercicio=2016 and balance.id_mes=15";
  $resultadoestado4= $mysqli->query($inversion);
  $total1 = 0; 
      while ($arrayresult4 = $resultadoestado4->fetch_row()) {
      $total1 = $total1 +  $arrayresult4[0];
        }

        $pasivo_cor = "SELECT pasivo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_pasivo  LIKE'%Total de Pasivo Corriente%' AND balance.id_escuela=41 AND balance.id_ejercicio=2016 and balance.id_mes=15";
  $resultadoestado5= $mysqli->query($pasivo_cor);
     $pasivo_cor = 0.0; 
      while ($arrayresult5 = $resultadoestado5->fetch_row()) {
     $pasivo_cor = $pasivo_cor +  $arrayresult5[0];
        }

  
  ?>
              <tr> 
              <td<?php echo $concepto_activo ?>><?php echo $arrayresult1['concepto_activo']?></td>
              <td></td>
              <td<?php echo $concepto_activo ?>><?php echo   $arrayresult1['tactivo']?></td>
            
              </tr>
              <tr>
              <td<?php echo $concepto_activo ?>><?php echo $arrayresult2['concepto_pasivo']?></td>
               <td></td>
               <td<?php echo $concepto_activo ?>><?php echo  $arrayresult2['tpasivo']?></td>
                 </tr>
                 <tr>
                   <td<?php echo $concepto_activo ?>>Total de CAPITAL OPERATIVO ACTUAL</td>
                   <td></td>
              <td<?php  ?>><?php  echo $tot;   ?></td>
                 </tr>
                     <tr>
                   <td<?php echo $concepto_activo ?>>LIQUIDEZ</td>
              <td> </td>
                 </tr>
                 <tr>
                   <td<?php echo $concepto_activo ?>>Caja, Bancos</td>
                    <td></td>
              <td<?php  ?>><?php  echo $total;   ?></td>
                 </tr>

                  <tr>
                   <td<?php echo $concepto_activo ?>>Inversiones</td>
                    <td></td>
              <td<?php  ?>><?php  echo $total1;   ?></td>
                 </tr>

                  <tr>
                   <td<?php echo $concepto_activo ?>>Total de ACTIVO LIQUIDO</td>
                    <td></td>
              <td<?php  ?>><?php  echo $total2=$total+$Total;   ?></td>
                 </tr>


                  <tr>
                   <td<?php echo $concepto_activo ?>>PASIVO Y REMANENTE DEL EJERCICIO</td>

              <td> </td>
                 </tr>
                 <tr>
                   <td<?php echo $concepto_activo ?>>Pasivo Corriente</td>
                    <td></td>
              <td<?php  ?>><?php  echo $pasivo_cor;   ?></td>
                 </tr>
            
  
              
    <?php
       //Cierra while de 
    ?>
                </tbody>
              </table>
               
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
             </div>
          </div>
          <!-- /.box -->
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
