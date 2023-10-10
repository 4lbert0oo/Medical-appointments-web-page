<?php include("PHP/seguridad.php");
require_once 'PHP/BaseDatos.php';
require_once 'PHP/buscar.php';
require_once 'PHP/funciones.php';
include("PHP/navegador_med.php");
if(isset($_GET['lista_citas'])){
  $medico = isset($_GET['medico']) ? limpia($_GET['medico']) : null;
}
 ?>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/lista_cdi.css" />
    <head><title>Lista de citas</title>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                              if($("#medico").val()==""){
                                options += '<option selected value=""> </option>';
                              }
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

<div class="cuerpo">
        <form id="Busqueda" method="get">
          <table  >
            <tr>
              <td>
              Medico:<input type="text" id="medico"  onchange="carga_medicos()" placeholder="nombre o usuario" value="">
              <select id="medicos" name="medico">
                <option value=""></option>
              </select>
              </td>
            </tr>
            <tr>
              <td>
                Paciente:<input type="text" id="paciente"  onchange="carga_pacientes()" placeholder="nombre, apellidos o dni" value="">
                <select id="pacientes" name="paciente">
                  <option value=""></option>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                Fecha:<select name="anios" id="anios" >
                  <option selected value="">----</option>
                  <option value="2020">2020</option>
                  <option value="2021">2021</option>
                  <option value="2022">2022</option>
                  <option value="2023">2023</option>
                </select>
                - <select id="mes" name="mes" onchange="cargar_dias()">
                  <option  selected  value="">----</option>
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
                - <select name="dia" id="dia" size="1">
                    <option value="">--</option>

                  </select>
              </td>
            </tr>

            <tr>
              <td><input type="submit" class="botones" name="lista_citas" value="Buscar"></td>
            </tr>
          </table>

        </form>



        <table id="tabla">
          <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Duracion</th>
            <th>Medico</th>
            <th>Paciente</th>
            <th colspan="4" >Acciones</th>
          </tr>

          <?php

            $db=new BaseDatos();
            $db=$db->conectar();

            $sql="SELECT * FROM cita order by codigo DESC";

            if(isset($_GET['lista_citas'])){
              $M=$_GET['medico'];
              $P=$_GET['paciente'];
              $f=$_GET['anios']."-".$_GET['mes']."-".$_GET['dia'];

              if(!validaFecha($f)){
                $f="";
              }

              if($M!="" and $P!="" and $f!="" ){
                $sql="SELECT * FROM cita WHERE medico='$M' and fecha='$f' and paciente='$P'";
              }else if($M=="" and $P!="" and $f!="" ){
                $sql="SELECT * FROM cita WHERE fecha='$f' and paciente='$P'";
              }else if($M!="" and $P=="" and $f!="" ){
                $sql="SELECT * FROM cita WHERE fecha='$f' and medico='$M'";
              }else if($M!="" and $P!="" and $f=="" ){
                $sql="SELECT * FROM cita WHERE paciente='$P' and medico='$M'";
              }

              if($M!="" and $P=="" and $f=="" ){
                $sql="SELECT * FROM cita WHERE medico='$M'";
              }else if($M=="" and $P!="" and $f=="" ){
                $sql="SELECT * FROM cita WHERE paciente='$P'";
              }else if($M=="" and $P=="" and $f!="" ){
                $sql="SELECT * FROM cita WHERE fecha='$f' ";
              }


            }

            if(isset($_GET['ver_cita'])){
              $codigo=$_GET['codigo'];
              $sql="SELECT * FROM cita WHERE codigo='$codigo'";
            }

            $resultado=$db->query($sql);
            //Para ver si se ha insertado
            if($resultado->num_rows>0){
              $db=new BaseDatos();
              $db=$db->conectar();

                $i=2;
                $color="lightblue";
                while($fila= mysqli_fetch_assoc($resultado)){
                  if($i%2===0){
                    $color="#59C5F3";
                  }else{
                    $color="#9DDEFA";
                  }
                  $i=$i+1;
                  $f=$fila['fecha'];
                  $h=$fila['hora'];
                  $d=$fila['duracion'];
                  $m=$fila['medico'];
                  $p=$fila['paciente'];
                  $o=$fila['observaciones'];
                  $c=$fila['codigo'];



                  $medico=buscar_usuario($m);
                  $paciente=buscar_paciente($p);
                  echo '<tr style="background-color:'.$color.'">';
                  echo    "<td>$f</td>";
                  echo    "<td>$h</td>";
                  echo    "<td>$d minutos</td>";
                  echo    '<td>'.$medico[0].' '.$medico[2].'</td>';
                  echo    '<td>'.$paciente[0].' '.$paciente[1].' '.$paciente[2].'</td>';

                  ?>
                  <td>
                    <form action="PHP/borrar.php" method="GET">
                      <input type="hidden" name="codigo" value="<?php echo $c; ?>"/>

                      <input type="submit" class="botones" value="Borrar" name="borrar_cita" title="Borrar"  onclick="return confirm('¿Quieres borrar esta cita?')"/>
                    </form>
                  </td>

                  <td>
                    <form action="modificar_cita.php" method="GET">
                      <input type="hidden" name="codigo" value="<?php echo $fila['codigo']; ?>"/>
                      <input type="hidden" name="FECHA" value="<?php echo $fila['fecha']; ?>"/>
                      <input type="hidden" name="hora" value="<?php echo $h; ?>"/>
                      <input type="hidden" name="minuto" value="<?php echo $minuto; ?>"/>
                      <input type="hidden" name="duracion" value="<?php echo $d; ?>"/>
                      <input type="hidden" name="medico" value="<?php echo $m; ?>"/>
                      <input type="hidden" name="observaciones" value="<?php echo $o; ?>">
                      <input type="submit" class="botones" value="Modificar" title="Modificar" />
                    </form>

                  </td>
                  <td>
                    <form  action="lista_pacientes_med.php" method="get" target="_blank">
                      <input type="hidden" name="id" value="<?php echo $p; ?>">
                      <input type="submit" class="botones" name="ver_paciente" value="Ver paciente">
                    </form>
                  </td>

                  <td>
                    <form  action="lista_usuarios.php" method="get" target="_blank">
                      <input type="hidden" name="id" value="<?php echo $m; ?>">
                      <input type="submit" class="botones" name="ver_medico" value="Ver medico">
                    </form>
                  </td>

                  <?php


                  echo "</tr>";
                  echo '<tr style="background-color:'.$color.'">';
                  ?>
                    <td colspan="9">Observaciones:<?php echo $o; ?></td>
                  </tr>
                <?php
                }

            }else if (!$resultado){
                echo '<p> Error</p><br/>';
                echo $sql;
                echo $db->error;
            }else if($resultado->num_rows===0){
              echo' <tr> <td colspan="7"> Sin resultados</td></tr> ';
            }

          ?>



        </table>

          </div>
      </body>
</html>
