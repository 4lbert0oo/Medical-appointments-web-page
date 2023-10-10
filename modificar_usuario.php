<?php include("PHP/seguridad.php");
require_once 'PHP/funciones.php';
require_once 'PHP/modificar.php';

$nombre = isset($_POST['nombre']) ? limpia($_POST['nombre']) : null;
$usuario = isset($_POST['usuario']) ? limpia($_POST['usuario']) : null;
$clave = isset($_POST['clave']) ? limpia($_POST['clave']) : null;
$especialidad = isset($_POST['especialidad']) ? limpia($_POST['especialidad']) : null;
$correo = isset($_POST['correo']) ? limpia($_POST['correo']) : null;

$nueva_clave=isset($_POST['nueva_clave']) ? limpia($_POST['nueva_clave']) : null;
$id= isset($_POST['id']) ? limpia($_POST['id']) : null;

$errores = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $nombre = isset($_GET['nombre']) ? limpia($_GET['nombre']) : null;
  $usuario = isset($_GET['usuario']) ? limpia($_GET['usuario']) : null;
  $clave = isset($_GET['clave']) ? limpia($_GET['clave']) : null;
  $especialidad = isset($_GET['especialidad']) ? limpia($_GET['especialidad']) : null;
  $correo = isset($_GET['correo']) ? limpia($_GET['correo']) : null;

  $nueva_clave=isset($_GET['nueva_clave']) ? limpia($_GET['nueva_clave']) : null;
  $id= isset($_GET['id']) ? limpia($_GET['id']) : null;

    //Valida que el campo nombre no esté vacío.
    if (!validaRequerido($nombre)) {
        $errores[] = 'El campo nombre es requerido.';
    }

    if (!validaRequerido($usuario)) {
        $errores[] = 'El campo usuario es requerido.';
    }

  if (validaRequerido($nueva_clave) and validaLongitud($nueva_clave,3,20)) {
        $errores[] = 'La clave ha de tener entre 3 y 20 caracteres.';
    }

    if (!validaRequerido($especialidad)) {
        $errores[] = 'El campo especialidad es requerido.';
    }

    if (!validaRequerido($correo)) {
        $errores[] = 'El campo correo es requerido.';
    }else if(!validaEmail($correo)){
        $errores[] = 'Correo no valido.';
    }

    //Verifica si ha encontrado errores y de no haber redirige a la página con el mensaje de que pasó la validación.
    if(!$errores){

      $resultado=modificar_usuario($nombre,$usuario,$clave,$nueva_clave,$especialidad,$correo,$id);
      if($resultado){
        //header('Location:lista_usuarios.php');
        $errores[]="Usuario modificado";

      }else{
          $errores[] ="Ese usuario ya esta usado";

      }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/mod_u.css" />
    <head>
      <meta charset="UTF-8">
      <title>Modificar usuario</title>
    </head>
      <body>

        <?php include("PHP/navegador_med.php");
          ?>
          <form action="modificar_usuario.php" id="modificar" method="GET">
              <table id="tabla">
                <tr>
                  <td ><a>Nombre</a></td>
                  <td><input type="text" name="nombre" value="<?php echo $nombre; ?>"/><a id="cosa">*</a></td>
                </tr>
                <tr>
                  <td><a>Usuario</a></td>
                  <td><input type="text" name="usuario" value="<?php echo $usuario; ?>"/><a id="cosa">*</a></td>
                </tr>
                <input  type="hidden" name="id" value="<?php echo $id; ?>"/>
                <tr>
                  <td><a>Nueva Clave</a></td>
                  <td><input id="clave" type="password" name="nueva_clave" value=""/></td>
                </tr>
                <input  type="hidden" name="clave" value="<?php echo $clave; ?>"/>

                <tr>
                  <td><a>Especialidad</a></td>
                  <td>
                    <select id="especialidades"  name="especialidad">
                      <?php
                      if($especialidad!=""){
                          echo '<option value="Alergología">Alergología</option>';
                          echo '<option value="Análisis Clínicos">Análisis Clínicos</option>';
                          echo '<option value="Cardiología">Cardiología</option>';
                          echo '<option value="Neurología">Neurología</option>';
                          echo '<option value="Oftalmología">Oftalmología</option>';
                          echo '<option value="Otorrinolaringología">Otorrinolaringología</option>';
                          echo '<option value="Psicología">Psicología</option>';
                          echo '<option value="Psiquiatría">Psiquiatría</option>';
                          echo '<option value="Reumatología">Reumatología</option>';
                          echo '<option value="Traumatología">Traumatología</option>';
                          echo '<option value="Urología">Urología</option>';
                        }
                      ?>
                    </select><a id="cosa">*</a>
                  </td>
                </tr>

                <tr>
                  <td><a>Correo</a></td>
                  <td>  <input type="email" name="correo" value="<?php echo $correo; ?>"/><a id="cosa">*</a></td>
                </tr>

                <tr>
                  <td colspan="2"><a id="obligatorio">Obligatorio rellenar los campos con <a  id="cosa"> *</a></a></td>
                </tr>
              </table>

              <input id="Registrar" type="submit"  title=": D" onclick="return confirm('¿Quieres guardar los cambios?')" value="Guardar cambios">
          </form>
          <?php if ($errores): ?>
              <ul style="color: #f00;">
                  <?php foreach ($errores as $error): ?>
                      <li> <?php echo $error ?> </li>
                  <?php endforeach; ?>
              </ul>
          <?php endif; ?>
          <form action="lista_usuarios.php" method="post">
            <input type="submit" id="atras"  value="Atras">
          </form>
      <footer>@Copyrigth</footer>

    </body>
</html>
