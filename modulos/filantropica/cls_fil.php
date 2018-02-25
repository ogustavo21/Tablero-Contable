<?php
/**
* 
* @author Beyda Mariana Trejo Román <1130032@unav.edu.mx>
* @copyright 2017-2018 Área de Desarrollo UNAV 
* @version 1.0
*/
		class filantropica
	   	{
	   		public $nombre;

	   		function __construct($nombre)
	   		{
	   			$this->nombre = $nombre;
	   		}


	   		public function insertar()
	   		{
	   			session_start();
				require("../../conex/connect.php");
			        // AGREGAR  
				  $lstZona = "SELECT `id_org` FROM `usuarios_escuelas` WHERE `id_usuario`=$_SESSION[id_usuarios]";
                  $res_lstZona = $mysqli->query($lstZona);
                  $row_res_lstZona = $res_lstZona->fetch_array();
					$insertF = "INSERT INTO `filantropica`(`nombre`, `id_zona`, `status`) VALUES ('$this->nombre',$row_res_lstZona[id_org],1)";
					$insertF = $mysqli->query($insertF);

					if ($mysqli->error) {
						echo "<script>if(confirm('Algunos datos no se pudieron guardar')){ 
						document.location='/modulos/filantropica/r_filantropica.php';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						echo "<script> document.location='/modulos/filantropica/'; </script>";
					}
	   		}

	   		public function modificar()
	   		{
	   			session_start();
				require("../../conex/connect.php");
			        // AGREGAR  
					$updtF = "UPDATE `filantropica` SET `nombre`='$this->nombre' WHERE `id_filantropica` = $_SESSION[id_tmp]";
					$updtF = $mysqli->query($updtF);

					if ($mysqli->error) {
						echo "<script>if(confirm('Algunos datos no se pudieron guardar')){ 
						document.location='/modulos/filantropica/m_filantropica.php';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						unset($_SESSION[id_tmp]);
						echo "<script> document.location='/modulos/filantropica/'; </script>";
					}
	   		}
	   	} 

	   	if (isset($_GET["estatus"])) {
		    require("../../conex/connect.php");
	   		$estatus = $_GET["estatus"];
	   		$id = $_GET["id"];
	   		$updtF = "UPDATE `filantropica` SET `status`=$estatus WHERE `id_filantropica` = $id";
			$updtF = $mysqli->query($updtF);

			if ($mysqli->error) {
				echo "<script>if(confirm('Algunos datos no se pudieron actualizar')){ 
				document.location='/modulos/filantropica';}
				else{ alert('Operacion Cancelada'); 
				}</script>";
			}else{
				echo "<script> document.location='/modulos/filantropica/'; </script>";
			}
	   	}
?>