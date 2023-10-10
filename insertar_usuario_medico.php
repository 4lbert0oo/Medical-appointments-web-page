<?php
include("PHP/navegador_med.php");
include("PHP/seguridad.php");
require_once 'PHP/funciones.php';
require_once 'PHP/insertar.php';

?>

<html lang="es">
<link rel="stylesheet" type="text/css" href="css/insertar_usuario_medi.css" />
    <head><meta charset="utf-8"><title>Registrar usuario</title>

      <script type="text/javascript" >

        function algo(){
          var vector=["Alergología","Análisis Clínicos","Cardiología","Neurología","Oftalmología","Otorrinolaringología","Psicología","Psiquiatría","Reumatología","Traumatología","Urología"];
          var rol= document.getElementById("rol").value;

          if(rol==1){
            var select = document.getElementById("especialidades");
            for(var i=0;i<vector.length; i++){
                var opts = document.createElement("option");
                 opts.text =vector[i] ;
                 select.options.add(opts, 0);
            }
          }else{
            var select = document.getElementById("especialidades");
            console.log(select.length);
            for(var i=0;i<11; i++){
                console.log(i);
                select.options.remove(0);
            }
          }
        }
      </script>
    </head>
      <body>

<?php

$nombre = isset($_POST['nombre']) ? limpia($_POST['nombre']) : null;
$usuario = isset($_POST['usuario']) ? limpia($_POST['usuario']) : null;
$clave = isset($_POST['clave']) ? limpia($_POST['clave']) : null;
$rol = isset($_POST['rol']) ? limpia($_POST['rol']) : null;
$especialidad = isset($_POST['especialidad']) ? limpia($_POST['especialidad']) : null;
$correo = isset($_POST['correo']) ? limpia($_POST['correo']) : null;


$errores = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Valida que el campo nombre no esté vacío.
    if (!validaRequerido($nombre)) {
        $errores[] = 'El campo nombre es requerido.';
    }

    if (!validaRequerido($usuario)) {
        $errores[] = 'El campo usuario es requerido.';
    }

    if (!validaRequerido($clave)) {
        $errores[] = 'El campo clave es requerido.';
    }elseif (validaLongitud($clave,3,20)) {
        $errores[] = 'La clave ha de tener entre 3 y 20 caracteres.';
    }

    if ($rol==="1" and !validaRequerido($especialidad)) {
        $errores[] = 'El campo especialidad es requerido.';
    }

    if (!validaRequerido($correo)) {
        $errores[] = 'El campo correo es requerido.';
    }else if(!validaEmail($correo)){
        $errores[] = 'Correo no valido.';
    }

    //Verifica si ha encontrado errores y de no haber redirige a la página con el mensaje de que pasó la validación.
    if(!$errores){

      $resultado=registrar_usuario($nombre,$usuario,$clave,$rol,$especialidad,$correo,$fecha_b);
      if($resultado){
        header('Location:lista_usuarios.php');

      }else{
          $errores[] ="Ese usuario ya esta usado";

      }
    }
}
?>

        <form action="insertar_usuario_medico.php"  method="POST">
            <table id="tabla">
              <tr>
                <td> <a>Nombre</a> </td>
                <td>
                  <input type="text" name="nombre" value="<?php echo $nombre; ?>"><a id="cosa">*</a>
              </td>
              </tr>
              <tr>
                <td ><a>Usuario</a></td>
                <td><input type="text" name="usuario" value="<?php echo $usuario; ?>"/><a id="cosa">*</a></td>
              </tr>
              <tr>
                <td><a>Clave</a></td>
                <td><input type="password" name="clave" value=""/><a id="cosa">*</a></td>
              </tr>
              <tr>
                <td><a>Rol</a></td>
                <td>
                  <select onchange="javascript:algo()" id="rol"  name="rol">
                    <option  value="0">Administrador</option>
                    <option  value="1">M&eacutedico</option>
                  </select> <a id="cosa">*</a></td>
              </tr>
              <tr>
                <td><a>Especialidad</a></td>
                <td>
                  <select id="especialidades" name="especialidad">

                  </select><a id="cosa">*</a>
                </td>
              </tr>
              <tr>
                <td><a>Correo</a></td>
                <td><input type="email" name="correo" value="<?php echo $correo; ?>"/><a id="cosa">*</a></td>
              </tr>

              <tr >
                <td colspan="2"><a id="obligatorio">Obligatorio rellenar los campos con <a  id="cosa"> *</a></a></td>
              </tr>
            </table>

            <input id="Registrar" type="submit"  title=": D"  value="Registrar">
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
