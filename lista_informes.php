<?php include("PHP/seguridad.php");
require_once 'PHP/BaseDatos.php';
require_once 'PHP/buscar.php';
require_once 'PHP/funciones.php';
 include("PHP/navegador_med.php");
$titulo="";
if(isset($_GET['lista_informes'])){
  $titulo = $_GET['titulo'];
}
 ?>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/list_inf.css" />
    <head><title>Informes</title>
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


          <form action="" method="get" >
            <table id="Busqueda">
              <tr>
                <td>Titulo</td>
                <td><input type="text" name="titulo" value="<?php echo $titulo;?>"></td>
              </tr>
              <tr>
                <td>Paciente:</td>
                <td>
                  <input type="text" id="paciente"  onchange="carga_pacientes()" placeholder="nombre, apellidos o dni" value="">
                  <select id="pacientes" name="paciente">
                    <option value=""></option>
                  </select>
                </td>
              </tr>
              <tr>
                <td>Fecha:</td>
                <td>
                  <select name="anios" id="anios" >
                    <option selected value="">----</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                  </select>-
                  <select id="mes" name="mes" onchange="cargar_dias()">
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
                  </select>-
                  <select name="dia" id="dia" size="1">
                      <option value="">--</option>

                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="2"><input type="submit" name="lista_informes" class="botones" value="Buscar"></td>
              </tr>

            </table>
          </form>



          <table id="tabla">
            <tr>
              <th>Titulo</th>
              <th>Medico</th>
              <th>Paciente</th>
              <th>Fecha</th>
              <th>Hora</th>

              <th colspan="4" >Acciones</th>
            </tr>

            <?php

              $db=new BaseDatos();
              $db=$db->conectar();

              @session_start();
              $medico=$_SESSION["id_usuario"];
              $sql="SELECT * FROM informe WHERE medico='$medico' order by cod_inf DESC";

              if(isset($_GET['lista_informes'])){
                $P=$_GET['paciente'];
                $T=$_GET['titulo'];
                $f=$_GET['anios']."-".$_GET['mes']."-".$_GET['dia'];

                if(!validaFecha($f)){
                  $f="";
                }

                if($P!="" and $T!="" and $f!="" ){
                  $sql="SELECT * FROM informe WHERE paciente='$P' and fecha='$f' and titulo LIKE '%$T%' and medico='$medico'";
                }else if($P=="" and $T!="" and $f!="" ){
                  $sql="SELECT * FROM informe WHERE fecha='$f' and titulo LIKE '%$T%' and medico='$medico'";
                }else if($P!="" and $T=="" and $f!="" ){
                  $sql="SELECT * FROM informe WHERE fecha='$f' and paciente='$P' and medico='$medico'";
                }else if($P!="" and $T!="" and $f=="" ){
                  $sql="SELECT * FROM informe WHERE titulo LIKE '%$T%' and paciente='$P' and medico='$medico'";
                }

                if($P!="" and $T=="" and $f=="" ){
                  $sql="SELECT * FROM informe WHERE paciente='$P' and medico='$medico'";
                }else if($P=="" and $T!="" and $f=="" ){
                  $sql="SELECT * FROM informe WHERE titulo LIKE '%$T%' and medico='$medico'";
                }else if($P=="" and $T=="" and $f!="" ){
                  $sql="SELECT * FROM informe WHERE fecha='$f' and medico='$medico'";
                }

              }

              $resultado=$db->query($sql);

              if($resultado->num_rows>0){//Para ver si se ha insertado

                  $i=2;
                  $color="lightblue";
                  while($fila= mysqli_fetch_assoc($resultado)){
                    if($i%2===0){
                      $color="#59C5F3";
                    }else{
                      $color="#9DDEFA";
                    }
                    $i=$i+1;
                    $t=$fila['titulo'];
                    $f=$fila['fecha'];
                    $h=$fila['hora'];
                    $p=$fila['paciente'];
                    $m=$fila['medico'];
                    $c=$fila['contenido'];

                    $cod=$fila['cod_inf'];

                    $b_p=buscar_paciente($p);
                    $n_p=$b_p[0];
                    $apellidos=$b_p[1];
                    $dni=$b_p[2];

                    $b_m=buscar_usuario($m);
                    $n_m=$b_m[0];
                    $correo=$b_m[2];


                    echo '<tr style="background-color:'.$color.'">';
                    echo    "<td>$t</td>";

                    echo    '<td>'.$n_m.' '.$correo.'</td>';
                    echo    '<td>'.$n_p.' '.$apellidos.' '.$dni.'</td>';;

                    echo    '<td>'.$fila['fecha'].'</td>';
                    echo    "<td>$h</td>";
                    ?>
                      <td>
                        <form action="PHP/borrar.php" method="GET">
                          <input type="hidden" name="codigo" value="<?php echo $cod; ?>  "/>
                          <input type="submit" class="botones" value="Borrar" name="borrar_informe" title="Borrar"  onclick="return confirm('¿Quieres borrar a este informe?')"/>
                        </form>
                      </td>

                      <td>
                        <form action="modificar_informe.php" method="post">
                          <input type="hidden" name="titulo" value="<?php echo $t; ?>"/>
                          <input type="hidden" name="paciente" value="<?php echo $p; ?>"/>
                          <input type="hidden" name="contenido" value="<?php echo $c; ?>"/>
                          <input type="hidden" name="codigo" value="<?php echo $cod; ?> "/>
                          <input type="submit" class="botones" value="Modificar" name="modificar_informe" title="Modificar" />

                        </form>
                      </td>
                      <td>
                        <form  action="lista_pacientes_med.php" method="get" target="_blank">
                          <input type="hidden" name="id" value="<?php echo $p; ?>">
                          <input type="submit" class="botones" name="ver_paciente" value="Ver paciente">
                        </form>
                      </td>

                      <td>
                        <form action="PHP/informe.php" method="GET" target="_blank">
                          <input type="hidden" name="titulo" value="<?php echo $t; ?>  "/>
                          <input type="hidden" name="medico" value="<?php echo $n_m; ?>  "/>
                          <input type="hidden" name="paciente" value="<?php echo $n_p." ".$apellidos; ?>  "/>
                          <input type="hidden" name="fecha" value="<?php echo $f; ?>  "/>
                          <input type="hidden" name="hora" value="<?php echo $h; ?>  "/>
                          <input type="hidden" name="contenido" value="<?php echo $c; ?>  "/>
                          <input type="submit" class="botones" value="Informe" name="informe" title="Muestra el informe completo en un pdf" />
                        </form>
                      </td>
                    </tr>
                    <?php
                  }

              }else if (!$resultado){
                  echo '<p> Error</p><br/>';
                  echo $sql;
                  echo $db->error;
              }else if($resultado->num_rows===0){
                echo' <tr> <td colspan="8" style="background-color: lightblue"> Sin resultados</td></tr> ';
              }

            ?>



          </table>

          <footer>@Copyrigth</footer>
        </div>
          </div>
      </body>
</html>
