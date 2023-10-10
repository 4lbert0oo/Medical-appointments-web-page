<?php include("PHP/seguridad.php");
require_once 'PHP/funciones.php';
require_once 'PHP/buscar.php';
require_once 'PHP/modificar.php';

$id       =isset($_POST['id']) ? limpia($_POST['id']) : null;
$h1=isset($_POST['h1']) ? limpia($_POST['h1']) : null;
$m1=isset($_POST['m1']) ? limpia($_POST['m1']) : null;

$h2=isset($_POST['h2']) ? limpia($_POST['h2']) : null;
$m2=isset($_POST['m2']) ? limpia($_POST['m2']) : null;

$h3=isset($_POST['h3']) ? limpia($_POST['h3']) : null;
$m3=isset($_POST['m3']) ? limpia($_POST['m3']) : null;

$h4=isset($_POST['h4']) ? limpia($_POST['h4']) : null;
$m4=isset($_POST['m4']) ? limpia($_POST['m4']) : null;

$duracion =isset($_POST['duracion']) ? limpia($_POST['duracion']) : null;
$sabado =isset($_POST['sabado']) ? limpia($_POST['sabado']) : null;
$domingo =isset($_POST['domingo']) ? limpia($_POST['domingo']) : null;
$errores = array();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $id=isset($_GET['id']) ? limpia($_GET['id']) : null;

  $h1=isset($_GET['h1']) ? limpia($_GET['h1']) : null;
  $m1=isset($_GET['m1']) ? limpia($_GET['m1']) : null;
  $hora_i_m=$h1.":".$m1;

  $h2=isset($_GET['h2']) ? limpia($_GET['h2']) : null;
  $m2=isset($_GET['m2']) ? limpia($_GET['m2']) : null;
  $hora_f_m=$h2.":".$m2;

  $h3=isset($_GET['h3']) ? limpia($_GET['h3']) : null;
  $m3=isset($_GET['m3']) ? limpia($_GET['m3']) : null;
  $hora_i_t=$h3.":".$m3;

  $h4=isset($_GET['h4']) ? limpia($_GET['h4']) : null;
  $m4=isset($_GET['m4']) ? limpia($_GET['m4']) : null;
  $hora_f_t=$h4.":".$m4;

  $sabado =isset($_GET['sabado']) ? limpia($_GET['sabado']) : null;
  $domingo =isset($_GET['domingo']) ? limpia($_GET['domingo']) : null;

  $duracion=isset($_GET['duracion']) ? limpia($_GET['duracion']) : null;

    //Valida que el campo nombre no esté vacío.
    if (!validaRequerido($hora_i_m)) {
        $errores[] = 'El horario de mañana no esta completo';
    }elseif (validaRequerido($hora_i_m) and !checktime($hora_i_m)) {
        $errores[] = 'Horario de mañana no valido.';
    }

    if (!validaRequerido($hora_f_m)) {
        $errores[] = 'El horario de mañana no esta completo';
    }elseif (validaRequerido($hora_f_m) and !checktime($hora_f_m)) {
        $errores[] = 'Horario de mañana no valido.';
    }

    if (!validaRequerido($hora_i_t)) {
        $errores[] = 'El horario de tarde no esta completo';
    }elseif (validaRequerido($hora_i_t) and !checktime($hora_i_t)) {
        $errores[] = 'Horario de tarde no valido.';
    }

    if (!validaRequerido($hora_f_t)) {
        $errores[] = 'El horario de tarde no esta completo';
    }elseif (validaRequerido($hora_f_t) and !checktime($hora_f_t)) {
        $errores[] = 'Horario de tarde no valido.';
    }

    if (!validaRequerido($duracion)) {
        $errores[] = 'El campo duracion es requerido.';
    }else if(!validaLongitud($duracion,15,120)) {
      $errores[] = 'La duracion ha de ser entre 15 y 120 minutos.';
    }


    //Verifica si ha encontrado errores y de no haber redirige a la página con el mensaje de que pasó la validación.
    if(!$errores){

      $resultado=modificar_calendario($id,$hora_i_m,$hora_f_m,$hora_i_t,$hora_f_t,$sabado,$domingo,$duracion);
      if($resultado){
      //  header('Location:gestionar_calendario.php');
      $errores[]="Calendario modificado";
      }else{
          $errores[] ="ERROR";
      }
    }
}
?>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/modificar_calendario.css" />
    <head><title>Modificar calendario</title>
    </head>
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript">

    </script>
    <body>

      <?php include("PHP/navegador_med.php");
        ?>
        <form action="modificar_calendario.php" id="form"  method="GET">
          <table id="tabla">

            <tr>
              <td ><a>Horario mañana</a></td>
              <td> <input type="number" min="0" max="12" name="h1" value="<?php echo $h1;?>">: <input type="number" max="30" min="0" step="30" name="m1" value="<?php echo $m1;?>">-
                <input type="number" min="1" max="13" name="h2" value="<?php echo $h2;?>">:<input type="number" max="30" min="0" step="30" name="m2" value="<?php echo $m2;?>">
                <a id="cosa">*</a></td>
            </tr>
            <tr>
              <td ><a>Horario tarde</a></td>
              <td> <input type="number" min="13" max="22" name="h3" value="<?php echo $h3;?>">: <input type="number" max="30" min="0" step="30" name="m3" value="<?php echo $m3;?>">-
                <input type="number" min="14" max="23" name="h4" value="<?php echo $h4;?>">:<input type="number" max="30" min="0" step="30" name="m4" value="<?php echo $m4;?>">
                <a id="cosa">*</a></td>
            </tr>
            <tr>
              <td><a>Sabado habil</a></td>
              <td>
                <select  name="sabado" >
                  <?php
                  if($sabado=="0"){
                    echo '<option selected value="0">Libre</option>';
                    echo ' <option value="1">Ocupado</option>';
                  }else{
                    echo '<option value="0">Libre</option>';
                    echo ' <option selected  value="1">Ocupado</option>';
                  }

                  ?>
                </select>

              <a id="cosa">*</a></td>
            </tr>
            <tr>
              <td><a>Domingo habil</a></td>
              <td>
                <select name="domingo" >
                  <?php
                  if($domingo=="0"){
                    echo '<option selected value="0">Libre</option>';
                    echo ' <option value="1">Ocupado</option>';
                  }else{
                    echo '<option value="0">Libre</option>';
                    echo ' <option selected  value="1">Ocupado</option>';
                  }

                  ?>
                </select>
              <a id="cosa">*</a></td>
            </tr>
            <tr>
              <td><a>Duracion</a></td>
              <td><input type="number" name="duracion" min="30" step="30" max="120" value="<?php echo $duracion; ?>"/><a id="cosa">*</a></td>
            </tr>

          </table>
          <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input id="Registrar" type="submit"  title=": D" onclick="return confirm('¿Quieres guardar los cambios?')" value="Guardar cambios">
        </form>

        <?php if ($errores): ?>
            <ul style="color: #f00;">
                <?php foreach ($errores as $error): ?>
                    <li> <?php echo $error ?> </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <form action="gestionar_calendario.php" method="post">
          <input type="submit" id="Registrar"  value="Atras">
        </form>
    <footer>@Copyrigth</footer>

  </body>
</html>
