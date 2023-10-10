<?php include("PHP/seguridad.php");
require_once 'PHP/funciones.php';
require_once 'PHP/modificar.php';
require_once 'PHP/buscar.php';

$anio = isset($_POST['anios']) ? limpia($_POST['anios']) : null;
$mes = isset($_POST['mes']) ? limpia($_POST['mes']) : null;
$dia = isset($_POST['dia']) ? limpia($_POST['dia']) : null;
$fecha =$anio."-".$mes."-".$dia;

$tipo = isset($_POST['tipo']) ? limpia($_POST['tipo']) : null;
$medico = isset($_POST['medico']) ? limpia($_POST['medico']) : null;
$codigo = isset($_POST['codigo']) ? limpia($_POST['codigo']) : null;

$errores = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $anio = isset($_GET['anios']) ? limpia($_GET['anios']) : null;
  $mes = isset($_GET['mes']) ? limpia($_GET['mes']) : null;
  $dia = isset($_GET['dia']) ? limpia($_GET['dia']) : null;
  $fecha =$anio."-".$mes."-".$dia;
  $tipo = isset($_GET['tipo']) ? limpia($_GET['tipo']) : null;
  $medico = isset($_GET['medico']) ? limpia($_GET['medico']) : null;
  $codigo = isset($_GET['codigo']) ? limpia($_GET['codigo']) : null;

    //Valida que el campo nombre no esté vacío.
    if (!validaRequerido($medico)) {
        $errores[] = 'El campo medico es requerido.';
    }
    //Valida que el campo fecha contenga una fecha correcta dd/mm/yyyy.
    if (!validaRequerido($fecha))
        $errores[] = 'El campo fecha es requerido.';
    else if (!validaFecha($fecha)) {
        $errores[] = 'El campo fecha es incorrecto.';
    }
    //Verifica si ha encontrado errores y de no haber redirige a la página con el mensaje de que pasó la validación.
    if(!$errores){

      $resultado=modificar_festivos($codigo,$fecha,$tipo,$medico);
      if($resultado){
        //header('Location:lista_festivos.php');
        $errores[] = "Festivo modificado";

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
    </head>
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
      <body>

        <?php include("PHP/navegador_med.php"); ?>

        <form action="modificar_festivos.php" id="form" method="GET">
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
                		<option value="<?php echo $medico; ?>"><?php if($medico!=""){echo buscar_usuario($medico)[0]." :".buscar_usuario($medico)[1];}else{
                      echo "Todos";
                    } ?></option>
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

              <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">

              <tr>
                <td colspan="2"><a id="obligatorio">Obligatorio rellenar los campos con <a  id="cosa"> *</a></a></td>
              </tr>
            </table>

            <input id="Registrar" type="submit"  title=": D"  value="Modificar">
        </form>
        <?php if ($errores): ?>
            <ul style="color: #f00;">
                <?php foreach ($errores as $error): ?>
                    <li> <?php echo $error ?> </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form class="" action="lista_festivos.php" method="post">
          <input type="submit" id="Registrar" value="Atras">
        </form>

        <footer>@Copyrigth</footer>


      </body>
</html>
