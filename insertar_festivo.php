<?php include("PHP/seguridad.php");
require_once 'PHP/funciones.php';
require_once 'PHP/insertar.php';
include("PHP/navegador_med.php");

$anio = isset($_POST['anios']) ? limpia($_POST['anios']) : null;
$mes = isset($_POST['mes']) ? limpia($_POST['mes']) : null;
$dia = isset($_POST['dia']) ? limpia($_POST['dia']) : null;
$fecha =$anio."-".$mes."-".$dia;
$tipo = isset($_POST['tipo']) ? limpia($_POST['tipo']) : null;
$medico = isset($_POST['medico']) ? limpia($_POST['medico']) : null;

$errores = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Valida que el campo fecha contenga una fecha correcta dd/mm/yyyy.
    if (!validaRequerido($fecha))
        $errores[] = 'El campo fecha es requerido.';
    else if (!validaFecha($fecha)) {
        $errores[] = 'El campo fecha es incorrecto.';
    }
    //Verifica si ha encontrado errores y de no haber redirige a la página con el mensaje de que pasó la validación.
    if(!$errores){

      $resultado=registrar_festivos($fecha,$tipo,$medico);
      if($resultado){
        $errores[] = "Festivo insertado";
        header('Location:lista_festivos.php');
      }else{
          $errores[] ="ERROR";
      }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/insertar_fest.css" />
    <head>
      <meta charset="UTF-8">
      <title>Registrar pacientes</title>
      <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script type="text/javascript">
          function cargar_dias(){
            var mes=$("#mes").val();
            var select = $("#dia"), options = '';
            select.empty();
            if(mes==1 || mes==3 || mes==5 || mes==7 || mes==8 || mes==10 || mes==12){


              for(var i=1;i<32;i++){
                options+='<option value="'+i+'">'+i+'</option>';
              }
              select.append(options);

            }else if(mes==4 || mes==6 || mes==9 || mes==11){


              for(var i=1;i<31;i++){
                options+='<option value="'+i+'">'+i+'</option>';
              }
              select.append(options);

            }else if(mes==2){


              for(var i=1;i<29;i++){
                options+='<option value="'+i+'">'+i+'</option>';
              }
              select.append(options);

            }
          }
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
                             options += '<option selected value="">Todos </option>';
                             select.append(options);
                         }else{
                           options+='<option value="">'+$("#medico").val()+' no existe</option>';
                           select.append(options);
                         }
                      }
                  });
          }
      </script>
    </head>
      <body>

        <form action="insertar_festivo.php" id="form" method="POST">
            <table id="tabla">
              <tr>
                <td ><a>Fecha</a></td>
                <td><select name="anios" id="anios" >
                  <?php
                  for ($i=2020; $i <2030 ; $i++) {
                    if($i==$anio){
                      echo '<option selected value="'.$i.'">'.$i.'</option>';
                    }else{
                      echo '<option value="'.$i.'">'.$i.'</option>';
                    }
                  }
                  ?>
                </select>
                - <select id="mes" name="mes" onchange="cargar_dias()">
                  <?php
                  $MESES=['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                  for ($i=1; $i <12 ; $i++) {
                    if($i==$mes){
                      echo '<option selected value="'.$i.'">'.$MESES[$i].'</option>';
                    }else{
                      echo '<option value="'.$i.'">'.$MESES[$i].'</option>';
                    }
                  }
                  ?>
                </select>
                - <select name="dia" id="dia" size="1">
                    <?php
                    for ($i=1; $i <32 ; $i++) {
                      if($i==$dia){
                        echo '<option selected value="'.$i.'">'.$i.'</option>';
                      }else{
                        echo '<option value="'.$i.'">'.$i.'</option>';
                      }
                    }
                    ?>

                  </select><a id="cosa">*</a></td>
              </tr>
              <tr>
                <td><b>Medico</b></td>
                <td>
                  <input type="text" id="medico"  onchange="carga_medicos()" placeholder="nombre o usuario" value=""> <br>
                	<select id="medicos" name="medico">
                		<option value="">todos</option>
                	</select> <a id="cosa">*</a>
                </td>
              </tr>
              <tr>
                <td> <b>Tipo</b></td>
                  <td> <select  name="tipo">
                    <option value="completo">completo</option>
                    <option value="maniana">maniana</option>
                    <option value="tarde">tarde</option>
                  </select></td>

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

        <footer>@Copyrigth</footer>


      </body>
</html>
