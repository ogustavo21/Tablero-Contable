<?php

		class liquidez
	   	{
	   		public $t_acorriente;
	   		public $t_pcorriente;  
	   		public $e_operativosanual; 
	   		public $e_operativosmensual; 
	   		public $p_aplicado;
	   		public $f_asignadoanual;
	   		public $f_asignadomensual;
	   		public $caja_bancos; 
	   		public $inversiones; 
	   		public $p_corriente; 
	   		public $f_asignadosbrutos; 
	   		public $ingreso_operativo;
	   		public $subcidios_ingresos; 
	   		public $egresos_operativo; 
	   		public $cole_mcobrar;
	   		public $becas_motorgadas; 
	   		public $saldo_cclientes; 
	   		public $cobros_erealizados; 
	   		public $cole_preanual;
	   		public $becas_preanual; 
	   		public $cole_cobrarperiodo; 
	   		public $becas_operiodo;
 
	   		function __construct($t_acorriente,  $t_pcorriente,  $e_operativosanual, $e_operativosmensual, $p_aplicado, $f_asignadoanual, $f_asignadomensual, $caja_bancos, $inversiones, $p_corriente, $f_asignadosbrutos,$ingreso_operativo, $subcidios_ingresos, $egresos_operativo, $cole_mcobrar, $becas_motorgadas, $saldo_cclientes, $cobros_erealizados, $cole_preanual, $becas_preanual, $cole_cobrarperiodo, $becas_operiodo)
	   		{ 
			$this->t_acorriente=$t_acorriente;
			$this->t_pcorriente=$t_pcorriente;   
			$this->e_operativosanual=$e_operativosanual; 
			$this->e_operativosmensual=$e_operativosmensual; 
			$this->p_aplicado=$p_aplicado;
			$this->f_asignadoanual=$f_asignadoanual;
			$this->f_asignadomensual=$f_asignadomensual;
			$this->caja_bancos=$caja_bancos; 
			$this->inversiones=$inversiones; 
			$this->p_corriente=$p_corriente;  
			$this->f_asignadosbrutos=$f_asignadosbrutos; 
			$this->ingreso_operativo=$ingreso_operativo; 
			$this->subcidios_ingresos=$subcidios_ingresos;  
			$this->egresos_operativo=$egresos_operativo; 
			$this->cole_mcobrar=$cole_mcobrar;
			$this->becas_motorgadas=$becas_motorgadas;  
			$this->saldo_cclientes=$saldo_cclientes;  
			$this->cobros_erealizados=$cobros_erealizados;  
			$this->cole_preanual=$cole_preanual;
			$this->becas_preanual=$becas_preanual; 
			$this->cole_cobrarperiodo=$cole_cobrarperiodo; 
			$this->becas_operiodo=$becas_operiodo;
	   		 
	   		}


	   		public function insertar()
	   		{
	   			 session_start();
				require("../../conex/connect.php");
				  $lstZona = "SELECT escuela.id_zona FROM escuela INNER JOIN zona on escuela.id_zona=zona.id_zona WHERE escuela.id_escuela=$_SESSION[id_superior]";
                  $res_lstZona = $mysqli->query($lstZona);
                  $row_res_lstZona = $res_lstZona->fetch_array();
					$insertEscuela = "INSERT INTO `liquidez`(`id_usuarios`, `id_ejercicio`, `id_zona`, `id_escuela`, `id_mes`, `fecha`) VALUES ($_SESSION[id_usuarios],$_SESSION[id_ejercicio],$row_res_lstZona[id_zona],$_SESSION[id_superior],$_SESSION[id_mes],now())";
					$insertE = $mysqli->query($insertEscuela); 

					$lstidliquidez = "SELECT id_liquidez FROM liquidez, usuarios WHERE usuarios.id_usuarios=$_SESSION[id_usuarios] AND liquidez.id_ejercicio=$_SESSION[id_ejercicio] and liquidez.id_escuela=$_SESSION[id_superior] and liquidez.id_mes=$_SESSION[id_mes] AND liquidez.id_zona=$row_res_lstZona[id_zona] GROUP BY usuarios.id_usuarios";
                  $res_lstLiquidez = $mysqli->query($lstidliquidez);
                  $row_res_lstidliquidez= $res_lstLiquidez->fetch_array();

					$liquidezdetalle = "INSERT INTO `liquidez_detalles`(`t_acorriente`, `t_pcorriente`, `e_operativosanual`, `e_operativosmensual`, `p_aplicado`, `f_asignadoanual`, `f_asignadomensual`, `caja_bancos`, `inversiones`, `p_corriente`, `f_asignadosbrutos`, `ingreso_operativo`, `subcidios_ingresos`, `egresos_operativo`, `cole_mcobrar`, `becas_motorgadas`, `saldo_cclientes`, `cobros_erealizados`, `cole_preanual`, `becas_preanual`, `cole_cobrarperiodo`, `becas_operiodo`, `id_liquidez`, `status`) VALUES ('$this->t_acorriente','$this->t_pcorriente','$this->e_operativosanual', '$this->e_operativosmensual', '$this->p_aplicado','$this->f_asignadoanual','$this->f_asignadomensual','$this->caja_bancos','$this->inversiones','$this->p_corriente','$this->f_asignadosbrutos', $this->ingreso_operativo, '$this->subcidios_ingresos','$this->egresos_operativo','$this->cole_mcobrar','$this->becas_motorgadas','$this->saldo_cclientes','$this->cobros_erealizados','$this->cole_preanual','$this->becas_preanual','$this->cole_cobrarperiodo','$this->becas_operiodo',$row_res_lstidliquidez[id_liquidez],1)";
					$liquidezdetalle = $mysqli->query($liquidezdetalle); 


					if ($mysqli->error) {
						echo "<script>if(confirm('No se Guardo la Liquidez')){ 
						document.location='/modulos/liquidezacum/indexr.php;}
						else{ alert('Operacion Cancelada'); 
						}</script>";
					}else{
						echo "<script> document.location='/modulos/liquidezacum/indexr.php'; </script>";
						 
					}
	   		}

	   	/*	public function modificar()
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
	   		}*/
	   	} 

	    
?>