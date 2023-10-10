<?php include("PHP/seguridad.php");

 include("PHP/navegador_med.php");
require_once 'PHP/funciones.php';
require_once 'PHP/insertar.php';
require_once 'PHP/BaseDatos.php';

$nombre = isset($_POST['nombre']) ? limpia($_POST['nombre']) : null;
$apellidos = isset($_POST['apellidos']) ? limpia($_POST['apellidos']) : null;
$documento_id = isset($_POST['DNI']) ? limpia($_POST['DNI']) : null;
$tipo = isset($_POST['tipo']) ? limpia($_POST['tipo']) : null;
$direccion = isset($_POST['direccion']) ? limpia($_POST['direccion']) : null;
$localidad = isset($_POST['localidad']) ? limpia($_POST['localidad']) : null;
$provincia = isset($_POST['provincia'])? limpia($_POST['provincia']) : null;
$pais = isset($_POST['pais'])? limpia($_POST['pais']) : null;
$anio = isset($_POST['anios']) ? limpia($_POST['anios']) : null;
$mes = isset($_POST['mes']) ? limpia($_POST['mes']) : null;
$dia = isset($_POST['dia']) ? limpia($_POST['dia']) : null;
$fecha_n =$anio."-".$mes."-".$dia;

$errores = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Valida que el campo nombre no esté vacío.
    if (!validaRequerido($nombre)) {
        $errores[] = 'El campo nombre es requerido.';
    }

    if (!validaRequerido($apellidos)) {
        $errores[] = 'El campo apellidos es requerido.';
    }

    if (!validaRequerido($documento_id)) {
        $errores[] = 'El campo Documento id es requerido.';
    }else if(validaRequerido($documento_id) and  !validar_dni($documento_id,"",$tipo)){
        $errores[] = $tipo.' no valido.';
    }

    if (!validaRequerido($direccion)) {
        $errores[] = 'El campo direccion es requerido.';
    }

    if (!validaRequerido($localidad)) {
        $errores[] = 'El campo localidad es requerido.';
    }

    if (!validaRequerido($provincia)) {
        $errores[] = 'El campo provincia es requerido.';
    }
    if (!validaRequerido($pais)) {
        $errores[] = 'El campo pais es requerido.';
    }


    //Valida que el campo fecha contenga una fecha correcta dd/mm/yyyy.
    if (!validaRequerido($fecha_n))
        $errores[] = 'El campo fecha es requerido.';
    else if (!validaFecha($fecha_n)) {
        $errores[] = 'El campo fecha es incorrecto.';
    }
    //Verifica si ha encontrado errores y de no haber redirige a la página con el mensaje de que pasó la validación.
    if(!$errores){

      $resultado=registrar_paciente($nombre,$apellidos,$documento_id,$tipo,$fecha_n,$direccion,$localidad,$provincia,$pais);
      if($resultado){
        header('Location:lista_pacientes_med.php');
        $errores[] = "Paciente insertado";

      }else{
          $errores[] ="ERROR";
      }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/insertar_pacien.css" />
    <head>
      <title>Registrar pacientes</title>
      <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
      <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script type="text/javascript">

        function esconder(jQuery){
            $('.manual').show();
            $('.selectores').hide();
        }
            $(document).ready(esconder);

          function cargaProvincias(){
              var pais=$("#pais").val();

              if(pais=="España"){
                $('.manual').hide();
                $('.selectores').show();
                  $.ajax({
                          type: "GET",
                          url: 'PHP/cargaProvincias.php',
                          dataType:'json',

                          success: function(data) {

                             var select = $("#provincias"), options = '';
                             select.empty();

                             for(var i=0;i<data.length; i++)
                             {
                                  if (i==0){
                                    options += "<option selected value='"+data[i].id+"'>"+ data[i].nombre +"</option>";
                                  }else{
                                    options += "<option value='"+data[i].id+"'>"+ data[i].nombre +"</option>";
                                  }
                             }
                             //console.log(pais);
                             select.append(options);
                          }
                      });
                      console.log(pais);


              }else {
                $('.manual').show();
                $('.selectores').hide();

                var select = $("#provincias");
                select.empty();

                var select2 = $("#municipio");
                select2.empty();

                //console.log(pais);
              }
          }

          function cargaMunicipios(){
            console.log($("#provincias").val());
              $.ajax({
                      type: "POST",
                      url: 'PHP/cargaMunicipios.php',
                      data: { 'provincia': $("#provincias").val() },
                      dataType:'json',

                      success: function(data) {

                         var select = $("#municipio"), options = '';
                         select.empty();

                         for(var i=0;i<data.length; i++)
                         {
                              options += "<option value='"+data[i].id+"'>"+ data[i].nombre +"</option>";
                         }

                         select.append(options);
                      }
                  });
          }

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

          function validarDNI(value) {
            var validChars = 'TRWAGMYFPDXBNJZSQVHLCKET';
            var nifRexp = /^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKET]{1}$/i;
            var nieRexp = /^[XYZ]{1}[0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKET]{1}$/i;
            var str = value.toString().toUpperCase();

            var resultado;

            if (!nifRexp.test(str) && !nieRexp.test(str)) resultado= false;

            var nie = str
              .replace(/^[X]/, '0')
              .replace(/^[Y]/, '1')
              .replace(/^[Z]/, '2');

            var letter = str.substr(-1);
            var charIndex = parseInt(nie.substr(0, 8)) % 23;

            if (validChars.charAt(charIndex) === letter) resultado= true;

            console.log(resultado);
            if(!resultado){
              document.getElementById("DNI").style.color="red";
              document.getElementById("aviso").innerHTML="Numero invalido";
            }else{
              document.getElementById("aviso").innerHTML="";
              document.getElementById("DNI").style.color="black";
            }

          }
      </script>
    </head>

      <body>



        <form action="crear_pacientes.php"  method="POST">
            <table id="tabla">
              <tr>
                <td ><a>Nombre</a></td>
                <td><input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>"/><a id="cosa"> *</a></td>

              </tr>
              <tr>
                <td><a>Apellidos</a></td>
                <td><input type="text" id="apellidos" name="apellidos" value="<?php echo $apellidos; ?>"/><a id="cosa"> *</a></td>

              </tr>
              <tr>
                <td><a>Fecha de nacimiento</a></td>
                <td><select name="anios" id="anios" >
                  <?php
                  for ($i=1980; $i <2021 ; $i++) {
                    if($i==$anio){
                      echo '<option selected value="'.$i.'">'.$i.'</option>';
                    }else{
                      echo '<option value="'.$i.'">'.$i.'</option>';
                    }
                  }
                  ?>
                </select>
                - <select id="mes" name="mes" onchange="cargar_dias()">
            			<option selected value="1">Enero</option>
            	    <option value="2">Febrero</option>
            	    <option value="3">Marzo</option>
            	    <option value="4">Abril</option>
            	    <option value="5">Mayo</option>
            	    <option value="6">Junio</option>
            	    <option value="7">Julio</option>
            	    <option value="8">Agosto</option>
            	    <option value="9">Septiembre</option>
            	    <option value="10">Octubre</option>
            	    <option value="11">Noviembre</option>
            	    <option value="12">Diciembre</option>
                </select>
                - <select name="dia" id="dia" size="1">
                    <?php
                    for ($i=1; $i <32 ; $i++) {
                      echo '<option value="'.$i.'">'.$i.'</option>';
                    }
                    ?>

                  </select><a id="cosa"> *</a></td>

              </tr>
              <tr>
                <td><a>Documento ID</a></td>
                <td><input type="text" name="DNI" id="DNI" onchange="validarDNI(this.value)"  value="<?php echo $documento_id; ?>"/><a id="cosa"> *</a> </td>
              </tr>

              <tr>
                <td> <a>Tipo de documento</a> </td>
                <td>
                  <select class="" name="tipo">
                    <?php
                    switch ($tipo) {
                      case 'dni':
                        echo '<option value="dni" selected>DNI</option>';
                        echo '<option value="nie">NIE</option>';
                        break;

                      case 'nie':
                        echo '<option value="dni">DNI</option>';
                        echo '<option value="nie" selected>NIE</option>';
                        break;

                      default:
                        echo '<option value="dni">DNI</option>';
                        echo '<option value="nie">NIE</option>';
                        break;
                    }


                    ?>

                  </select>
                </td>
              </tr>

              <tr>
                <td><a>Direccion</a></td>
                <td>  <input type="text" name="direccion" id="direccion" value="<?php echo $direccion; ?>"/><a id="cosa"> *</a></td>

              </tr>
              <tr>
                <td>Pais</td>
                <td>
                  <select id="pais" onchange="javascript:cargaProvincias()"  name="pais">
                    <option value="" >Elija un pais</option>
                    <option value="Afganistán" id="AF">Afganistán</option>
                    <option value="Albania" id="AL">Albania</option>
                    <option value="Alemania" id="DE">Alemania</option>
                    <option value="Andorra" id="AD">Andorra</option>
                    <option value="Angola" id="AO">Angola</option>
                    <option value="Anguila" id="AI">Anguila</option>
                    <option value="Antártida" id="AQ">Antártida</option>
                    <option value="Antigua y Barbuda" id="AG">Antigua y Barbuda</option>
                    <option value="Antillas holandesas" id="AN">Antillas holandesas</option>
                    <option value="Arabia Saudí" id="SA">Arabia Saudí</option>
                    <option value="Argelia" id="DZ">Argelia</option>
                    <option value="Argentina" id="AR">Argentina</option>
                    <option value="Armenia" id="AM">Armenia</option>
                    <option value="Aruba" id="AW">Aruba</option>
                    <option value="Australia" id="AU">Australia</option>
                    <option value="Austria" id="AT">Austria</option>
                    <option value="Azerbaiyán" id="AZ">Azerbaiyán</option>
                    <option value="Bahamas" id="BS">Bahamas</option>
                    <option value="Bahrein" id="BH">Bahrein</option>
                    <option value="Bangladesh" id="BD">Bangladesh</option>
                    <option value="Barbados" id="BB">Barbados</option>
                    <option value="Bélgica" id="BE">Bélgica</option>
                    <option value="Belice" id="BZ">Belice</option>
                    <option value="Benín" id="BJ">Benín</option>
                    <option value="Bermudas" id="BM">Bermudas</option>
                    <option value="Bhután" id="BT">Bhután</option>
                    <option value="Bielorrusia" id="BY">Bielorrusia</option>
                    <option value="Birmania" id="MM">Birmania</option>
                    <option value="Bolivia" id="BO">Bolivia</option>
                    <option value="Bosnia y Herzegovina" id="BA">Bosnia y Herzegovina</option>
                    <option value="Botsuana" id="BW">Botsuana</option>
                    <option value="Brasil" id="BR">Brasil</option>
                    <option value="Brunei" id="BN">Brunei</option>
                    <option value="Bulgaria" id="BG">Bulgaria</option>
                    <option value="Burkina Faso" id="BF">Burkina Faso</option>
                    <option value="Burundi" id="BI">Burundi</option>
                    <option value="Cabo Verde" id="CV">Cabo Verde</option>
                    <option value="Camboya" id="KH">Camboya</option>
                    <option value="Camerún" id="CM">Camerún</option>
                    <option value="Canadá" id="CA">Canadá</option>
                    <option value="Chad" id="TD">Chad</option>
                    <option value="Chile" id="CL">Chile</option>
                    <option value="China" id="CN">China</option>
                    <option value="Chipre" id="CY">Chipre</option>
                    <option value="Ciudad estado del Vaticano" id="VA">Ciudad estado del Vaticano</option>
                    <option value="Colombia" id="CO">Colombia</option>
                    <option value="Comores" id="KM">Comores</option>
                    <option value="Congo" id="CG">Congo</option>
                    <option value="Corea" id="KR">Corea</option>
                    <option value="Corea del Norte" id="KP">Corea del Norte</option>
                    <option value="Costa del Marfíl" id="CI">Costa del Marfíl</option>
                    <option value="Costa Rica" id="CR">Costa Rica</option>
                    <option value="Croacia" id="HR">Croacia</option>
                    <option value="Cuba" id="CU">Cuba</option>
                    <option value="Dinamarca" id="DK">Dinamarca</option>
                    <option value="Djibouri" id="DJ">Djibouri</option>
                    <option value="Dominica" id="DM">Dominica</option>
                    <option value="Ecuador" id="EC">Ecuador</option>
                    <option value="Egipto" id="EG">Egipto</option>
                    <option value="El Salvador" id="SV">El Salvador</option>
                    <option value="Emiratos Arabes Unidos" id="AE">Emiratos Arabes Unidos</option>
                    <option value="Eritrea" id="ER">Eritrea</option>
                    <option value="Eslovaquia" id="SK">Eslovaquia</option>
                    <option value="Eslovenia" id="SI">Eslovenia</option>

                    <option value="España"  id="ES">España</option>

                    <option value="Estados Unidos" id="US">Estados Unidos</option>
                    <option value="Estonia" id="EE">Estonia</option>
                    <option value="c" id="ET">Etiopía</option>
                    <option value="Ex-República Yugoslava de Macedonia" id="MK">Ex-República Yugoslava de Macedonia</option>
                    <option value="Filipinas" id="PH">Filipinas</option>
                    <option value="Finlandia" id="FI">Finlandia</option>
                    <option value="Francia" id="FR">Francia</option>
                    <option value="Gabón" id="GA">Gabón</option>
                    <option value="Gambia" id="GM">Gambia</option>
                    <option value="Georgia" id="GE">Georgia</option>
                    <option value="Ghana" id="GH">Ghana</option>
                    <option value="Gibraltar" id="GI">Gibraltar</option>
                    <option value="Granada" id="GD">Granada</option>
                    <option value="Grecia" id="GR">Grecia</option>
                    <option value="Groenlandia" id="GL">Groenlandia</option>
                    <option value="Guadalupe" id="GP">Guadalupe</option>
                    <option value="Guam" id="GU">Guam</option>
                    <option value="Guatemala" id="GT">Guatemala</option>
                    <option value="Guayana" id="GY">Guayana</option>
                    <option value="Guayana francesa" id="GF">Guayana francesa</option>
                    <option value="Guinea" id="GN">Guinea</option>
                    <option value="Guinea Ecuatorial" id="GQ">Guinea Ecuatorial</option>
                    <option value="Guinea-Bissau" id="GW">Guinea-Bissau</option>
                    <option value="Haití" id="HT">Haití</option>
                    <option value="Holanda" id="NL">Holanda</option>
                    <option value="Honduras" id="HN">Honduras</option>
                    <option value="Hong Kong R. A. E" id="HK">Hong Kong R. A. E</option>
                    <option value="Hungría" id="HU">Hungría</option>
                    <option value="India" id="IN">India</option>
                    <option value="Indonesia" id="ID">Indonesia</option>
                    <option value="Irak" id="IQ">Irak</option>
                    <option value="Irán" id="IR">Irán</option>
                    <option value="Irlanda" id="IE">Irlanda</option>
                    <option value="Isla Bouvet" id="BV">Isla Bouvet</option>
                    <option value="Isla Christmas" id="CX">Isla Christmas</option>
                    <option value="Isla Heard e Islas McDonald" id="HM">Isla Heard e Islas McDonald</option>
                    <option value="Islandia" id="IS">Islandia</option>
                    <option value="Islas Caimán" id="KY">Islas Caimán</option>
                    <option value="Islas Cook" id="CK">Islas Cook</option>
                    <option value="Islas de Cocos o Keeling" id="CC">Islas de Cocos o Keeling</option>
                    <option value="Islas Faroe" id="FO">Islas Faroe</option>
                    <option value="Islas Fiyi" id="FJ">Islas Fiyi</option>
                    <option value="Islas Malvinas Islas Falkland" id="FK">Islas Malvinas Islas Falkland</option>
                    <option value="Islas Marianas del norte" id="MP">Islas Marianas del norte</option>
                    <option value="Islas Marshall" id="MH">Islas Marshall</option>
                    <option value="Islas menores de Estados Unidos" id="UM">Islas menores de Estados Unidos</option>
                    <option value="Islas Palau" id="PW">Islas Palau</option>
                    <option value="Islas Salomón" d="SB">Islas Salomón</option>
                    <option value="Islas Tokelau" id="TK">Islas Tokelau</option>
                    <option value="Islas Turks y Caicos" id="TC">Islas Turks y Caicos</option>
                    <option value="Islas Vírgenes EE.UU." id="VI">Islas Vírgenes EE.UU.</option>
                    <option value="Islas Vírgenes Reino Unido" id="VG">Islas Vírgenes Reino Unido</option>
                    <option value="Israel" id="IL">Israel</option>
                    <option value="Italia" id="IT">Italia</option>
                    <option value="Jamaica" id="JM">Jamaica</option>
                    <option value="Japón" id="JP">Japón</option>
                    <option value="Jordania" id="JO">Jordania</option>
                    <option value="Kazajistán" id="KZ">Kazajistán</option>
                    <option value="Kenia" id="KE">Kenia</option>
                    <option value="Kirguizistán" id="KG">Kirguizistán</option>
                    <option value="Kiribati" id="KI">Kiribati</option>
                    <option value="Kuwait" id="KW">Kuwait</option>
                    <option value="Laos" id="LA">Laos</option>
                    <option value="Lesoto" id="LS">Lesoto</option>
                    <option value="Letonia" id="LV">Letonia</option>
                    <option value="Líbano" id="LB">Líbano</option>
                    <option value="Liberia" id="LR">Liberia</option>
                    <option value="Libia" id="LY">Libia</option>
                    <option value="Liechtenstein" id="LI">Liechtenstein</option>
                    <option value="Lituania" id="LT">Lituania</option>
                    <option value="Luxemburgo" id="LU">Luxemburgo</option>
                    <option value="Macao R. A. E" id="MO">Macao R. A. E</option>
                    <option value="Madagascar" id="MG">Madagascar</option>
                    <option value="Malasia" id="MY">Malasia</option>
                    <option value="Malawi" id="MW">Malawi</option>
                    <option value="Maldivas" id="MV">Maldivas</option>
                    <option value="Malí" id="ML">Malí</option>
                    <option value="Malta" id="MT">Malta</option>
                    <option value="Marruecos" id="MA">Marruecos</option>
                    <option value="Martinica" id="MQ">Martinica</option>
                    <option value="Mauricio" id="MU">Mauricio</option>
                    <option value="Mauritania" id="MR">Mauritania</option>
                    <option value="Mayotte" id="YT">Mayotte</option>
                    <option value="México" id="MX">México</option>
                    <option value="Micronesia" id="FM">Micronesia</option>
                    <option value="Moldavia" id="MD">Moldavia</option>
                    <option value="Mónaco" id="MC">Mónaco</option>
                    <option value="Mongolia" id="MN">Mongolia</option>
                    <option value="Montserrat" id="MS">Montserrat</option>
                    <option value="Mozambique" id="MZ">Mozambique</option>
                    <option value="Namibia" id="NA">Namibia</option>
                    <option value="Nauru" id="NR">Nauru</option>
                    <option value="Nepal" id="NP">Nepal</option>
                    <option value="Nicaragua" id="NI">Nicaragua</option>
                    <option value="Níger" id="NE">Níger</option>
                    <option value="Nigeria" id="NG">Nigeria</option>
                    <option value="Niue" id="NU">Niue</option>
                    <option value="Norfolk" id="NF">Norfolk</option>
                    <option value="Noruega" id="NO">Noruega</option>
                    <option value="Nueva Caledonia" id="NC">Nueva Caledonia</option>
                    <option value="Nueva Zelanda" id="NZ">Nueva Zelanda</option>
                    <option value="Omán" id="OM">Omán</option>
                    <option value="Panamá" id="PA">Panamá</option>
                    <option value="Papua Nueva Guinea" id="PG">Papua Nueva Guinea</option>
                    <option value="Paquistán" id="PK">Paquistán</option>
                    <option value="Paraguay" id="PY">Paraguay</option>
                    <option value="Perú" id="PE">Perú</option>
                    <option value="Pitcairn" id="PN">Pitcairn</option>
                    <option value="Polinesia francesa" id="PF">Polinesia francesa</option>
                    <option value="Polonia" id="PL">Polonia</option>
                    <option value="Portugal" id="PT">Portugal</option>
                    <option value="Puerto Rico" id="PR">Puerto Rico</option>
                    <option value="Qatar" id="QA">Qatar</option>
                    <option value="Reino Unido" id="UK">Reino Unido</option>
                    <option value="República Centroafricana" id="CF">República Centroafricana</option>
                    <option value="República Checa" id="CZ">República Checa</option>
                    <option value="República de Sudáfrica" id="ZA">República de Sudáfrica</option>
                    <option value="República Democrática del Congo Zaire" id="CD">República Democrática del Congo Zaire</option>
                    <option value="República Dominicana" id="DO">República Dominicana</option>
                    <option value="Reunión" id="RE">Reunión</option>
                    <option value="Ruanda" id="RW">Ruanda</option>
                    <option value="Rumania" id="RO">Rumania</option>
                    <option value="Rusia" id="RU">Rusia</option>
                    <option value="Samoa" id="WS">Samoa</option>
                    <option value="Samoa occidental" id="AS">Samoa occidental</option>
                    <option value="San Kitts y Nevis" id="KN">San Kitts y Nevis</option>
                    <option value="San Marino" id="SM">San Marino</option>
                    <option value="San Pierre y Miquelon" id="PM">San Pierre y Miquelon</option>
                    <option value="San Vicente e Islas Granadinas" id="VC">San Vicente e Islas Granadinas</option>
                    <option value="Santa Helena" id="SH">Santa Helena</option>
                    <option value="Santa Lucía" id="LC">Santa Lucía</option>
                    <option value="Santo Tomé y Príncipe" id="ST">Santo Tomé y Príncipe</option>
                    <option value="Senegal" id="SN">Senegal</option>
                    <option value="Serbia y Montenegro" id="YU">Serbia y Montenegro</option>
                    <option value="Sychelles" id="SC">Seychelles</option>
                    <option value="Sierra Leona" id="SL">Sierra Leona</option>
                    <option value="Singapur" id="SG">Singapur</option>
                    <option value="Siria" id="SY">Siria</option>
                    <option value="Somalia" id="SO">Somalia</option>
                    <option value="Sri Lanka" id="LK">Sri Lanka</option>
                    <option value="Suazilandia" id="SZ">Suazilandia</option>
                    <option value="Sudán" id="SD">Sudán</option>
                    <option value="Suecia" id="SE">Suecia</option>
                    <option value="Suiza" id="CH">Suiza</option>
                    <option value="Surinam" id="SR">Surinam</option>
                    <option value="Svalbard" id="SJ">Svalbard</option>
                    <option value="Tailandia" id="TH">Tailandia</option>
                    <option value="Taiwán" id="TW">Taiwán</option>
                    <option value="Tanzania" id="TZ">Tanzania</option>
                    <option value="Tayikistán" id="TJ">Tayikistán</option>
                    <option value="Territorios británicos del océano Indico" id="IO">Territorios británicos del océano Indico</option>
                    <option value="Territorios franceses del sur" id="TF">Territorios franceses del sur</option>
                    <option value="Timor Oriental" id="TP">Timor Oriental</option>
                    <option value="Togo" id="TG">Togo</option>
                    <option value="Tonga" id="TO">Tonga</option>
                    <option value="Trinidad y Tobago" id="TT">Trinidad y Tobago</option>
                    <option value="Túnez" id="TN">Túnez</option>
                    <option value="Turkmenistán" id="TM">Turkmenistán</option>
                    <option value="Turquía" id="TR">Turquía</option>
                    <option value="Tuvalu" id="TV">Tuvalu</option>
                    <option value="Ucrania" id="UA">Ucrania</option>
                    <option value="Uganda" id="UG">Uganda</option>
                    <option value="Uruguay" id="UY">Uruguay</option>
                    <option value="Uzbekistán" id="UZ">Uzbekistán</option>
                    <option value="Vanuatu" id="VU">Vanuatu</option>
                    <option value="Venezuela" id="VE">Venezuela</option>
                    <option value="Vietnam" id="VN">Vietnam</option>
                    <option value="Wallis y Futuna" id="WF">Wallis y Futuna</option>
                    <option value="Yemen" id="YE">Yemen</option>
                    <option value="Zambia" id="ZM">Zambia</option>
                    <option value="Zimbabue" id="ZW">Zimbabue</option>
                  </select>
                <a id="cosa"> *</a></td>

              </tr>
              <tr>
                <td><a>Provincia</a></td>
                <td>
                  <select class="selectores" id="provincias" onchange="javascript:cargaMunicipios()"   name="provincia" >

                  </select>
                  <input type="text" class="manual" name="provincia" value="<?php echo $provincia; ?>"><a id="cosa"> *</a>
                </td>

              </tr>
              <tr>
                <td><a>Localidad</a></td>
                <td>
                  <select class="selectores" id="municipio" name="localidad">

                  </select>
                  <input type="text" class="manual" name="localidad" value="<?php echo $localidad; ?>"><a id="cosa"> *</a>
                </td>

              </tr>


              <tr>
                <td colspan="2"> <br> <a id="obligatorio">Obligatorio rellenar los campos con <a id="cosa"> *</a></a></td>
              </tr>
            </table>

            <input id="Registrar" type="submit"  title=": D"  value="Registrar">
        </form>
        <a id="aviso"></a>
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
