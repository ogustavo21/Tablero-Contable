<?php
session_start();
    
//HACE LA VARIABLE DEL ID EN SESSION
if (isset($_POST['id_ejercicio'])) {
  $_SESSION['id_tmp']=$_POST['id_ejercicio'];
}

//HACER AJAX CON EL EJERCICIO PARA MOSTRAR EL MES
if (isset($_POST['ej1'])) {
  require("../../conex/connect.php");

  $ejercicio=$_POST['ej1'];

  $query = "SELECT `id_mes`,`mes` FROM `mes` WHERE `id_ejercicio` = $ejercicio";
  $result = $mysqli->query($query) or die ($mysqli->error);
  $filas = $result->num_rows;
  if($filas > 0){
    echo '<option selected disabled value="">Selecciona un mes</option>';
    while($row = $result->fetch_array()){
      $id_mes=$row["id_mes"];
      $mes=$row["mes"];
      echo '<option value="'.$id_mes.'">'. $mes .'</option>'; 
    }
   }else{
    echo '<option selected disabled value="">No existen meses en este ejercicio</option>';
  }
}

/*este es para rellenar la seccion de el select zona, si selecciona de tipo alguna opcion, balance, estado lo que sea, entonces va rellenarme los datos que puedan existir en las consultas especificamente obtengo valores de la consultas de las opciones que existan en la base de datos */

if (isset($_POST['t1']))
{
  require("../../conex/connect.php");
  $tipo = $_POST["t1"];
  $mes = $_POST["t2"];
  $año = $_POST["t3"];
  if ($tipo == "Balance general") {
   $selZona = "SELECT DISTINCT  z.`id_zona`, z.`zona` FROM `zona` z INNER JOIN `balance` b ON z.`id_zona` = b.`id_zona` AND b.`id_mes` = $mes AND z.`status` = 1";
  }elseif($tipo == "Estado de resultados"){
     $selZona = "SELECT DISTINCT  z.`id_zona`, z.`zona` FROM `zona` z INNER JOIN `edo_res` e ON z.`id_zona` = e.`id_zona` AND e.`id_mes` = $mes AND z.`status` = 1";
  }

  elseif($tipo == "Presupuesto"){
     $selZona = "SELECT DISTINCT  z.`id_zona`, z.`zona` FROM `zona` z INNER JOIN `presupuesto` p ON z.`id_zona` = p.`id_zona` AND p.`id_ejercicio` =$año AND z.`status` = 1";/*mostrar todos los presupuesto por zona y año  */  
  }
  $resul_selZona = $mysqli->query($selZona);
  $filas = $resul_selZona->num_rows;
/*si existe alguna zona con reportes del año de la opcion de presupuesto o balance o estado entonces, me mostrara un consolidado o una zona en especifico que exista*/
  if($filas > 0){
    echo '<option value="">Selecciona una opción</option>';
    echo '<option value="todos">Consolidado</option>';
    while($row = $resul_selZona->fetch_array()){
      echo '<option value="'.$row[id_zona].'">'. $row[zona] .'</option>';
    }     
   }


   elseif($tipo == "Liquidez"){
     $selZona = "SELECT DISTINCT  z.`id_zona`, z.`zona` FROM `zona` z INNER JOIN `liquidez` l ON z.`id_zona` = l.`id_zona` AND l.`id_ejercicio` =$año AND z.`status` = 1";/*mostrar todos los presupuesto por zona y año  */  
  }
  $resul_selZona = $mysqli->query($selZona);
  $filas = $resul_selZona->num_rows;
/*si existe alguna zona con reportes del año de la opcion de presupuesto o balance o estado entonces, me mostrara un consolidado o una zona en especifico que exista*/
  if($filas > 0){
    echo '<option value="">Selecciona una opción</option>';
    echo '<option value="todos">Consolidada General</option>';
        while($row = $resul_selZona->fetch_array()){
      echo '<option value="'.$row[id_zona].'">'. $row[zona] .'</option>';
    }     
   }else{
    echo '<option selected disabled value="">No existen zonas con reportes</option>';
  }

}
/* cuando z1 sea igual a todos me va a selecionar si quiero mostrar todos los balances estados de resultados y todo el rollo */
if (isset($_POST['z1']))
{
  require("../../conex/connect.php");
  $zona = $_POST["z1"];
  $tipo = $_POST["z2"];
  $mes = $_POST["z3"];
  $anio = $_POST["z4"];
  if ($zona == "todos") {
            if ($tipo == "Estado de resultados") {
            $obt_edoResul = "SELECT `concepto`, SUM(`presupuestom`) AS presupuestom, SUM(`realm`) AS realm, SUM(`variacionm`) AS variacionm, SUM(`presupuestoa`) AS presupuestoa, SUM(`reala`) AS reala, SUM(`variaciona`) AS variaciona, `edo_res_detalle`.`id_edo_res_detalle`, `edo_res_detalle`.`id_edo_res` FROM `edo_res_detalle`, `edo_res` WHERE `edo_res_detalle`.`id_edo_res` = `edo_res`.`id_edo_res` AND `edo_res`.`id_mes`=$mes GROUP BY `concepto` ORDER BY `id_edo_res_detalle`";  
            edo_resultados($obt_edoResul,$zona);

            }elseif($tipo == "Balance general"){

              $obt_balance = "SELECT `concepto_activo`, SUM(`activo`) as activo, `concepto_pasivo`,SUM(`pasivo`) AS pasivo FROM balance, `balance_detalle` WHERE balance.id_balance=balance_detalle.id_balance and balance.`id_mes`=$mes GROUP by `concepto_activo` order by `id_balance_detalle`";
              balance_general($obt_balance,$zona);
            }//Cierra elseif de que es una Balance general
            elseif( $tipo == "Presupuesto"){

              $obt_presupuesto1 = "SELECT concepto_presupuesto, SUM(anual) as anual, concepto_presupuesto, SUM(mensual) as mensual, id_ejercicio FROM presupuesto_detalle, presupuesto WHERE presupuesto_detalle.id_presupuesto=presupuesto.id_presupuesto and presupuesto.id_ejercicio=$anio GROUP BY concepto_presupuesto ORDER BY id_presupuesto_detalle";
              presupuesto($obt_presupuesto1,$zona);
            }//Cierra elseif  que suma todos los presupuestos
             elseif( $tipo == "Liquidez"){

              $obt_liquidezgeneral = "SELECT  sum(`t_acorriente`) as t_acorriente, sum(`t_pcorriente`) as t_pcorriente, sum(`e_operativosanual`) as e_operativosanual, sum(`e_operativosmensual`) as e_operativosmensual, sum(`p_aplicado`) as p_aplicado, sum(`f_asignadoanual`) as f_asignadoanual, sum(`f_asignadomensual`) as f_asignadomensual, sum(`caja_bancos`) as caja_bancos, sum(`inversiones`) as inversiones, sum(`p_corriente`) as p_corriente, sum(`f_asignadosbrutos`) as f_asignadosbrutos, sum(`ingreso_operativo`) as ingreso_operativo, sum(`subcidios_ingresos`) as subcidios_ingresos, sum(`egresos_operativo`) as egresos_operativo, sum(`cole_mcobrar`) as cole_mcobrar, sum(`becas_motorgadas`) as becas_motorgadas, sum(`saldo_cclientes`) as saldo_cclientes, sum(`cobros_erealizados`) as cobros_erealizados, sum(`cole_preanual`) as cole_preanual, sum(`becas_preanual`) as becas_preanual, sum(`cole_cobrarperiodo`) as cole_cobrarperiodo, sum(`becas_operiodo`) as becas_operiodo FROM `liquidez_detalles` INNER JOIN liquidez ON liquidez.id_liquidez=liquidez_detalles.id_liquidez WHERE id_mes =$mes";
              liquidezGeneral($obt_liquidezgeneral,$mes,$anio);
            }//Cierra elseif  que suma todos los presupuestos
  }
/*si no los va a mostrar individuales, por escuela por estado de resultados etc*/
  else{
            if ($tipo == "Estado de resultados") {
             $selEsc = "SELECT e.`id_escuela`,e.`nombre` FROM `escuela` e INNER JOIN `edo_res` er ON e.`id_escuela` = er.`id_escuela` AND e.`status` = 1 AND e.`id_zona` = $zona AND er.`id_mes` = $mes";
            }elseif($tipo == "Balance general"){
               $selEsc = "SELECT e.`id_escuela`,e.`nombre` FROM `escuela` e INNER JOIN `balance` b ON e.`id_escuela` = b.`id_escuela` AND e.`status` = 1 AND e.`id_zona` = $zona AND b.`id_mes` = $mes";
            }elseif($tipo == "Presupuesto"){
               $selEsc = "SELECT e.`id_escuela`,e.`nombre` FROM `escuela` e INNER JOIN `presupuesto` b ON e.`id_escuela` = b.`id_escuela` AND e.`status` = 1 AND e.`id_zona` = $zona AND b.`id_ejercicio` = $anio";
            }elseif($tipo == "Liquidez"){
               $selEsc = "SELECT e.`id_escuela`,e.`nombre` FROM `escuela` e INNER JOIN `liquidez` l ON e.`id_escuela` = l.`id_escuela` AND e.`status` = 1 AND e.`id_zona` = $zona AND l.`id_ejercicio` = $anio";
            }
        $resul_selEsc = $mysqli->query($selEsc);
        $filas = $resul_selEsc->num_rows;

        if($filas > 0){
          echo '<option value="">Selecciona una opción</option>';
          while($row = $resul_selEsc->fetch_array()){
            echo '<option value="'.$row[id_escuela].'">'. $row[nombre] .'</option>';
          }     
   }else{

    echo '<option selected disabled value="">No existe escuela con reporte</option>';
  }
}//Cierra el if de que si viene solo un id de la zona

}



/*te muestra tgodos los estados de resultado por escuela*/
if (isset($_POST['e1']))
{
  require("../../conex/connect.php");
  $tipo = $_POST["e1"];
  $escuela = $_POST["e2"];
  $mes = $_POST["e3"];
  $zona = $_POST["e4"];
   $anio = $_POST["e5"];
/*lista todos los estados de resultados habidos  y por haber ya sean generales o por escuela*/
  if ($tipo == "Estado de resultados") {
            if ($escuela == "todos") {
             $obt_edoResul = "SELECT `concepto`, SUM(`presupuestom`) AS presupuestom, SUM(`realm`) AS realm, SUM(`variacionm`) AS variacionm, SUM(`presupuestoa`) AS presupuestoa, SUM(`reala`) AS reala, SUM(`variaciona`) AS variaciona, `edo_res_detalle`.`id_edo_res_detalle`, `edo_res_detalle`.`id_edo_res` FROM `edo_res_detalle`, `edo_res` WHERE `edo_res_detalle`.`id_edo_res` = `edo_res`.`id_edo_res` AND id_zona=$zona AND `edo_res`.`id_mes`=$mes GROUP BY `concepto` ORDER BY `id_edo_res_detalle`";  
            }else{
             $obt_edoResul = "SELECT erd.* FROM `edo_res_detalle` erd INNER JOIN `edo_res`er ON erd.`id_edo_res` = er.`id_edo_res` WHERE er.`id_escuela`=$escuela AND er.`id_mes`=$mes";  
            }
                edo_resultados($obt_edoResul,$escuela);

    }
  elseif($tipo == "Balance general"){

            if ($escuela == "todos") {
              $obt_balance = "SELECT `concepto_activo`, SUM(`activo`) as activo, `concepto_pasivo`,SUM(`pasivo`) AS pasivo FROM balance, `balance_detalle` WHERE balance.id_balance=balance_detalle.id_balance and id_zona=$zona and balance.`id_mes`=$mes GROUP by `concepto_activo` order by `id_balance_detalle`";
              }else{
              $obt_balance = "SELECT bd.* FROM `balance_detalle` bd INNER JOIN `balance` b ON bd.`id_balance` = b.`id_balance` AND b.`id_escuela` = $escuela AND  b.`id_mes`=$mes";
              }
              balance_general($obt_balance,$escuela);
     }
 
/*para listar todos los presupuesto, pero antes de esto tuvo que haber pasado otro filtro */
  elseif($tipo == "Presupuesto"){

            if ($escuela == "todos") {
              $obt_presupuesto = "SELECT `concepto_presupuesto`, SUM(`anual`) as anual, `concepto_presupuesto`, SUM(`mensual`) as mensual, id_ejercicio  FROM presupuesto, presupuesto_detalle WHERE presupuesto.id_presupuesto=presupuesto_detalle.id_presupuesto and id_zona=$zona and presupuesto.id_ejercicio=$anio GROUP by `concepto_presupuesto` order by `id_presupuesto_detalle`";
              }else{
                  $obt_presupuesto = "SELECT bd.*, b.id_ejercicio FROM `presupuesto_detalle` bd INNER JOIN `presupuesto` b ON bd.`id_presupuesto` = b.`id_presupuesto` AND b.`id_escuela` =$escuela AND  b.`id_ejercicio`=$anio"; /*añadir que no sea mes que solo sea el del año para un presupuesto*/
              }
              echo presupuesto($obt_presupuesto,$escuela);
     }

     elseif($tipo == "Liquidez"){

            if ($escuela == "todos") {
               
              }else{
                 $obt_liquidez="SELECT liquidez.id_liquidez FROM liquidez INNER JOIN liquidez_detalles  ON liquidez_detalles.id_liquidez=liquidez.id_liquidez AND  liquidez.id_escuela=$escuela and liquidez.id_mes=$mes AND liquidez.id_ejercicio=$anio AND liquidez.id_zona=$zona";
              }
             echo  liquidez($obt_liquidez,$escuela,$anio,$zona,$mes);
     }

}



/* ############################################## R-E-P-O-R-T-E-S ##############################################*/
/*Funcion de General liquidez*/
 function liquidezGeneral($sentencia,$me,$an) {
 
   
    require("../../conex/connect.php");

    $consulta_liquidez = "SELECT id_liquidez FROM liquidez  WHERE  liquidez.id_ejercicio=2017  and liquidez.id_mes=$me ";
    $res_liquidez = $mysqli->query($consulta_liquidez);
    $row_liquidez= $res_liquidez->fetch_array();
    $id_liquidez=$row_liquidez[id_liquidez];
    $row_num = $row_liquidez->num_rows;
    if ($row_num = $res_liquidez->num_rows<1){
      
      echo "<script>if(confirm('¡No existe ninguna liquidez en el mes y ejercicio seleccionado!')){ 
        document.location='index.php';}
        else{ alert('Operacion Cancelada'); 
      }</script>";
                  //header("location: index.php"); 
    }else{


       
     
      $liquidezdetalle = $mysqli->query($sentencia); 
      $row_liquidez= $liquidezdetalle->fetch_array();
    }
    
    ?>
    <style type="text/css">
    th{
      text-align: center;
    }
    td{
      text-align: right;
    }

    #combo{
      background-color: rgba(0,3,0,0.0);
      border: none;
      text-align: right;
    }
    .defaultInput {
      background-color: rgba(0,0,0,0.0);
      border: none;
      text-align: right;
    }

 </style>
  
  <!-- Content Wrapper. Contains page content -->
  
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>
    <!-- Main content -->
    <section class="content"> 
        
              <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">
                  <table width="100%">
      <?php
      if ($_SESSION["tipo_usuario"] == 'Financiero General'){
              /*echo $_SESSION[id_usuarios].'usuarios';
              echo $_SESSION[id_ejercicio].'ejercicio';
              echo $_SESSION[id_superior].'idsuperior';
              echo $_SESSION[id_mes].'mes'; */


              ?>        
          
                <!-- /.box-header -->
                <div class="box-body">
                  <table width="100%">
                    <?php 
 
                    $slcFecha = "SELECT `f_inicio`,`f_final` FROM `mes` WHERE `id_mes` = $me";
                    $sql_slcFecha = $mysqli->query($slcFecha);
                    $row_slcFecha = $sql_slcFecha->fetch_array();
                    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
                    ?>
                    <thead >
                      <tr><th colspan="5">Consolidado General</th></tr>
                      <tr><th colspan="5"> </th></tr>
                      <tr><th colspan="5"> Informe Financiero Comparativo de Capital Operativo y Liquidez</th></tr>
                      <tr style="border-bottom: medium double black;"><th colspan="5"><?php echo "Al ". strftime('%d de %B de %Y', strtotime($row_slcFecha['f_final'])) ?></th></tr>
                    </thead> 
                    <?php

                    $consulta_liquidez = "SELECT id_liquidez FROM liquidez, usuarios WHERE    liquidez.id_ejercicio=$an  and liquidez.id_mes=$me ";
                    $res_liquidez = $mysqli->query($consulta_liquidez);
                    $num_Liquidez = $res_liquidez->num_rows;

                    if ($num_Liquidez==0) { ?>
                    <div style="position: absolute; top: 55px;right: 10px;">
                      <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <strong>Error!</strong> Hace falta Informacion.&nbsp; Ingresa los datos de liquidez.
                      </div>
                    </div>
                    <?php
                  }else{
                    ?>
                    <div style="position: absolute; top: auto; right: 2%;">
                      <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <strong>Liquidez Consolidada </strong> al mes de <?php echo strftime('%B del %Y', strtotime($row_slcFecha['f_final'])) ?>
                      </div>
                    </div>    
                    <?php
                  }


                 
                  $sql1 = "SELECT concepto_activo, SUM(activo) as tactivo   from balance_detalle INNER JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE '%Total de Activo Corriente%'  AND balance.id_ejercicio=$an and balance.id_mes=$me";
                  $resultadoestado1= $mysqli->query($sql1);
                  $arrayresult1 = $resultadoestado1->fetch_array();
                  $arrayresult1['concepto_activo'];
                  $arrayresult1['tactivo'];

                  $sql2 = "SELECT concepto_pasivo, SUM(pasivo) as tpasivo   from balance_detalle INNER JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_pasivo  LIKE '%Total de Pasivo Corriente%'AND balance.id_ejercicio=$an and balance.id_mes=$me";
                  $resultadoestado2= $mysqli->query($sql2);
                  $arrayresult2 = $resultadoestado2->fetch_array();
                  $arrayresult2['concepto_pasivo'];
                  $arrayresult2['tpasivo'];
                  $tot=  $arrayresult2['tpasivo']+  $arrayresult1['tactivo'];


                                                  //suma de fondocajachica, caja otros ingresos, bancos
                  $cajabancos = "SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Fondo de Caja Chica%'  AND balance.id_ejercicio=$an and balance.id_mes=$me union SELECT   activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Caja Otros Ingresos%'  AND balance.id_ejercicio=$an and balance.id_mes=$me UNION SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Bancos%'  AND balance.id_ejercicio=$an and balance.id_mes=$me";

                  $cajabancos1= $mysqli->query($cajabancos);
                  while ( $arrayresult3 = $cajabancos1->fetch_row()) {
                    $total+=$arrayresult3[0];
                  } 
  
                  $inversion = "SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Inversiones%'    AND balance.id_ejercicio=$an and balance.id_mes=$me";
                  $resultadoestado4= $mysqli->query($inversion);
                  $total1 = 0; 
                  while ($arrayresult4 = $resultadoestado4->fetch_row()) {
                    $total1 = $total1 +  $arrayresult4[0];
                  }

                  $pasivo_cor = "SELECT pasivo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_pasivo  LIKE'%Total de Pasivo Corriente%'  AND balance.id_ejercicio=$an and balance.id_mes=$me";
                  $resultadoestado5= $mysqli->query($pasivo_cor);
                  $pasivo_cor = 0.0; 
                  while ($arrayresult5 = $resultadoestado5->fetch_row()) {
                    $pasivo_cor = $pasivo_cor +  $arrayresult5[0];
                  }
                
                                                                  // Total Ingresos Educativos Netos
                  $totingresoseducativosnetos= "SELECT  reala  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Total Ingresos Educativos Netos%'  AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $toingresedunetos= $mysqli->query($totingresoseducativosnetos);
                  $tingedunetos = $toingresedunetos->fetch_array();

                                                                // Subsidios y Otros Ingresos
                  $subsioneto= "SELECT  reala  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Total de Otros Ingresos%'   AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $subsi= $mysqli->query($subsioneto);
                  $totsubneto = $subsi->fetch_array();
                  
                                                              // Egresos Operativos
                  $egropera= "SELECT  reala  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%GRAN TOTAL DE GASTOS%'  AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $egreopera= $mysqli->query($egropera);
                  $egreopera = $egreopera->fetch_array();
                  

                                                              //Colegiaturas Mensuales por Cobrar
                  $colemen= "SELECT  realm  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Enseñanza%'  AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $querycolemen= $mysqli->query($colemen);
                  $arraycolemen = $querycolemen->fetch_array();

                                                                //Becas Mensuales Otorgadas
                  $becamensualotorga= "SELECT realm from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Becas y Descuentos%'  AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an UNION  SELECT  realm  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Ingresos por Colegiaturas no Recibidos%'  AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $becamensualotorga1= $mysqli->query($becamensualotorga);
                  while ( $arraybmotorgadas = $becamensualotorga1->fetch_row()) {
                    $acumbecamensualotorga+=$arraybmotorgadas[0];
                  } 

                  

                                                          //Saldo Inicial en Cuenta de Clientes
                  $salinicuenclient= "SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Cuentas por Cobrar Activas%' AND balance.id_ejercicio=$an and balance.id_mes=$me";
                  $querysalinicuenclient= $mysqli->query($salinicuenclient);
                  $arraycsinicialcuenclient = $querysalinicuenclient->fetch_array();



                                                          //Saldo Final en cuenta de Clientes solo falta igualarle a la variable para no tener que registrar abajo
                  $salfinacuenclient= "SELECT  realm  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Enseñanza%'   AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $quersalfinacuenclient= $mysqli->query($salfinacuenclient);
                  $arrayquersalfinacuenclient = $quersalfinacuenclient->fetch_array();






                                                          //Colegiaturas Presupuestadas Anualmente
                  $colepresuanual= "SELECT anual from presupuesto_detalle INNER JOIN presupuesto ON presupuesto_detalle.id_presupuesto=presupuesto.id_presupuesto  WHERE concepto_presupuesto  LIKE'%ENSEÑANZA%' AND presupuesto.id_ejercicio=$an and presupuesto.id_mes=$me";
                  $quercolepresuanual= $mysqli->query($colepresuanual);
                  $arrayquercolepresuanual= $quercolepresuanual->fetch_array();


                                                            //Becas Presupuestadas Anualmente
                  $bepresuanual= "SELECT anual from presupuesto_detalle INNER JOIN presupuesto ON presupuesto_detalle.id_presupuesto=presupuesto.id_presupuesto  WHERE concepto_presupuesto  LIKE'%MENOS: BECAS Y DESCUENTOS%'  AND presupuesto.id_ejercicio=$an and presupuesto.id_mes=$me UNION SELECT anual from presupuesto_detalle INNER JOIN presupuesto ON presupuesto_detalle.id_presupuesto=presupuesto.id_presupuesto  WHERE concepto_presupuesto  LIKE'%MENOS: INGRESOS COLEG NO RECIB%'   AND presupuesto.id_ejercicio=$an and presupuesto.id_mes=$me";
                  $qubepreanual= $mysqli->query($bepresuanual);
                  while ( $arrayqubepreanual = $qubepreanual->fetch_row()) {
                    $becaspresupuestadasanualmente+=$arrayqubepreanual[0];
                  } 
  
                                                            //Colegiaturas Cobradas en el Período falta
                  $colecobraperio= "SELECT  reala  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Enseñanza%'    AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $quercolecobraperio= $mysqli->query($colecobraperio);
                  $arrayquercolecobraperio= $quercolecobraperio->fetch_array();

                  
                                                              //Becas otorgadas en el Período
                  $beotorperio= "SELECT reala from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Becas y Descuentos%'   AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an UNION  SELECT  realm  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Ingresos por Colegiaturas no Recibidos%'    AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $querbeotorperio= $mysqli->query($beotorperio);
                  while ( $arrayqubepreanual = $querbeotorperio->fetch_row()) {
                    $arrayquerbeotorperio+=$arrayqubepreanual[0];
                  }


                  ?>


                  
                </table>
                
                <form method="post" class="form-horizontal" name="registro" role="form">
                  <legend></legend>
                  <table>
                    <thead >
                      <tr>
                        <td style="text-align: center; "><h4 style="font-weight: bold;">Conceptos Financieros</h4></td>
                        <td style="text-align: center; "><h4 style="font-weight: bold;">Año Actual</h4></td>
                        <td style="text-align: center; "><h4 style="font-weight: bold;">Año Anterior</h4></td>
                        <td style="text-align: center; "><h4 style="font-weight: bold;">%</h4></td>
                        <td></td>
                      </tr>     

                      <script type="text/javascript">
                        
                        function sumar(){
                          /*CONSULTA CORRECTA activo corriente*/
                          tacor=document.registro.t_acorriente.value;
                          var  obttacor="<?php echo   $arrayresult1['tactivo']?>";
                          tact= ( (parseFloat(obttacor)-parseFloat(tacor)) /parseFloat(obttacor) )*100; 
                          document.registro.txttacorriente.value=new Intl.NumberFormat('es-MX').format(tact.toFixed(2));

                          /*CONSULTA CORRECTA pasivo corriente*/
                          pcor=document.registro.t_pcorriente.value;
                          var  obttpcor="<?php echo   $arrayresult2['tpasivo']?>";
                          tpas=(parseFloat(obttpcor)-parseFloat(pcor))/parseFloat(obttpcor)*100;  
                          document.registro.txttpcorriente.value=new Intl.NumberFormat('es-MX').format(tpas.toFixed(2));

                          /* Total capital Operativo anio anterior*/
                          totalco=parseFloat(tacor)+parseFloat(pcor);
                          document.registro.totalcoperativo.value=new Intl.NumberFormat('es-MX').format(totalco.toFixed(2));
                          var  totporcentaje="<?php echo $tot; ?>";
                          portotalco=(parseFloat(totporcentaje)-parseFloat(totalco))/parseFloat(totporcentaje)*100;  
                          document.registro.porctotcapop.value=new Intl.NumberFormat('es-MX').format(portotalco.toFixed(2));
                          

                          /*Egresos operativos anio anter y actual*/
                          eoa=document.registro.e_operativosanual.value;                   
                          eom=document.registro.e_operativosmensual.value;
                          tteoa=(parseFloat(eoa)-parseInt(eom))/parseFloat(eoa)*100; 
                          document.registro.hola.value=new Intl.NumberFormat('es-MX').format(tteoa.toFixed(2));

                          /*Porcentaje aplicado */
                          pa=document.registro.p_aplicado.value;
                          var  paplicadoanual=(parseFloat(eoa)*0.15);
                          paplicadoo=(parseFloat(paplicadoanual)-parseFloat(pa))/parseFloat(paplicadoanual)*100; 
                          document.registro.txtaplicado.value= new Intl.NumberFormat('es-MX').format(paplicadoo.toFixed(2));
                          document.registro.totalaplicadoanual.value= new Intl.NumberFormat('es-MX').format(paplicadoanual.toFixed(2));

                          /* Fondo asigando anual y mensual*/
                          faa=document.registro.f_asignadoanual.value;
                          fam=document.registro.f_asignadomensual.value;
                          ttfa=(parseFloat(faa)-parseInt(fam))/parseFloat(faa)*100; 
                          document.registro.totalfasignadomensualanual.value=new Intl.NumberFormat('es-MX').format(ttfa.toFixed(2));


                          /* Total capital operativo reco anual y mensual*/
                          var totcoprecomendadot=(parseFloat(paplicadoanual)+parseFloat(faa));  
                          document.registro.totalcoprecomendadoanualact.value= new Intl.NumberFormat('es-MX').format(totcoprecomendadot.toFixed(2));
                          var totcoprecomendadoant=(parseFloat(pa)+parseFloat(fam));  
                          document.registro.totalcoprecomendadoant.value= new Intl.NumberFormat('es-MX').format(totcoprecomendadoant.toFixed(2));
                          var porcoper=(parseFloat(totcoprecomendadot)-parseFloat(totcoprecomendadoant))/parseFloat(totcoprecomendadot)*100; 
                          document.registro.porcecoprecomendadoanualact.value=new Intl.NumberFormat('es-MX').format(porcoper.toFixed(2));

                          /* Total SUPERAVIT O (DEFICIT) CAPITAL OPERATIVO*/
                          var totcapitalanoactual="<?php echo $tot; ?>";
                          var  deficitoperativoact=(parseFloat(totcapitalanoactual)-parseFloat(totcoprecomendadot));  
                          document.registro.superavitdeficitoperativoact.value=new Intl.NumberFormat('es-MX').format(deficitoperativoact.toFixed(2));
                          var deficitoperativoant=(parseFloat(totalco)-parseFloat(totcoprecomendadoant));  
                          document.registro.superavitdeficitoperativoant.value= new Intl.NumberFormat('es-MX').format(deficitoperativoant.toFixed(2));
                          var porcoper=(parseFloat(deficitoperativoact)-parseFloat(deficitoperativoant))/parseFloat(deficitoperativoact)*100; 
                          document.registro.porcesuperavitdeficitoperativoant.value= new Intl.NumberFormat('es-MX').format(porcoper.toFixed(2));

                          /* % CAP. OPERATIVO EN RELACIÓN AL RECOMENDADO*/
                          var totcapitalanoactual="<?php echo $tot; ?>";
                          var  deficitoperativoact=(parseFloat(totcapitalanoactual)/parseFloat(totcoprecomendadot));  
                          document.registro.capoperativorecomendadoact.value= new Intl.NumberFormat('es-MX').format( deficitoperativoact.toFixed(2));
                          var deficitoperativoant=(parseFloat(totalco)/parseFloat(totcoprecomendadoant));  
                          document.registro.capoperativorecomendadoant.value= new Intl.NumberFormat('es-MX').format(deficitoperativoant.toFixed(2));
                          var porcoperrela=(parseFloat(deficitoperativoact)-parseFloat(deficitoperativoant))/parseFloat(deficitoperativoact)*100;  
                          document.registro.porcecapoperativorecomendadoant.value= new Intl.NumberFormat('es-MX').format(porcoperrela.toFixed(2));
                          

                          /* cajabancos*/                    
                          cb=document.registro.caja_bancos.value;
                          var  cajabancoscaja="<?php echo $total; ?>";
                          tpas=(parseFloat(obttpcor)-parseFloat(pcor))/parseFloat(obttpcor)*100; 

                          /* Inversiones*/ 
                          i=document.registro.inversiones.value;

                          /* Total de ACTIVO LIQUIDO*/  
                          var activoliquido=(parseFloat(cb)+parseFloat(i));  
                          document.registro.txttactivoliquidoant.value= new Intl.NumberFormat('es-MX').format(activoliquido.toFixed(2));
                          var  totactliqui="<?php echo $total2=$total+$Total;  ?>";
                          var poractivoliquido=(parseFloat(totactliqui)-parseFloat(activoliquido))/parseFloat(totactliqui)*100; 
                          document.registro.txttactivoliquido.value= new Intl.NumberFormat('es-MX').format(poractivoliquido.toFixed(2));


                          /* Pasivo Corriente*/  
                          pc=document.registro.p_corriente.value;
                          var txtlcorriente=(parseFloat(obttpcor)-parseFloat(pc))/parseFloat(obttpcor); 
                          document.registro.txtliquidezpcorriente.value= new Intl.NumberFormat('es-MX').format(txtlcorriente.toFixed(2));


                          /* Fondos Asignados Brutos*/  
                          fab=document.registro.f_asignadosbrutos.value;
                          faab=(parseFloat(faa));
                          document.registro.totalfasignadobruto.value= new Intl.NumberFormat('es-MX').format(faab.toFixed(2));
                          var totfas=(parseFloat(faab)-parseFloat(fab))/parseFloat(faab)*100; 
                          document.registro.txtfasignadobrutoant.value= new Intl.NumberFormat('es-MX').format(totfas.toFixed(2));

                          /* Total de PASIVO  Y REMANENTE DEL EJERC.*/  
                          var totalpasivor=(parseFloat(obttpcor)+parseFloat(faab));  
                          document.registro.totalpasivoremanenteant.value= new Intl.NumberFormat('es-MX').format(totalpasivor.toFixed(2));
                          var totalpasivoact=(parseFloat(pc)+parseFloat(fab));  
                          document.registro.totalpasivoremanenteact.value= new Intl.NumberFormat('es-MX').format(totalpasivoact.toFixed(2));
                          var pasivoremanenteporc=(parseFloat(totalpasivoact)-parseFloat(totalpasivor))/parseFloat(totalpasivoact)*100; 
                          document.registro.totalpasivoremanenteporc.value= new Intl.NumberFormat('es-MX').format(pasivoremanenteporc.toFixed(2));
                          
                          /* Total activo liquido neto  exit*/  
                          var  totactliqnean="<?php  echo $total2=$total+$Total;  ?>";                
                          var totactliqneant=(parseFloat(totactliqnean)-parseFloat(totalpasivor));  
                          document.registro.totactliquinetoant.value= new Intl.NumberFormat('es-MX').format(totactliqneant.toFixed(2));
                          var totactliqneact=(parseFloat(totalpasivoact)-parseFloat(activoliquido));  
                          document.registro.totactliquinetoact.value= new Intl.NumberFormat('es-MX').format(totactliqneact.toFixed(2));
                          var totactliqneporc=(parseFloat(totactliqneant)-parseFloat(totactliqneact))/parseFloat(totactliqneant)*100; 
                          document.registro.totactliquinetoporc.value=new Intl.NumberFormat('es-MX').format( totactliqneporc.toFixed(2));               
                          
                          /* % liquidez*/                     
                          var liquidezactual=(parseFloat(totactliqnean)/parseFloat(totalpasivor));  
                          document.registro.liquidezact.value= new Intl.NumberFormat('es-MX').format(liquidezactual.toFixed(2));
                          /**/var liquidezanterior=(parseFloat(totalpasivoact)/parseFloat(activoliquido));  
                          document.registro.liquidezant.value=new Intl.NumberFormat('es-MX').format( liquidezanterior.toFixed(2));
                          var liquidezporcentaje=(parseFloat(liquidezactual)-parseFloat(liquidezanterior))/parseFloat(liquidezactual)*100; 
                          document.registro.liquidezporc.value= new Intl.NumberFormat('es-MX').format(liquidezporcentaje.toFixed(2));

                          /* Rentabilidad y Sosten Propio*/ 
                          /*Ingreso Operativo*/
                          var  totoperativoporc="<?php  echo $tingedunetos['reala'];?>";
                          ingopera=document.registro.ingreso_operativo.value;
                          var operativoporc=(parseFloat(totoperativoporc)-parseFloat(ingopera))/parseFloat(totoperativoporc)*100; 
                          document.registro.ingreso_operativoporc.value= new Intl.NumberFormat('es-MX').format(operativoporc.toFixed(2));
                          

                          /* subcidios_ingresos*/  
                          si=document.registro.subcidios_ingresos.value;
                          var  totsubingres="<?php echo $totsubneto['reala'];?>";
                          var subingres=(parseFloat(totsubingres)-parseFloat(si))/parseFloat(totsubingres)*100; 
                          
                          
                          /*Total de ingresos final*/
                          var totingact=(parseFloat(totoperativoporc) + parseFloat(totsubingres));  
                          document.registro.totaldeingreact.value= new Intl.NumberFormat('es-MX').format(totingact.toFixed(2));

                          var totingant=(parseFloat(ingopera)+parseFloat(si)); 
                          document.registro.totaldeingreant.value=new Intl.NumberFormat('es-MX').format( totingant.toFixed(2));

                          var deingreporc=(parseFloat(totingact)-parseFloat(totingant))/parseFloat(totingact)*100; 
                          document.registro.totaldeingreporc.value= new Intl.NumberFormat('es-MX').format(deingreporc.toFixed(2));
                          
                          /*% sosten propio*/
                          var porsosproact=(parseFloat(totoperativoporc)/parseFloat(totingact))*100;
                          document.registro.sostenpropioact.value=new Intl.NumberFormat('es-MX').format( porsosproact.toFixed(2));
                          var porsosproant=(parseFloat(ingopera)/parseFloat(totingant))*100;
                          document.registro.sostenpropioant.value= new Intl.NumberFormat('es-MX').format(porsosproant.toFixed(2));
                          var totsostenpropioporc=(parseFloat(porsosproact)-parseFloat(porsosproant))/parseFloat(porsosproact)*100; 
                          document.registro.sostenpropioporc.value= new Intl.NumberFormat('es-MX').format(totsostenpropioporc.toFixed(2));
                          

                          /*Egresos Operativos.*/
                          var  egresos_operativoactual="<?php  echo $egreopera['reala'];  ?>";
                          eo=document.registro.egresos_operativo.value;
                          var egresosoperativoporc=(parseFloat(egresos_operativoactual)-parseFloat(eo))/parseFloat(egresos_operativoactual)*100; 
                          document.registro.egresos_operativoporc.value=new Intl.NumberFormat('es-MX').format(egresosoperativoporc.toFixed(2));

                          /*Total de EGRESOS final*/
                          var totaldeegreactu=(parseFloat(egresos_operativoactual));  
                          document.registro.totaldeegreact.value=new Intl.NumberFormat('es-MX').format( totaldeegreactu.toFixed(2));
                          var totaldeegreante=(parseFloat(eo)); 
                          document.registro.totaldeegreant.value= new Intl.NumberFormat('es-MX').format(totaldeegreante.toFixed(2));
                          var  egreporc=(parseFloat(egresos_operativoactual)-parseFloat(eo))/parseFloat(egresos_operativoactual)*100; 
                          document.registro.totaldeegreporc.value= new Intl.NumberFormat('es-MX').format(egreporc.toFixed(2));

                          /*UTILIDAD O (PÉRDIDA) OPERATIVA final*/
                          var totalutiperoperaact=(parseFloat(totingact)-parseFloat(totaldeegreactu)); 
                          document.registro.utiperoperaact.value= new Intl.NumberFormat('es-MX').format(totalutiperoperaact.toFixed(2));
                          var totalutiperoperaant=(parseFloat(totingant)-parseFloat(totaldeegreante)); 
                          document.registro.utiperoperaant.value=new Intl.NumberFormat('es-MX').format(totalutiperoperaant.toFixed(2));
                          totalutiperoperaporc=(parseFloat(totalutiperoperaact)-parseFloat(totalutiperoperaant))/parseFloat(totalutiperoperaact)*100; 
                          document.registro.utiperoperaporc.value= new Intl.NumberFormat('es-MX').format(totalutiperoperaporc.toFixed(2));
                          
                          

                          /*% RENTABILIDAD EN RELACIÓN A UTILIDAD OPERATIVA final*/                
                          var totalrentreutiopeact=(parseFloat(totalutiperoperaact)/parseFloat(totingact)); 
                          document.registro.rentreutiopeact.value= new Intl.NumberFormat('es-MX').format(totalrentreutiopeact.toFixed(2));

                          var totalrentreutiopeant=(parseFloat(totalutiperoperaant)/parseFloat(totingant)); 
                          document.registro.rentreutiopeant.value= new Intl.NumberFormat('es-MX').format(totalrentreutiopeant.toFixed(2));

                          
                          /* ÍNDICE DE COBRANZA */ 
                          /*Colegiaturas Mensuales por Cobrar*/
                          var  cole_mcobraractual="<?php  echo $arraycolemen['realm'];  ?>";
                          cmc=document.registro.cole_mcobrar.value;
                          var cole_mcobrarporce=(parseFloat(cole_mcobraractual)-parseFloat(cmc))/parseFloat(cole_mcobraractual)*100; 
                          document.registro.cole_mcobrarporc.value=new Intl.NumberFormat('es-MX').format(cole_mcobrarporce.toFixed(2));

                          /* Becas Mensuales Otorgadas*/                   
                          var  becas_motorgadasactual="<?php  echo $acumbecamensualotorga;  ?>";
                          bmo=document.registro.becas_motorgadas.value;
                          var becas_motorgadasporce=(parseFloat(becas_motorgadasactual)-parseFloat(bmo))/parseFloat(becas_motorgadasactual)*100; 
                          document.registro.becas_motorgadasporc.value=new Intl.NumberFormat('es-MX').format(becas_motorgadasporce.toFixed(2));

                          /*Neto a cobrar Mensual*/
                          var totalnetoacobrarmensact=(parseFloat(cole_mcobraractual)-parseFloat(becas_motorgadasactual)); 
                          document.registro.netoacobrarmensact.value= new Intl.NumberFormat('es-MX').format(totalnetoacobrarmensact.toFixed(2));
                          var totalnetoacobrarmensant=(parseFloat(cmc)-parseFloat(bmo)); 
                          document.registro.netoacobrarmensant.value= new Intl.NumberFormat('es-MX').format(totalnetoacobrarmensant.toFixed(2));
                          totalnetoacobrarmensporc=(parseFloat(totalnetoacobrarmensact)-parseFloat(totalnetoacobrarmensant))/parseFloat(totalnetoacobrarmensact)*100; 
                          document.registro.netoacobrarmensporc.value= new Intl.NumberFormat('es-MX').format(totalnetoacobrarmensporc.toFixed(2));
                          
                          /*Saldo Inicial en Cuenta de Clientes*/
                          var  totalsaldo_cclientesactual="<?php  echo $arraycsinicialcuenclient['activo'];  ?>";
                          scc=document.registro.saldo_cclientes.value;
                          var totalsaldo_cclientesporc=(parseFloat(totalsaldo_cclientesactual)-parseFloat(scc))/parseFloat(totalsaldo_cclientesactual)*100; 
                          document.registro.saldo_cclientesporc.value=new Intl.NumberFormat('es-MX').format(totalsaldo_cclientesporc.toFixed(2));

                          /*masNeto a cobrar Mensual*/
                          var totalmasnetoacobrarmensact=(parseFloat(totalnetoacobrarmensact));  
                          document.registro.masnetoacobrarmensact.value=new Intl.NumberFormat('es-MX').format( totalmasnetoacobrarmensact.toFixed(2));
                          var totalmasnetoacobrarmensant=(parseFloat(totalnetoacobrarmensant)); 
                          document.registro.masnetoacobrarmensant.value= new Intl.NumberFormat('es-MX').format(totalmasnetoacobrarmensant.toFixed(2));
                          var  totmasnetoacobrarmensporc=(parseFloat(totalmasnetoacobrarmensact)-parseFloat(totalmasnetoacobrarmensant))/parseFloat(totalmasnetoacobrarmensact)*100; 
                          document.registro.masnetoacobrarmensporc.value= new Intl.NumberFormat('es-MX').format(totmasnetoacobrarmensporc.toFixed(2));


                          /*Total Cartera a Recuperar en el Mes*/
                          var totaltotalcarremesact=(parseFloat(totalsaldo_cclientesactual)+parseFloat(totalmasnetoacobrarmensact)); 
                          document.registro.totalcarremesact.value= new Intl.NumberFormat('es-MX').format(totaltotalcarremesact.toFixed(2));

                          var totaltotalcarremesant=(parseFloat(scc)+parseFloat(totalmasnetoacobrarmensant)); 
                          document.registro.totalcarremesant.value= new Intl.NumberFormat('es-MX').format(totaltotalcarremesant.toFixed(2));
                          totaltotalcarremesporc=(parseFloat(totaltotalcarremesact)-parseFloat(totalmasnetoacobrarmensant))/parseFloat(totaltotalcarremesact)*100; 
                          document.registro.totalcarremesporc.value= new Intl.NumberFormat('es-MX').format(totaltotalcarremesporc.toFixed(2)); 
                          

                          /*Cobros Efectivamente Realizados */
                          cer=document.registro.cobros_erealizados.value; 

                          var  resultsaldocuentaclientes="<?php  echo $arrayquersalfinacuenclient['realm'];  ?>";
                          var totalcobros_erealizadosact=(parseFloat(totaltotalcarremesact) - parseFloat(resultsaldocuentaclientes)); 
                          document.registro.cobros_erealizadosact.value= new Intl.NumberFormat('es-MX').format(totalcobros_erealizadosact.toFixed(2)); 
                          

                          /*Saldo Final en cuenta de Clientes final*/
                          var  totsaldofinalcuentaclienteact="<?php  echo $arrayquersalfinacuenclient['realm'];  ?>";
                          var totalsaldocuentaclienteant=(parseFloat(totaltotalcarremesant)-parseFloat(cer)); 
                          document.registro.saldocuentaclienteant.value= new Intl.NumberFormat('es-MX').format(totalsaldocuentaclienteant.toFixed(2));
                          totalsaldocuentaclienteporc =(parseFloat(totsaldofinalcuentaclienteact)-parseFloat(totalsaldocuentaclienteant))/parseFloat(totsaldofinalcuentaclienteact)*100; 
                          document.registro.saldocuentaclienteporc.value= new Intl.NumberFormat('es-MX').format(totalsaldocuentaclienteporc.toFixed(2));
                          
                          

                          /*% DE EFICIENCIA EN LA COBRANZA final*/
                          var totaleficienciaact=(parseFloat(totaltotalcarremesact)/parseFloat(totalcobros_erealizadosact))*100; 
                          document.registro.eficienciaact.value= new Intl.NumberFormat('es-MX').format(totaleficienciaact.toFixed(2));
                          var totaleficienciaant=(parseFloat(totaltotalcarremesant)/parseFloat(cer))*100; 
                          document.registro.eficienciaant.value=new Intl.NumberFormat('es-MX').format(totaleficienciaant.toFixed(2));


                          /* RELACIÓN DE BECAS CON INGRESOS */ 
                          /*% Colegiaturas Presupuestadas Anualmente*/
                          var  totalcole_preanualactual="<?php  echo $arrayquercolepresuanual['anual'];  ?>";
                          cpa=document.registro.cole_preanual.value;
                          var totalcole_preanualporc=(parseFloat(totalcole_preanualactual)-parseFloat(cpa))/parseFloat(totalcole_preanualactual)*100; 
                          document.registro.cole_preanualporc.value=new Intl.NumberFormat('es-MX').format(totalcole_preanualporc.toFixed(2));


                          /*Becas Presupuestadas Anualmente*/              
                          var  totalbecas_preanualactual="<?php  echo $becaspresupuestadasanualmente; ?>";
                          bpa=document.registro.becas_preanual.value;
                          var totalbecas_preanualporc=(parseFloat(totalbecas_preanualactual)-parseFloat(bpa))/parseFloat(totalbecas_preanualactual)*100; 
                          document.registro.becas_preanualporc.value=new Intl.NumberFormat('es-MX').format(totalbecas_preanualporc.toFixed(2));

                          /*% de Becas en Base a Presupuesto*/
                          var totalbecasenbpresuact=(parseFloat(totalcole_preanualactual)/parseFloat(totalbecas_preanualactual)); 
                          document.registro.becasenbpresuact.value=new Intl.NumberFormat('es-MX').format( totalbecasenbpresuact.toFixed(2));
                          var totalbecasenbpresuant=(parseFloat(cpa)/parseFloat(bpa)); 
                          document.registro.becasenbpresuant.value=new Intl.NumberFormat('es-MX').format( totalbecasenbpresuant.toFixed(2));
                          totalbecasenbpresuporc=(parseFloat(totalbecasenbpresuact)-parseFloat(totalbecasenbpresuant))/parseFloat(totalbecasenbpresuact)*100; 
                          document.registro.becasenbpresuporc.value=new Intl.NumberFormat('es-MX').format(totalbecasenbpresuporc.toFixed(2)); 

                          /*Colegiaturas Cobradas en el Período*/
                          var  totalcole_cobrarperiodoactual="<?php  echo $arrayquercolecobraperio['reala'];  ?>";
                          ccp=document.registro.cole_cobrarperiodo.value;
                          var totalcole_cobrarperiodoporc=(parseFloat(totalcole_cobrarperiodoactual)-parseFloat(ccp))/parseFloat(totalcole_cobrarperiodoactual)*100; 
                          document.registro.cole_cobrarperiodoporc.value=new Intl.NumberFormat('es-MX').format(totalcole_cobrarperiodoporc.toFixed(2));

                          /*Becas otorgadas en el Período*/
                          var  totalbecas_operiodoactual="<?php  echo $arrayquerbeotorperio;  ?>";
                          bop=document.registro.becas_operiodo.value;
                          var totalbecas_operiodoporc=(parseFloat(totalbecas_operiodoactual)-parseFloat(bop))/parseFloat(totalbecas_operiodoactual)*100; 
                          document.registro.becas_operiodoporc.value=new Intl.NumberFormat('es-MX').format(totalbecas_operiodoporc.toFixed(2));

                          /*% de Becas en Realción a los Cobros del Período*/
                          var totalberelacoperiodoact=(parseFloat(totalcole_cobrarperiodoactual)/parseFloat(totalbecas_operiodoactual))*100; 
                          document.registro.berelacoperiodoact.value=new Intl.NumberFormat('es-MX').format( totalberelacoperiodoact.toFixed(2));
                          var totalberelacoperiodoant=(parseFloat(ccp)-parseFloat(bop)); 
                          document.registro.berelacoperiodoant.value= new Intl.NumberFormat('es-MX').format(totalberelacoperiodoant.toFixed(2));
                          totalberelacoperiodoporc=(parseFloat(totalberelacoperiodoact)-parseFloat(totalberelacoperiodoant))/parseFloat(totalberelacoperiodoact)*100; 
                          document.registro.berelacoperiodoporc.value=new Intl.NumberFormat('es-MX').format(totalberelacoperiodoporc.toFixed(2));
                          
                          
                          
                        }
                      </script>       

                      <tr> <td><h3>Capital Operativo</h3></td> </tr>
                      
                      <tr> 

                        <td><?php echo' <div class="col-lg-10" style="text-align: center" role="form"><label for="ejemplo_email_3" class="">Total Activo Corriente</label>';?> </td>
                          <td class="col-lg-1"   ><?php echo  number_format($arrayresult1['tactivo'], 2, '.', ',' ); ?></td>
                          <td class="col-lg-2" >   <div class="form-line jf-required"><input   type="text" readonly id="combo" OnKeyup="sumar()" name="t_acorriente" type="text" placeholder="Total Activo Corriente" required value="<?php echo number_format($row_liquidez[t_acorriente], 2, '.', ',' ); ?>"</div></div></td>
                          <td class="col-lg-1" >  <input  name ="txttacorriente" id="combo" readonly="readonly"  ></> </td>
                        </tr>
                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="t_pcorriente">Total Pasivo Corriente</label>';?></td>
                            <td class="col-lg-1"  ><?php echo   number_format($arrayresult2['tpasivo'], 2, '.', ',' );?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input    readonly id="combo"  name="t_pcorriente" type="text" OnKeyup="sumar()" placeholder="Total Pasivo Corriente" required value="<?php echo number_format($row_liquidez[t_pcorriente], 2, '.', ',' ); ?>">
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="txttpcorriente" id="combo" readonly="readonly"  ></></td>
                        </tr>
                        
                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total de CAPITAL OPERATIVO ACTUAL</label>';?></td>
                            <td class="col-lg-1"  ><?php  echo number_format($tot, 2, '.', ',' ); ?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input  name ="totalcoperativo" id="combo" readonly="readonly"  ></>
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="porctotcapop" id="combo" readonly="readonly"  ></></td>
                        </tr>

                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  for="e_operativos">Egresos Operativos de 12 meses anteriores</label>';?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input    readonly id="combo"  name="e_operativosanual" type="text" OnKeyup="sumar()" placeholder="Egresos Operativos de 12 meses anteriores" required value="<?php echo number_format($row_liquidez[e_operativosanual], 2, '.', ',' ); ?>">
                              
                            </div>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input    readonly id="combo"  name="e_operativosmensual" type="text" OnKeyup="sumar()" placeholder="Egresos Operativos de 12 meses anteriores" required value="<?php echo number_format($row_liquidez[e_operativosmensual], 2, '.', ',' ); ?>">
                              
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="hola" id="combo" readonly="readonly"  ></></td>
                        </tr>


                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class=""  for="p_aplicado">15% Aplicado</label>';?></td>
                            <td class="col-lg-1" >  <input  name ="totalaplicadoanual" id="combo" readonly="readonly"  ></></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input   OnKeyup="sumar()"  readonly id="combo"  name="p_aplicado" type="text" placeholder="15% Aplicado" required value="<?php echo number_format($row_liquidez[p_aplicado], 2, '.', ',' ); ?>">
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="txtaplicado" id="combo" readonly="readonly"  ></></td>
                        </tr>

                        
                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  for="f_asignado">Fondos Asignados</label>';?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input   OnKeyup="sumar()"  readonly id="combo" name="f_asignadoanual" type="text" placeholder="Fondos Asignados" required value="<?php echo number_format($row_liquidez[f_asignadoanual], 2, '.', ',' ); ?>">
                              
                            </div>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input   OnKeyup="sumar()"  readonly id="combo"  name="f_asignadomensual" type="text" placeholder="Fondos Asignados" required value="<?php echo number_format($row_liquidez[f_asignadomensual], 2, '.', ',' ); ?>">
                              
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="totalfasignadomensualanual" id="combo" readonly="readonly"  ></></td>
                        </tr>

                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total de CAPITAL OPERATIVO RECOMENDADO</label>';?></td>
                            <td class="col-lg-1" > <input  name ="totalcoprecomendadoanualact" id="combo" readonly="readonly"  > </td>
                            <td class="col-lg-2">  <input  name ="totalcoprecomendadoant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                              
                            </div>
                          </div></td>
                          <td class="col-lg-1" > <input  name ="porcecoprecomendadoanualact" id="combo" readonly="readonly"  > </td>
                        </tr>

                        
                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >SUPERAVIT O (DEFICIT) CAPITAL OPERATIVO</label>';?></td>
                            <td class="col-lg-1" > <input  name ="superavitdeficitoperativoact" id="combo" readonly="readonly"  > </td>
                            <td class="col-lg-2"> <input  name ="superavitdeficitoperativoant" id="combo" readonly="readonly"  > <div class="form-line jf-required"> 
                              
                            </div>
                          </div></td>
                          <td class="col-lg-1" > <input  name ="porcesuperavitdeficitoperativoant" id="combo" readonly="readonly"  > </td>
                        </tr>


                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >% CAP. OPERATIVO EN RELACIÓN AL RECOMENDADO</label>';?></td>
                            <td class="col-lg-1" > <input  name ="capoperativorecomendadoact" id="combo" readonly="readonly"  > </td>
                            <td class="col-lg-2"> <input  name ="capoperativorecomendadoant" id="combo" readonly="readonly"  >  <div class="form-line jf-required"> 
                              
                            </div>
                          </div></td>

                          <td class="col-lg-1" > <input  name ="porcecapoperativorecomendadoant" id="combo" readonly="readonly"  > </td>
                        </tr>
                        
                        

                        <tr> <td>  <legend></legend></td> </tr>
                        <tr> <td><h3>Liquidez<small>  </small></h3></td> </tr>
                        

                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="caja_bancos">Caja, Bancos</label>';?></td>
                            <td class="col-lg-1"  ><?php  echo number_format($total, 2, '.', ',' );?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input   OnKeyup="sumar()"  readonly id="combo"  name="caja_bancos" type="text" placeholder="Caja, Bancos" required value="<?php echo number_format($row_liquidez[caja_bancos], 2, '.', ',' ); ?>">
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="txtcajabancos" id="combo" readonly="readonly"  ></></td>
                        </tr>


                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="inversiones">Inversiones</label>';?></td>
                            <td class="col-lg-1"  ><?php echo number_format($total1, 2, '.', ',' ); ?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input   OnKeyup="sumar()"  readonly id="combo"  name="inversiones" type="text" placeholder="Inversiones" required value="<?php echo number_format($row_liquidez[inversiones], 2, '.', ',' ); ?>">
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="txttinversiones" id="combo" readonly="readonly"  ></></td>
                        </tr>


                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="Total de ACTIVO LIQUIDO">Total de ACTIVO LIQUIDO</label>';?></td>
                            <td class="col-lg-1"  ><?php echo number_format($total2=$total+$Total, 2, '.', ',' );  ?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input  name ="txttactivoliquidoant" id="combo" readonly="readonly"  ></></td>
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="txttactivoliquido" id="combo" readonly="readonly"  ></></td>
                        </tr>

                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="PASIVO Y REMANENTE DEL EJERCICIO">PASIVO Y REMANENTE DEL EJERCICIO</label>';?></td>
                          </tr>

                          
                          
                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="p_corriente">Pasivo Corriente</label>';?></td>
                              <td class="col-lg-1"  ><?php echo   number_format($arrayresult2['tpasivo'], 2, '.', ',' ); ?></td>
                              <td class="col-lg-2"> <div class="form-line jf-required">
                                <input   OnKeyup="sumar()"  readonly id="combo" name="p_corriente" type="text" placeholder="Pasivo Corriente" required value="<?php echo number_format($row_liquidez[p_corriente], 2, '.', ',' ); ?>">
                              </div>
                            </div></td>
                            <td class="col-lg-1" >  <input  name ="txtliquidezpcorriente" id="combo" readonly="readonly"  ></></td>
                          </tr>

                          
                          <!-- meequede aqui-->
                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="f_asignadosbrutos">Fondos Asignados Brutos</label>';?></td>
                              <td class="col-lg-1" > <input  name ="totalfasignadobruto" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2"> <div class="form-line jf-required">
                                <input   OnKeyup="sumar()"  readonly id="combo"  name="f_asignadosbrutos" type="text" placeholder="Fondos Asignados Brutos" required value="<?php echo number_format($row_liquidez[f_asignadosbrutos], 2, '.', ',' ); ?>">
                              </div>
                            </div></td>
                            <td class="col-lg-1" >  <input  name ="txtfasignadobrutoant" id="combo" readonly="readonly"  > </td>
                          </tr>




                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total de PASIVO  Y REMANENTE DEL EJERC.</label>';?></td>
                              <td class="col-lg-1" > <input  name ="totalpasivoremanenteant" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="totalpasivoremanenteact" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="totalpasivoremanenteporc" id="combo" readonly="readonly"  > </td>
                          </tr>


                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total de ACTIVO LIQUIDO NETO</label>';?></td>
                              <td class="col-lg-1" > <input  name ="totactliquinetoant" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="totactliquinetoact" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="totactliquinetoporc" id="combo" readonly="readonly"  > </td>
                          </tr>


                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >% LIQUIDEZ</label>';?></td>
                              <td class="col-lg-1" > <input  name ="liquidezact" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="liquidezant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="liquidezporc" id="combo" readonly="readonly"  > </td>
                          </tr>

                          


                          <tr> <td>  <legend></legend></td> </tr>
                          
                          <tr> <td><h3>Rentabilidad y Sostén Propio</h3></td> </tr>

                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="ingreso_operativo">Ingreso Operativo</label>';?></td>

                              <td class="col-lg-1"  ><?php  echo number_format($tingedunetos['reala'], 2, '.', ',' ); ?></td>
                              <td class="col-lg-2"> <div class="form-line jf-required">
                                <input   OnKeyup="sumar()"  readonly id="combo"  name="ingreso_operativo" type="text" placeholder="Ingreso Operativo" required value="<?php echo number_format($row_liquidez[ingreso_operativo], 2, '.', ',' ); ?>">
                              </div>
                            </div></td>
                            <td class="col-lg-1" >  <input  name ="ingreso_operativoporc" id="combo" readonly="readonly"  ></></td>
                          </tr>



                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="subcidios_ingresos">Subsidios y Otros Ingresos</label>';?></td>
                              <td class="col-lg-1"  ><?php  echo  number_format($totsubneto['reala'], 2, '.', ',' );  ?></td>
                              <td class="col-lg-2"> <div class="form-line jf-required">
                                <input   OnKeyup="sumar()"  readonly id="combo"  name="subcidios_ingresos" type="text" placeholder="Subsidios y Otros Ingresos" required value="<?php echo number_format($row_liquidez[subcidios_ingresos], 2, '.', ',' ); ?>">
                              </div>
                            </div></td>
                            <td class="col-lg-1" >  <input  name ="subcidios_ingresosporc" id="combo" readonly="readonly"  ></></td>
                          </tr>


                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total de INGRESOS</label>';?></td>
                              <td class="col-lg-1" > <input  name ="totaldeingreact" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="totaldeingreant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="totaldeingreporc" id="combo" readonly="readonly"  > </td>
                          </tr>

                          <!a esta le modifique -!>
                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >% Sosten Propio</label>';?></td>
                              <td class="col-lg-1" > <input  name ="sostenpropioact" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="sostenpropioant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="sostenpropioporc" id="combo" readonly="readonly"  > </td>
                          </tr>
                          


                          <tr> 
                            <!a esta le modifique -!>
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="egresos_operativo">Egresos Operativos</label>';?></td>
                              <td class="col-lg-1"  ><?php echo number_format($egreopera['reala'], 2, '.', ',' ); ?></td>
                              <td class="col-lg-2"> <div class="form-line jf-required">
                                <input   OnKeyup="sumar()"  readonly id="combo"  name="egresos_operativo" type="text" placeholder="Egresos Operativos" required value="<?php echo number_format($row_liquidez[egresos_operativo], 2, '.', ',' ); ?>">
                              </div>
                            </div></td>
                            <td class="col-lg-1" >  <input  name ="egresos_operativoporc" id="combo" readonly="readonly"  ></></td>
                          </tr>


                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total de EGRESOS</label>';?></td>
                              <td class="col-lg-1" > <input  name ="totaldeegreact" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="totaldeegreant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="totaldeegreporc" id="combo" readonly="readonly"  > </td>
                          </tr>


                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >UTILIDAD O (PÉRDIDA) OPERATIVA</label>';?></td>
                              <td class="col-lg-1" > <input  name ="utiperoperaact" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="utiperoperaant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="utiperoperaporc" id="combo" readonly="readonly"  > </td>
                          </tr>
                          
                          
                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="egresosoperativos">% Rentabilidad en Relación a Utilidad Operativa</label>';?></td>
                              <td class="col-lg-1" >  <input  name ="rentreutiopeact" id="combo" readonly="readonly"  ></></td>
                              <td class="col-lg-2"> <div class="form-line jf-required"> <input  name ="rentreutiopeant" id="combo" readonly="readonly"  > 
                                
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="rentreutiopeporc" id="combo" readonly="readonly"  ></></td>
                            </tr>

                            <tr> <td>  <legend></legend></td> </tr>
                            <tr> <td><h3>Índice de Cobranza</h3></td> </tr>

                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="cole_mcobrar">Colegiaturas Mensuales por Cobrar</label>';?></td>
                                <td class="col-lg-1"  ><?php echo number_format($arraycolemen['realm'], 2, '.', ',' ); ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="cole_mcobrar" type="text" placeholder="Colegiaturas Mensuales por Cobrar" required value="<?php echo number_format($row_liquidez[cole_mcobrar], 2, '.', ',' ); ?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="cole_mcobrarporc" id="combo" readonly="readonly"  ></></td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" for="becas_motorgadas">Becas Mensuales Otorgadas</label>';?></td>
                                <td class="col-lg-1"  ><?php echo number_format($acumbecamensualotorga, 2, '.', ',' ); ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="becas_motorgadas" type="text" placeholder="Becas Mensuales Otorgadas" required value="<?php echo number_format($row_liquidez[becas_motorgadas], 2, '.', ',' ); ?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="becas_motorgadasporc" id="combo" readonly="readonly"  ></></td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Neto a cobrar Mensual</label>';?></td>
                                <td class="col-lg-1" > <input  name ="netoacobrarmensact" id="combo" readonly="readonly"  > </td>
                                <td class="col-lg-2">  <input  name ="netoacobrarmensant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="netoacobrarmensporc" id="combo" readonly="readonly"  > </td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  for="saldo_cclientes">Saldo Inicial en Cuenta de Clientes</label>';?></td>
                                <td class="col-lg-1"  ><?php  echo number_format( $arraycsinicialcuenclient['activo'], 2, '.', ',' ); ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="saldo_cclientes" type="text" placeholder="Saldo Inicial en Cuenta de Clientes" required value="<?php echo number_format($row_liquidez[saldo_cclientes], 2, '.', ',' ); ?>"
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="saldo_cclientesporc" id="combo" readonly="readonly"  ></></td>
                            </tr>

                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Mas: Neto a Cobrar Mensual</label>';?></td>
                                <td class="col-lg-1" > <input  name ="masnetoacobrarmensact" id="combo" readonly="readonly"  > </td>
                                <td class="col-lg-2">  <input  name ="masnetoacobrarmensant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="masnetoacobrarmensporc" id="combo" readonly="readonly"  > </td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total Cartera a Recuperar en el Mes</label>';?></td>
                                <td class="col-lg-1" > <input  name ="totalcarremesact" id="combo" readonly="readonly"  > </td>
                                <td class="col-lg-2">  <input  name ="totalcarremesant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="totalcarremesporc" id="combo" readonly="readonly"  > </td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  for="cobros_erealizados">Cobros Efectivamente Realizados</label>';?></td>
                                <td class="col-lg-1" >  <input  name ="cobros_erealizadosact" id="combo" readonly="readonly"  ></></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="cobros_erealizados" type="text" placeholder="Cobros Efectivamente Realizados" required value="<?php echo number_format($row_liquidez[cobros_erealizados], 2, '.', ',' ); ?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="cobros_erealizadosporc" id="combo" readonly="readonly"  ></></td>
                            </tr>



                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Saldo Final en cuenta de Clientes</label>';?></td>
                                <td class="col-lg-1"  ><?php   echo number_format($arrayquersalfinacuenclient['realm'], 2, '.', ',' ); ?></td>
                                <td class="col-lg-2">  <input  name ="saldocuentaclienteant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="saldocuentaclienteporc" id="combo" readonly="readonly"  > </td>
                            </tr>

                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >% DE EFICIENCIA EN LA COBRANZA</label>';?></td>
                                <td class="col-lg-1" > <input  name ="eficienciaact" id="combo" readonly="readonly"  > </td>
                                <td class="col-lg-2">  <input  name ="eficienciaant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="eficienciaporc" id="combo" readonly="readonly"  > </td>
                            </tr>


                            <tr> <td>  <legend></legend></td> </tr>
                            
                            <tr> <td><h3>Relación de Becas con Ingresos</h3></td> </tr>

                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" for="cole_preanual">Colegiaturas Presupuestadas Anualmente</label>';?></td>
                                <td class="col-lg-1"  ><?php   echo number_format($arrayquercolepresuanual['anual'], 2, '.', ',' );  ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="cole_preanual" type="text" placeholder="Colegiaturas Presupuestadas Anualmente" required value="<?php echo number_format($row_liquidez[cole_preanual], 2, '.', ',' ); ?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="cole_preanualporc" id="combo" readonly="readonly"  ></></td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" for="becas_preanual">Becas Presupuestadas Anualmente</label>';?></td>
                                <td class="col-lg-1"  ><?php echo number_format($becaspresupuestadasanualmente, 2, '.', ',' ); ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="becas_preanual" type="text" placeholder="Becas Presupuestadas Anualmente" required value="<?php echo number_format($row_liquidez[becas_preanual], 2, '.', ',' ); ?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="becas_preanualporc" id="combo" readonly="readonly"  ></></td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >% de Becas en Base a Presupuesto</label>';?></td>
                                <td class="col-lg-1" > <input  name ="becasenbpresuact" id="combo" readonly="readonly"  > </td>
                                <td class="col-lg-2">  <input  name ="becasenbpresuant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="becasenbpresuporc" id="combo" readonly="readonly"  > </td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" for="cole_cobrarperiodo">Colegiaturas Cobradas en el Período</label>';?></td>
                                <td class="col-lg-1"  ><?php  echo number_format($arrayquercolecobraperio['reala'], 2, '.', ',' ); ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="cole_cobrarperiodo" type="text" placeholder="Colegiaturas Cobradas en el Período" required value="<?php echo number_format($row_liquidez[cole_cobrarperiodo], 2, '.', ',' ); ?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="cole_cobrarperiodoporc" id="combo" readonly="readonly"  ></></td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  for="becas_operiodo">Becas otorgadas en el Período</label>';?></td>
                                <td class="col-lg-1"  ><?php  echo number_format($arrayquerbeotorperio, 2, '.', ',' ); ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"   readonly id="combo"  name="becas_operiodo" type="text" placeholder="Becas otorgadas en el Período" required value="<?php echo number_format($row_liquidez[becas_operiodo], 2, '.', ',' );?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="becas_operiodoporc" id="combo" readonly="readonly"  ></></td>
                            </tr>



                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >% de Becas en Relación a los Cobros del Período</label>';?></td>
                                <td class="col-lg-1" > <input  name ="berelacoperiodoact" id="combo" readonly="readonly"  > </td>
                                <td class="col-lg-2">  <input  name ="berelacoperiodoant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="berelacoperiodoporc" id="combo" readonly="readonly"  > </td>
                            </tr>


                          </table>
                        </fieldset>
                      </form>
                      
                    </div>
                  </div>
                </div>
                <script type="text/javascript">
                  window.sumar();
                </script>



                
            </section>
            <!-- /.content -->
          </div>
          <!-- /.content-wrapper -->
          
        </div>

          <?php }//Cierra el if de que exista algún registro en el estado de resultados ?>


        </div>
    <?
  


}


/*Funcion de liquidez individual*/
function liquidez($sentencia,$esc,$an,$zon,$me) {

include($atras.'template/todo.php');
    ////consulta de detalle de liquidez
    require("../../conex/connect.php");

  $res_liquidez = $mysqli->query($sentencia);
   $row_liquidez= $res_liquidez->fetch_array();
     $id_liquidez=$row_liquidez[id_liquidez];

  $num_mes =  $res_liquidez->num_rows;
         $mesinicial=  $num_mes ;

     if ($mesinicial<0){
      
      echo "<script>if(confirm('Hace falta Informacion, Ingresa los datos de liquidez')){ 
        document.location='index.php';}
        else{ alert('Operacion Cancelada'); 
      }</script>";
                  //header("location: index.php"); 
    }else{

      $consulta = "SELECT * FROM `liquidez_detalles` WHERE `id_liquidez`= $id_liquidez";
      $liquidezdetalle = $mysqli->query($consulta); 
      $row_liquidez= $liquidezdetalle->fetch_array();
    } 
  if ($mesinicial > 0) {



?>  

        
     <style type="text/css">
    th{
      text-align: center;
    }
    td{
      text-align: right;
    }

    #combo{
      background-color: rgba(0,3,0,0.0);
      border: none;
      text-align: right;
    }
    .defaultInput {
      background-color: rgba(0,0,0,0.0);
      border: none;
      text-align: right;
    }

  </style>
  
  <!-- Content Wrapper. Contains page content -->
  
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>
    <!-- Main content -->
    <section class="content"> 
        
              <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">
                  <table width="100%">
                    <?php                   

                    $slcInfo = "SELECT es.`nombre` escuela, f.`nombre` filantropica FROM `escuela` es INNER JOIN `filantropica` f ON es.`id_filantropica` = f.`id_filantropica` WHERE `id_escuela` = $esc";
                    $sql_slcInfo = $mysqli->query($slcInfo);
                    $row_slcInfo = $sql_slcInfo->fetch_array();
                    $slcFecha = "SELECT `f_inicio`,`f_final` FROM `mes` WHERE `id_mes` = $me";
                    $sql_slcFecha = $mysqli->query($slcFecha);
                    $row_slcFecha = $sql_slcFecha->fetch_array();
                    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
                    ?>
                    <thead >
                      <tr><th colspan="5"><?php echo $row_slcInfo['filantropica'] ?></th></tr>
                      <tr><th colspan="5"><?php echo $row_slcInfo['escuela'] ?></th></tr>
                      <tr><th colspan="5"> Informe Financiero Comparativo de Capital Operativo y Liquidez</th></tr>
                      <tr style="border-bottom: medium double black;"><th colspan="5"><?php echo "Al ". strftime('%d de %B de %Y', strtotime($row_slcFecha['f_final'])) ?></th></tr>
                    </thead> 
                    <?php

                    $consulta_liquidez = "SELECT liquidez.id_liquidez FROM liquidez INNER JOIN liquidez_detalles  ON liquidez_detalles.id_liquidez=liquidez.id_liquidez AND  liquidez.id_escuela=$esc and liquidez.id_mes=$me AND liquidez.id_ejercicio=$an AND liquidez.id_zona=$zon";
                    $res_liquidez = $mysqli->query($consulta_liquidez);
                    $num_Liquidez = $res_liquidez->num_rows;

                    if ($num_Liquidez==0) { ?>
                    <div style="position: absolute; top: 55px;right: 10px;">
                      <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <strong>Error!</strong> Hace falta Informacion.&nbsp; Ingresa los datos de liquidez.
                      </div>
                    </div>
                    <?php
                  }else{
                    ?>
                    <div style="position: absolute; top: auto; right: 2%;">
                      <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <strong>¡Muy bien!  <?php echo    $_SESSION[tituloalertanombre] ?></strong>La liquidez esta actualizada al mes de <?php echo strftime('%B del %Y', strtotime($row_slcFecha['f_final'])) ?>
                      </div>
                    </div>    
                    <?php
                  }



                  $sql1 = "SELECT concepto_activo, SUM(activo) as tactivo   from balance_detalle INNER JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE '%Total de Activo Corriente%' AND balance.id_escuela=$esc AND balance.id_ejercicio=$an and balance.id_mes=$me";
                  $resultadoestado1= $mysqli->query($sql1);
                  $arrayresult1 = $resultadoestado1->fetch_array();
                  $arrayresult1['concepto_activo'];
                  $arrayresult1['tactivo'];

                  $sql2 = "SELECT concepto_pasivo, SUM(pasivo) as tpasivo   from balance_detalle INNER JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_pasivo  LIKE '%Total de Pasivo Corriente%' AND balance.id_escuela=$esc AND balance.id_ejercicio=$an and balance.id_mes=$me";
                  $resultadoestado2= $mysqli->query($sql2);
                  $arrayresult2 = $resultadoestado2->fetch_array();
                  $arrayresult2['concepto_pasivo'];
                  $arrayresult2['tpasivo'];
                  $tot=  $arrayresult2['tpasivo']-  $arrayresult1['tactivo'];


                                                  //suma de fondocajachica, caja otros ingresos, bancos
                  $cajabancos = "SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Fondo de Caja Chica%' AND balance.id_escuela=$esc AND balance.id_ejercicio=$_SESSION[id_ejercicio] and balance.id_mes=$me union SELECT   activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Caja Otros Ingresos%' AND balance.id_escuela=$esc AND balance.id_ejercicio=$an and balance.id_mes=$me UNION SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Bancos%' AND balance.id_escuela=$esc AND balance.id_ejercicio=$an and balance.id_mes=$me";

                  $cajabancos1= $mysqli->query($cajabancos);
                  while ( $arrayresult3 = $cajabancos1->fetch_row()) {
                    $total+=$arrayresult3[0];
                  } 

                  $inversion = "SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Inversiones%' AND balance.id_escuela=$esc AND balance.id_ejercicio=$an and balance.id_mes=$me";
                  $resultadoestado4= $mysqli->query($inversion);
                  $total1 = 0; 
                  while ($arrayresult4 = $resultadoestado4->fetch_row()) {
                    $total1 = $total1 +  $arrayresult4[0];
                  }

                  $pasivo_cor = "SELECT pasivo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_pasivo  LIKE'%Total de Pasivo Corriente%' AND balance.id_escuela=$esc AND balance.id_ejercicio=$an and balance.id_mes=$me";
                  $resultadoestado5= $mysqli->query($pasivo_cor);
                  $pasivo_cor = 0.0; 
                  while ($arrayresult5 = $resultadoestado5->fetch_row()) {
                    $pasivo_cor = $pasivo_cor +  $arrayresult5[0];
                  }
                  
                                                                  // Total Ingresos Educativos Netos
                  $totingresoseducativosnetos= "SELECT  reala  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Total Ingresos Educativos Netos%'  AND edo_res.id_escuela=$esc AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $toingresedunetos= $mysqli->query($totingresoseducativosnetos);
                  $tingedunetos = $toingresedunetos->fetch_array();

                                                                // Subsidios y Otros Ingresos
                  $subsioneto= "SELECT  reala  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Total de Otros Ingresos%'  AND edo_res.id_escuela=$esc AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $subsi= $mysqli->query($subsioneto);
                  $totsubneto = $subsi->fetch_array();
                  
                                                              // Egresos Operativos
                  $egropera= "SELECT  reala  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%GRAN TOTAL DE GASTOS%'  AND edo_res.id_escuela=$esc AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $egreopera= $mysqli->query($egropera);
                  $egreopera = $egreopera->fetch_array();
                  

                                                              //Colegiaturas Mensuales por Cobrar
                  $colemen= "SELECT  realm  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Enseñanza%'  AND edo_res.id_escuela=$esc AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $querycolemen= $mysqli->query($colemen);
                  $arraycolemen = $querycolemen->fetch_array();

                                                                //Becas Mensuales Otorgadas
                  $becamensualotorga= "SELECT realm from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Becas y Descuentos%'  AND edo_res.id_escuela=$esc AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an UNION  SELECT  realm  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Ingresos por Colegiaturas no Recibidos%'  AND edo_res.id_escuela=$esc AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $becamensualotorga1= $mysqli->query($becamensualotorga);
                  while ( $arraybmotorgadas = $becamensualotorga1->fetch_row()) {
                    $acumbecamensualotorga+=$arraybmotorgadas[0];
                  } 

                  

                                                          //Saldo Inicial en Cuenta de Clientes
                  $salinicuenclient= "SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Cuentas por Cobrar Activas%' AND balance.id_escuela=$esc AND balance.id_ejercicio=$an and balance.id_mes=$me";
                  $querysalinicuenclient= $mysqli->query($salinicuenclient);
                  $arraycsinicialcuenclient = $querysalinicuenclient->fetch_array();



                                                          //Saldo Final en cuenta de Clientes solo falta igualarle a la variable para no tener que registrar abajo
                  $salfinacuenclient= "SELECT  realm  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Enseñanza%'  AND edo_res.id_escuela=$esc AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $quersalfinacuenclient= $mysqli->query($salfinacuenclient);
                  $arrayquersalfinacuenclient = $quersalfinacuenclient->fetch_array();






                                                          //Colegiaturas Presupuestadas Anualmente
                  $colepresuanual= "SELECT anual from presupuesto_detalle INNER JOIN presupuesto ON presupuesto_detalle.id_presupuesto=presupuesto.id_presupuesto  WHERE concepto_presupuesto  LIKE'%ENSEÑANZA%' AND  presupuesto.id_escuela=$esc AND presupuesto.id_ejercicio=$an and presupuesto.id_mes=$me";
                  $quercolepresuanual= $mysqli->query($colepresuanual);
                  $arrayquercolepresuanual= $quercolepresuanual->fetch_array();


                                                            //Becas Presupuestadas Anualmente
                  $bepresuanual= "SELECT anual from presupuesto_detalle INNER JOIN presupuesto ON presupuesto_detalle.id_presupuesto=presupuesto.id_presupuesto  WHERE concepto_presupuesto  LIKE'%MENOS: BECAS Y DESCUENTOS%' AND  presupuesto.id_escuela=$esc AND presupuesto.id_ejercicio=$an and presupuesto.id_mes=$me UNION SELECT anual from presupuesto_detalle INNER JOIN presupuesto ON presupuesto_detalle.id_presupuesto=presupuesto.id_presupuesto  WHERE concepto_presupuesto  LIKE'%MENOS: INGRESOS COLEG NO RECIB%' AND  presupuesto.id_escuela=$esc AND presupuesto.id_ejercicio=$an and presupuesto.id_mes=$me";
                  $qubepreanual= $mysqli->query($bepresuanual);
                  while ( $arrayqubepreanual = $qubepreanual->fetch_row()) {
                    $becaspresupuestadasanualmente+=$arrayqubepreanual[0];
                  } 

                                                            //Colegiaturas Cobradas en el Período falta
                  $colecobraperio= "SELECT  reala  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Enseñanza%'  AND edo_res.id_escuela=$esc AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $quercolecobraperio= $mysqli->query($colecobraperio);
                  $arrayquercolecobraperio= $quercolecobraperio->fetch_array();

                  
                                                              //Becas otorgadas en el Período
                  $beotorperio= "SELECT reala from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Becas y Descuentos%'  AND edo_res.id_escuela=$esc AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an UNION  SELECT  realm  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Ingresos por Colegiaturas no Recibidos%'  AND edo_res.id_escuela=$esc AND edo_res.id_mes=$me and edo_res.id_ejercicio=$an";
                  $querbeotorperio= $mysqli->query($beotorperio);
                  while ( $arrayqubepreanual = $querbeotorperio->fetch_row()) {
                    $arrayquerbeotorperio+=$arrayqubepreanual[0];
                  }


                  ?>


                  
                </table>
                
                <form method="post" class="form-horizontal" name="registro" role="form">
                  <legend></legend>
                  <table>
                    <thead >
                      <tr>
                        <td style="text-align: center; "><h4 style="font-weight: bold;">Conceptos Financieros</h4></td>
                        <td style="text-align: center; "><h4 style="font-weight: bold;">Año Actual</h4></td>
                        <td style="text-align: center; "><h4 style="font-weight: bold;">Año Anterior</h4></td>
                        <td style="text-align: center; "><h4 style="font-weight: bold;">%</h4></td>
                        <td></td>
                      </tr>     

                      <script type="text/javascript">
                        
                        function sumar(){
                          /*CONSULTA CORRECTA activo corriente*/
                          tacor=document.registro.t_acorriente.value;
                          var  obttacor="<?php echo   $arrayresult1['tactivo']?>";
                          tact= ( (parseFloat(obttacor)-parseFloat(tacor)) /parseFloat(obttacor) )*100;  
                          document.registro.txttacorriente.value=new Intl.NumberFormat('es-MX').format(tact.toFixed(2));

                          /*CONSULTA CORRECTA pasivo corriente*/
                          pcor=document.registro.t_pcorriente.value;
                          var  obttpcor="<?php echo   $arrayresult2['tpasivo']?>";
                          tpas=(parseFloat(obttpcor)-parseFloat(pcor))/parseFloat(obttpcor)*100;  
                          document.registro.txttpcorriente.value=new Intl.NumberFormat('es-MX').format(tpas.toFixed(2));

                          /* Total capital Operativo anio anterior*/
                          totalco=parseFloat(tacor)+parseFloat(pcor);
                          document.registro.totalcoperativo.value=new Intl.NumberFormat('es-MX').format(totalco.toFixed(2));
                          var  totporcentaje="<?php echo $tot; ?>";
                          portotalco=(parseFloat(totporcentaje)-parseFloat(totalco))/parseFloat(totporcentaje)*100;  
                          document.registro.porctotcapop.value=new Intl.NumberFormat('es-MX').format(portotalco.toFixed(2));
                          

                          /*Egresos operativos anio anter y actual*/
                          eoa=document.registro.e_operativosanual.value;                   
                          eom=document.registro.e_operativosmensual.value;
                          tteoa=(parseFloat(eoa)-parseInt(eom))/parseFloat(eoa)*100; 
                          document.registro.hola.value=new Intl.NumberFormat('es-MX').format(tteoa.toFixed(2));

                          /*Porcentaje aplicado */
                          pa=document.registro.p_aplicado.value;
                          var  paplicadoanual=(parseFloat(eoa)*0.15);
                          paplicadoo=(parseFloat(paplicadoanual)-parseFloat(pa))/parseFloat(paplicadoanual)*100; 
                          document.registro.txtaplicado.value=new Intl.NumberFormat('es-MX').format( paplicadoo.toFixed(2));
                          document.registro.totalaplicadoanual.value=new Intl.NumberFormat('es-MX').format( paplicadoanual.toFixed(2));

                          /* Fondo asigando anual y mensual*/
                          faa=document.registro.f_asignadoanual.value;
                          fam=document.registro.f_asignadomensual.value;
                          ttfa=(parseFloat(faa)-parseInt(fam))/parseFloat(faa)*100; 
                          document.registro.totalfasignadomensualanual.value=new Intl.NumberFormat('es-MX').format(ttfa.toFixed(2));


                          /* Total capital operativo reco anual y mensual*/
                          var totcoprecomendadot=(parseFloat(paplicadoanual)+parseFloat(faa));  
                          document.registro.totalcoprecomendadoanualact.value=new Intl.NumberFormat('es-MX').format( totcoprecomendadot.toFixed(2));
                          var totcoprecomendadoant=(parseFloat(pa)+parseFloat(fam));  
                          document.registro.totalcoprecomendadoant.value=new Intl.NumberFormat('es-MX').format( totcoprecomendadoant.toFixed(2));
                          var porcoper=(parseFloat(totcoprecomendadot)-parseFloat(totcoprecomendadoant))/parseFloat(totcoprecomendadot)*100; 
                          document.registro.porcecoprecomendadoanualact.value=new Intl.NumberFormat('es-MX').format( porcoper.toFixed(2));

                          /* Total SUPERAVIT O (DEFICIT) CAPITAL OPERATIVO*/
                          var totcapitalanoactual="<?php echo $tot; ?>";
                          var  deficitoperativoact=(parseFloat(totcapitalanoactual)-parseFloat(totcoprecomendadot));  
                          document.registro.superavitdeficitoperativoact.value=new Intl.NumberFormat('es-MX').format(  deficitoperativoact.toFixed(2));
                          var deficitoperativoant=(parseFloat(totalco)-parseFloat(totcoprecomendadoant));  
                          document.registro.superavitdeficitoperativoant.value=new Intl.NumberFormat('es-MX').format( deficitoperativoant.toFixed(2));
                          var porcoper=(parseFloat(deficitoperativoact)-parseFloat(deficitoperativoant))/parseFloat(deficitoperativoact)*100; 
                          document.registro.porcesuperavitdeficitoperativoant.value=new Intl.NumberFormat('es-MX').format( porcoper.toFixed(2));

                          /* % CAP. OPERATIVO EN RELACIÓN AL RECOMENDADO*/
                          var totcapitalanoactual="<?php echo $tot; ?>";
                          var  deficitoperativoact=(parseFloat(totcapitalanoactual)/parseFloat(totcoprecomendadot));  
                          document.registro.capoperativorecomendadoact.value=new Intl.NumberFormat('es-MX').format(  deficitoperativoact.toFixed(2));
                          var deficitoperativoant=(parseFloat(totalco)/parseFloat(totcoprecomendadoant));  
                          document.registro.capoperativorecomendadoant.value=new Intl.NumberFormat('es-MX').format( deficitoperativoant.toFixed(2));
                          var porcoperrela=(parseFloat(deficitoperativoact)-parseFloat(deficitoperativoant))/parseFloat(deficitoperativoact)*100;  
                          document.registro.porcecapoperativorecomendadoant.value=new Intl.NumberFormat('es-MX').format( porcoperrela.toFixed(2));
                          

                          /* cajabancos*/                    
                          cb=document.registro.caja_bancos.value;
                          var  cajabancoscaja="<?php echo $total; ?>";
                          tpas=(parseFloat(obttpcor)-parseFloat(pcor))/parseFloat(obttpcor)*100; 

                          /* Inversiones*/ 
                          i=document.registro.inversiones.value;

                          /* Total de ACTIVO LIQUIDO*/  
                          var activoliquido=(parseFloat(cb)+parseFloat(i));  
                          document.registro.txttactivoliquidoant.value=new Intl.NumberFormat('es-MX').format( activoliquido.toFixed(2));
                          var  totactliqui="<?php echo $total2=$total+$Total;  ?>";
                          var poractivoliquido=(parseFloat(totactliqui)-parseFloat(activoliquido))/parseFloat(totactliqui)*100; 
                          document.registro.txttactivoliquido.value=new Intl.NumberFormat('es-MX').format( poractivoliquido.toFixed(2));


                          /* Pasivo Corriente*/  
                          pc=document.registro.p_corriente.value;
                          var txtlcorriente=(parseFloat(obttpcor)-parseFloat(pc))/parseFloat(obttpcor); 
                          document.registro.txtliquidezpcorriente.value=new Intl.NumberFormat('es-MX').format( txtlcorriente.toFixed(2));


                          /* Fondos Asignados Brutos*/  
                          fab=document.registro.f_asignadosbrutos.value;
                          faab=(parseFloat(faa));
                          document.registro.totalfasignadobruto.value=new Intl.NumberFormat('es-MX').format( faab.toFixed(2));
                          var totfas=(parseFloat(faab)-parseFloat(fab))/parseFloat(faab)*100; 
                          document.registro.txtfasignadobrutoant.value=new Intl.NumberFormat('es-MX').format( totfas.toFixed(2));

                          /* Total de PASIVO  Y REMANENTE DEL EJERC.*/  
                          var totalpasivor=(parseFloat(obttpcor)+parseFloat(faab));  
                          document.registro.totalpasivoremanenteant.value=new Intl.NumberFormat('es-MX').format( totalpasivor.toFixed(2));
                          var totalpasivoact=(parseFloat(pc)+parseFloat(fab));  
                          document.registro.totalpasivoremanenteact.value=new Intl.NumberFormat('es-MX').format( totalpasivoact.toFixed(2));
                          var pasivoremanenteporc=(parseFloat(totalpasivoact)-parseFloat(totalpasivor))/parseFloat(totalpasivoact)*100; 
                          document.registro.totalpasivoremanenteporc.value=new Intl.NumberFormat('es-MX').format( pasivoremanenteporc.toFixed(2));
                          
                          /* Total activo liquido neto  exit*/  
                          var  totactliqnean="<?php  echo $total2=$total+$Total;  ?>";                
                          var totactliqneant=(parseFloat(totactliqnean)-parseFloat(totalpasivor));  
                          document.registro.totactliquinetoant.value=new Intl.NumberFormat('es-MX').format( totactliqneant.toFixed(2));
                          var totactliqneact=(parseFloat(totalpasivoact)-parseFloat(activoliquido));  
                          document.registro.totactliquinetoact.value=new Intl.NumberFormat('es-MX').format( totactliqneact.toFixed(2));
                          var totactliqneporc=(parseFloat(totactliqneant)-parseFloat(totactliqneact))/parseFloat(totactliqneant)*100; 
                          document.registro.totactliquinetoporc.value=new Intl.NumberFormat('es-MX').format( totactliqneporc.toFixed(2));               
                          
                          /* % liquidez*/                     
                          var liquidezactual=(parseFloat(totactliqnean)/parseFloat(totalpasivor));  
                          document.registro.liquidezact.value=new Intl.NumberFormat('es-MX').format( liquidezactual.toFixed(2));
                          /**/var liquidezanterior=(parseFloat(totalpasivoact)/parseFloat(activoliquido));  
                          document.registro.liquidezant.value=new Intl.NumberFormat('es-MX').format( liquidezanterior.toFixed(2));
                          var liquidezporcentaje=(parseFloat(liquidezactual)-parseFloat(liquidezanterior))/parseFloat(liquidezactual)*100; 
                          document.registro.liquidezporc.value=new Intl.NumberFormat('es-MX').format( liquidezporcentaje.toFixed(2));

                          /* Rentabilidad y Sosten Propio*/ 
                          /*Ingreso Operativo*/
                          var  totoperativoporc="<?php  echo $tingedunetos['reala'];?>";
                          ingopera=document.registro.ingreso_operativo.value;
                          var operativoporc=(parseFloat(totoperativoporc)-parseFloat(ingopera))/parseFloat(totoperativoporc)*100; 
                          document.registro.ingreso_operativoporc.value=new Intl.NumberFormat('es-MX').format( operativoporc.toFixed(2));
                          

                          /* subcidios_ingresos*/  
                          si=document.registro.subcidios_ingresos.value;
                          var  totsubingres="<?php echo $totsubneto['reala'];?>";
                          var subingres=(parseFloat(totsubingres)-parseFloat(si))/parseFloat(totsubingres)*100; 
                          
                          
                          /*Total de ingresos final*/
                          var totingact=(parseFloat(totoperativoporc) + parseFloat(totsubingres));  
                          document.registro.totaldeingreact.value=new Intl.NumberFormat('es-MX').format( totingact.toFixed(2));

                          var totingant=(parseFloat(ingopera)+parseFloat(si)); 
                          document.registro.totaldeingreant.value=new Intl.NumberFormat('es-MX').format( totingant.toFixed(2));

                          var deingreporc=(parseFloat(totingact)-parseFloat(totingant))/parseFloat(totingact)*100; 
                          document.registro.totaldeingreporc.value=new Intl.NumberFormat('es-MX').format( deingreporc.toFixed(2));
                          
                          /*% sosten propio*/
                          var porsosproact=(parseFloat(totoperativoporc)/parseFloat(totingact))*100;
                          document.registro.sostenpropioact.value=new Intl.NumberFormat('es-MX').format( porsosproact.toFixed(2));
                          var porsosproant=(parseFloat(ingopera)/parseFloat(totingant))*100;
                          document.registro.sostenpropioant.value=new Intl.NumberFormat('es-MX').format( porsosproant.toFixed(2));
                          var totsostenpropioporc=(parseFloat(porsosproact)-parseFloat(porsosproant))/parseFloat(porsosproact)*100; 
                          document.registro.sostenpropioporc.value=new Intl.NumberFormat('es-MX').format( totsostenpropioporc.toFixed(2));
                          

                          /*Egresos Operativos.*/
                          var  egresos_operativoactual="<?php  echo $egreopera['reala'];  ?>";
                          eo=document.registro.egresos_operativo.value;
                          var egresosoperativoporc=(parseFloat(egresos_operativoactual)-parseFloat(eo))/parseFloat(egresos_operativoactual)*100; 
                          document.registro.egresos_operativoporc.value=new Intl.NumberFormat('es-MX').format(egresosoperativoporc.toFixed(2));

                          /*Total de EGRESOS final*/
                          var totaldeegreactu=(parseFloat(egresos_operativoactual));  
                          document.registro.totaldeegreact.value=new Intl.NumberFormat('es-MX').format( totaldeegreactu.toFixed(2));
                          var totaldeegreante=(parseFloat(eo)); 
                          document.registro.totaldeegreant.value=new Intl.NumberFormat('es-MX').format( totaldeegreante.toFixed(2));
                          var  egreporc=(parseFloat(egresos_operativoactual)-parseFloat(eo))/parseFloat(egresos_operativoactual)*100; 
                          document.registro.totaldeegreporc.value=new Intl.NumberFormat('es-MX').format( egreporc.toFixed(2));

                          /*UTILIDAD O (PÉRDIDA) OPERATIVA final*/
                          var totalutiperoperaact=(parseFloat(totingact)-parseFloat(totaldeegreactu)); 
                          document.registro.utiperoperaact.value=new Intl.NumberFormat('es-MX').format( totalutiperoperaact.toFixed(2));
                          var totalutiperoperaant=(parseFloat(totingant)-parseFloat(totaldeegreante)); 
                          document.registro.utiperoperaant.value=new Intl.NumberFormat('es-MX').format( totalutiperoperaant.toFixed(2));
                          totalutiperoperaporc=(parseFloat(totalutiperoperaact)-parseFloat(totalutiperoperaant))/parseFloat(totalutiperoperaact)*100; 
                          document.registro.utiperoperaporc.value=new Intl.NumberFormat('es-MX').format( totalutiperoperaporc.toFixed(2));
                          
                          

                          /*% RENTABILIDAD EN RELACIÓN A UTILIDAD OPERATIVA final*/                
                          var totalrentreutiopeact=(parseFloat(totalutiperoperaact)/parseFloat(totingact)); 
                          document.registro.rentreutiopeact.value=new Intl.NumberFormat('es-MX').format( totalrentreutiopeact.toFixed(2));

                          var totalrentreutiopeant=(parseFloat(totalutiperoperaant)/parseFloat(totingant)); 
                          document.registro.rentreutiopeant.value=new Intl.NumberFormat('es-MX').format( totalrentreutiopeant.toFixed(2));

                          
                          /* ÍNDICE DE COBRANZA */ 
                          /*Colegiaturas Mensuales por Cobrar*/
                          var  cole_mcobraractual="<?php  echo $arraycolemen['realm'];  ?>";
                          cmc=document.registro.cole_mcobrar.value;
                          var cole_mcobrarporce=(parseFloat(cole_mcobraractual)-parseFloat(cmc))/parseFloat(cole_mcobraractual)*100; 
                          document.registro.cole_mcobrarporc.value=new Intl.NumberFormat('es-MX').format(cole_mcobrarporce.toFixed(2));

                          /* Becas Mensuales Otorgadas*/                   
                          var  becas_motorgadasactual="<?php  echo $acumbecamensualotorga;  ?>";
                          bmo=document.registro.becas_motorgadas.value;
                          var becas_motorgadasporce=(parseFloat(becas_motorgadasactual)-parseFloat(bmo))/parseFloat(becas_motorgadasactual)*100; 
                          document.registro.becas_motorgadasporc.value=new Intl.NumberFormat('es-MX').format(becas_motorgadasporce.toFixed(2));

                          /*Neto a cobrar Mensual*/
                          var totalnetoacobrarmensact=(parseFloat(cole_mcobraractual)-parseFloat(becas_motorgadasactual)); 
                          document.registro.netoacobrarmensact.value=new Intl.NumberFormat('es-MX').format( totalnetoacobrarmensact.toFixed(2));
                          var totalnetoacobrarmensant=(parseFloat(cmc)-parseFloat(bmo)); 
                          document.registro.netoacobrarmensant.value=new Intl.NumberFormat('es-MX').format( totalnetoacobrarmensant.toFixed(2));
                          totalnetoacobrarmensporc=(parseFloat(totalnetoacobrarmensact)-parseFloat(totalnetoacobrarmensant))/parseFloat(totalnetoacobrarmensact)*100; 
                          document.registro.netoacobrarmensporc.value=new Intl.NumberFormat('es-MX').format( totalnetoacobrarmensporc.toFixed(2));
                          
                          /*Saldo Inicial en Cuenta de Clientes*/
                          var  totalsaldo_cclientesactual="<?php  echo $arraycsinicialcuenclient['activo'];  ?>";
                          scc=document.registro.saldo_cclientes.value;
                          var totalsaldo_cclientesporc=(parseFloat(totalsaldo_cclientesactual)-parseFloat(scc))/parseFloat(totalsaldo_cclientesactual)*100; 
                          document.registro.saldo_cclientesporc.value=new Intl.NumberFormat('es-MX').format(totalsaldo_cclientesporc.toFixed(2));

                          /*masNeto a cobrar Mensual*/
                          var totalmasnetoacobrarmensact=(parseFloat(totalnetoacobrarmensact));  
                          document.registro.masnetoacobrarmensact.value=new Intl.NumberFormat('es-MX').format( totalmasnetoacobrarmensact.toFixed(2));
                          var totalmasnetoacobrarmensant=(parseFloat(totalnetoacobrarmensant)); 
                          document.registro.masnetoacobrarmensant.value=new Intl.NumberFormat('es-MX').format( totalmasnetoacobrarmensant.toFixed(2));
                          var  totmasnetoacobrarmensporc=(parseFloat(totalmasnetoacobrarmensact)-parseFloat(totalmasnetoacobrarmensant))/parseFloat(totalmasnetoacobrarmensact)*100; 
                          document.registro.masnetoacobrarmensporc.value=new Intl.NumberFormat('es-MX').format( totmasnetoacobrarmensporc.toFixed(2));


                          /*Total Cartera a Recuperar en el Mes*/
                          var totaltotalcarremesact=(parseFloat(totalsaldo_cclientesactual)+parseFloat(totalmasnetoacobrarmensact)); 
                          document.registro.totalcarremesact.value=new Intl.NumberFormat('es-MX').format( totaltotalcarremesact.toFixed(2));

                          var totaltotalcarremesant=(parseFloat(scc)+parseFloat(totalmasnetoacobrarmensant)); 
                          document.registro.totalcarremesant.value=new Intl.NumberFormat('es-MX').format( totaltotalcarremesant.toFixed(2));
                          totaltotalcarremesporc=(parseFloat(totaltotalcarremesact)-parseFloat(totalmasnetoacobrarmensant))/parseFloat(totaltotalcarremesact)*100; 
                          document.registro.totalcarremesporc.value=new Intl.NumberFormat('es-MX').format( totaltotalcarremesporc.toFixed(2)); 
                          

                          /*Cobros Efectivamente Realizados */
                          cer=document.registro.cobros_erealizados.value; 

                          var  resultsaldocuentaclientes="<?php  echo $arrayquersalfinacuenclient['realm'];  ?>";
                          var totalcobros_erealizadosact=(parseFloat(totaltotalcarremesact) - parseFloat(resultsaldocuentaclientes)); 
                          document.registro.cobros_erealizadosact.value=new Intl.NumberFormat('es-MX').format( totalcobros_erealizadosact.toFixed(2)); 
                          

                          /*Saldo Final en cuenta de Clientes final*/
                          var  totsaldofinalcuentaclienteact="<?php  echo $arrayquersalfinacuenclient['realm'];  ?>";
                          var totalsaldocuentaclienteant=(parseFloat(totaltotalcarremesant)-parseFloat(cer)); 
                          document.registro.saldocuentaclienteant.value=new Intl.NumberFormat('es-MX').format( totalsaldocuentaclienteant.toFixed(2));
                          totalsaldocuentaclienteporc =(parseFloat(totsaldofinalcuentaclienteact)-parseFloat(totalsaldocuentaclienteant))/parseFloat(totsaldofinalcuentaclienteact)*100; 
                          document.registro.saldocuentaclienteporc.value=new Intl.NumberFormat('es-MX').format( totalsaldocuentaclienteporc.toFixed(2));
                          
                          

                          /*% DE EFICIENCIA EN LA COBRANZA final*/
                          var totaleficienciaact=(parseFloat(totaltotalcarremesact)/parseFloat(totalcobros_erealizadosact))*100; 
                          document.registro.eficienciaact.value=new Intl.NumberFormat('es-MX').format( totaleficienciaact.toFixed(2));
                          var totaleficienciaant=(parseFloat(totaltotalcarremesant)/parseFloat(cer))*100; 
                          document.registro.eficienciaant.value=new Intl.NumberFormat('es-MX').format( totaleficienciaant.toFixed(2));


                          /* RELACIÓN DE BECAS CON INGRESOS */ 
                          /*% Colegiaturas Presupuestadas Anualmente*/
                          var  totalcole_preanualactual="<?php  echo $arrayquercolepresuanual['anual'];  ?>";
                          cpa=document.registro.cole_preanual.value;
                          var totalcole_preanualporc=(parseFloat(totalcole_preanualactual)-parseFloat(cpa))/parseFloat(totalcole_preanualactual)*100; 
                          document.registro.cole_preanualporc.value=new Intl.NumberFormat('es-MX').format(totalcole_preanualporc.toFixed(2));


                          /*Becas Presupuestadas Anualmente*/              
                          var  totalbecas_preanualactual="<?php  echo $becaspresupuestadasanualmente; ?>";
                          bpa=document.registro.becas_preanual.value;
                          var totalbecas_preanualporc=(parseFloat(totalbecas_preanualactual)-parseFloat(bpa))/parseFloat(totalbecas_preanualactual)*100; 
                          document.registro.becas_preanualporc.value=new Intl.NumberFormat('es-MX').format(totalbecas_preanualporc.toFixed(2));

                          /*% de Becas en Base a Presupuesto*/
                          var totalbecasenbpresuact=(parseFloat(totalcole_preanualactual)/parseFloat(totalbecas_preanualactual)); 
                          document.registro.becasenbpresuact.value=new Intl.NumberFormat('es-MX').format( totalbecasenbpresuact.toFixed(2));
                          var totalbecasenbpresuant=(parseFloat(cpa)/parseFloat(bpa)); 
                          document.registro.becasenbpresuant.value=new Intl.NumberFormat('es-MX').format( totalbecasenbpresuant.toFixed(2));
                          totalbecasenbpresuporc=(parseFloat(totalbecasenbpresuact)-parseFloat(totalbecasenbpresuant))/parseFloat(totalbecasenbpresuact)*100; 
                          document.registro.becasenbpresuporc.value=new Intl.NumberFormat('es-MX').format( totalbecasenbpresuporc.toFixed(2)); 

                          /*Colegiaturas Cobradas en el Período*/
                          var  totalcole_cobrarperiodoactual="<?php  echo $arrayquercolecobraperio['reala'];  ?>";
                          ccp=document.registro.cole_cobrarperiodo.value;
                          var totalcole_cobrarperiodoporc=(parseFloat(totalcole_cobrarperiodoactual)-parseFloat(ccp))/parseFloat(totalcole_cobrarperiodoactual)*100; 
                          document.registro.cole_cobrarperiodoporc.value=new Intl.NumberFormat('es-MX').format(totalcole_cobrarperiodoporc.toFixed(2));

                          /*Becas otorgadas en el Período*/
                          var  totalbecas_operiodoactual="<?php  echo $arrayquerbeotorperio;  ?>";
                          bop=document.registro.becas_operiodo.value;
                          var totalbecas_operiodoporc=(parseFloat(totalbecas_operiodoactual)-parseFloat(bop))/parseFloat(totalbecas_operiodoactual)*100; 
                          document.registro.becas_operiodoporc.value=new Intl.NumberFormat('es-MX').format(totalbecas_operiodoporc.toFixed(2));

                          /*% de Becas en Realción a los Cobros del Período*/
                          var totalberelacoperiodoact=(parseFloat(totalcole_cobrarperiodoactual)/parseFloat(totalbecas_operiodoactual))*100; 
                          document.registro.berelacoperiodoact.value=new Intl.NumberFormat('es-MX').format( totalberelacoperiodoact.toFixed(2));
                          var totalberelacoperiodoant=(parseFloat(ccp)-parseFloat(bop)); 
                          document.registro.berelacoperiodoant.value=new Intl.NumberFormat('es-MX').format( totalberelacoperiodoant.toFixed(2));
                          totalberelacoperiodoporc=(parseFloat(totalberelacoperiodoact)-parseFloat(totalberelacoperiodoant))/parseFloat(totalberelacoperiodoact)*100; 
                          document.registro.berelacoperiodoporc.value=new Intl.NumberFormat('es-MX').format( totalberelacoperiodoporc.toFixed(2));
                          
                          
                          
                        }
                      </script>       

                      <tr> <td><h3>Capital Operativo</h3></td> </tr>
                      
                      <tr> 

                        <td><?php echo' <div class="col-lg-10" style="text-align: center" role="form"><label for="ejemplo_email_3" class="">Total Activo Corriente</label>';?> </td>
                          <td class="col-lg-1"   ><?php echo   number_format($arrayresult1['tactivo'], 2, '.', ',' ); ?></td>
                          <td class="col-lg-2" >   <div class="form-line jf-required"><input   type="text" readonly id="combo" OnKeyup="sumar()" name="t_acorriente" type="text" placeholder="Total Activo Corriente" required value="<?php echo number_format($row_liquidez[t_acorriente], 2, '.', ',' );?>"></div></div></td>
                          <td class="col-lg-1" >  <input  name ="txttacorriente" id="combo" readonly="rea donly"  ></> </td>
                        </tr>
                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="t_pcorriente">Total Pasivo Corriente</label>';?></td>
                            <td class="col-lg-1"  ><?php echo  number_format($arrayresult2['tpasivo'], 2, '.', ',' )?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input    readonly id="combo"  name="t_pcorriente" type="text" OnKeyup="sumar()" placeholder="Total Pasivo Corriente" required value="<?php echo number_format($row_liquidez[t_pcorriente], 2, '.', ',' );?>">
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="txttpcorriente" id="combo" readonly="readonly"  ></></td>
                        </tr>
                        
                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total de CAPITAL OPERATIVO ACTUAL</label>';?></td>
                            <td class="col-lg-1"  ><?php  echo number_format($tot, 2, '.', ',' ); ?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input  name ="totalcoperativo" id="combo" readonly="readonly"  ></>
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="porctotcapop" id="combo" readonly="readonly"  ></></td>
                        </tr>

                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  for="e_operativos">Egresos Operativos de 12 meses anteriores</label>';?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input    readonly id="combo"  name="e_operativosanual" type="text" OnKeyup="sumar()" placeholder="Egresos Operativos de 12 meses anteriores" required value="<?php echo number_format($row_liquidez[e_operativosanual], 2, '.', ',' );?>">
                              
                            </div>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input    readonly id="combo"  name="e_operativosmensual" type="text" OnKeyup="sumar()" placeholder="Egresos Operativos de 12 meses anteriores" required value="<?php echo number_format($row_liquidez[e_operativosmensual], 2, '.', ',' );?>">
                              
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="hola" id="combo" readonly="readonly"  ></></td>
                        </tr>


                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class=""  for="p_aplicado">15% Aplicado</label>';?></td>
                            <td class="col-lg-1" >  <input  name ="totalaplicadoanual" id="combo" readonly="readonly"  ></></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input   OnKeyup="sumar()"  readonly id="combo"  name="p_aplicado" type="text" placeholder="15% Aplicado" required value="<?php echo number_format($row_liquidez[p_aplicado], 2, '.', ',' );?>">
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="txtaplicado" id="combo" readonly="readonly"  ></></td>
                        </tr>

                        
                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  for="f_asignado">Fondos Asignados</label>';?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input   OnKeyup="sumar()"  readonly id="combo" name="f_asignadoanual" type="text" placeholder="Fondos Asignados" required value="<?php echo number_format($row_liquidez[f_asignadoanual], 2, '.', ',' );?>">
                              
                            </div>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input   OnKeyup="sumar()"  readonly id="combo"  name="f_asignadomensual" type="text" placeholder="Fondos Asignados" required value="<?php echo number_format($row_liquidez[f_asignadomensual], 2, '.', ',' );?>">
                              
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="totalfasignadomensualanual" id="combo" readonly="readonly"  ></></td>
                        </tr>

                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total de CAPITAL OPERATIVO RECOMENDADO</label>';?></td>
                            <td class="col-lg-1" > <input  name ="totalcoprecomendadoanualact" id="combo" readonly="readonly"  > </td>
                            <td class="col-lg-2">  <input  name ="totalcoprecomendadoant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                              
                            </div>
                          </div></td>
                          <td class="col-lg-1" > <input  name ="porcecoprecomendadoanualact" id="combo" readonly="readonly"  > </td>
                        </tr>

                        
                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >SUPERAVIT O (DEFICIT) CAPITAL OPERATIVO</label>';?></td>
                            <td class="col-lg-1" > <input  name ="superavitdeficitoperativoact" id="combo" readonly="readonly"  > </td>
                            <td class="col-lg-2"> <input  name ="superavitdeficitoperativoant" id="combo" readonly="readonly"  > <div class="form-line jf-required"> 
                              
                            </div>
                          </div></td>
                          <td class="col-lg-1" > <input  name ="porcesuperavitdeficitoperativoant" id="combo" readonly="readonly"  > </td>
                        </tr>


                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >% CAP. OPERATIVO EN RELACIÓN AL RECOMENDADO</label>';?></td>
                            <td class="col-lg-1" > <input  name ="capoperativorecomendadoact" id="combo" readonly="readonly"  > </td>
                            <td class="col-lg-2"> <input  name ="capoperativorecomendadoant" id="combo" readonly="readonly"  >  <div class="form-line jf-required"> 
                              
                            </div>
                          </div></td>

                          <td class="col-lg-1" > <input  name ="porcecapoperativorecomendadoant" id="combo" readonly="readonly"  > </td>
                        </tr>
                        
                        

                        <tr> <td>  <legend></legend></td> </tr>
                        <tr> <td><h3>Liquidez<small>  </small></h3></td> </tr>
                        

                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="caja_bancos">Caja, Bancos</label>';?></td>
                            <td class="col-lg-1"  ><?php  echo number_format($total, 2, '.', ',' );?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input   OnKeyup="sumar()"  readonly id="combo"  name="caja_bancos" type="text" placeholder="Caja, Bancos" required value="<?php echo number_format($row_liquidez[caja_bancos], 2, '.', ',' );?>">
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="txtcajabancos" id="combo" readonly="readonly"  ></></td>
                        </tr>


                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="inversiones">Inversiones</label>';?></td>
                            <td class="col-lg-1"  ><?php echo number_format($total1, 2, '.', ',' ); ?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input   OnKeyup="sumar()"  readonly id="combo"  name="inversiones" type="text" placeholder="Inversiones" required value="<?php echo number_format($row_liquidez[inversiones], 2, '.', ',' );?>">
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="txttinversiones" id="combo" readonly="readonly"  ></></td>
                        </tr>


                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="Total de ACTIVO LIQUIDO">Total de ACTIVO LIQUIDO</label>';?></td>
                            <td class="col-lg-1"  ><?php echo $total2=$total+$Total;  ?></td>
                            <td class="col-lg-2"> <div class="form-line jf-required">
                              <input  name ="txttactivoliquidoant" id="combo" readonly="readonly"  ></></td>
                            </div>
                          </div></td>
                          <td class="col-lg-1" >  <input  name ="txttactivoliquido" id="combo" readonly="readonly"  ></></td>
                        </tr>

                        <tr> 
                          <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="PASIVO Y REMANENTE DEL EJERCICIO">PASIVO Y REMANENTE DEL EJERCICIO</label>';?></td>
                          </tr>

                          
                          
                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="p_corriente">Pasivo Corriente</label>';?></td>
                              <td class="col-lg-1"  ><?php echo  number_format($arrayresult2['tpasivo'], 2, '.', ',' )?></td>
                              <td class="col-lg-2"> <div class="form-line jf-required">
                                <input   OnKeyup="sumar()"  readonly id="combo" name="p_corriente" type="text" placeholder="Pasivo Corriente" required value="<?php echo number_format($row_liquidez[p_corriente], 2, '.', ',' );?>">
                              </div>
                            </div></td>
                            <td class="col-lg-1" >  <input  name ="txtliquidezpcorriente" id="combo" readonly="readonly"  ></></td>
                          </tr>

                          
                          <!-- meequede aqui-->
                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="f_asignadosbrutos">Fondos Asignados Brutos</label>';?></td>
                              <td class="col-lg-1" > <input  name ="totalfasignadobruto" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2"> <div class="form-line jf-required">
                                <input   OnKeyup="sumar()"  readonly id="combo"  name="f_asignadosbrutos" type="text" placeholder="Fondos Asignados Brutos" required value="<?php echo number_format($row_liquidez[f_asignadosbrutos], 2, '.', ',' );?>">
                              </div>
                            </div></td>
                            <td class="col-lg-1" >  <input  name ="txtfasignadobrutoant" id="combo" readonly="readonly"  > </td>
                          </tr>




                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total de PASIVO  Y REMANENTE DEL EJERC.</label>';?></td>
                              <td class="col-lg-1" > <input  name ="totalpasivoremanenteant" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="totalpasivoremanenteact" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="totalpasivoremanenteporc" id="combo" readonly="readonly"  > </td>
                          </tr>


                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total de ACTIVO LIQUIDO NETO</label>';?></td>
                              <td class="col-lg-1" > <input  name ="totactliquinetoant" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="totactliquinetoact" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="totactliquinetoporc" id="combo" readonly="readonly"  > </td>
                          </tr>


                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >% LIQUIDEZ</label>';?></td>
                              <td class="col-lg-1" > <input  name ="liquidezact" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="liquidezant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="liquidezporc" id="combo" readonly="readonly"  > </td>
                          </tr>

                          


                          <tr> <td>  <legend></legend></td> </tr>
                          
                          <tr> <td><h3>Rentabilidad y Sostén Propio</h3></td> </tr>

                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="ingreso_operativo">Ingreso Operativo</label>';?></td>

                              <td class="col-lg-1"  ><?php  echo number_format($tingedunetos['reala'], 2, '.', ',' ); ?></td>
                              <td class="col-lg-2"> <div class="form-line jf-required">
                                <input   OnKeyup="sumar()"  readonly id="combo"  name="ingreso_operativo" type="text" placeholder="Ingreso Operativo" required value="<?php echo number_format($row_liquidez[ingreso_operativo], 2, '.', ',' );?>">
                              </div>
                            </div></td>
                            <td class="col-lg-1" >  <input  name ="ingreso_operativoporc" id="combo" readonly="readonly"  ></></td>
                          </tr>



                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="subcidios_ingresos">Subsidios y Otros Ingresos</label>';?></td>
                              <td class="col-lg-1"  ><?php  echo  number_format($totsubneto['reala'], 2, '.', ',' );  ?></td>
                              <td class="col-lg-2"> <div class="form-line jf-required">
                                <input   OnKeyup="sumar()"  readonly id="combo"  name="subcidios_ingresos" type="text" placeholder="Subsidios y Otros Ingresos" required value="<?php echo number_format($row_liquidez[subcidios_ingresos], 2, '.', ',' );?>">
                              </div>
                            </div></td>
                            <td class="col-lg-1" >  <input  name ="subcidios_ingresosporc" id="combo" readonly="readonly"  ></></td>
                          </tr>


                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total de INGRESOS</label>';?></td>
                              <td class="col-lg-1" > <input  name ="totaldeingreact" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="totaldeingreant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="totaldeingreporc" id="combo" readonly="readonly"  > </td>
                          </tr>

                          <!a esta le modifique -!>
                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >% Sosten Propio</label>';?></td>
                              <td class="col-lg-1" > <input  name ="sostenpropioact" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="sostenpropioant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="sostenpropioporc" id="combo" readonly="readonly"  > </td>
                          </tr>
                          


                          <tr> 
                            <!a esta le modifique -!>
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="egresos_operativo">Egresos Operativos</label>';?></td>
                              <td class="col-lg-1"  ><?php echo number_format($egreopera['reala'], 2, '.', ',' ); ?></td>
                              <td class="col-lg-2"> <div class="form-line jf-required">
                                <input   OnKeyup="sumar()"  readonly id="combo"  name="egresos_operativo" type="text" placeholder="Egresos Operativos" required value="<?php echo number_format($row_liquidez[egresos_operativo], 2, '.', ',' );?>">
                              </div>
                            </div></td>
                            <td class="col-lg-1" >  <input  name ="egresos_operativoporc" id="combo" readonly="readonly"  ></></td>
                          </tr>


                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total de EGRESOS</label>';?></td>
                              <td class="col-lg-1" > <input  name ="totaldeegreact" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="totaldeegreant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="totaldeegreporc" id="combo" readonly="readonly"  > </td>
                          </tr>


                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >UTILIDAD O (PÉRDIDA) OPERATIVA</label>';?></td>
                              <td class="col-lg-1" > <input  name ="utiperoperaact" id="combo" readonly="readonly"  > </td>
                              <td class="col-lg-2">  <input  name ="utiperoperaant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                              </div>
                            </div></td>
                            <td class="col-lg-1" > <input  name ="utiperoperaporc" id="combo" readonly="readonly"  > </td>
                          </tr>
                          
                          
                          <tr> 
                            <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="egresosoperativos">% Rentabilidad en Relación a Utilidad Operativa</label>';?></td>
                              <td class="col-lg-1" >  <input  name ="rentreutiopeact" id="combo" readonly="readonly"  ></></td>
                              <td class="col-lg-2"> <div class="form-line jf-required"> <input  name ="rentreutiopeant" id="combo" readonly="readonly"  > 
                                
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="rentreutiopeporc" id="combo" readonly="readonly"  ></></td>
                            </tr>

                            <tr> <td>  <legend></legend></td> </tr>
                            <tr> <td><h3>Índice de Cobranza</h3></td> </tr>

                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="cole_mcobrar">Colegiaturas Mensuales por Cobrar</label>';?></td>
                                <td class="col-lg-1"  ><?php echo number_format($arraycolemen['realm'], 2, '.', ',' ); ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="cole_mcobrar" type="text" placeholder="Colegiaturas Mensuales por Cobrar" required value="<?php echo number_format($row_liquidez[cole_mcobrar], 2, '.', ',' );?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="cole_mcobrarporc" id="combo" readonly="readonly"  ></></td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" for="becas_motorgadas">Becas Mensuales Otorgadas</label>';?></td>
                                <td class="col-lg-1"  ><?php echo number_format($acumbecamensualotorga, 2, '.', ',' ); ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="becas_motorgadas" type="text" placeholder="Becas Mensuales Otorgadas" required value="<?php echo number_format($row_liquidez[becas_motorgadas], 2, '.', ',' );?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="becas_motorgadasporc" id="combo" readonly="readonly"  ></></td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Neto a cobrar Mensual</label>';?></td>
                                <td class="col-lg-1" > <input  name ="netoacobrarmensact" id="combo" readonly="readonly"  > </td>
                                <td class="col-lg-2">  <input  name ="netoacobrarmensant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="netoacobrarmensporc" id="combo" readonly="readonly"  > </td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  for="saldo_cclientes">Saldo Inicial en Cuenta de Clientes</label>';?></td>
                                <td class="col-lg-1"  ><?php  echo number_format($arraycsinicialcuenclient['activo'], 2, '.', ',' ); ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="saldo_cclientes" type="text" placeholder="Saldo Inicial en Cuenta de Clientes" required value="<?php echo number_format($row_liquidez[saldo_cclientes], 2, '.', ',' );?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="saldo_cclientesporc" id="combo" readonly="readonly"  ></></td>
                            </tr>

                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Mas: Neto a Cobrar Mensual</label>';?></td>
                                <td class="col-lg-1" > <input  name ="masnetoacobrarmensact" id="combo" readonly="readonly"  > </td>
                                <td class="col-lg-2">  <input  name ="masnetoacobrarmensant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="masnetoacobrarmensporc" id="combo" readonly="readonly"  > </td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total Cartera a Recuperar en el Mes</label>';?></td>
                                <td class="col-lg-1" > <input  name ="totalcarremesact" id="combo" readonly="readonly"  > </td>
                                <td class="col-lg-2">  <input  name ="totalcarremesant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="totalcarremesporc" id="combo" readonly="readonly"  > </td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  for="cobros_erealizados">Cobros Efectivamente Realizados</label>';?></td>
                                <td class="col-lg-1" >  <input  name ="cobros_erealizadosact" id="combo" readonly="readonly"  ></></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="cobros_erealizados" type="text" placeholder="Cobros Efectivamente Realizados" required value="<?php echo number_format($row_liquidez[cobros_erealizados], 2, '.', ',' );?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="cobros_erealizadosporc" id="combo" readonly="readonly"  ></></td>
                            </tr>



                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Saldo Final en cuenta de Clientes</label>';?></td>
                                <td class="col-lg-1"  ><?php   echo number_format($arrayquersalfinacuenclient['realm'], 2, '.', ',' ) ?></td>
                                <td class="col-lg-2">  <input  name ="saldocuentaclienteant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="saldocuentaclienteporc" id="combo" readonly="readonly"  > </td>
                            </tr>

                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >% DE EFICIENCIA EN LA COBRANZA</label>';?></td>
                                <td class="col-lg-1" > <input  name ="eficienciaact" id="combo" readonly="readonly"  > </td>
                                <td class="col-lg-2">  <input  name ="eficienciaant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="eficienciaporc" id="combo" readonly="readonly"  > </td>
                            </tr>


                            <tr> <td>  <legend></legend></td> </tr>
                            
                            <tr> <td><h3>Relación de Becas con Ingresos</h3></td> </tr>

                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" for="cole_preanual">Colegiaturas Presupuestadas Anualmente</label>';?></td>
                                <td class="col-lg-1"  ><?php   echo number_format($arrayquercolepresuanual['anual'], 2, '.', ',' );  ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="cole_preanual" type="text" placeholder="Colegiaturas Presupuestadas Anualmente" required value="<?php echo number_format($row_liquidez[cole_preanual], 2, '.', ',' );?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="cole_preanualporc" id="combo" readonly="readonly"  ></></td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" for="becas_preanual">Becas Presupuestadas Anualmente</label>';?></td>
                                <td class="col-lg-1"  ><?php echo number_format($becaspresupuestadasanualmente, 2, '.', ',' ); ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="becas_preanual" type="text" placeholder="Becas Presupuestadas Anualmente" required value="<?php echo number_format($row_liquidez[becas_preanual], 2, '.', ',' );?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="becas_preanualporc" id="combo" readonly="readonly"  ></></td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >% de Becas en Base a Presupuesto</label>';?></td>
                                <td class="col-lg-1" > <input  name ="becasenbpresuact" id="combo" readonly="readonly"  > </td>
                                <td class="col-lg-2">  <input  name ="becasenbpresuant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="becasenbpresuporc" id="combo" readonly="readonly"  > </td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" for="cole_cobrarperiodo">Colegiaturas Cobradas en el Período</label>';?></td>
                                <td class="col-lg-1"  ><?php  echo number_format($arrayquercolecobraperio['reala'], 2, '.', ',' ); ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"  readonly id="combo"  name="cole_cobrarperiodo" type="text" placeholder="Colegiaturas Cobradas en el Período" required value="<?php echo number_format($row_liquidez[cole_cobrarperiodo], 2, '.', ',' );?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="cole_cobrarperiodoporc" id="combo" readonly="readonly"  ></></td>
                            </tr>


                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  for="becas_operiodo">Becas otorgadas en el Período</label>';?></td>
                                <td class="col-lg-1"  ><?php  echo number_format($arrayquerbeotorperio, 2, '.', ',' ); ?></td>
                                <td class="col-lg-2"> <div class="form-line jf-required">
                                  <input   OnKeyup="sumar()"   readonly id="combo"  name="becas_operiodo" type="text" placeholder="Becas otorgadas en el Período" required value="<?php echo number_format($row_liquidez[becas_operiodo], 2, '.', ',' );?>">
                                </div>
                              </div></td>
                              <td class="col-lg-1" >  <input  name ="becas_operiodoporc" id="combo" readonly="readonly"  ></></td>
                            </tr>



                            <tr> 
                              <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >% de Becas en Relación a los Cobros del Período</label>';?></td>
                                <td class="col-lg-1" > <input  name ="berelacoperiodoact" id="combo" readonly="readonly"  > </td>
                                <td class="col-lg-2">  <input  name ="berelacoperiodoant" id="combo" readonly="readonly"  >  <div class="form-line jf-required">
                                </div>
                              </div></td>
                              <td class="col-lg-1" > <input  name ="berelacoperiodoporc" id="combo" readonly="readonly"  > </td>
                            </tr>


                          </table>
                        </fieldset>
                      </form>
                      
                    </div>
                  </div>
                </div>
                <script type="text/javascript">
                  window.sumar();
                </script>


                
            </section>
            <!-- /.content -->
          </div>
          <!-- /.content-wrapper -->
          
        </div>

          <?php }//Cierra el if de que exista algún registro en el estado de resultados ?>


        </div>
    <?
  


}//Cierra función de liquidez



/*Funcion de presupuesto*/
function presupuesto($sentencia,$esc) {
  require("../../conex/connect.php");
    ?>
  <div class="col-xs-12">
         
 <?php 
  $res_edoResul = $mysqli->query($sentencia);
  $row_ejer = $res_edoResul->fetch_array();
  $num_edoResul = $res_edoResul->num_rows;
  if ($num_edoResul > 0) {
?>  
          <div class="box box-primary">
            <div class="box-header">
            <h3 class="box-title">Presupuesto</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table width="100%">
              <?php 
               
               
              if ($esc != "todos") {

                 $slcInfo = "SELECT es.`nombre` escuela, f.`nombre` filantropica FROM `escuela` es INNER JOIN `filantropica` f ON es.`id_filantropica` = f.`id_filantropica` WHERE `id_escuela` = $esc";
              // echo $slcInfo = "SELECT es.`nombre` escuela, f.`nombre` filantropica FROM `escuela` es INNER JOIN `filantropica` f ON es.`id_filantropica` = f.`id_filantropica` WHERE `id_escuela` = $_SESSION['añoelegido']";
           
                $sql_slcInfo = $mysqli->query($slcInfo);
                $row_slcInfo = $sql_slcInfo->fetch_array();
              }else{
                $row_slcInfo['filantropica']="CONSOLIDADO";
                //$row_slcInfo['escuela']="Todos";
              }
                $slcFecha = "SELECT `f_inicio`,`f_final`,id_ejercicio FROM `mes` WHERE `id_mes` = $_SESSION[id_mes]";
                $sql_slcFecha = $mysqli->query($slcFecha);
                $row_slcFecha = $sql_slcFecha->fetch_array();
                //setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
              ?>
                <thead>
                  <tr><th colspan="5"><?php echo $row_slcInfo['filantropica'] ?></th></tr>
                  <tr><th colspan="5"><?php echo $row_slcInfo['escuela'] ?></th></tr>
                  <tr><th colspan="5">PRESUPUESTO <?php echo $row_ejer['id_ejercicio'] ?></th></tr>
                  <tr style="border-bottom: medium double black;"><th colspan="5"></th></tr>
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
      while ($row_balance = $res_edoResul->fetch_array()) {
       
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
                <td id="activo" style="text-align: right;"<?php echo $anual ?>><?php echo number_format($row_balance['anual'], 2, '.', ',' ) ?></td>
                <td></td>
                   <td <?php echo $concepto_activo ?>><?php echo $row_balance['concepto_presupuesto'] ?></td>
                <td id="pasivo" style="text-align: right;"<?php echo $mensual ?>><?php echo number_format($row_balance['mensual'], 2, '.', ',' ) ?></td>
              </tr>
    <?php
      }//Cierra while de 
    ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <?php }//Cierra el if de que exista algún registro en el estado de resultados ?>


        </div>
    <?
  }//Cierra función de Presupuesto


/*Funcion de estados de resultados*/
function edo_resultados($sentencia,$esc) {
  require("../../conex/connect.php");
    ?>
  <div class="col-xs-12">
         
 <?php 
  $res_edoResul = $mysqli->query($sentencia);
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
               
               
              if ($esc != "todos") {

                 $slcInfo = "SELECT es.`nombre` escuela, f.`nombre` filantropica FROM `escuela` es INNER JOIN `filantropica` f ON es.`id_filantropica` = f.`id_filantropica` WHERE `id_escuela` = $esc";
              // echo $slcInfo = "SELECT es.`nombre` escuela, f.`nombre` filantropica FROM `escuela` es INNER JOIN `filantropica` f ON es.`id_filantropica` = f.`id_filantropica` WHERE `id_escuela` = $_SESSION['añoelegido']";
           
                $sql_slcInfo = $mysqli->query($slcInfo);
                $row_slcInfo = $sql_slcInfo->fetch_array();
              }else{
                $row_slcInfo['filantropica']="Consolidado";
                $row_slcInfo['escuela']="Todos";
              }
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
                    <td style="text-align: right;" <?php echo $presupuestom ?>><?php echo number_format($row_edoResul['presupuestom'], 2, '.', ',' )?></td>
                    <td style="text-align: right;" <?php echo $realm ?>><?php echo number_format($row_edoResul['realm'], 2, '.', ',' ) ?></td>
                    <td style="text-align: right;" <?php echo $variacionm ?>><?php echo number_format($row_edoResul['variacionm'], 2, '.', ',' ) ?></td>
                    <td style="text-align: right;" <?php echo $presupuestoa ?>><?php echo number_format($row_edoResul['presupuestoa'], 2, '.', ',' ) ?></td>
                    <td style="text-align: right;" <?php echo $reala ?>><?php echo number_format($row_edoResul['reala'], 2, '.', ',' ) ?></td>
                    <td style="text-align: right;" <?php echo $variaciona ?>><?php echo number_format($row_edoResul['variaciona'], 2, '.', ',' ) ?></td>
                    </div>
              </tr>
    <?php
      }//Cierra while de 
    ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <?php }//Cierra el if de que exista algún registro en el estado de resultados ?>


        </div>
    <?
  }//Cierra función de estado de resultados
  /////////////////////////////////////////////////////
  /////////////BALANCE GENERAL////////////////////
function balance_general($sentencia,$esc){
  require("../../conex/connect.php");
  $res_balance = $mysqli->query($sentencia);
  $num_balance = $res_balance->num_rows;
  if ($num_balance > 0) {
?>
         <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header">
            <h3 class="box-title">Balance general</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <table width="100%">
              <?php 
              if ($esc != "todos") {
                $slcInfo = "SELECT es.`nombre` escuela, f.`nombre` filantropica FROM `escuela` es INNER JOIN `filantropica` f ON es.`id_filantropica` = f.`id_filantropica` WHERE `id_escuela` = $esc";
                $sql_slcInfo = $mysqli->query($slcInfo);
                $row_slcInfo = $sql_slcInfo->fetch_array();
              }else{
                $row_slcInfo['filantropica']="Consolidado";
                $row_slcInfo['escuela']="Todos";
              }
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
                    <th colspan="2">ACTIVO</th>
                    <th width="60px"></th>
                    <th colspan="2">PASIVO</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="font-weight: bold;">Activo corriente</td>
                    <td></td>
                    <td></td>
                    <td style="font-weight: bold;">Pasivo corriente</td>
                    <td></td>
                  </tr>
    <?php
      while ($row_balance = $res_balance->fetch_array()) {
        $concepto_activo=""; $activo=""; $concepto_pasivo=""; $pasivo="";
        $ca = $row_balance['concepto_activo']; $cp = $row_balance['concepto_pasivo'];
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
        }
    ?>
              <tr>
                <td <?php echo $concepto_activo ?>><?php echo $row_balance['concepto_activo'] ?></td>
                <td id="activo"  style="text-align: right;"  <?php echo $activo ?>><?php echo number_format($row_balance['activo'], 2, '.', ',' ) ?></td>
                <td></td>
                <td <?php echo $concepto_pasivo ?>><?php echo $row_balance['concepto_pasivo'] ?></td>
                <td id="pasivo"  style="text-align: right;"  <?php echo $pasivo ?>><?php echo number_format($row_balance['pasivo'], 2, '.', ',' ) ?></td>
              </tr>
    <?php
      }//Cierra while de 
    ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <?php
    }//Cierra if de num_valance
  }//Cierra function de balance general
?>