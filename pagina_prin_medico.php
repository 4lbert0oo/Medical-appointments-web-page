<?php include("PHP/seguridad.php");

require_once 'PHP/BaseDatos.php';
require_once 'PHP/buscar.php';

$mesActual=date("n");
$anioActual=date("Y");
$diaActual=date("j");

$fecha_actual=$anioActual."-".$mesActual."-".$diaActual;

$es_festivo=false;
$es_fin_semana=false;
@session_start();
if($_SESSION["rol"]==1){

  $festivos=array();
  $festivos_tarde=buscar_festivos_tarde($_SESSION["id_usuario"]);
  $festivos_maniana=buscar_festivos_maniana($_SESSION["id_usuario"]);
  $festivos=buscar_festivos($_SESSION["id_usuario"]);

  if(in_array($fecha_actual, $festivos)){
    $es_festivo=true;
  }
  if(in_array($fecha_actual, $festivos_tarde) and in_array($fecha_actual, $festivos_maniana)){
    $es_festivo=true;
  }

  if(es_finde_semana($fecha_actual,$_SESSION["id_usuario"]) ){
    $es_fin_semana=true;
  }

}
?>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/pp_medic.css" />
    <head><title>Pagina principal</title>
      <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script type="text/javascript">

      </script>
    </head>

      <body>

        <?php
          include("PHP/navegador_med.php");
        ?>
<div class="cuerpo">



<?php
echo "<br>";

if($_SESSION["rol"]==1){

    if(!$es_fin_semana)  {
      if($es_festivo){


        echo "<br>";
        echo "Dia festivo";
        echo "<br>";
        echo "Día libre";
      }else{
        $horario=buscar_horario($_SESSION["id_usuario"]);
        $meses=array(1=>"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
    		"Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
      ?>
              <br>
              <table id="horario">
                <tr>
                  <th><?php echo "  ".$diaActual." de ".$meses[$mesActual]." de ".$anioActual; ?></th>
                </tr>
                <tr>
                  <th>|  Horario Mañana  |</th>
                  <th>|  Horario Tarde   |</th>
                </tr>
                <tr>
                  <td><?php
                    if(in_array($fecha_actual, $festivos_maniana)){
                      echo "Mañana libre";
                    }else{
                      echo $horario[0]; ?> - <?php echo $horario[1];
                    }
                  ?></td>
                  <td><?php
                  if(in_array($fecha_actual, $festivos_tarde)){
                    echo "Tarde libre";
                  }else{
                    echo $horario[2]; ?> - <?php echo $horario[3];
                  }
                  ?></td>
                </tr>
              </table>
              <br>
              <?php
              if(in_array($fecha_actual, $festivos)){
                $es_festivo=true;
              }

        $db=new BaseDatos();
        $db=$db->conectar();

        $hoy=$anioActual."-".$mesActual."-".$diaActual;
        $medico=trim($_SESSION["id_usuario"]);
        $sql ="SELECT * FROM cita where fecha='$hoy' and medico='$medico'";

        echo "<br>";
        $r=$db->query($sql);
        if($r->num_rows>0){
          echo '<table id="citas" >';
          echo "<tr>";
          echo '<th colspan="2">Citas de hoy</th>';
          echo "</tr>";

          while($fila= mysqli_fetch_assoc($r)){
            $p=$fila['paciente'];
            $array=buscar_paciente($p);
            $n=$array[0];
            $a=$array[1];
            $d=$array[2];
            $hora=$fila['hora'];

            echo "<tr>";
            echo "<td> -- ".$n." ".$a." ".$d."  a las ".$hora."</td>";
            ?>
            <td>
              <form class="" action="lista_citas.php" method="get">
                <input type="hidden" name="codigo" value="<?php echo $fila['codigo'];?>">
                <input type="submit" class="boton" name="ver_cita" value="Ver cita">
              </form>
            </td>
            <?php
            echo "</tr>";
          }
          echo "</table>";

        }else{
          echo "<br>";
          echo '<a id="citas">Hoy no tienes citas</a>';
        }
      }
    }else{
      echo "<br>";
      echo "<a>Es fin de semana. No hay trabajo </a>";
    }
}
?>

        <footer>@Copyrigth</footer>
      </div>
      </body>
</html>
