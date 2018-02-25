<?php
/**
* 
* @author Beyda Mariana Trejo Román <1130032@unav.edu.mx>
* @copyright 2016-2017 Área de Desarrollo UNAV 
* @version 1.0
*
*Aquí se suben cada uno de los archivos por empleado en la siguiente variable
*@var string $_SESSION['id_datosper'] aqui se manda el id del empleado
*@var string $archivo aqui se mandan el nombre del archivo
*@var string $nombre aqui se guarda el nombre completo del archivo
*@var string $ruta aqui se manda la ruta donde se guardará el archivo
*/
  session_start();
  require("../../conex/connect.php");
if (isset($_FILES['archivo']) && $_POST['id_archivo'] != "") {
  $archivo = $_FILES['archivo'];
  $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
	$time = time();
	$id_doc = $_POST['id_archivo'];
  $desc = $_POST['desc'];
  $abrev = "SELECT `abrev` FROM `tipo_comprobantes` WHERE `id_tipo_comprobante` = $id_doc";
  $resul_abrev = $mysqli->query($abrev);
  $row = $resul_abrev->fetch_array();
    $nombre = "$_SESSION[id_usuarios]_$_SESSION[id_mes]_$row[0].$extension";
    $ruta = "carga_archivos/archivos_subidos/$nombre";
    if (!file_exists($ruta))
    {
      if (move_uploaded_file($archivo['tmp_name'], "archivos_subidos/$nombre")) {
          echo $v = 1;
      } else {
          echo $v = 0;
      }
    }
   if ($v == 1) {
   	$doc = "INSERT INTO `comprobantes`(`id_ejercicio`, `id_mes`, `id_escuela`, `id_usuario`, `id_tipo_comprobante`, `descripcion`, `fecha`, `url`, `comprobante_al`) VALUES ($_SESSION[id_ejercicio], $_SESSION[id_mes], $_SESSION[id_superior], $_SESSION[id_usuarios], $id_doc, '$desc', now(), '$nombre', 0)";
    $resul_doc = $mysqli->query($doc);
    if ($mysqli->error) {
      unlink("archivos_subidos/$nombre");
      echo 0;
    }
   }
}


class inscritos
{  
  public $blanco;
  public $alcanzado;

  function __construct($blanco, $alcanzado)
  {
    $this->blanco = $blanco;
    $this->alcanzado = $alcanzado;
  }

  public function insertar(){
  require("../../conex/connect.php");
    $insertDato = "INSERT INTO `inscritos`(`id_ejercicio`, `id_mes`, `id_escuela`, `id_usuario`, `blanco`, `alcanzado`, `inscritos_al`, `fecha`) VALUES ($_SESSION[id_ejercicio], $_SESSION[id_mes], $_SESSION[id_superior], $_SESSION[id_usuarios], $this->blanco, $this->alcanzado, 0, now())";
    $insertDato = $mysqli->query($insertDato);
    if ($mysqli->error) {
    echo "<script>if(confirm('Algunos datos no se pudieron actualizar')){ 
    document.location='/modulos/comprobante';}
    else{ alert('Operacion Cancelada'); 
    }</script>";
    }else{
    echo "<script> document.location='/modulos/comprobante/'; </script>";
    }
  }
}


?>
