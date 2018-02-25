<?php 
$atras ="../../";

include($atras."conex/connect.php");
// include db config 
include_once("../../phpgrid/config.php"); 

// include and create object 
include(PHPGRID_LIBPATH."inc/jqgrid_dist.php"); 

// Database config file to be passed in phpgrid constructor 
$db_conf = array(      
                    "type"         => PHPGRID_DBTYPE,  
                    "server"     => PHPGRID_DBHOST, 
                    "user"         => PHPGRID_DBUSER, 
                    "password"     => PHPGRID_DBPASS, 
                    "database"     => PHPGRID_DBNAME 
                ); 

$g = new jqgrid($db_conf); 

$opt = array();
$opt["sortname"] = 'id_zona'; // by default sort grid by this field
$opt["sortorder"] = "asc"; // ASC or DESC
$opt["autowidth"] = true; // expand grid to screen width
$opt["multiselect"] = true; // allow you to multi-select through checkboxes
$opt["altRows"] = true; 
$opt["altclass"] = "myAltRowClass"; 

$opt["caption"] = "Zonas"; 
$g->set_options($opt); 



// set table for CRUD operations 
//$g->select_command = "SELECT `zona`, `direc`, `status` FROM `zona` WHERE `id_union` = 1";
$g->table = "zona"; 
$g->select_command = "SELECT * FROM `zona` WHERE `id_union` = $_SESSION[id_superior]";
$col = array();
$col["title"] = "Id"; // caption of column
$col["name"] = "id_zona"; // grid column name, must be exactly same as returned column-name from sql (tablefield or field-alias) 
$col["width"] = "10";
$col["editable"] = true;
$col["hidden"] = true;
$cols[] = $col;

$col = array();
$col["title"] = "Zona"; // caption of column
$col["name"] = "zona"; // grid column name, must be exactly same as returned column-name from sql (tablefield or field-alias) 
$col["width"] = "10";
$col["editable"] = true;
$col["hidden"] = false;
$cols[] = $col;

$col = array();
$col["title"] = "Dirección"; // caption of column
$col["name"] = "direc"; // grid column name, must be exactly same as returned column-name from sql (tablefield or field-alias) 
$col["width"] = "10";
$col["editable"] = true;
$col["hidden"] = false;
$cols[] = $col;

$col = array();
$col["title"] = "Unión"; // caption of column
$col["name"] = "id_union"; // grid column name, must be exactly same as returned column-name from sql (tablefield or field-alias) 
$col["width"] = "10";
$col["editable"] = true;
$col["hidden"] = false;
$col["edittype"] = "select";
$lstUnion = "SELECT `id_union`, `union` FROM `union`";
$res_lstUnion = $mysqli->query($lstUnion);
while ($row_res_lstUnion = $res_lstUnion->fetch_array()) {   
  $id_union[] = $row_res_lstUnion[id_union] .":". $row_res_lstUnion[union];
}   
$total = implode(";",$id_union);
//echo "Aqui". $total;
$col["editoptions"] = array("value"=> $total);
$col["default"] = "1";
$cols[] = $col;

$col = array();
$col["title"] = "Estatus"; // caption of column
$col["name"] = "status"; // grid column name, must be exactly same as returned column-name from sql (tablefield or field-alias) 
$col["width"] = "10";
$col["editable"] = true;
//$col["editrules"] = array("edithidden"=>true); 
$col["edittype"] = "select";
$col["editoptions"] = array("value"=>'Activo:Activo;Inactivo:Inactivo');
$cols[] = $col;

// pass the cooked columns to grid
$g->set_columns($cols);
             
// render grid 
$out = $g->render("list1"); 

?> 
<?php 
$atras = "../../";
  include($atras.'template/todo.php');
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista
        <small>Zonas</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if ($_SESSION["id_tipo_usuario"] == 'Financiero General'){
    ?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-xs-12">
            <!-- /.box-header -->
            <div class="box-body">
            <?php echo $out?> 
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.col -->
      </div>
      <?php
    }
    ?>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
</div>
<!-- ./wrapper -->
<?php 
  include($atras.'template/footer.php');
  include($atras.'template/script.php');
?>

</body>
</html>
