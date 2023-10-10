<?php include("PHP/seguridad.php");
require_once 'PHP/funciones.php';
require_once 'PHP/insertar.php';




$medico = isset($_POST['medico']) ? limpia($_POST['medico']) : null;
$paciente = isset($_POST['paciente']) ? limpia($_POST['paciente']) : null;
$observaciones = isset($_POST['observaciones']) ? limpia($_POST['observaciones']) : null;

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
  $medico = isset($_GET['medico']) ? limpia($_GET['medico']) : null;
  $paciente = isset($_GET['paciente']) ? limpia($_GET['paciente']) : null;
  $observaciones = isset($_GET['observaciones']) ? limpia($_GET['observaciones']) : null;
}
$errores = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!validaRequerido($medico)) {
        $errores[] = 'Elija un medico.';
    }
    if (!validaRequerido($paciente)) {
        $errores[] = 'Elija un paciente.';
    }

    if (!validaRequerido($observaciones)) {
        $errores[] = 'El campo observaciones es requerido.';
    }

    //Verifica si ha encontrado errores y de no haber redirige a la página con el mensaje de que pasó la validación.
    if(!$errores){

        header('location:calendario_med.php?medico='.$medico.'&paciente='.$paciente.'&observaciones='.$observaciones);

    }
}
?>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/i_ci.css" />
    <head><title>Crear cita</title>
      <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script type="text/javascript">

      </script>
    </head>
      <body>
        <?php
          include("PHP/navegador_med.php");
        ?>

        <form action="crear_cita_med.php"  method="POST">

          <table id="tabla">
            <tr>
              <td><a>M&eacutedico</a></td>
              <td>
                <select  id="medico" name="medico">
                  <option value="">Elija un medico</option>
                  <?php

                      $db=new BaseDatos();
                      $db=$db->conectar();

                      $sql='SELECT * FROM usuario WHERE rol="1"';

                      $resultado=$db->query($sql);

                      if($resultado->num_rows>0){//Para ver si se ha insertado

                          while($fila= mysqli_fetch_assoc($resultado)){
                            $n=$fila['nombre'];
                            $u=$fila['usuario'];
                            $c=$fila['correo'];
                            $id=$fila['id'];

                            if($fila['fecha_baja']==="0000-00-00"){
                                if($id===$medico){
                                  echo    '<option value="'.$id.'" selected>'.$n.' '.$c.'</option>';
                                }else{
                                  echo    '<option value="'.$id.'">'.$n.' '.$c.'</option>';
                                }
                            }
                          }

                      }else if (!$resultado){
                          echo '<option> Error</option><br/>';
                          echo '<option>'.$sql.'</option>';
                          echo '<option>'.$db->error.'</option>';
                      }

                  ?>
                </select> <a id="cosa">*</a>
              </td>
            </tr>
            <tr>
              <td><a>Paciente</a></td>
              <td>
                <select name="paciente">
                  <option value="">Elija un paciente</option>
                  <?php

                      $db=new BaseDatos();
                      $db=$db->conectar();

                      $sql="SELECT * FROM paciente";

                      $resultado=$db->query($sql);

                      if($resultado->num_rows>0){//Para ver si se ha insertado


                          while($fila= mysqli_fetch_assoc($resultado)){
                            $n=$fila['nombre'];
                            $a=$fila['apellidos'];
                            $dni=$fila['documento_id'];
                            $id=$fila['id'];

                            if($id===$paciente){
                              echo    '<option value="'.$id.'" selected>'.$n.' '.$a.' :'.$dni.'</option>';
                            }else{
                              echo    '<option value="'.$id.'">'.$n.' '.$a.' :'.$dni.'</option>';
                            }
                          }

                      }else if (!$resultado){
                          echo '<option> Error</option><br/>';
                          echo $sql;
                          echo $db->error;
                      }

                  ?>

                </select> <a id="cosa">*</a>
              </td>
            </tr>
            <tr>
              <td><a>Observaciones</a></td>
              <td><textarea name="observaciones" rows="15" cols="30"><?php echo $observaciones; ?></textarea></td>
            </tr>


            <tr >
              <td colspan="2"><a id="obligatorio">Obligatorio rellenar los campos con <a  id="cosa"> *</a></a></td>
            </tr>


          </table>


            <input id="Registrar" type="submit" title=": D"  value="Siguiente">
        </form>
        <?php if ($errores): ?>
            <ul style="color: #f00;">
                <?php foreach ($errores as $error): ?>
                    <li> <?php echo $error ?> </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <footer>@Copyrigth</footer>

      </body>
</html>
