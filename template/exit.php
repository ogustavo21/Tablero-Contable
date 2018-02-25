<?php
session_start();
if (isset($_SESSION["id_usuarios"])) {
	session_destroy();
  $_SESSION["id_usuarios"];

	header("location: http://tablero.unav.edu.mx");
}

?>