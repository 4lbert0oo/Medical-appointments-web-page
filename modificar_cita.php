<?php

require_once 'PHP/BaseDatos.php';
require_once 'PHP/buscar.php';
require_once 'PHP/modificar.php';
require_once 'PHP/funciones.php';

$medico = isset($_GET['medico']) ? limpia($_GET['medico']) : null;
$duracion = isset($_GET['duracion']) ? limpia($_GET['duracion']) : null;
$codigo = isset($_GET['codigo']) ? limpia($_GET['codigo']) : null;
$observaciones = isset($_GET['observaciones']) ? limpia($_GET['observaciones']) : null;
$FECHA= isset($_GET['FECHA']) ? limpia($_GET['FECHA']) : null;
$FECHA_NUEVA= isset($_GET['FECHA_NUEVA']) ? limpia($_GET['FECHA_NUEVA']) : null;
$hora= isset($_GET['hora']) ? limpia($_GET['hora']) : null;
$hora_nueva= isset($_GET['hora_nueva']) ? limpia($_GET['hora_nueva']) : null;

if ($hora_nueva=="") {
	$hora_nueva=$hora;
}

$festivos=array();


$month=date("n");
$year=date("Y");

$diaActual=date("j");
$mesActual=date("n");
$anioActual=date("Y");

$fecha_actual=$anioActual."-".$mesActual."-".$diaActual;

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	$medico = isset($_POST['medico']) ? limpia($_POST['medico']) : null;
	$duracion = isset($_POST['duracion']) ? limpia($_POST['duracion']) : null;
	$codigo = isset($_POST['codigo']) ? limpia($_POST['codigo']) : null;
	$FECHA= isset($_POST['FECHA']) ? limpia($_POST['FECHA']) : null;
	$hora= isset($_POST['hora']) ? limpia($_POST['hora']) : null;
	$hora_nueva= isset($_POST['hora_nueva']) ? limpia($_POST['hora_nueva']) : null;

if ($hora_nueva=="") {
	$hora_nueva=$hora;
}

	$observaciones = isset($_POST['observaciones']) ? limpia($_POST['observaciones']) : null;
	$hora_nueva= $hora_nueva;

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
if (isset($_GET['modificar'])) {
		if($FECHA_NUEVA==""){
			$FECHA_NUEVA=$FECHA;
		}

    if (!validaRequerido($FECHA_NUEVA)) {
        $errores[] = 'Elija una fecha.';
    }else if(validaRequerido($FECHA_NUEVA) and !combrobar_fecha($FECHA_NUEVA,$fecha_actual)){
				$errores[] = 'No puedes poner una cita en el pasado.';
		}

		if (!validaRequerido($hora_nueva)) {
        $errores[] = 'Elija una hora.';
    }
		$duracion=abs($duracion);
		if (!validaRequerido($duracion)) {
        $errores[] = 'El campo duracion es requerido.';
    }

		if(validaRequerido($hora_nueva) and validaRequerido($duracion)){
			if(!comprobar_hora($duracion,$FECHA_NUEVA,$hora_nueva,$medico,$codigo) or !comprobar_festivo_hora($duracion,$FECHA_NUEVA,$hora_nueva,$medico)){
				$errores[] = 'El medico no trabaja a esa hora, eliga otra.';
			}
		}
    //Verifica si ha encontrado errores y de no haber redirige a la página con el mensaje de que pasó la validación.
    if(!$errores){

				if(modificar_cita($FECHA_NUEVA, $hora_nueva, $duracion,$codigo,$observaciones)){
	        //header('Location:lista_citas.php');
	        $errores[] = "Cita modificada";
				}else{
          $errores[] = "Error";
        }

    }
}
?>

<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/calendario.css" />
<head>

	<title>Modificar cita</title>
	<meta charset="utf-8">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script >
		function onload(){
			select_dia($("#fecha").val());
		}
			function select_dia(id){
				console.log(id);
				var fecha=document.getElementById("fecha_nueva");
				console.log(fecha.value);
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
                  data: { 'medico': $("#medico").val(), 'fecha':$("#fecha_nueva").val(),'codigo':$("#codigo").val()},
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
														 options += '<li>'+data[i].hora+' Ocupado</li>';
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

	<form id="M" action="modificar_cita.php" method="post">
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
    <input type="hidden" name="FECHA" value="<?php echo $FECHA; ?>">
    <input type="hidden" name="hora" value="<?php echo $hora; ?>">
    <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">

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
	<p> <b>Medico:</b> <br>
	<?php echo buscar_usuario($medico)[1]." ".buscar_usuario($medico)[0]; ?> </p>
	<p> <b>Fecha orginal:</b> <br>
		<?php echo $FECHA; ?></p>
	<p> <b>Hora orginal:</b> <br>
	<?php echo $hora; ?> </p>
	<p> <b> Leyenda:</b>
	<a id="seleccionado">Seleccionado</a>
	<a id="festivo">Dia libre</a>
	<a id="maniana">Ma&ntilde;ana libre</a>
	<a id="tarde">Tarde libre</a> <br>
	<a id="habil">Dia de guardia</a></p>
</article>

<form action="modificar_cita.php" method="get">
  <input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>">
	<input type="hidden" id="medico" name="medico" value="<?php echo $medico; ?>">
  <a id="D">Duracion: <input type="number" id="duracion" name="duracion"  min="30" step="30" max="120" value="<?php echo $duracion; ?>"> minutos</a>
	<input type="hidden" id="fecha_nueva" name="FECHA_NUEVA" value="<?php echo $FECHA_NUEVA; ?>">
	<input type="hidden" id="fecha" name="FECHA" value="<?php echo $FECHA; ?>">
	<input type="hidden" name="hora" value="<?php echo $hora; ?>">
	<a id="H" >	Hora nueva: <select id="hora" name="hora_nueva" > <option value=""></option> </select> </a> <br>
<a id="O">Observaciones:<br><textarea name="observaciones"  rows="15" cols="30"><?php echo $observaciones; ?></textarea></a><br>
	<input type="submit" id="Registrar" name="modificar" value="Guardar">
</form>

 <form action="lista_citas.php" method="get">

	 <input type="submit" id="atras" value="Atras">
 </form>

</body>
</html>
