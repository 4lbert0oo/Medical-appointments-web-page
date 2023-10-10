<?php

require_once 'PHP/BaseDatos.php';
require_once 'PHP/buscar.php';
require_once 'PHP/insertar.php';
require_once 'PHP/funciones.php';

$medico = isset($_GET['medico']) ? limpia($_GET['medico']) : null;
$paciente = isset($_GET['paciente']) ? limpia($_GET['paciente']) : null;
$observaciones = isset($_GET['observaciones']) ? limpia($_GET['observaciones']) : null;
$duracion = isset($_GET['duracion']) ? limpia($_GET['duracion']) : null;
$FECHA= isset($_GET['FECHA']) ? limpia($_GET['FECHA']) : null;

$hora= isset($_GET['hora']) ? limpia($_GET['hora']) : null;
$HORA= $hora;


$festivos=array();


$month=date("n");
$year=date("Y");

$diaActual=date("j");
$mesActual=date("n");
$anioActual=date("Y");

$fecha_actual=$anioActual."-".$mesActual."-".$diaActual;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$medico = isset($_POST['medico']) ? limpia($_POST['medico']) : null;
	$paciente = isset($_POST['paciente']) ? limpia($_POST['paciente']) : null;
	$observaciones = isset($_POST['observaciones']) ? limpia($_POST['observaciones']) : null;
	$duracion = isset($_POST['duracion']) ? limpia($_POST['duracion']) : null;


	$m=$_POST['mes'];
	if($m!=""){
		$month=$m;
	}
}

$festivos=buscar_festivos($medico);
$festivos_tarde=buscar_festivos_tarde($medico);
$festivos_maniana=buscar_festivos_maniana($medico);

		$diaSemana=date("w",mktime(0,0,0,$month,1,$year));
		if($diaSemana==0){ $diaSemana=7;}

		$ultimoDiaMes=date("d",(mktime(0,0,0,$month+1,1,$year)-1));

		$meses=array(1=>"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
		"Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");


$errores = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!validaRequerido($FECHA)) {
        $errores[] = 'Elija una fecha.';
    }

		if (!validaRequerido($HORA)) {
        $errores[] = 'Elija una hora.';
    }

		if (!validaRequerido($duracion)) {
        $errores[] = 'Escriba la duración de la cita.';
    }

		if(validaRequerido($HORA) and validaRequerido($duracion)){

				if(!comprobar_hora($duracion,$FECHA,$HORA,$medico,"") or !comprobar_festivo_hora($duracion,$FECHA,$HORA,$medico)){
					$errores[] = 'El medico no trabaja a esas horas, elija otra.';
				}
		}
    //Verifica si ha encontrado errores y de no haber redirige a la página con el mensaje de que pasó la validación.
    if(!$errores){

				if(registrar_cita($FECHA,$HORA,$paciente,$medico,$duracion,$observaciones)){

	        header('Location:lista_citas.php');
	        $errores[] = "Cita insertada";
				}


    }
}
?>

<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/calendario.css" />
<head>

	<title>Calendario</title>
	<meta charset="utf-8">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script >
			function select_dia(id){
				//console.log(id);
				var fecha=document.getElementById("fecha");
				//console.log(fecha.value);
				if(fecha.value!=""){
					document.getElementById(fecha.value).style.border= "solid 1px black";
				}

				document.getElementById(id).style.border= "solid 1.5px red";

				fecha.value=id;

				cargaCitas();
			}

			function cargaCitas(){
				console.log("citas");
				console.log($("#medico").val());
          $.ajax({
                  type: "GET",
                  url: 'PHP/cargar_eventos.php',
                  data: { 'medico': $("#medico").val(), 'fecha':$("#fecha").val(), 'codigo':''},
                  dataType:'json',

									success: function(data) {

										 var select = $("#datos"), options = '';
										 var horas= $("#hora"), hs = '';
										 console.log(data);
										 select.empty();
										 horas.empty();
										 if(data.length>1){
											 options +='<b>Citas</b>';
											 for(var i=0;i<data.length; i++){
												 if(data[i].libre=='no'){
														 options += '<li>'+data[i].hora+' Ocupado: </li>';
												 }
												 else{
														options += '<li>'+data[i].hora+' Disponible</li>';
														hs+='<option value="'+data[i].hora+ '">'+ data[i].hora +'</option> '
												 }
											 }
											 select.append(options);
											 horas.append(hs);
										 }else{
											 options += '<li>No hay citas disponibles</li>';
											 select.append(options);
										 }


									}
              });
      }
	</script>

</head>

<body>

<form id="M" action="calendario_med.php" method="post">
	<select name="mes">
		<option value="">Selecciona un mes</option>
		<option value="1">Enero</option>
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
	<input type="hidden" name="medico" value="<?php echo $medico; ?>">
	<input type="hidden" name="duracion" value="<?php echo $duracion; ?>">
	<input type="hidden" name="observaciones" value="<?php echo $observaciones; ?>">
	<input type="hidden" name="paciente" value="<?php echo $paciente; ?>">
	<input type="submit" name="siguiente" value="Seleccionar">
</form>

<table type="hidden" id="calendar">
	<caption><?php echo $meses[$month]." ".$year?></caption>
	<tr>
		<th>Lun</th><th>Mar</th><th>Mie</th><th>Jue</th>
		<th>Vie</th><th>Sab</th><th>Dom</th>
	</tr>
	<tr bgcolor="silver">
		<?php
		$last_cell=$diaSemana+$ultimoDiaMes;
		// hacemos un bucle hasta 42, que es el máximo de valores que puede
		// haber... 6 columnas de 7 dias
		for($i=1;$i<=42;$i++)
		{
			if($i==$diaSemana)
			{
				// determinamos en que dia empieza
				$day=1;
			}
			if($i<$diaSemana || $i>=$last_cell)
			{

				echo '<td >&nbsp;</td>';
			}else{
					$DIA=$year."-".$month."-".$day;
						if(in_array($DIA, $festivos) or (in_array($DIA, $festivos_tarde) and in_array($DIA, $festivos_maniana)) ){
							echo '<td class="festivo">'.$day.'</td>';
						}else if(es_finde_semana($DIA,$medico)){
							echo '<td style="background-color:silver;" >'.$day.'</td>';
						}else if(in_array($DIA, $festivos_tarde)){
							echo '<td id="'.$DIA.'" style="" onclick="select_dia(this.id)" class="tarde">'.$day.'</td>';
						}else if(in_array($DIA, $festivos_maniana)){
							echo '<td id="'.$DIA.'" style="" onclick="select_dia(this.id)" class="maniana">'.$day.'</td>';
						}else{
							echo '<td style="" id="'.$DIA.'" onclick="select_dia(this.id)" >'.$day.'</td>';
						}

				$day++;
			}
			// cuando llega al final de la semana, iniciamos una columna nueva
			if($i%7==0){
				echo "</tr><tr>\n";
			}
		}
	?>
	</tr>
</table>



<ul id="datos">
	Fecha seleccionada:<?php echo $FECHA; ?>


</ul>


	<?php if ($errores): ?>
			<ul id="errores" style="color: #f00;">
					<?php foreach ($errores as $error): ?>
							<li> <?php echo $error ?> </li>
					<?php endforeach; ?>
			</ul>
	<?php endif; ?>


<article class="leyenda">
	<p><?php echo buscar_usuario($medico)[1]." ".buscar_usuario($medico)[0]; ?> </p>
	<p> <b> Leyenda:</b></p>
	<p id="seleccionado">Seleccionado</p>
	<p id="festivo">Dia libre</p>
	<p id="maniana">Ma&ntilde;ana libre</p>
	<p id="tarde">Tarde libre</p>
	<p id="habil">Dia de guardia</p>
</article>

<form action="calendario_med.php" method="get">
	<input type="hidden" id="medico" name="medico" value="<?php echo $medico; ?>">
	<a id="D">Duracion :<input type="number" id="duracion" name="duracion"  min="30" step="30" max="120" value="<?php echo $duracion; ?>"> minutos</a>
	<input type="hidden" name="observaciones" value="<?php echo $observaciones; ?>">
	<input type="hidden" name="paciente" value="<?php echo $paciente; ?>">
	<input type="hidden" id="fecha" name="FECHA" value="<?php echo $FECHA; ?>">
	<a id="H" >	Hora: <select id="hora" name="hora" > <option value="">--:--</option> </select> </a> <br>
	<input type="submit" id="Registrar" value="Registrar">
</form>

<form action="crear_cita_med.php" method="get">
	<input type="hidden" name="medico" value="<?php echo $medico; ?>">
	<input type="hidden" name="observaciones" value="<?php echo $observaciones; ?>">
	<input type="hidden" name="paciente" value="<?php echo $paciente; ?>">
	<input type="submit" id="atras" value="Atras">
</form>

</body>
</html>
