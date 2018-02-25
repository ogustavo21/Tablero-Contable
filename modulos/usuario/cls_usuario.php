<?php
/**
* 
* @author Beyda Mariana Trejo Román <1130032@unav.edu.mx> 
* @copyright 2017-2018 Área de Desarrollo UNAV 
* @version 1.0
*/
		class usuario
	   	{
	   		public $rfc;
	   		public $nombre;
	   		public $apaterno;
	   		public $amaterno;
	   		public $correo;
	   		public $pass;
	   		public $escuela;
	   		public $creador;
	   		function __construct($rfc, $nombre, $apaterno, $amaterno, $correo, $pass, $escuela,$creador)
	   		{
	   			$this->rfc = $rfc;
	   			$this->nombre = $nombre;
	   			$this->apaterno = $apaterno;
	   			$this->amaterno = $amaterno;
	   			$this->correo = $correo;
	   			$this->pass = $pass;
	   			$this->escuela = $escuela;
	   		 	$this->creador = $creador;
	   		}

	   		
	   		public function insertar()
	   		{
	   			session_start();
	   			$pass2 = md5($this->pass);
				require("../../conex/connect.php");
				 if ($_SESSION["tipo_usuario"] == 'Financiero General'){
			        // AGREGAR  
					$insertU = "INSERT INTO `usuarios`(`rfc`, `nombre`, `apaterno`, `amaterno`, `correo`, `contrasena`, `id_tipo_usuario`, `status`) VALUES ('$this->rfc', '$this->nombre', '$this->apaterno','$this->amaterno', '$this->correo', '$pass2', $_SESSION[id_tipo_hijo], 1)";
				}elseif($_SESSION["tipo_usuario"] == 'Financiero de Zona'){


					$insertU = "INSERT INTO `usuarios`(`rfc`, `nombre`, `apaterno`, `amaterno`, `correo`, `contrasena`, `id_tipo_usuario`, `status`, `id_creador`) VALUES ('$this->rfc', '$this->nombre', '$this->apaterno', '$this->amaterno', '$this->correo', '$pass2', 5, 1, $this->creador)";


				}
					$insertU = $mysqli->query($insertU);

					if ($mysqli->error) {
						//ARREGLAR LA NOTIFICACION JONATHAN
						echo "<script>if(
							confirm('Algunas datos no se pudieron guardar')){ 
						document.location='/modulos/usuario';}
						else{ alert('Operacion Cancelada,'); 
						}</script>";

					}else{
                     $consulta="SELECT `id_usuarios` FROM `usuarios` WHERE `correo` ='$this->correo'";
                     $resultado = $mysqli->query($consulta);
                    $resultado = $resultado->fetch_array();  
                    $consulta="INSERT INTO `usuarios_escuelas`(`id_usuario`, `id_org`) VALUES ($resultado[id_usuarios], $this->escuela)";
                     $resultado = $mysqli->query($consulta);
				     echo "<script> document.location='/modulos/usuario/'; </script>";
					}

			

	   		}
	   		public function modificar()
	   		{
	   			session_start();
				require("../../conex/connect.php"); 
			        // AGREGAR  
					$updtU = "UPDATE `usuarios` SET `rfc`='$this->rfc',`nombre`='$this->nombre',`apaterno`='$this->apaterno',`amaterno`='$this->amaterno',`correo`='$this->correo',`contrasena`='$this->pass' WHERE `id_usuarios` = $_SESSION[id_tmp]";
					$updtU = $mysqli->query($updtU);

					$org =$this->creador;
						if ($org=="") {
				}else{
			$updtU = "UPDATE `usuarios_escuelas` SET  `id_org`='$this->creador' WHERE id_usuario=$_SESSION[id_tmp] and id_org=$_SESSION[id_escuel]";
					$updtU = $mysqli->query($updtU);

				     if ($mysqli->error) {

						echo "<script>if(confirm('error consulta')){ 
						document.location='/modulos/usuario';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						//unset($_SESSION[id_tmp]);
						 
					}
					}
					if ($mysqli->error) {

						echo "<script>if(confirm('No se pudo actualizar la escuela')){ 
						document.location='/modulos/usuario';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						//unset($_SESSION[id_tmp]);
						echo "<script> document.location='/modulos/usuario/'; </script>";
					}
	   		}

	   		public function cambiar()
	   		{
	   			session_start();
				require("../../conex/connect.php");
			        // AGREGAR  
					echo $updtU = "UPDATE `usuarios` SET `contrasena`='$this->pass' WHERE `id_usuarios` = $_SESSION[id_usuarios]";
					$updtU = $mysqli->query($updtU);

					if ($mysqli->error) {
						echo "<script>if(confirm('No se pudo actualizar la contraseña, revisar que sean iguales')){ 
						document.location='/modulos/usuario/cambiar.php';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						//unset($_SESSION[id_tmp]);
						echo "<script> document.location='/modulos/principal/union.php'; </script>";
					}
	   		}
	   		public function agregaEscuela()
	   		{
	   			session_start();
				require("../../conex/connect.php");
			        // AGREGAR  
					
					 $consulta="INSERT INTO `usuarios_escuelas`(`id_usuario`, `id_org`) VALUES ($this->creador, $this->escuela)";
                     $resultado = $mysqli->query($consulta);;

					if ($mysqli->error) {
						echo "<script>if(confirm('La escuela no se pudo agregar')){ 
						document.location='/modulos/usuario';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						//unset($_SESSION[id_tmp]);
						echo "<script> document.location='/modulos/usuario/'; </script>";
					}
	   		}
	   	} 

	   	if (isset($_GET["estatus"])) {
		    require("../../conex/connect.php");
	   		$estatus = $_GET["estatus"];
	   		$id = $_GET["id"];
	   		$updtU1 = "UPDATE `usuarios` SET `status`= $estatus WHERE `id_usuarios` = $id";
			$updtU1 = $mysqli->query($updtU1);

			if ($mysqli->error) {	
				echo "<script>if(confirm('Algunas datos no se pudieron actualizar')){ 
				document.location='/modulos/usuario';}
				else{ alert('Operacion Cancelada'); 
				}</script>";
			}else{
				echo "<script> document.location='/modulos/usuario/'; </script>";
			}
	   	}

	   	if (isset($_GET["borrar"])) {
		    require("../../conex/connect.php");
	   		$iduser = $_GET["iduser"];
	   		$idorg = $_GET["idorg"];
	   		$updtU1 = "delete from usuarios_escuelas WHERE `id_usuario` = $iduser and `id_org`=$idorg ";
			$updtU1 = $mysqli->query($updtU1);

			if ($mysqli->error) {	
				echo "<script>if(confirm('No se pudo borrar la escuela')){ 
				document.location='/modulos/usuario';}
				else{ alert('Operacion Cancelada'); 
				}</script>";
			}else{
				echo "<script> document.location='/modulos/usuario/'; </script>";
			}
	   	}
?>