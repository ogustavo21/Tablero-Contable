<?php
/**
* 
*  
* @copyright 2017-2018 Área de Desarrollo UNAV 
* @version 1.0
*/
		class escuela
	   	{
	   		public $rfc;
	   		public $nombre;
	   		public $direccion;
	   		public $telefono;
	   		public $correo;
	   		public $filantropica;

	   		function __construct($rfc, $nombre, $direccion, $telefono, $correo, $filantropica)
	   		{
	   			$this->rfc = $rfc;
	   			$this->nombre = $nombre;
	   			$this->direccion = $direccion;
	   			$this->telefono = $telefono;
	   			$this->correo = $correo;
	   			$this->filantropica = $filantropica;
	   		}


	   		public function insertar()
	   		{
	   			session_start();
				require("../../conex/connect.php");
			        // AGREGAR  
				  $lstZona = "SELECT `id_org` FROM `usuarios_escuelas` WHERE `id_usuario`=$_SESSION[id_usuarios]";
                  $res_lstZona = $mysqli->query($lstZona);
                  $row_res_lstZona = $res_lstZona->fetch_array();
					$insertE = "INSERT INTO `escuela`(`rfc`, `nombre`, `direccion`, `telefono`, `correo`, `id_filantropica`, `id_zona`, `status`) VALUES ('$this->rfc', '$this->nombre', '$this->direccion', $this->telefono, '$this->correo', $this->filantropica, $row_res_lstZona[id_org],1)";
					$insertE = $mysqli->query($insertE); 

					if ($mysqli->error) {
						echo "<script>if(confirm('Algunos datos no se pudieron guardar')){ 
						document.location='/modulos/escuela/r_escuela.php';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						echo "<script> document.location='/modulos/escuela/'; </script>";
					}
	   		}

	   		public function modificar()
 			{
	   			session_start();
				require("../../conex/connect.php");
			        // AGREGAR  
					$updtE = "UPDATE `escuela` SET `rfc`='$this->rfc',`nombre`='$this->nombre',`direccion`='$this->direccion',`telefono`=$this->telefono,`correo`='$this->correo',`id_filantropica`=$this->filantropica WHERE `id_escuela` = $_SESSION[id_superior]";
					$updtE = $mysqli->query($updtE);

					if ($mysqli->error) {
						echo "<script>if(confirm('Algunos datos no se pudieron guardar')){ 
						document.location='/modulos/escuela/m_escuela.php';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						unset($_SESSION[id_superior]);
						echo "<script> document.location='/modulos/escuela/'; </script>";
					}
	   		}
	   	} 

	   	if (isset($_GET["estatus"])) {
		    require("../../conex/connect.php");
	   		$estatus = $_GET["estatus"];
	   		$id = $_GET["id"];
	   		$updtE = "UPDATE `escuela` SET `status`=$estatus WHERE `id_escuela` = $id";
			$updtE = $mysqli->query($updtE);

			if ($mysqli->error) {
				echo "<script>if(confirm('Algunos datos no se pudieron actualizar')){ 
				document.location='/modulos/escuela';}
				else{ alert('Operacion Cancelada'); 
				}</script>";
			}else{
				echo "<script> document.location='/modulos/escuela/'; </script>";
			}
	   	}
?>