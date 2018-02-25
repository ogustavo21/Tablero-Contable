
<?php 
//inicio de librerias 

require('libs.php');
//require('script.php');
//Final de Librerrias
?>

<body class="hold-transition skin-purple-light sidebar-mini">
 <?php
   if (is_null($_SESSION['id_usuarios'])){
   	header("location: http://tablero.unav.edu.mx");
  }
  ?>
<div class="wrapper">
      

      
<?php 
//Inicio de cabecera y navegacion
require('header.php');
require('nav.php');
//Final de Cabecera y Navegacion


?>