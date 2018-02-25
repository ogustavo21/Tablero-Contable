<?php
session_start();
$_SESSION['id_tmp']=$_POST['id_usuarios'];
$_SESSION['id_escuel']=$_POST['id_escuela'];
$_SESSION['id_indexfilantropica']=$_POST['id_fil'];
$_SESSION['id_escuela']=$_POST['id_esc'];
 
//header("location: m_usuario.php");
$_SESSION['id_filantropica']=$_POST['id_filantropica'];
 
 


										if (isset($_POST['id_filantropica'])) {
										?>


											 <select class="form-control" name="escuela">
										<?php

											require("../../conex/connect.php");
											$_SESSION['id_tmp']= $_POST["id_tmp"];
 
											$id_filantropica=$_POST['id_filantropica'];

										                       $Zona2 = "SELECT id_zona  FROM `usuarios`, zona, usuarios_escuelas WHERE id_usuarios=$_SESSION[id_usuarios] and usuarios_escuelas.id_usuario=usuarios.id_usuarios and zona.id_zona=usuarios_escuelas.id_org";
										                  $res_lstZona1=$mysqli->query($Zona2);
										                     $row_reslstZona1 = $res_lstZona1->fetch_array();
										                    $id_zona=$row_reslstZona1['id_zona']; 
										                  

											  $query = "SELECT DISTINCT escuela.id_escuela, escuela.nombre FROM escuela INNER JOIN filantropica ON escuela.id_zona=filantropica.id_zona INNER JOIN zona ON zona.id_zona=filantropica.id_zona WHERE escuela.id_zona=$id_zona AND escuela.id_filantropica=$id_filantropica and escuela.id_escuela not in (SELECT DISTINCT escuela.id_escuela FROM `usuarios_escuelas`, escuela, usuarios, filantropica WHERE usuarios.id_tipo_usuario=5 and  usuarios.id_usuarios=usuarios_escuelas.id_usuario and usuarios_escuelas.id_org=escuela.id_escuela AND escuela.id_filantropica=filantropica.id_filantropica and escuela.id_filantropica=$id_filantropica) GROUP BY escuela.id_escuela";
											$result = $mysqli->query($query) or die ($mysqli->error);
											$filas = $result->num_rows;
													 	echo '<option selected disabled value="">Seleccione una Escuela</option>';
											if($filas > 0){
												
												while($row = $result->fetch_array()){
													$id_escuela=$row["id_escuela"];
													$nombre=$row["nombre"];

													echo '<option value="'.$id_escuela.'">'. $nombre .'</option>';
												}
											 }else{
												echo '<option selected disabled value="">No existen Escuelas en esta Filantropica</option>';
											}
										}

 if (isset($_POST['id_fila']))
{

	 
	require("../../conex/connect.php");

					  session_start();
				 echo	 $_SESSION[id_tmp]= $_POST["id_tmp"];
					$id_filantropica = $_POST["id_fila"];
					$id_escuela = $_POST["idresult"];
				 $idusu=$_SESSION['id_usuarios'];

 

				      $lstEscuela = "SELECT id_filantropica, nombre FROM filantropica WHERE id_filantropica=	$id_filantropica";
      $res_lstEscuela = $mysqli->query($lstEscuela);
      while ($row_reslstFila = $res_lstEscuela->fetch_array()) {                    
      ?>
      <option value="<?php echo $row_reslstFila["id_filantropica"] ?>"><?php echo $row_reslstFila["nombre"] ?></option>
     <?php
     echo $_SESSION['id_tmp']=$_POST['id_usuarios'];
     echo $_SESSION['id_filantropica']=$row_reslstFila["id_filantropica"];
      echo $_SESSION['nombre']=$row_reslstFila["nombre"];



     }
     ?>              


  <?php
	}



?>