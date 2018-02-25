<?php
/**
* 
* @author Beyda Mariana Trejo Román <1130032@unav.edu.mx>
* @copyright 2017-2018 Área de Desarrollo UNAV 
* @version 1.0
*/
		class mes
	   	{
	   		public $mes;
	   		public $f_inicio;
	   		public $f_final;
	   		public $f_limite;

	   		function __construct($mes, $f_inicio, $f_final, $f_limite)
	   		{
	   			$this->mes = $mes;
	   			$this->f_inicio = $f_inicio;
	   			$this->f_final = $f_final;
	   			$this->f_limite = $f_limite;
	   		}


	   		public function insertar()
	   		{
	   			session_start();
				require("../../conex/connect.php");
			        // AGREGAR  
					$insertM = "INSERT INTO `mes`(`mes`, `f_inicio`, `f_final`, `f_limite`, `id_ejercicio`, `status`) VALUES ('$this->mes', '$this->f_inicio', '$this->f_final', '$this->f_limite', $_SESSION[id_ejercicio], 0)";
					$insertM = $mysqli->query($insertM);

					if ($mysqli->error) {
						echo "<script>if(confirm('Algunos datos no se pudieron guardar')){ 
						document.location='/modulos/mes/r_mes.php';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						echo "<script> document.location='/modulos/mes/'; </script>";
					}
	   		}

	   		public function modificar()
	   		{
	   			session_start();
				require("../../conex/connect.php");
			        // AGREGAR  
					$updtM = "UPDATE `mes` SET `mes`='$this->mes',`f_inicio`='$this->f_inicio',`f_final`='$this->f_final',`f_limite`='$this->f_limite' WHERE `id_mes` = $_SESSION[id_tmp]";
					$updtM = $mysqli->query($updtM);

					if ($mysqli->error) {
						echo "<script>if(confirm('Algunos datos no se pudieron guardar')){ 
						document.location='/modulos/mes/m_mes.php';}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						unset($_SESSION[id_tmp]);
						echo "<script> document.location='/modulos/mes/'; </script>";
					}
	   		}
	   	} 

	   	if (isset($_GET["estatus"])) {
	   		session_start();
		    require("../../conex/connect.php");
	   		$estatus = $_GET["estatus"];
	   		$id = $_GET["id"];

	   		$updtStatus = "UPDATE `mes` SET `status`=0";
			$updtStatus = $mysqli->query($updtStatus);

	   		$updtM = "UPDATE `mes` SET `status`=$estatus WHERE `id_mes` = $id";
			$updtM = $mysqli->query($updtM);

			if ($estatus == 1) {
				$_SESSION["id_mes"] = $id;
			}else{
				$_SESSION["id_mes"] = "";
			}

			if ($mysqli->error) {
				echo "<script>if(confirm('Algunos datos no se pudieron actualizar')){ 
				document.location='/modulos/mes';}
				else{ alert('Operacion Cancelada'); 
				}</script>";
			}else{
				echo "<script> document.location='/modulos/mes/'; </script>";
			}
	   	}
?>