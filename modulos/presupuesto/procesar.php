<?php
session_start();

$atras = "../../";
include($atras."conex/connect.php");

	if (substr($_FILES['excel']['name'],-3)=="csv")
	{ 
		$fecha		= date("Y-m-d");
		$carpeta 	= "../carga_archivos/tmp_presupuesto/";
		$excel  	= $fecha."-".$_FILES['excel']['name'];

		move_uploaded_file($_FILES['excel']['tmp_name'], "$carpeta$excel");
		
		$file_data = file_get_contents('../carga_archivos/tmp_presupuesto/'.$excel);	
		$utf8_file_data = utf8_encode($file_data);
		$new_file_name = '../carga_archivos/tmp_presupuesto/'.$excel;
		file_put_contents($new_file_name , $utf8_file_data );

		$buscar = "SELECT `id_usuario` FROM `presupuesto_detalle_temp` WHERE `id_usuario` =$_SESSION[id_usuarios] AND `id_mes`=$_SESSION[id_mes]";
		$sql_buscar = $mysqli->query($buscar);
		$num_buscar = $sql_buscar->num_rows;
		if ($num_buscar > 0) {
			$borrarBal = "DELETE FROM `presupuesto_detalle_temp` WHERE `id_usuario` =$_SESSION[id_usuarios] AND `id_mes`=$_SESSION[id_mes]";
			$sql_borrarBal = $mysqli->query($borrarBal);
		}

		$fp = fopen ("$new_file_name","r");

    $insertar="LOAD DATA LOCAL INFILE '$new_file_name' 
     INTO TABLE presupuesto_detalle_temp 
     FIELDS 
     	TERMINATED BY ';' 
     	ESCAPED BY ','
     	ENCLOSED BY '' 
     LINES 
     	TERMINATED BY '\r'
     IGNORE 1 LINES
     (`concepto_presupuesto`, `anual`, `mensual`)
      set id_presupuesto_detalle_temp='',`id_usuario`=$_SESSION[id_usuarios],`id_mes`=$_SESSION[id_mes]";
		$sql = $mysqli->query($insertar);
		$borrar = "DELETE FROM `presupuesto_detalle_temp` WHERE `concepto_presupuesto` = '\n'";

		//$borrar = "DELETE FROM `edo_resultados` WHERE `concepto` = ';' OR `concepto` = '\n' OR `concepto` = 'Otros Ingresos' OR `concepto` = 'EGRESOS' OR `concepto` = 'Gastos de Administración y Generales' OR `concepto` = 'Gastos por Sueldos y Prestaciones'";
		$sql_borrar = $mysqli->query($borrar);

		if ($mysqli->error) {
				echo "<script>if(confirm('esla consulta de eliminar where concepto_presupuesto')){ 
				document.location='/modulos/presupuesto/';}
				else{ alert('Operacion Cancelada'); 
				}</script>";
			}
		fclose ($fp);
		header("location: /modulos/presupuesto/");
		//echo "<div>La importacion de archivo subio satisfactoriamente</div >";
		
		exit;

	}elseif (isset($_GET["subir"])) {
		$selXZona = "SELECT `id_zona` FROM `escuela` WHERE `id_escuela` = $_SESSION[id_superior]";//Busca la zona a la pertenece la escuela
		$sql_selXZona = $mysqli->query($selXZona);
		$row_selXZona = $sql_selXZona->fetch_array();
		  $insBalance = "INSERT INTO `presupuesto`(`id_ejercicio`, `id_zona`, `id_escuela`, `id_mes`, `fecha`) VALUES ($_SESSION[id_ejercicio], $row_selXZona[id_zona] ,$_SESSION[id_superior], $_SESSION[id_mes], now())";
		$sql_insBalance = $mysqli->query($insBalance);

		$Balance = "SELECT `id_presupuesto` FROM `presupuesto` WHERE `id_escuela` = $_SESSION[id_superior] AND `id_mes` = $_SESSION[id_mes]";
		$sql_Balance = $mysqli->query($Balance);
		$row_selbalance = $sql_Balance->fetch_array();
		$id_balance = $row_selbalance['id_presupuesto'];

		$insEduRes = "INSERT INTO `presupuesto_detalle`(`concepto_presupuesto`, `anual`, `mensual`,  `id_presupuesto`) SELECT `concepto_presupuesto`, `anual`, `mensual` , $id_balance FROM `presupuesto_detalle_temp` WHERE `id_usuario` =$_SESSION[id_usuarios] AND `id_mes`=$_SESSION[id_mes]";
		$sql_insEduRes = $mysqli->query($insEduRes);

		if ($mysqli->error) {
				echo "<script>if(confirm('Algunos datos no se pudieron guardar es donde inserta en presupuesto detalle ')){ 
				document.location='/modulos/presupuesto/';}
				else{ alert('Operacion Cancelada'); 
				}</script>";
			}else{
				$borrar = "DELETE FROM `presupuesto_detalle_temp` WHERE `id_usuario` =$_SESSION[id_usuarios] AND `id_mes`=$_SESSION[id_mes]";
				$sql_borrar = $mysqli->query($borrar);
				header("location: /modulos/presupuesto/");
			}
	}

?>