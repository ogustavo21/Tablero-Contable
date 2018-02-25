<?php
session_start();

//HACE LA VARIABLE DEL ID EN SESSION
if (isset($_POST['id_ejercicio'])) {
	$_SESSION['id_tmp']=$_POST['id_ejercicio'];
}

//HACER AJAX CON EL EJERCICIO PARA MOSTRAR EL MES
if (isset($_POST['ejercicio'])) {
	require("../../conex/connect.php");

	$ejercicio=$_POST['ejercicio'];

	echo $query = "SELECT `id_mes`,`mes` FROM `mes` WHERE `id_ejercicio` = $ejercicio";
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
//PARA LA ALERTA DE QUE SE HA CAMBIADO EL ESTADO DEL EJERCICIO Y MÉS EN SESSION
if (isset($_POST['mes']))
{
	require("../../conex/connect.php");
	session_start();

	$mes = $_POST["mes"];

	$selMes = "SELECT `mes`,`id_ejercicio` FROM `mes` WHERE `id_mes` = $mes";
	$resul_selMes = $mysqli->query($selMes);
	$row_selMes=$resul_selMes->fetch_array();
	$filas = $resul_selMes->num_rows;

	$_SESSION["id_mes"] = $mes;
	$_SESSION["id_ejercicio"] = $row_selMes[1];

	if ($mysqli->error) { ?>
		<div style="position: absolute; top: 55px;right: 10px;">
			<div class="alert alert-danger" role="alert">
			    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			    <strong>Error!</strong> No se pudieron guardar los datos.
			</div>
		</div>
	<?php
	}else{
	?>
		<div style="position: absolute; top: auto; right: 2%;">
			<div class="alert alert-success" role="alert">
			    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			    <strong>Éxito!</strong> Se actualizo el mes a <?php echo $row_selMes[0] ." del ". $row_selMes[1] ?>
			</div>
		</div>		
	<?php
	}
}
?>