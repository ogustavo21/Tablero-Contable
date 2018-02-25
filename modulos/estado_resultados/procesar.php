<?php
session_start();

$atras = "../../";
include($atras."conex/connect.php");

	if (substr($_FILES['excel']['name'],-3)=="csv")
	{ 
		$fecha		= date("Y-m-d");
		$carpeta 	= "../carga_archivos/tmp_excel/";
		$excel  	= $fecha."-".$_FILES['excel']['name'];

		move_uploaded_file($_FILES['excel']['tmp_name'], "$carpeta$excel");
		
		$file_data = file_get_contents('../carga_archivos/tmp_excel/'.$excel);	
		$utf8_file_data = utf8_encode($file_data);
		$new_file_name = '../carga_archivos/tmp_excel/'.$excel;
		file_put_contents($new_file_name , $utf8_file_data );

		$buscar = "SELECT `id_usuario` FROM `edo_res_detalle_temp` WHERE `id_usuario` =$_SESSION[id_usuarios] AND `id_mes`=$_SESSION[id_mes]";
		$sql_buscar = $mysqli->query($buscar);
		$num_buscar = $sql_buscar->num_rows;
		if ($num_buscar > 0) {
			$borrarRes = "DELETE FROM `edo_res_detalle_temp` WHERE `id_usuario` =$_SESSION[id_usuarios] AND `id_mes`=$_SESSION[id_mes]";
			$sql_borrarRes = $mysqli->query($borrarRes);
		}

		$fp = fopen ("$new_file_name","r");

    $insertar="LOAD DATA LOCAL INFILE '$new_file_name' 
     INTO TABLE edo_res_detalle_temp 
     FIELDS 
     	TERMINATED BY ';' 
     	ESCAPED BY ','
     	ESCAPED BY '\n'
     	ENCLOSED BY ''
     LINES 
     	TERMINATED BY '\r'
     IGNORE 10 LINES
     (concepto,presupuestom,realm,variacionm,presupuestoa,reala,variaciona,id_edo_resultados)
      set id_edo_resultados='',`id_usuario`=$_SESSION[id_usuarios],`id_mes`=$_SESSION[id_mes]";
		$sql = $mysqli->query($insertar);
		$borrar = "DELETE FROM `edo_res_detalle_temp` WHERE `concepto` = '\n'";

		//$borrar = "DELETE FROM `edo_resultados` WHERE `concepto` = ';' OR `concepto` = '\n' OR `concepto` = 'Otros Ingresos' OR `concepto` = 'EGRESOS' OR `concepto` = 'Gastos de Administración y Generales' OR `concepto` = 'Gastos por Sueldos y Prestaciones'";
		$sql_borrar = $mysqli->query($borrar);

		if ($mysqli->error) {
				echo "<script>if(confirm('Algunos datos no se pudieron guardar')){ 
				document.location='/modulos/estado_resultados/';}
				else{ alert('Operacion Cancelada'); 
				}</script>";
			}
		fclose ($fp);
		header("location: /modulos/estado_resultados/");
		//echo "<div>La importacion de archivo subio satisfactoriamente</div >";
		
		exit;

	}elseif (isset($_GET["subir"])) {
		$selXZona = "SELECT `id_zona` FROM `escuela` WHERE `id_escuela` = $_SESSION[id_superior]";//Busca la zona a la pertenece la escuela
		$sql_selXZona = $mysqli->query($selXZona);
		$row_selXZona = $sql_selXZona->fetch_array();

		$insEdoResDe = "INSERT INTO `edo_res`(`id_ejercicio`, `id_zona`, `id_escuela`, `id_mes`, `fecha`) VALUES ($_SESSION[id_ejercicio], $row_selXZona[id_zona] ,$_SESSION[id_superior], $_SESSION[id_mes], now())";
		$sql_insEdoResDe = $mysqli->query($insEdoResDe);

		$selEdoResDe = "SELECT `id_edo_res` FROM `edo_res` WHERE `id_escuela` = $_SESSION[id_superior] AND `id_mes` = $_SESSION[id_mes]";
		$sql_selEdoResDe = $mysqli->query($selEdoResDe);
		$row_selEdoRes = $sql_selEdoResDe->fetch_array();
		$id_edo_res = $row_selEdoRes['id_edo_res'];

		$insEduRes = "INSERT INTO `edo_res_detalle`(`concepto`, `presupuestom`, `realm`, `variacionm`, `presupuestoa`, `reala`, `variaciona`, `id_edo_res`) SELECT `concepto`, `presupuestom`, `realm`, `variacionm`, `presupuestoa`, `reala`, `variaciona`, $id_edo_res FROM `edo_res_detalle_temp` WHERE `id_usuario` =$_SESSION[id_usuarios] AND `id_mes`=$_SESSION[id_mes]";
		$sql_insEduRes = $mysqli->query($insEduRes);

		if ($mysqli->error) {
				echo "<script>if(confirm('Algunos datos no se pudieron guardar')){ 
				document.location='/modulos/estado_resultados/';}
				else{ alert('Operacion Cancelada'); 
				}</script>";
			}else{
				$borrar = "DELETE FROM `edo_res_detalle_temp` WHERE `id_usuario` =$_SESSION[id_usuarios] AND `id_mes`=$_SESSION[id_mes]";
				$sql_borrar = $mysqli->query($borrar);
				header("location: /modulos/estado_resultados/");
			}
	}

?>