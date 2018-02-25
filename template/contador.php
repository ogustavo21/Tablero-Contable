<?php
$atras ="../";
include($atras."conex/connect.php");
$sql2="SELECT c.`id_comprobante` FROM `comprobantes` c INNER JOIN `mes` m ON c.`id_mes`= m.`id_mes` INNER JOIN `escuela` e ON c.`id_escuela` = e.`id_escuela` INNER JOIN `tipo_comprobantes` tp ON c.`id_tipo_comprobante` = tp.`id_tipo_comprobante` WHERE c.`comprobante_al` = 0";
$result=$mysqli->query($sql2);
$count=$result->num_rows;
?>
<?php if($count>0) { echo $count; } ?>