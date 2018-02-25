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

if (isset($_POST['h1']))
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
   }else{
    echo '<option selected disabled value="">No existen zonas con reportes</option>';
  }

}
/* cuando z1 sea igual a todos me va a selecionar si quiero mostrar todos los balances estados de resultados y todo el rollo */
if (isset($_POST['t1']))
{
  require("../../conex/connect.php");
  $zona =  $_SESSION["zona"];
  $tipo = $_POST["t1"];
  $mes = $_POST["t2"];
  $anio = $_POST["t3"];
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
  }
/*si no los va a mostrar individuales, por escuela por estado de resultados etc*/
  else{
            if ($tipo == "Estado de resultados") {
             $selEsc = "SELECT e.`id_escuela`,e.`nombre` FROM `escuela` e INNER JOIN `edo_res` er ON e.`id_escuela` = er.`id_escuela` AND e.`status` = 1 AND e.`id_zona` = $zona AND er.`id_mes` = $mes";
            }elseif($tipo == "Balance general"){
               $selEsc = "SELECT e.`id_escuela`,e.`nombre` FROM `escuela` e INNER JOIN `balance` b ON e.`id_escuela` = b.`id_escuela` AND e.`status` = 1 AND e.`id_zona` = $zona AND b.`id_mes` = $mes";
            }elseif($tipo == "Presupuesto"){
               $selEsc = "SELECT e.`id_escuela`,e.`nombre` FROM `escuela` e INNER JOIN `presupuesto` b ON e.`id_escuela` = b.`id_escuela` AND e.`status` = 1 AND e.`id_zona` = $zona AND b.`id_ejercicio` = $anio";
            }
        $resul_selEsc = $mysqli->query($selEsc);
        $filas = $resul_selEsc->num_rows;

        if($filas > 0){
          echo '<option value="">Selecciona una opción</option>';
          echo '<option value="todos">Consolidado</option>';
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
  $zona =$_SESSION["zona"];
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
              presupuesto($obt_presupuesto,$escuela);
     }

}



/* ############################################## R-E-P-O-R-T-E-S ##############################################*/


/*Funcion de estados de resultados*/
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
              </tr>s
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