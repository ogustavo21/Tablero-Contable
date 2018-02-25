<?php 
$atras = "../../";
  include($atras.'template/todo.php');
?>
<style type="text/css">
th{
  text-align: center;
}
#combo{
 
 
 
 
  border-width:0;
}

</style>
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if ($_SESSION["tipo_usuario"] == 'Contador de Escuela'){
     /* echo $_SESSION[id_usuarios].'usuarios';
      echo $_SESSION[id_ejercicio].'ejercicio';
        echo $_SESSION[id_superior].'idsuperior';
         echo $_SESSION[id_mes].'mes'; */


         $sql2 = "SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Fondo de Caja Chica%' AND balance.id_escuela=$_SESSION[id_superior] AND balance.id_ejercicio=$_SESSION[id_ejercicio] and balance.id_mes=$_SESSION[id_mes] union SELECT   activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Caja Otros Ingresos%' AND balance.id_escuela=$_SESSION[id_superior] AND balance.id_ejercicio=$_SESSION[id_ejercicio] and balance.id_mes=$_SESSION[id_mes] UNION SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Bancos%' AND balance.id_escuela=$_SESSION[id_superior] AND balance.id_ejercicio=$_SESSION[id_ejercicio] and balance.id_mes=$_SESSION[id_mes]";
                           $exis_datos = $mysqli->query($sql2);
                             $num_exdatos =  $exis_datos->num_rows;
                             if($num_exdatos>0 ) {
                            


    ?>        
          <div class="box box-primary">
             
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
                <thead >
                  <tr><th colspan="5"><?php echo $row_slcInfo['filantropica'] ?></th></tr>
                  <tr><th colspan="5"><?php echo $row_slcInfo['escuela'] ?></th></tr>
                  <tr><th colspan="5"> Informe Financiero Comparativo de Capital Operativo y Liquidez</th></tr>
                  <tr style="border-bottom: medium double black;"><th colspan="5"><?php echo "Al ". strftime('%d de %B de %Y', strtotime($row_slcFecha['f_final'])) ?></th></tr>
                   
                </thead> 



                           <?php
                                          

                          $sql1 = "SELECT concepto_activo, SUM(activo) as tactivo   from balance_detalle INNER JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE '%Total de Activo Corriente%' AND balance.id_escuela=$_SESSION[id_superior] AND balance.id_ejercicio=$_SESSION[id_ejercicio] and balance.id_mes=$_SESSION[id_mes]";
                           $resultadoestado1= $mysqli->query($sql1);
                               $arrayresult1 = $resultadoestado1->fetch_array();
                               $arrayresult1['concepto_activo'];
                             $arrayresult1['tactivo'];

                              
                          $sql2 = "SELECT concepto_pasivo, SUM(pasivo) as tpasivo   from balance_detalle INNER JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_pasivo  LIKE '%Total de Pasivo Corriente%' AND balance.id_escuela=$_SESSION[id_superior] AND balance.id_ejercicio=$_SESSION[id_ejercicio] and balance.id_mes=$_SESSION[id_mes]";
                          $resultadoestado2= $mysqli->query($sql2);
                               $arrayresult2 = $resultadoestado2->fetch_array();
                             $arrayresult2['concepto_pasivo'];
                              $arrayresult2['tpasivo'];
                              $tot=  $arrayresult2['tpasivo']- $arrayresult1['tactivo'];


                        //suma de fondocajachica, caja otros ingresos, bancos
                              $cajabancos = "SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Fondo de Caja Chica%' AND balance.id_escuela=$_SESSION[id_superior] AND balance.id_ejercicio=$_SESSION[id_ejercicio] and balance.id_mes=$_SESSION[id_mes] union SELECT   activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Caja Otros Ingresos%' AND balance.id_escuela=$_SESSION[id_superior] AND balance.id_ejercicio=$_SESSION[id_ejercicio] and balance.id_mes=$_SESSION[id_mes] UNION SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Bancos%' AND balance.id_escuela=$_SESSION[id_superior] AND balance.id_ejercicio=$_SESSION[id_ejercicio] and balance.id_mes=$_SESSION[id_mes]";

                                  $cajabancos1= $mysqli->query($cajabancos);
                                    while ( $arrayresult3 = $cajabancos1->fetch_row()) {
                                     $total+=$arrayresult3[0];
                                      } 


                             

                          $inversion = "SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Inversiones%' AND balance.id_escuela=$_SESSION[id_superior] AND balance.id_ejercicio=$_SESSION[id_ejercicio] and balance.id_mes=$_SESSION[id_mes]";
                          $resultadoestado4= $mysqli->query($inversion);
                          $total1 = 0; 
                              while ($arrayresult4 = $resultadoestado4->fetch_row()) {
                              $total1 = $total1 +  $arrayresult4[0];
                                }

                                $pasivo_cor = "SELECT pasivo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_pasivo  LIKE'%Total de Pasivo Corriente%' AND balance.id_escuela=$_SESSION[id_superior] AND balance.id_ejercicio=$_SESSION[id_ejercicio] and balance.id_mes=$_SESSION[id_mes]";
                          $resultadoestado5= $mysqli->query($pasivo_cor);
                             $pasivo_cor = 0.0; 
                              while ($arrayresult5 = $resultadoestado5->fetch_row()) {
                             $pasivo_cor = $pasivo_cor +  $arrayresult5[0];
                                }
                                
                                // Total Ingresos Educativos Netos
                                 $totingresoseducativosnetos= "SELECT  reala  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Total Ingresos Educativos Netos%'  AND edo_res.id_escuela=$_SESSION[id_superior] AND edo_res.id_mes=$_SESSION[id_mes] and edo_res.id_ejercicio=$_SESSION[id_ejercicio]";
                           $toingresedunetos= $mysqli->query($totingresoseducativosnetos);
                               $tingedunetos = $toingresedunetos->fetch_array();

                               // Subsidios y Otros Ingresos
                                 $subsioneto= "SELECT  reala  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Total de Otros Ingresos%'  AND edo_res.id_escuela=$_SESSION[id_superior] AND edo_res.id_mes=$_SESSION[id_mes] and edo_res.id_ejercicio=$_SESSION[id_ejercicio]";
                           $subsi= $mysqli->query($subsioneto);
                               $totsubneto = $subsi->fetch_array();
                              
                              // Egresos Operativos
                                 $egropera= "SELECT  reala  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%GRAN TOTAL DE GASTOS%'  AND edo_res.id_escuela=$_SESSION[id_superior] AND edo_res.id_mes=$_SESSION[id_mes] and edo_res.id_ejercicio=$_SESSION[id_ejercicio]";
                           $egreopera= $mysqli->query($egropera);
                               $egreopera = $egreopera->fetch_array();
                              

                              //Colegiaturas Mensuales por Cobrar
                                 $colemen= "SELECT  realm  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Enseñanza%'  AND edo_res.id_escuela=$_SESSION[id_superior] AND edo_res.id_mes=$_SESSION[id_mes] and edo_res.id_ejercicio=$_SESSION[id_ejercicio]";
                           $querycolemen= $mysqli->query($colemen);
                               $arraycolemen = $querycolemen->fetch_array();

                               //Becas Mensuales Otorgadas
                                 $becamensualotorga= "SELECT realm from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Becas y Descuentos%'  AND edo_res.id_escuela=$_SESSION[id_superior] AND edo_res.id_mes=$_SESSION[id_mes] and edo_res.id_ejercicio=$_SESSION[id_ejercicio] UNION  SELECT  realm  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Ingresos por Colegiaturas no Recibidos%'  AND edo_res.id_escuela=$_SESSION[id_superior] AND edo_res.id_mes=$_SESSION[id_mes] and edo_res.id_ejercicio=$_SESSION[id_ejercicio]";
                                    $becamensualotorga1= $mysqli->query($becamensualotorga);
                                    while ( $arraybmotorgadas = $becamensualotorga1->fetch_row()) {
                                    $acumbecamensualotorga+=$arraybmotorgadas[0];
                                      } 

                             

                            //Saldo Inicial en Cuenta de Clientes
                           $salinicuenclient= "SELECT activo from balance_detalle JOIN balance ON balance_detalle.id_balance=balance.id_balance  WHERE   concepto_activo  LIKE'%Cuentas por Cobrar Activas%' AND balance.id_escuela=$_SESSION[id_superior] AND balance.id_ejercicio=$_SESSION[id_ejercicio] and balance.id_mes=$_SESSION[id_mes]";
                           $querysalinicuenclient= $mysqli->query($salinicuenclient);
                           $arraycsinicialcuenclient = $querysalinicuenclient->fetch_array();



                            //Saldo Final en cuenta de Clientes solo falta igualarle a la variable para no tener que registrar abajo
                           $salfinacuenclient= "SELECT  realm  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Enseñanza%'  AND edo_res.id_escuela=$_SESSION[id_superior] AND edo_res.id_mes=$_SESSION[id_mes] and edo_res.id_ejercicio=$_SESSION[id_ejercicio]";
                           $quersalfinacuenclient= $mysqli->query($salfinacuenclient);
                           $arrayquersalfinacuenclient = $quersalfinacuenclient->fetch_array();






                            //Colegiaturas Presupuestadas Anualmente
                           $colepresuanual= "SELECT anual from presupuesto_detalle INNER JOIN presupuesto ON presupuesto_detalle.id_presupuesto=presupuesto.id_presupuesto  WHERE concepto_presupuesto  LIKE'%ENSEÑANZA%' AND  presupuesto.id_escuela=$_SESSION[id_superior] AND presupuesto.id_ejercicio=$_SESSION[id_ejercicio] and presupuesto.id_mes=$_SESSION[id_mes]";
                           $quercolepresuanual= $mysqli->query($colepresuanual);
                           $arrayquercolepresuanual= $quercolepresuanual->fetch_array();


                             //Becas Presupuestadas Anualmente
                           $bepresuanual= "SELECT anual from presupuesto_detalle INNER JOIN presupuesto ON presupuesto_detalle.id_presupuesto=presupuesto.id_presupuesto  WHERE concepto_presupuesto  LIKE'%MENOS: BECAS Y DESCUENTOS%' AND  presupuesto.id_escuela=$_SESSION[id_superior] AND presupuesto.id_ejercicio=$_SESSION[id_ejercicio] and presupuesto.id_mes=$_SESSION[id_mes] UNION SELECT anual from presupuesto_detalle INNER JOIN presupuesto ON presupuesto_detalle.id_presupuesto=presupuesto.id_presupuesto  WHERE concepto_presupuesto  LIKE'%MENOS: INGRESOS COLEG NO RECIB%' AND  presupuesto.id_escuela=$_SESSION[id_superior] AND presupuesto.id_ejercicio=$_SESSION[id_ejercicio] and presupuesto.id_mes=$_SESSION[id_mes]";
                            $qubepreanual= $mysqli->query($bepresuanual);
                           while ( $arrayqubepreanual = $qubepreanual->fetch_row()) {
                           $becaspresupuestadasanualmente+=$arrayqubepreanual[0];
                             } 

                             //Colegiaturas Cobradas en el Período falta
                                $colecobraperio= "SELECT  reala  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Enseñanza%'  AND edo_res.id_escuela=$_SESSION[id_superior] AND edo_res.id_mes=$_SESSION[id_mes] and edo_res.id_ejercicio=$_SESSION[id_ejercicio]";
                           $quercolecobraperio= $mysqli->query($colecobraperio);
                           $arrayquercolecobraperio= $quercolecobraperio->fetch_array();

                           
                              //Becas otorgadas en el Período
                                $beotorperio= "SELECT reala from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Becas y Descuentos%'  AND edo_res.id_escuela=$_SESSION[id_superior] AND edo_res.id_mes=$_SESSION[id_mes] and edo_res.id_ejercicio=$_SESSION[id_ejercicio] UNION  SELECT  realm  from edo_res_detalle INNER JOIN edo_res ON edo_res_detalle.id_edo_res=edo_res.id_edo_res WHERE   concepto LIKE '%Ingresos por Colegiaturas no Recibidos%'  AND edo_res.id_escuela=$_SESSION[id_superior] AND edo_res.id_mes=$_SESSION[id_mes] and edo_res.id_ejercicio=$_SESSION[id_ejercicio]";
                          $querbeotorperio= $mysqli->query($beotorperio);
                           while ( $arrayqubepreanual = $querbeotorperio->fetch_row()) {
                           $arrayquerbeotorperio+=$arrayqubepreanual[0];
                             }


                           ?>


                           <script type="text/javascript">
               
               function sumar(){
                     /*CONSULTA CORRECTA activo corriente*/
                    tacor=document.registro.t_acorriente.value;
                   var  obttacor="<?php echo   $arrayresult1['tactivo']?>";
                  tact= ( (parseFloat(obttacor)-parseFloat(tacor)) /parseFloat(obttacor) )*100;  
                  document.registro.txttacorriente.value=tact.toFixed(2);

                    /*CONSULTA CORRECTA pasivo corriente*/
                     pcor=document.registro.t_pcorriente.value;
                      var  obttpcor="<?php echo   $arrayresult2['tpasivo']?>";
                  tpas=(parseFloat(obttpcor)-parseFloat(pcor))/parseFloat(obttpcor)*100;  
                  document.registro.txttpcorriente.value=tpas.toFixed(2);

                   /* Total capital Operativo anio anterior*/
                   totalco=parseFloat(tacor)+parseFloat(pcor);
                   document.registro.totalcoperativo.value=totalco.toFixed(2);
                  var  totporcentaje="<?php echo $tot; ?>";
                   portotalco=(parseFloat(totporcentaje)-parseFloat(totalco))/parseFloat(totporcentaje)*100;  
                   document.registro.porctotcapop.value=portotalco.toFixed(2);
                   

                   /*Egresos operativos anio anter y actual*/
                    eoa=document.registro.e_operativosanual.value;                   
                    eom=document.registro.e_operativosmensual.value;
                    tteoa=(parseFloat(eoa)-parseInt(eom))/parseFloat(eoa)*100; 
                    document.registro.hola.value=tteoa.toFixed(2);

                    /*Porcentaje aplicado */
                     pa=document.registro.p_aplicado.value;
                      var  paplicadoanual=(parseFloat(eoa)*0.15);
                  paplicadoo=(parseFloat(paplicadoanual)-parseFloat(pa))/parseFloat(paplicadoanual)*100; 
                  document.registro.txtaplicado.value= paplicadoo.toFixed(2);
                  document.registro.totalaplicadoanual.value= paplicadoanual.toFixed(2);

                  /* Fondo asigando anual y mensual*/
                    faa=document.registro.f_asignadoanual.value;
                    fam=document.registro.f_asignadomensual.value;
                    ttfa=(parseFloat(faa)-parseInt(fam))/parseFloat(faa)*100; 
                    document.registro.totalfasignadomensualanual.value=ttfa.toFixed(2);


                    /* Total capital operativo reco anual y mensual*/
                    var totcoprecomendadot=(parseFloat(paplicadoanual)+parseFloat(faa));  
                    document.registro.totalcoprecomendadoanualact.value= totcoprecomendadot.toFixed(2);
                     var totcoprecomendadoant=(parseFloat(pa)+parseFloat(fam));  
                    document.registro.totalcoprecomendadoant.value= totcoprecomendadoant.toFixed(2);
                    var porcoper=(parseFloat(totcoprecomendadot)-parseFloat(totcoprecomendadoant))/parseFloat(totcoprecomendadot)*100; 
                    document.registro.porcecoprecomendadoanualact.value= porcoper.toFixed(2);

                    /* Total SUPERAVIT O (DEFICIT) CAPITAL OPERATIVO*/
                    var totcapitalanoactual="<?php echo $tot; ?>";
                    var  deficitoperativoact=(parseFloat(totcapitalanoactual)-parseFloat(totcoprecomendadot));  
                    document.registro.superavitdeficitoperativoact.value=  deficitoperativoact.toFixed(2);
                    var deficitoperativoant=(parseFloat(totalco)-parseFloat(totcoprecomendadoant));  
                    document.registro.superavitdeficitoperativoant.value= deficitoperativoant.toFixed(2);
                    var porcoper=(parseFloat(deficitoperativoact)-parseFloat(deficitoperativoant))/parseFloat(deficitoperativoact)*100; 
                    document.registro.porcesuperavitdeficitoperativoant.value= porcoper.toFixed(2);

                    /* % CAP. OPERATIVO EN RELACIÓN AL RECOMENDADO*/
                    var totcapitalanoactual="<?php echo $tot; ?>";
                    var  deficitoperativoact=(parseFloat(totcapitalanoactual)/parseFloat(totcoprecomendadot));  
                    document.registro.capoperativorecomendadoact.value=  deficitoperativoact.toFixed(2);
                    var deficitoperativoant=(parseFloat(totalco)/parseFloat(totcoprecomendadoant));  
                    document.registro.capoperativorecomendadoant.value= deficitoperativoant.toFixed(2);
                     var porcoperrela=(parseFloat(deficitoperativoact)-parseFloat(deficitoperativoant))/parseFloat(deficitoperativoact)*100;  
                    document.registro.porcecapoperativorecomendadoant.value= porcoperrela.toFixed(2);
                    

                    /* cajabancos*/                    
                    cb=document.registro.caja_bancos.value;
                     var  cajabancoscaja="<?php echo $total; ?>";
                     tpas=(parseFloat(obttpcor)-parseFloat(pcor))/parseFloat(obttpcor)*100; 

                      /* Inversiones*/ 
                    i=document.registro.inversiones.value;

                     /* Total de ACTIVO LIQUIDO*/  
                      var activoliquido=(parseFloat(cb)+parseFloat(i));  
                    document.registro.txttactivoliquidoant.value= activoliquido.toFixed(2);
                     var  totactliqui="<?php echo $total2=$total+$Total;  ?>";
                    var poractivoliquido=(parseFloat(totactliqui)-parseFloat(activoliquido))/parseFloat(totactliqui)*100; 
                    document.registro.txttactivoliquido.value= poractivoliquido.toFixed(2);


                     /* Pasivo Corriente*/  
                    pc=document.registro.p_corriente.value;
                     var txtlcorriente=(parseFloat(obttpcor)-parseFloat(pc))/parseFloat(obttpcor); 
                    document.registro.txtliquidezpcorriente.value= txtlcorriente.toFixed(2);


                    /* Fondos Asignados Brutos*/  
                    fab=document.registro.f_asignadosbrutos.value;
                     faab=(parseFloat(faa));
                      document.registro.totalfasignadobruto.value= faab.toFixed(2);
                      var totfas=(parseFloat(faab)-parseFloat(fab))/parseFloat(faab)*100; 
                      document.registro.txtfasignadobrutoant.value= totfas.toFixed(2);

                       /* Total de PASIVO  Y REMANENTE DEL EJERC.*/  
                       var totalpasivor=(parseFloat(obttpcor)+parseFloat(faab));  
                    document.registro.totalpasivoremanenteant.value= totalpasivor.toFixed(2);
                     var totalpasivoact=(parseFloat(pc)+parseFloat(fab));  
                    document.registro.totalpasivoremanenteact.value= totalpasivoact.toFixed(2);
                    var pasivoremanenteporc=(parseFloat(totalpasivoact)-parseFloat(totalpasivor))/parseFloat(totalpasivoact)*100; 
                    document.registro.totalpasivoremanenteporc.value= pasivoremanenteporc.toFixed(2);
   
                    /* Total activo liquido neto  exit*/  
                        var  totactliqnean="<?php  echo $total2=$total+$Total;  ?>";                
                       var totactliqneant=(parseFloat(totactliqnean)-parseFloat(totalpasivor));  
                    document.registro.totactliquinetoant.value= totactliqneant.toFixed(2);
                     var totactliqneact=(parseFloat(totalpasivoact)-parseFloat(activoliquido));  
                    document.registro.totactliquinetoact.value= totactliqneact.toFixed(2);
                    var totactliqneporc=(parseFloat(totactliqneant)-parseFloat(totactliqneact))/parseFloat(totactliqneant)*100; 
                    document.registro.totactliquinetoporc.value= totactliqneporc.toFixed(2);               
                    
                     /* % liquidez*/                     
                       var liquidezactual=(parseFloat(totactliqnean)/parseFloat(totalpasivor));  
                    document.registro.liquidezact.value= liquidezactual.toFixed(2);
                    /**/var liquidezanterior=(parseFloat(totalpasivoact)/parseFloat(activoliquido));  
                    document.registro.liquidezant.value= liquidezanterior.toFixed(2);
                      var liquidezporcentaje=(parseFloat(liquidezactual)-parseFloat(liquidezanterior))/parseFloat(liquidezactual)*100; 
                    document.registro.liquidezporc.value= liquidezporcentaje.toFixed(2);

                    /* Rentabilidad y Sosten Propio*/ 
                    /*Ingreso Operativo*/
                    var  totoperativoporc="<?php  echo $tingedunetos['reala'];?>";
                    ingopera=document.registro.ingreso_operativo.value;
                    var operativoporc=(parseFloat(totoperativoporc)-parseFloat(ingopera))/parseFloat(totoperativoporc)*100; 
                    document.registro.ingreso_operativoporc.value= operativoporc.toFixed(2);
                    

                     /* subcidios_ingresos*/  
                    si=document.registro.subcidios_ingresos.value;
                      var  totsubingres="<?php echo $totsubneto['reala'];?>";
                      var subingres=(parseFloat(totsubingres)-parseFloat(si))/parseFloat(totsubingres)*100; 
                       
                    
                     /*Total de ingresos final*/
                     var totingact=(parseFloat(totoperativoporc) + parseFloat(totsubingres));  
                    document.registro.totaldeingreact.value= totingact.toFixed(2);

                    var totingant=(parseFloat(ingopera)+parseFloat(si)); 
                    document.registro.totaldeingreant.value= totingant.toFixed(2);

                    var deingreporc=(parseFloat(totingact)-parseFloat(totingant))/parseFloat(totingact)*100; 
                    document.registro.totaldeingreporc.value= deingreporc.toFixed(2);
 
                     /*% sosten propio*/
                     var porsosproact=(parseFloat(totoperativoporc)/parseFloat(totingact))*100;
                    document.registro.sostenpropioact.value= porsosproact.toFixed(2);
                    var porsosproant=(parseFloat(ingopera)/parseFloat(totingant))*100;
                    document.registro.sostenpropioant.value= porsosproant.toFixed(2);
                     var totsostenpropioporc=(parseFloat(porsosproact)-parseFloat(porsosproant))/parseFloat(porsosproact)*100; 
                    document.registro.sostenpropioporc.value= totsostenpropioporc.toFixed(2);
                     

                     /*Egresos Operativos.*/
                      var  egresos_operativoactual="<?php  echo $egreopera['reala'];  ?>";
                   eo=document.registro.egresos_operativo.value;
                    var egresosoperativoporc=(parseFloat(egresos_operativoactual)-parseFloat(eo))/parseFloat(egresos_operativoactual)*100; 
                    document.registro.egresos_operativoporc.value=egresosoperativoporc.toFixed(2);

                     /*Total de EGRESOS final*/
                    var totaldeegreactu=(parseFloat(egresos_operativoactual));  
                    document.registro.totaldeegreact.value= totaldeegreactu.toFixed(2);
                    var totaldeegreante=(parseFloat(eo)); 
                    document.registro.totaldeegreant.value= totaldeegreante.toFixed(2);
                    var  egreporc=(parseFloat(egresos_operativoactual)-parseFloat(eo))/parseFloat(egresos_operativoactual)*100; 
                    document.registro.totaldeegreporc.value= egreporc.toFixed(2);

                  /*UTILIDAD O (PÉRDIDA) OPERATIVA final*/
                    var totalutiperoperaact=(parseFloat(totingact)-parseFloat(totaldeegreactu)); 
                        document.registro.utiperoperaact.value= totalutiperoperaact.toFixed(2);
                        var totalutiperoperaant=(parseFloat(totingant)-parseFloat(totaldeegreante)); 
                       document.registro.utiperoperaant.value= totalutiperoperaant.toFixed(2);
                       totalutiperoperaporc=(parseFloat(totalutiperoperaact)-parseFloat(totalutiperoperaant))/parseFloat(totalutiperoperaact)*100; 
                       document.registro.utiperoperaporc.value= totalutiperoperaporc.toFixed(2);
                    
                    

                    /*% RENTABILIDAD EN RELACIÓN A UTILIDAD OPERATIVA final*/                
                    var totalrentreutiopeact=(parseFloat(totalutiperoperaact)/parseFloat(totingact)); 
                    document.registro.rentreutiopeact.value= totalrentreutiopeact.toFixed(2);

                      var totalrentreutiopeant=(parseFloat(totalutiperoperaant)/parseFloat(totingant)); 
                    document.registro.rentreutiopeant.value= totalrentreutiopeant.toFixed(2);
                     
                     /* ÍNDICE DE COBRANZA */ 
                    /*Colegiaturas Mensuales por Cobrar*/
                   var  cole_mcobraractual="<?php  echo $arraycolemen['realm'];  ?>";
                   cmc=document.registro.cole_mcobrar.value;
                    var cole_mcobrarporce=(parseFloat(cole_mcobraractual)-parseFloat(cmc))/parseFloat(cole_mcobraractual)*100; 
                    document.registro.cole_mcobrarporc.value=cole_mcobrarporce.toFixed(2);

                     /* Becas Mensuales Otorgadas*/                   
                     var  becas_motorgadasactual="<?php  echo $acumbecamensualotorga;  ?>";
                   bmo=document.registro.becas_motorgadas.value;
                    var becas_motorgadasporce=(parseFloat(becas_motorgadasactual)-parseFloat(bmo))/parseFloat(becas_motorgadasactual)*100; 
                    document.registro.becas_motorgadasporc.value=becas_motorgadasporce.toFixed(2);

                    /*Neto a cobrar Mensual*/
                     var totalnetoacobrarmensact=(parseFloat(cole_mcobraractual)-parseFloat(becas_motorgadasactual)); 
                        document.registro.netoacobrarmensact.value= totalnetoacobrarmensact.toFixed(2);
                        var totalnetoacobrarmensant=(parseFloat(cmc)-parseFloat(bmo)); 
                       document.registro.netoacobrarmensant.value= totalnetoacobrarmensant.toFixed(2);
                       totalnetoacobrarmensporc=(parseFloat(totalnetoacobrarmensact)-parseFloat(totalnetoacobrarmensant))/parseFloat(totalnetoacobrarmensact)*100; 
                       document.registro.netoacobrarmensporc.value= totalnetoacobrarmensporc.toFixed(2);
                    
                    /*Saldo Inicial en Cuenta de Clientes*/
                  var  totalsaldo_cclientesactual="<?php  echo $arraycsinicialcuenclient['activo'];  ?>";
                  scc=document.registro.saldo_cclientes.value;
                  var totalsaldo_cclientesporc=(parseFloat(totalsaldo_cclientesactual)-parseFloat(scc))/parseFloat(totalsaldo_cclientesactual)*100; 
                    document.registro.saldo_cclientesporc.value=totalsaldo_cclientesporc.toFixed(2);

                    /*masNeto a cobrar Mensual*/
                    var totalmasnetoacobrarmensact=(parseFloat(totalnetoacobrarmensact));  
                    document.registro.masnetoacobrarmensact.value= totalmasnetoacobrarmensact.toFixed(2);
                    var totalmasnetoacobrarmensant=(parseFloat(totalnetoacobrarmensant)); 
                    document.registro.masnetoacobrarmensant.value= totalmasnetoacobrarmensant.toFixed(2);
                    var  totmasnetoacobrarmensporc=(parseFloat(totalmasnetoacobrarmensact)-parseFloat(totalmasnetoacobrarmensant))/parseFloat(totalmasnetoacobrarmensact)*100; 
                    document.registro.masnetoacobrarmensporc.value= totmasnetoacobrarmensporc.toFixed(2);


                     /*Total Cartera a Recuperar en el Mes*/
                      var totaltotalcarremesact=(parseFloat(totalsaldo_cclientesactual)+parseFloat(totalmasnetoacobrarmensact)); 
                        document.registro.totalcarremesact.value= totaltotalcarremesact.toFixed(2);

                        var totaltotalcarremesant=(parseFloat(scc)+parseFloat(totalmasnetoacobrarmensant)); 
                       document.registro.totalcarremesant.value= totaltotalcarremesant.toFixed(2);
                       totaltotalcarremesporc=(parseFloat(totaltotalcarremesact)-parseFloat(totalmasnetoacobrarmensant))/parseFloat(totaltotalcarremesact)*100; 
                       document.registro.totalcarremesporc.value= totaltotalcarremesporc.toFixed(2); 
                     

                      /*Cobros Efectivamente Realizados */
                       cer=document.registro.cobros_erealizados.value; 

                       var  resultsaldocuentaclientes="<?php  echo $arrayquersalfinacuenclient['realm'];  ?>";
                        var totalcobros_erealizadosact=(parseFloat(totaltotalcarremesact) - parseFloat(resultsaldocuentaclientes)); 
                       document.registro.cobros_erealizadosact.value= totalcobros_erealizadosact.toFixed(2); 
                     
                    /*Saldo Final en cuenta de Clientes final*/
                    var  totsaldofinalcuentaclienteact="<?php  echo $arrayquersalfinacuenclient['realm'];  ?>";
                    var totalsaldocuentaclienteant=(parseFloat(totaltotalcarremesant)-parseFloat(cer)); 
                    document.registro.saldocuentaclienteant.value= totalsaldocuentaclienteant.toFixed(2);
                    totalsaldocuentaclienteporc =(parseFloat(totsaldofinalcuentaclienteact)-parseFloat(totalsaldocuentaclienteant))/parseFloat(totsaldofinalcuentaclienteact)*100; 
                    document.registro.saldocuentaclienteporc.value= totalsaldocuentaclienteporc.toFixed(2);
                     
                    

                    /*% DE EFICIENCIA EN LA COBRANZA final*/
                        var totaleficienciaact=(parseFloat(totaltotalcarremesact)/parseFloat(totalcobros_erealizadosact))*100; 
                        document.registro.eficienciaact.value= totaleficienciaact.toFixed(2);
                        var totaleficienciaant=(parseFloat(totaltotalcarremesant)/parseFloat(cer))*100; 
                       document.registro.eficienciaant.value= totaleficienciaant.toFixed(2);


                    /* RELACIÓN DE BECAS CON INGRESOS */ 
                    /*% Colegiaturas Presupuestadas Anualmente*/
                  var  totalcole_preanualactual="<?php  echo $arrayquercolepresuanual['anual'];  ?>";
                  cpa=document.registro.cole_preanual.value;
                  var totalcole_preanualporc=(parseFloat(totalcole_preanualactual)-parseFloat(cpa))/parseFloat(totalcole_preanualactual)*100; 
                  document.registro.cole_preanualporc.value=totalcole_preanualporc.toFixed(2);


                    /*Becas Presupuestadas Anualmente*/              
                     var  totalbecas_preanualactual="<?php  echo $becaspresupuestadasanualmente; ?>";
                     bpa=document.registro.becas_preanual.value;
                  var totalbecas_preanualporc=(parseFloat(totalbecas_preanualactual)-parseFloat(bpa))/parseFloat(totalbecas_preanualactual)*100; 
                  document.registro.becas_preanualporc.value=totalbecas_preanualporc.toFixed(2);

                    /*% de Becas en Base a Presupuesto*/
                    var totalbecasenbpresuact=(parseFloat(totalcole_preanualactual)/parseFloat(totalbecas_preanualactual)); 
                        document.registro.becasenbpresuact.value= totalbecasenbpresuact.toFixed(2);
                        var totalbecasenbpresuant=(parseFloat(cpa)/parseFloat(bpa)); 
                       document.registro.becasenbpresuant.value= totalbecasenbpresuant.toFixed(2);
                       totalbecasenbpresuporc=(parseFloat(totalbecasenbpresuact)-parseFloat(totalbecasenbpresuant))/parseFloat(totalbecasenbpresuact)*100; 
                       document.registro.becasenbpresuporc.value= totalbecasenbpresuporc.toFixed(2); 

                    /*Colegiaturas Cobradas en el Período*/
                    var  totalcole_cobrarperiodoactual="<?php  echo $arrayquercolecobraperio['reala'];  ?>";
                     ccp=document.registro.cole_cobrarperiodo.value;
                  var totalcole_cobrarperiodoporc=(parseFloat(totalcole_cobrarperiodoactual)-parseFloat(ccp))/parseFloat(totalcole_cobrarperiodoactual)*100; 
                  document.registro.cole_cobrarperiodoporc.value=totalcole_cobrarperiodoporc.toFixed(2);

                     /*Becas otorgadas en el Período*/
                     var  totalbecas_operiodoactual="<?php  echo $arrayquerbeotorperio;  ?>";
                    bop=document.registro.becas_operiodo.value;
                  var totalbecas_operiodoporc=(parseFloat(totalbecas_operiodoactual)-parseFloat(bop))/parseFloat(totalbecas_operiodoactual)*100; 
                  document.registro.becas_operiodoporc.value=totalbecas_operiodoporc.toFixed(2);

                    /*% de Becas en Realción a los Cobros del Período*/
                      var totalberelacoperiodoact=(parseFloat(totalcole_cobrarperiodoactual)/parseFloat(totalbecas_operiodoactual))*100; 
                        document.registro.berelacoperiodoact.value= totalberelacoperiodoact.toFixed(2);
                        var totalberelacoperiodoant=(parseFloat(ccp)-parseFloat(bop)); 
                       document.registro.berelacoperiodoant.value= totalberelacoperiodoant.toFixed(2);
                       totalberelacoperiodoporc=(parseFloat(totalberelacoperiodoact)-parseFloat(totalberelacoperiodoant))/parseFloat(totalberelacoperiodoact)*100; 
                       document.registro.berelacoperiodoporc.value= totalberelacoperiodoporc.toFixed(2);
                    
                                       
                  
                  }
             </script>
                 
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

              <tr> <td><h3>Liquidez<small> Acumulada</small></h3></td> </tr>
            
               <tr> 

                 <td><?php echo' <div class="col-lg-10" style="text-align: center" role="form"><label for="ejemplo_email_3" class="">Total Activo Corriente</label>';?> </td>
                 <td class="col-lg-1"   ><?php echo   $arrayresult1['tactivo']?></td>
                   <td class="col-lg-2" >   <div class="form-line jf-required"><input class="form-control input-sm" type="text"  id="t_acorriente" OnKeyup="sumar()" name="t_acorriente" type="text" placeholder="Total Activo Corriente" required></div></div></td>
                   <td class="col-lg-1" >  <input  name ="txttacorriente" id="combo" readonly="readonly"  ></> </td>
              </tr>
               <tr> 
                <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="t_pcorriente">Total Pasivo Corriente</label>';?></td>
                  <td class="col-lg-1"  ><?php echo  $arrayresult2['tpasivo']?></td>
                  <td class="col-lg-2"> <div class="form-line jf-required">
                  <input class="form-control input-sm" id="t_pcorriente" name="t_pcorriente" type="text" OnKeyup="sumar()" placeholder="Total Pasivo Corriente" required>
                  </div>
                </div></td>
                 <td class="col-lg-1" >  <input  name ="txttpcorriente" id="combo" readonly="readonly"  ></></td>
                 </tr>
                  
               <tr> 
                <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Total de CAPITAL OPERATIVO ACTUAL</label>';?></td>
                  <td class="col-lg-1"  ><?php  echo $tot; ?></td>
                  <td class="col-lg-2"> <div class="form-line jf-required">
                   <input  name ="totalcoperativo" id="combo" readonly="readonly"  ></>
                  </div>
                </div></td>
                <td class="col-lg-1" >  <input  name ="porctotcapop" id="combo" readonly="readonly"  ></></td>
                 </tr>

                  <tr> 
                <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  for="e_operativos">Egresos Operativos de 12 meses anteriores</label>';?></td>
                   <td class="col-lg-2"> <div class="form-line jf-required">
                  <input class="form-control input-sm" id=" e_operativosanual" name="e_operativosanual" type="text" OnKeyup="sumar()" placeholder="Egresos Operativos de 12 meses anteriores" required>
                  <span class="help-block">* Anual</span>
                  </div>
                  <td class="col-lg-2"> <div class="form-line jf-required">
                  <input class="form-control input-sm" id="e_operativosmensual" name="e_operativosmensual" type="text" OnKeyup="sumar()" placeholder="Egresos Operativos de 12 meses anteriores" required>
                  <span class="help-block">* Mensual</span>
                  </div>
                </div></td>
                <td class="col-lg-1" >  <input  name ="hola" id="combo" readonly="readonly"  ></></td>
                 </tr>


                 <tr> 
                <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class=""  for="p_aplicado">15% Aplicado</label>';?></td>
                 <td class="col-lg-1" >  <input  name ="totalaplicadoanual" id="combo" readonly="readonly"  ></></td>
                  <td class="col-lg-2"> <div class="form-line jf-required">
                  <input class="form-control input-sm" OnKeyup="sumar()" id="p_aplicado" name="p_aplicado" type="text" placeholder="15% Aplicado" required>
                  </div>
                </div></td>
                 <td class="col-lg-1" >  <input  name ="txtaplicado" id="combo" readonly="readonly"  ></></td>
                 </tr>

                   
                   <tr> 
                <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  for="f_asignado">Fondos Asignados</label>';?></td>
                   <td class="col-lg-2"> <div class="form-line jf-required">
                  <input class="form-control input-sm" OnKeyup="sumar()" id="f_asignadoanual" name="f_asignadoanual" type="text" placeholder="Fondos Asignados" required>
                  <span class="help-block">* Anual</span>
                  </div>
                  <td class="col-lg-2"> <div class="form-line jf-required">
                  <input class="form-control input-sm" OnKeyup="sumar()" id="f_asignadomensual" name="f_asignadomensual" type="text" placeholder="Fondos Asignados" required>
                  <span class="help-block">* Mensual</span>
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
                        <td class="col-lg-1"  ><?php  echo $total;?></td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()" id="caja_bancos" name="caja_bancos" type="text" placeholder="Caja, Bancos" required>
                        </div>
                      </div></td>
                       <td class="col-lg-1" >  <input  name ="txtcajabancos" id="combo" readonly="readonly"  ></></td>
                       </tr>


                          <tr> 
                      <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="inversiones">Inversiones</label>';?></td>
                        <td class="col-lg-1"  ><?php echo $total1; ?></td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()" id="inversiones" name="inversiones" type="text" placeholder="Inversiones" required>
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
                        <td class="col-lg-1"  ><?php echo  $arrayresult2['tpasivo']?></td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()" id="p_corriente" name="p_corriente" type="text" placeholder="Pasivo Corriente" required>
                        </div>
                      </div></td>
                       <td class="col-lg-1" >  <input  name ="txtliquidezpcorriente" id="combo" readonly="readonly"  ></></td>
                       </tr>

                        
                        <!-- meequede aqui-->
                        <tr> 
                      <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="f_asignadosbrutos">Fondos Asignados Brutos</label>';?></td>
                         <td class="col-lg-1" > <input  name ="totalfasignadobruto" id="combo" readonly="readonly"  > </td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()" id="f_asignadosbrutos" name="f_asignadosbrutos" type="text" placeholder="Fondos Asignados Brutos" required>
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
                   
                    <tr> <td><h3>Rentabilidad<small> y Sostén Propio</small></h3></td> </tr>

                    <tr> 
                      <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="ingreso_operativo">Ingreso Operativo</label>';?></td>

                        <td class="col-lg-1"  ><?php  echo $tingedunetos['reala']; ?></td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()" id="ingreso_operativo" name="ingreso_operativo" type="text" placeholder="Ingreso Operativo" required>
                        </div>
                      </div></td>
                       <td class="col-lg-1" >  <input  name ="ingreso_operativoporc" id="combo" readonly="readonly"  ></></td>
                       </tr>



                      <tr> 
                      <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="subcidios_ingresos">Subsidios y Otros Ingresos</label>';?></td>
                        <td class="col-lg-1"  ><?php  echo  $totsubneto['reala'];  ?></td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()" id="subcidios_ingresos" name="subcidios_ingresos" type="text" placeholder="Subsidios y Otros Ingresos" required>
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
                        <td class="col-lg-1"  ><?php echo $egreopera['reala']; ?></td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()" id="egresos_operativo" name="egresos_operativo" type="text" placeholder="Egresos Operativos" required>
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
   
                       <!a esta le modifique -!>
                        <tr> 
                      <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="egresosoperativos">% Rentabilidad en Relación a Utilidad Operativa</label>';?></td>
                        <td class="col-lg-1" >  <input  name ="rentreutiopeact" id="combo" readonly="readonly"  ></></td>
                        <td class="col-lg-2"> <div class="form-line jf-required"> <input  name ="rentreutiopeant" id="combo" readonly="readonly"  > 
                                                
                      </div></td>
                       <td class="col-lg-1" >  <input  name ="rentreutiopeporc" id="combo" readonly="readonly"  ></></td>
                       </tr>

               <tr> <td>  <legend></legend></td> </tr>
                <tr> <td><h3>Índice<small> de Cobranza</small></h3></td> </tr>

                      <tr> 
                      <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" class="" for="cole_mcobrar">Colegiaturas Mensuales por Cobrar</label>';?></td>
                        <td class="col-lg-1"  ><?php echo $arraycolemen['realm']; ?></td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()" id="cole_mcobrar" name="cole_mcobrar" type="text" placeholder="Colegiaturas Mensuales por Cobrar" required>
                        </div>
                      </div></td>
                       <td class="col-lg-1" >  <input  name ="cole_mcobrarporc" id="combo" readonly="readonly"  ></></td>
                       </tr>


                       <tr> 
                      <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" for="becas_motorgadas">Becas Mensuales Otorgadas</label>';?></td>
                        <td class="col-lg-1"  ><?php echo $acumbecamensualotorga; ?></td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()" id="becas_motorgadas" name="becas_motorgadas" type="text" placeholder="Becas Mensuales Otorgadas" required>
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
                        <td class="col-lg-1"  ><?php  echo $arraycsinicialcuenclient['activo']; ?></td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()" id="saldo_cclientes" name="saldo_cclientes" type="text" placeholder="Saldo Inicial en Cuenta de Clientes" required>
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
                        <input class="form-control input-sm" OnKeyup="sumar()" id="cobros_erealizados" name="cobros_erealizados" type="text" placeholder="Cobros Efectivamente Realizados" required>
                        </div>
                      </div></td>
                       <td class="col-lg-1" >  <input  name ="cobros_erealizadosporc" id="combo" readonly="readonly"  ></></td>
                       </tr>



                       <tr> 
                      <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  >Saldo Final en cuenta de Clientes</label>';?></td>
                            <td class="col-lg-1"  ><?php   echo $arrayquersalfinacuenclient['realm'] ?></td>
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
                   
                    <tr> <td><h3>Relación de Becas<small> con Ingresos</small></h3></td> </tr>

                     <tr> 
                      <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" for="cole_preanual">Colegiaturas Presupuestadas Anualmente</label>';?></td>
                        <td class="col-lg-1"  ><?php   echo $arrayquercolepresuanual['anual'];  ?></td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()" id="cole_preanual" name="cole_preanual" type="text" placeholder="Colegiaturas Presupuestadas Anualmente" required>
                        </div>
                      </div></td>
                       <td class="col-lg-1" >  <input  name ="cole_preanualporc" id="combo" readonly="readonly"  ></></td>
                       </tr>


                        <tr> 
                      <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3" for="becas_preanual">Becas Presupuestadas Anualmente</label>';?></td>
                        <td class="col-lg-1"  ><?php echo $becaspresupuestadasanualmente; ?></td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()" id="becas_preanual" name="becas_preanual" type="text" placeholder="Becas Presupuestadas Anualmente" required>
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
                        <td class="col-lg-1"  ><?php  echo $arrayquercolecobraperio['reala']; ?></td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()"  id="cole_cobrarperiodo" name="cole_cobrarperiodo" type="text" placeholder="Colegiaturas Cobradas en el Período" required>
                        </div>
                      </div></td>
                       <td class="col-lg-1" >  <input  name ="cole_cobrarperiodoporc" id="combo" readonly="readonly"  ></></td>
                       </tr>


                        <tr> 
                      <td><?php echo '<div class="col-lg-10" style="text-align: center"><label for="ejemplo_email_3"  for="becas_operiodo">Becas otorgadas en el Período</label>';?></td>
                        <td class="col-lg-1"  ><?php  echo $arrayquerbeotorperio; ?></td>
                        <td class="col-lg-2"> <div class="form-line jf-required">
                        <input class="form-control input-sm" OnKeyup="sumar()"  id="becas_operiodo" name="becas_operiodo" type="text" placeholder="Becas otorgadas en el Período" required>
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
 
             
                      <legend></legend>
 
                      <?php $obt_existLiq = "SELECT * FROM liquidez WHERE id_usuarios=$_SESSION[id_usuarios] AND id_ejercicio=$_SESSION[id_ejercicio] and id_escuela=$_SESSION[id_superior] and id_mes=$_SESSION[id_mes]";     
                          $res_boton = $mysqli->query($obt_existLiq);
                          $num_boton = $res_boton->num_rows;
                            if ($num_boton>0){
                               echo "<script> document.location='/modulos/liquidezacum/indexr.php'; </script>";
                               
                              /*
                               <div class="form-footer">
                                    <a href="indexr.php">  <button type="submit" class="btn btn-primary btn-lg">Modificar</button></a>
                                    </div>*/
                                     
                            } else{
                              
                              ?> 
                              <div class="form-footer">
                                      <button type="submit" class="btn btn-primary btn-lg">Guardar</button>
                                    </div>

                              <?php
                            }



                             }else{ ?>

                                <div style="position: center; top: 55px;right: 10px;">
                                <div class="alert alert-danger" role="alert">
                              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                              <strong>¡Error!</strong> Hace falta Información, Ingrese los datos correspondientes al mes actual de liquidez.
                                </div>
                                </div>
                             <?php

                             }

                                 
             ?>
                  
                      </fieldset>
              </form>

             
               
              </div>
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

<?php
if (isset($_POST["t_acorriente"])) {
    include "cls_liquidez.php";
    $t_acorriente = $_POST["t_acorriente"];
    $t_pcorriente= $_POST["t_pcorriente"];
    $e_operativosanual = $_POST["e_operativosanual"];
    $e_operativosmensual = $_POST["e_operativosmensual"];
    $p_aplicado= $_POST["p_aplicado"];
    $f_asignadoanual = $_POST["f_asignadoanual"];
    $f_asignadomensual = $_POST["f_asignadomensual"];
    $caja_bancos = $_POST["caja_bancos"];
    $inversiones = $_POST["inversiones"];
    $p_corriente= $_POST["p_corriente"];
    $f_asignadosbrutos= $_POST["f_asignadosbrutos"];
    $ingreso_operativo= $_POST["ingreso_operativo"];
    $subcidios_ingresos = $_POST["subcidios_ingresos"];
    $egresos_operativo = $_POST["egresos_operativo"];
        $cole_mcobrar = $_POST["cole_mcobrar"];
    $becas_motorgadas = $_POST["becas_motorgadas"];
    $saldo_cclientes = $_POST["saldo_cclientes"];
    $cobros_erealizados = $_POST["cobros_erealizados"];
    $cole_preanual = $_POST["cole_preanual"];
    $becas_preanual = $_POST["becas_preanual"];
    $cole_cobrarperiodo = $_POST["cole_cobrarperiodo"];
    $becas_operiodo = $_POST["becas_operiodo"];
    

     
    $clasLiquidez= new liquidez($t_acorriente,  $t_pcorriente,  $e_operativosanual,$e_operativosmensual, $p_aplicado, $f_asignadoanual,  $f_asignadomensual, $caja_bancos, $inversiones, $p_corriente, $f_asignadosbrutos, $ingreso_operativo, $subcidios_ingresos, $egresos_operativo,  $cole_mcobrar, $becas_motorgadas, $saldo_cclientes, $cobros_erealizados, $cole_preanual, $becas_preanual, $cole_cobrarperiodo, $becas_operiodo);
    $clasLiquidez->insertar();
}
?>
