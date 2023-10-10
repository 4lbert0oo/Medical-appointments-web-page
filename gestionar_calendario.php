<?php
require_once 'PHP/BaseDatos.php';
require_once 'PHP/buscar.php';
require_once 'PHP/funciones.php';
include("PHP/seguridad.php");
$medic = isset($_POST['medic']) ? limpia($_POST['medic']) : null;
?>
<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/gestionar_calendario.css" />
    <head><meta charset="utf-8"><title>Calendarios</title>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script type="text/javascript">
        function carga_medicos(){
          console.log(1);
            $.ajax({
                    type: "GET",
                    url: 'PHP/carga_medicos.php',
                    data: { 'medico': $("#medico").val()},
                    dataType:'json',
                    success: function(data) {
                      console.log(2);
                      console.log(data);
                      var select = $("#medicos"), options = '';
                       select.empty();
                       if(data.length>0){
                           for(var i=0;i<data.length; i++){
                                options += '<option value="'+data[i].id+'">'+data[i].nombre+', '+ data[i].usuario +' correo: '+data[i].correo+' </option>';
                           }
                           select.append(options);
                       }else{
                         options+='<option>'+$("#medico").val()+' no existe</option>';
                         select.append(options);
                       }
                    }
                });
        }
      </script>
    </head>
      <body>


        <?php include("PHP/navegador_med.php"); ?>
<div class="cuerpo">
        <form action="" method="get">
          <div id="buscar" >

              Medico:<input type="text" id="medico"  onchange="carga_medicos()" placeholder="escriba el nombre del medico" value="">
              <select id="medicos" name="medic">

              </select>
               <input type="submit" name="horarios" class="botones"  value="Buscar">

          </div>
        </form>

<table id="tabla">
  <tr>
    <th>Medico</th>
    <th>Horario&nbsp;mañana</th>
    <th>Horario&nbsp;tarde</th>
    <th>Sabado</th>
    <th>Domingo</th>
    <th>Duracion&nbsp;cita</th>
    <th colspan="2" >Acciones</th>
  </tr>
<?php



  $db=new BaseDatos();
  $db=$db->conectar();
  $sql="SELECT * FROM calendario order by id DESC";

if(isset($_GET['horarios'])){

  $MEDIC=isset($_GET['medic'])? limpia($_GET['medic']) : null;
  if($MEDIC!=""){
    $sql="SELECT * FROM calendario WHERE medico='$MEDIC'";
  }

}
  $resultado=$db->query($sql);

  if($resultado->num_rows>0){//Para ver si se ha insertado
      $color="lightblue";
      $i=2;
      while($fila= mysqli_fetch_assoc($resultado)){
          if($i%2===0){
            $color="#59C5F3";
          }else{
            $color="#9DDEFA";
          }
          $i=$i+1;
          $id=$fila['id'];

          $med=trim($fila['medico']);


            $nombre=buscar_usuario($med)[0];
            $usuario=buscar_usuario($med)[1];
            $medico=$nombre.", ".$usuario;


          $hora_i_m=$fila['hora_inicio_ma'];
          $H= explode(":",$hora_i_m);
          $h1=$H[0];
          $m1=$H[1];

          $hora_f_m=$fila['hora_fin_ma'];
          $H= explode(":",$hora_f_m);
          $h2=$H[0];
          $m2=$H[1];

          $hora_i_t= $fila['hora_inicio_tard'];
          $H= explode(":",$hora_i_t);
          $h3=$H[0];
          $m3=$H[1];

          $hora_f_t= $fila['hora_fin_tard'];
          $H= explode(":",$hora_f_t);
          $h4=$H[0];
          $m4=$H[1];

          $hora_m=$hora_i_m."-".$hora_f_m;
          $hora_t=$hora_i_t."-".$hora_f_t;

          $sabado=$fila['sabado_h'];
          $domingo=$fila['domingo_h'];
          $duracion=$fila['duracion_cita'];


          echo '<tr style="background-color:'.$color.'">';
          echo    "<td>$medico</td>";
          echo    "<td>$hora_m</td>";
          echo    "<td>".$hora_t."</td>";
          if($sabado=='0'){
            echo    "<td>Libre</td>";
          }else{
            echo    "<td>Ocupado</td>";
          }

          if($domingo=='0'){
            echo    "<td>Libre</td>";
          }else{
            echo    "<td>Ocupado</td>";
          }
          echo    "<td>$duracion</td>";

      ?>
            <td>
              <form action="modificar_calendario.php"    method="post">
                <input type="hidden"  value="<?php echo $id;      ?>" name="id"  />
                <input type="hidden"  value="<?php echo $h1; ?>" name="h1"  />
                <input type="hidden"  value="<?php echo $m1; ?>" name="m1"  />
                <input type="hidden"  value="<?php echo $h2; ?>" name="h2"  />
                <input type="hidden"  value="<?php echo $m2; ?>" name="m2"  />
                <input type="hidden"  value="<?php echo $h3; ?>" name="h3"  />
                <input type="hidden"  value="<?php echo $m3; ?>" name="m3"  />
                <input type="hidden"  value="<?php echo $h4; ?>" name="h4"  />
                <input type="hidden"  value="<?php echo $m4; ?>" name="m4"  />

                <input type="hidden"  value="<?php echo $duracion; ?>" name="duracion"  />
                <input type="hidden" name="sabado" value="<?php echo $sabado; ?>">
                <input type="hidden" name="domingo" value="<?php echo $domingo; ?>">
                <input type="submit" class="botones" value="Modificar" name="modificar_calendario" title="Modificar" />
              </form>
            </td>
            <td>
              <form  action="lista_usuarios.php" method="get" target="_blank">
                <input type="hidden" name="id" value="<?php echo $med; ?>">
                <input type="submit" class="botones" name="ver_medico" value="Ver medico">
              </form>
            </td>

          <?php


          echo "</tr>";
        }

  }else if (!$resultado){
      echo '<p> Error</p><br/>';
      echo $sql;
      echo $db->error;
  }else if($resultado->num_rows===0){
    echo' <tr> <td colspan="7"style="background-color: lightblue"> Sin resultados</td></tr> ';
  }



?>

        </table>

        <footer>@Copyrigth</footer>
</div>
      </body>
</html>
