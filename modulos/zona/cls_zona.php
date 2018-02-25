<?php
/**
* 
* @author Beyda Mariana Trejo Román <1130032@unav.edu.mx>
* @copyright 2017-2018 Área de Desarrollo UNAV 
* @version 1.0
*/
		class zona
	   	{
	   		public $nombre;
	   		public $dir;

	   		function __construct($nombre, $dir)
	   		{
	   			$this->nombre = $nombre;
	   			$this->dir = $dir;
	   		}


	   		public function insertar()
	   		{
	   			session_start();
				require("../../conex/connect.php");
			        // AGREGAR  
					$insertZ = "INSERT INTO `zona`(`zona`, `direc`, `id_union`, `status`) VALUES ('$this->nombre','$this->dir',1,1)";
					$insertZ = $mysqli->query($insertZ);

					if ($mysqli->error) {
						echo "<script>if(confirm('Algunos datos no se pudieron guardar')){ 
						document.location='/modulos/zona/r_zona.php';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						echo "<script> document.location='/modulos/zona/'; </script>";
					}
	   		}

	   		public function modificar()
	   		{
	   			session_start();
				require("../../conex/connect.php");
			        // AGREGAR  
					$updtZ = "UPDATE `zona` SET `zona`='$this->nombre',`direc`='$this->dir' WHERE `id_zona` = $_SESSION[id_tmp]";
					$updtZ = $mysqli->query($updtZ);

					if ($mysqli->error) {
						echo "<script>if(confirm('Algunos datos no se pudieron guardar')){ 
						document.location='/modulos/zona/m_zona.php';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						unset($_SESSION[id_tmp]);
						echo "<script> document.location='/modulos/zona/'; </script>";
					}
	   		}
	   	} 

	   	if (isset($_GET["estatus"])) {
		    require("../../conex/connect.php");
	   		$estatus = $_GET["estatus"];
	   		$id = $_GET["id"];
	   		$updtZ = "UPDATE `zona` SET `status`=$estatus WHERE `id_zona` = $id";
			$updtZ = $mysqli->query($updtZ);

			if ($mysqli->error) {
				echo "<script>if(confirm('Algunos datos no se pudieron actualizar')){ 
				document.location='/modulos/zona';}
				else{ alert('Operacion Cancelada'); 
				}</script>";
			}else{
				echo "<script> document.location='/modulos/zona/'; </script>";
			}
	   	}
?>