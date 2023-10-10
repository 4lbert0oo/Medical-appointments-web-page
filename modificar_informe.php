<?php include("PHP/seguridad.php");
require_once 'PHP/funciones.php';
require_once 'PHP/modificar.php';
require_once 'PHP/buscar.php';
        include("PHP/navegador_med.php");

$titulo = isset($_POST['titulo']) ? limpia($_POST['titulo']) : null;

$paciente = isset($_POST['paciente']) ? limpia($_POST['paciente']) : null;
$contenido = isset($_POST['contenido']) ? limpia($_POST['contenido']) : null;
$codigo = isset($_POST['codigo']) ? limpia($_POST['codigo']) : null;

$errores = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $titulo = isset($_GET['titulo']) ? limpia($_GET['titulo']) : null;

  $paciente = isset($_GET['paciente']) ? limpia($_GET['paciente']) : null;
  $contenido = isset($_GET['contenido']) ? limpia($_GET['contenido']) : null;
  $codigo = isset($_GET['codigo']) ? limpia($_GET['codigo']) : null;

    //Valida que el campo nombre no esté vacío.
    if (!validaRequerido($titulo)) {
        $errores[] = 'El campo titulo es requerido.';
    }

    if (!validaRequerido($contenido)) {
        $errores[] = 'El campo contenido es requerido.';
    }
    //Verifica si ha encontrado errores y de no haber redirige a la página con el mensaje de que pasó la validación.
    if(!$errores){

      $resultado=modificar_informe($titulo,$paciente,$contenido,$codigo);
      if($resultado){
        //header('Location:lista_informes.php');
        $errores[] ="Informe modificado";

      }else{
          $errores[] ="ERROR";
      }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/modificar_informes.css" />
    <head>
      <meta charset="UTF-8">
      <title>Modificar informe</title>
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



        <form action="modificar_informe.php" id="form"  method="GET">
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
                    <option value="<?php echo $paciente;?>"><?php $P=buscar_paciente($paciente); echo $P[0]." ".$P[1].": ".$P[2]; ?></option>
                  </select> <a id="cosa">*</a>
                </td>
              </tr>


              <tr>
                <td colspan="2"><a id="obligatorio">Obligatorio rellenar los campos con <a  id="cosa"> *</a></a></td>
              </tr>
            </table>

            <input type="hidden" name="codigo" value="<?php echo $codigo;?>"/>
            <input class="boton" id="Registrar" type="submit" title=": D"  value="Guardar cambios">
        </form>
        <?php if ($errores): ?>
            <ul style="color: #f00;">
                <?php foreach ($errores as $error): ?>
                    <li> <?php echo $error ?> </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
         <textarea id="contenido" form="form" name="contenido" rows="50" cols="100"><?php echo $contenido;?></textarea>


         <form action="lista_informes.php" method="post">
           <input type="submit" id="atras" class="boton"  value="Atras">
         </form>

      <footer>@Copyrigth</footer>

      </body>
</html>
