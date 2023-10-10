<?php include("PHP/seguridad.php");
require_once 'PHP/funciones.php';
require_once 'PHP/insertar.php';
include("PHP/navegador_med.php");
@session_start();
$medico=$_SESSION["id_usuario"];


$titulo = isset($_POST['titulo']) ? limpia($_POST['titulo']) : null;

$paciente = isset($_POST['paciente']) ? limpia($_POST['paciente']) : null;
$contenido = isset($_POST['contenido']) ? limpia($_POST['contenido']) : null;

$errores = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Valida que el campo nombre no esté vacío.
    if (!validaRequerido($titulo)) {
        $errores[] = 'Escriba un título';
    }

    if (!validaRequerido($paciente)) {
        $errores[] = 'Elija un paciente.';
    }

    if (!validaRequerido($contenido)) {
        $errores[] = 'Escriba el contenido del informe';
    }
    //Verifica si ha encontrado errores y de no haber redirige a la página con el mensaje de que pasó la validación.
    if(!$errores){

      $resultado=registrar_informe($titulo,$paciente,$medico,$contenido);
      if($resultado){
        header('Location:lista_informes.php');


      }else{
          $errores[] ="ERROR";
      }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/crear__informes.css" />
    <head>
      <meta charset="UTF-8">
      <title>Crear informes</title>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script type="text/javascript">

          function carga_pacientes(){
            console.log(1);
              $.ajax({
                      type: "GET",
                      url: 'PHP/carga_pacientes.php',
                      data: { 'paciente': $("#paciente").val()},
                      dataType:'json',
                      success: function(data) {

                        var select = $("#pacientes"), options = '';
                         select.empty();
                         if(data.length>0){
                             if($("#paciente").val()==""){
                               options += '<option selected value=""> </option>';
                             }
                             for(var i=0;i<data.length; i++){
                                  options += '<option value="'+data[i].id+'">'+data[i].nombre+' '+ data[i].apellidos +', dni: '+data[i].documento_id+' </option>';
                             }
                             select.append(options);
                         }else{
                           options+='<option value="">'+$("#paciente").val()+' no existe</option>';
                           select.append(options);
                         }
                      }
                  });
          }
      </script>
    </head>
      <body>



        <form action="crear_informes.php" id="datos"  method="POST">
            <table id="tabla">
              <tr>
                <td ><b>Titulo</b></td>
                <td><input type="text" name="titulo" value="<?php echo $titulo;?>"/><a id="cosa">*</a></td>
              </tr>
              <tr>
                <td><b>Paciente</b></td>
                <td>
                  <input type="text" id="paciente"  onchange="carga_pacientes()" placeholder="nombre, apellidos o dni" value="">
                  <select id="pacientes" name="paciente">
                    <option value=""></option>
                  </select> <a id="cosa">*</a>
                </td>
              </tr>
              <tr>
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
         <textarea id="contenido" form="datos" placeholder="Escriba aquí el contenido del informe" name="contenido" rows="50" cols="100"><?php echo $titulo;?></textarea>



        <footer>@Copyrigth</footer>

      </body>
</html>
