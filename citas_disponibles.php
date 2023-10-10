<?php

require_once 'PHP/BaseDatos.php';
require_once 'PHP/buscar.php';

require_once 'PHP/funciones.php';
include("PHP/navegador_med.php");

$FECHA="";

$festivos=array();

$month=date("n");
$year=date("Y");

$diaActual=date("j");
$mesActual=date("n");
$anioActual=date("Y");

$fecha_actual=$anioActual."-".$mesActual."-".$diaActual;

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	$m=$_POST['mes'];
	if($m!=""){
		$month=$m;
	}
}
$festivos=[];
$festivos_tarde=[];
$festivos_maniana=[];
$dias_habiles=[];

$medico="";
if(isset($_GET['buscar_medico'])){

	$medico=$_GET['medico'];
	$festivos=buscar_festivos($medico);
	$festivos_tarde=buscar_festivos_tarde($medico);
	$festivos_maniana=buscar_festivos_maniana($medico);
}

		$diaSemana=date("w",mktime(0,0,0,$month,1,$year));
		if($diaSemana==0){ $diaSemana=7;}

		$ultimoDiaMes=date("d",(mktime(0,0,0,$month+1,1,$year)-1));

		$meses=array(1=>"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
		"Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");



?>

<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/citas_disponibles.css" />
<head>

	<title>Citas disponibles</title>
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
				console.log($("#medicos").val());
				console.log($("#fecha").val());
          $.ajax({
                  type: "GET",
                  url: 'PHP/cargar_eventos.php',
                  data: { 'medico': $("#medicos").val(), 'fecha':$("#fecha").val(), 'codigo':''},
                  dataType:'json',

									success: function(data) {

										 var select = $("#datos"), options = '';
										 console.log(data);
										 select.empty();
										 if(data.length>1){
											 options +='<b>Citas</b>';
											 for(var i=0;i<data.length; i++){
												 if(data[i].libre=='no'){
												 }
												 else{
														options += '<li>'+data[i].hora+' Disponible</li>';
												 }
											 }
											 select.append(options);
										 }else{
											 options += '<li>No hay citas disponibles</li>';
											 select.append(options);
										 }


									}
              });
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

<form id="M" action="citas_disponibles.php" method="post">
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
	<input type="submit" id="Registrar" name="siguiente" value="Seleccionar">
</form>

<table  id="calendar">
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

<form method="get" action="citas_disponibles.php" id="MEDIC">
	Medico:<input type="text" id="medico" onchange="carga_medicos()" placeholder="nombre o usuario" value=""> <br>
	<select id="medicos" name="medico">
		<?php
		if($medico!="") {
			echo '<option value="'.$medico.'">'.buscar_usuario($medico)[0].': '.buscar_usuario($medico)[1].' '.buscar_usuario($medico)[2].'</option>';

		}

		?>
	</select>
	<input type="submit" name="buscar_medico" id="Registrar" value="Seleccionar medico" title="Carga el calendario segun los datos del médico">
</form>

<article class="leyenda">
	<p> <b> Leyenda:</b></p>
	<p id="seleccionado">Seleccionado</p>
	<p id="festivo">Dia libre</p>
	<p id="maniana">Ma&ntilde;ana libre</p>
	<p id="tarde">Tarde libre</p>
	<p id="habil">Dia de guardia</p>
</article>

<input type="hidden" name="fecha" id="fecha" value="<?php echo $FECHA; ?>">


</body>
</html>
