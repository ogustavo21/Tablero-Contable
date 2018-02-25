<?php 
session_start();
$atras = "../../";
  include($atras.'template/todo.php');
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol> 
  
    </section>
<!-- ****************/.ajax id escuela selecionada******************************** -->

  <script type="text/javascript">
  $(document).ready(function(){
      $("#id_org").change(function(){
          var id_escuela=$(this).val();
          var dataString2 = 'id_org='+ id_escuela;
          $.ajax({
              type: "POST",
              url: "../escuela/ajax_link2.php",
              data: dataString2,
              cache: false,
          success: function(html){
           $("#resultado").html(html);
          }
          });
      });
    });
  </script>

            <!-- /.box-header -->
            <!-- form start -->
              <div class="box-body">

          <div class="col-xs-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Seleccione la Escuela</label>
                  <select class="form-control" id="id_org" >
                    <option><?php echo  "Elegida: ". $_SESSION["escuela"]; ?></option>
                  <?php
                    $lstMes = "SELECT usuarios_escuelas.id_org, escuela.nombre 
                    from usuarios_escuelas 
                    INNER JOIN escuela ON usuarios_escuelas.id_org=escuela.id_escuela 
                    WHERE usuarios_escuelas.id_usuario=$_SESSION[id_usuarios]";
                    $res_lstMes = $mysqli->query($lstMes);
                  while ($row_res_lstEjer = $res_lstMes->fetch_array()) { 
                    ?>
                    <option value="<?php echo $row_res_lstEjer["id_org"] ?>"><? echo $row_res_lstEjer["nombre"] ?></option>
                    
                    <?
                  }
                  ?>
                  </select>

                
                </div>
</div>
<div id="resultado"></div>
   </div>



    <!-- Main content -->   

        <section class="content">





      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">

          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
           
              <h3>150</h3>
 
              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>53<sup style="font-size: 20px">%</sup></h3>

              <p>Bounce Rate</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>44</h3>

              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>65</h3>

              <p>Unique Visitors</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->

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
