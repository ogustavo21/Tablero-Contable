<?php
require("../../conex/connect.php");
session_start();

if (isset($_POST['id_org']))
{
$_SESSION['id_superior']=$_POST['id_org'];
//ver por que no funciona la variable session y todo ese rollo para imprimir escuela y alerta
   $selid = "SELECT escuela.nombre from usuarios_escuelas 
  INNER JOIN escuela ON usuarios_escuelas.id_org=escuela.id_escuela
   WHERE usuarios_escuelas.id_org=$_SESSION[id_superior] group BY usuarios_escuelas.id_org";


  if ($result = $mysqli->query($selid)) { 
  	$row_escuela=$result->fetch_array();	
    ?>
		<div style="position: absolute; top: auto; right: 2%;">
			<div class="alert alert-success" role="alert">
			    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			    <strong>Éxito!</strong> Se actualizo la escuela a <?php echo $_SESSION['escuela']=$row_escuela[0]; ?>
			</div>
		</div>
	<?php
	}else{
	?>
	<div style="position: absolute; top: 55px;right: 10px;">
			<div class="alert alert-danger" role="alert">
			    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			    <strong>Error!</strong> No se pudieron guardar los datos.
			</div>
		</div>			
	<?php
	}

 
  }
  else { echo "nooo";}
?>	