<?php
/**
* 
* @author Beyda Mariana Trejo Román <1130032@unav.edu.mx>
* @copyright 2017-2018 Área de Desarrollo UNAV 
* @version 1.0
*/
		class ejercicio
	   	{
	   		public $ejercicio;
	   		public $nombre;

	   		function __construct($ejercicio, $nombre)
	   		{
	   			$this->ejercicio = $ejercicio;
	   			$this->nombre = $nombre;
	   		}


	   		public function insertar()
	   		{
	   			session_start();
				require("../../conex/connect.php");
			        // AGREGAR  
					$insertE = "INSERT INTO `ejercicio`(`id_ejercicio`, `nombre`, `status`) VALUES ($this->ejercicio,'$this->nombre',1)";
					$insertE = $mysqli->query($insertE);

					if ($mysqli->error) {
						echo "<script>if(confirm('Algunos datos no se pudieron guardar')){ 
						document.location='/modulos/ejercicio/r_ejercicio.php';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						echo "<script> document.location='/modulos/ejercicio/'; </script>";
					}
	   		}

	   		public function modificar()
	   		{
	   			session_start();
				require("../../conex/connect.php");
			        // AGREGAR  
					$updtZ = "UPDATE `ejercicio` SET `nombre`='$this->nombre' WHERE `id_ejercicio` = $_SESSION[id_tmp]";
					$updtZ = $mysqli->query($updtZ);

					if ($mysqli->error) {
						echo "<script>if(confirm('Algunos datos no se pudieron guardar')){ 
						document.location='/modulos/ejercicio/m_ejercicio.php';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						unset($_SESSION[id_tmp]);
						echo "<script> document.location='/modulos/ejercicio/'; </script>";
					}
	   		}
	   	} 

	   	if (isset($_GET["estatus"])) {
	   		session_start();
		    require("../../conex/connect.php");
	   		$estatus = $_GET["estatus"];
	   		$id = $_GET["id"];

	   		$updtStatus = "UPDATE `ejercicio` SET `status`=0";
			$updtStatus = $mysqli->query($updtStatus);

	   		$updtZ = "UPDATE `ejercicio` SET `status`=$estatus WHERE `id_ejercicio` = $id";
			$updtZ = $mysqli->query($updtZ);
			if ($estatus == 1) {
				$_SESSION["id_ejercicio"] = $id;
			}else{
				$_SESSION["id_ejercicio"] = "";
			}
			

			if ($mysqli->error) {
				echo "<script>if(confirm('Algunos datos no se pudieron actualizar')){ 
				document.location='/modulos/ejercicio';}
				else{ alert('Operacion Cancelada'); 
				}</script>";
			}else{
				echo "<script> document.location='/modulos/ejercicio/'; </script>";
			}
	   	}
?>