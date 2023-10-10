<?php include("PHP/seguridad.php");
require_once 'PHP/BaseDatos.php';
require_once 'PHP/buscar.php';
 ?>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/lista_fest.css" />
    <head><title>Lista de festivos</title>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script type="text/javascript">
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
                         options+='<option>'+$("#medico").val()+' no existe</option>';
                         select.append(options);
                       }
                    }
                });
        }
      </script>
    </head>
      <body>

        <?php include("PHP/navegador_med.php"); ?>
<div class="cuerpo">
        <form action="" method="get">
          <table id="Busqueda">
            <tr>
              <td> Fecha: </td>
              <td><select name="anio" id="anios" >
                  <option value="2020">2020</option>
                  <option value="2021">2021</option>
                  <option value="2022">2022</option>
                  <option value="2023">2023</option>
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

                  </select>
              </td>
            </tr>
            <tr>
              <td> Medico: </td>
              <td><input type="text" id="medico"  onchange="carga_medicos()" placeholder="escriba el nombre del medico" value="">
                <select id="medicos" name="medico">
                  <option value="">Todos</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                Tipo de festivo:</td> <td><select name="tipo">
                  <option value="">Todas</option>
                  <option value="completo">completo</option>
                  <option value="maniana">maniana</option>
                  <option value="tarde">tarde</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>Método de búsqueda:</td>
              <td><select  name="metodo">
                <option  value="0">Por medico</option>
                <option value="1">Por fecha</option>
                <option value="2">Por medico y fecha</option>
                <option selected value="">Todos</option>
              </select></td>
            </tr>
            <tr>
              <td><input type="submit" class="botones" name="buscar"  value="Buscar"></td>
            </tr>
          </table>
        </form>



        <table id="tabla">
          <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Medico</th>

            <th colspan="3" >Acciones</th>
          </tr>

          <?php

            $db=new BaseDatos();
            $db=$db->conectar();

            $sql="SELECT * FROM festivos";

            if(isset($_GET['buscar']) ){
                $m=$_GET['medico'];
                $f=$_GET['anio']."-".$_GET['mes']."-".$_GET['dia'];
                $t=$_GET['tipo'];

                $metodo=$_GET['metodo'];
                switch ($metodo) {
                  case '0':
                      $sql="SELECT * FROM festivos WHERE tipo='$t' and medico='$m'";
                      if($t==""){
                        $sql="SELECT * FROM festivos WHERE medico='$m'";
                      }
                  break;

                  case '1':
                      $sql="SELECT * FROM festivos WHERE tipo='$t' and fecha='$f'";
                      if($t==""){
                        $sql="SELECT * FROM festivos WHERE fecha='$f'";
                      }
                  break;

                  case '2':
                    $sql="SELECT * FROM festivos WHERE (fecha='$f' or medico='$m') and tipo='$t'";
                    if($t==""){
                      $sql="SELECT * FROM festivos WHERE fecha='$f' or medico='$m'";
                    }
                  break;

                  default:
                    $sql="SELECT * FROM festivos WHERE tipo='$t'";
                    if($t==""){
                      $sql="SELECT * FROM festivos";
                    }
                  break;
                }


            }

            $resultado=$db->query($sql);
            //Para ver si se ha insertado
            if($resultado->num_rows>0){
                $i=2;
                $color="lightblue";
                while($fila= mysqli_fetch_assoc($resultado)){
                  if($i%2===0){
                    $color="#59C5F3";
                  }else{
                    $color="#9DDEFA";
                  }
                  $i=$i+1;
                  $fecha=$fila['fecha'];
                  $t=$fila['tipo'];
                  $m=$fila['medico'];
                  $c=$fila['codigo'];

                  $arrray=explode("-",$fecha);
                  $anios=$arrray[0];
                  $mes=$arrray[1];
                  $dia=$arrray[2];

                  echo '<tr style="background-color:'.$color.'">';
                  echo    "<td>$fecha</td>";
                  echo    "<td>$t</td>";

                  if($m!=''){
                    $user=buscar_usuario($m);

                    echo    '<td>'.$user[0].', correo: '.$user[2].'</td>';

                  }else{
                    echo "<td>Todos</td>";
                  }
                  ?>
                  <td>
                    <form action="PHP/borrar.php" method="GET">
                      <input type="hidden" name="codigo" value="<?php echo $c; ?>"/>

                      <input type="submit" class="botones" value="Borrar" name="borrar_festivo" title="Borrar"  onclick="return confirm('¿Quieres borrar este festivo?')"/>
                    </form>
                  </td>

                  <td>
                    <form action="modificar_festivos.php" method="POST">

                      <input type="hidden" name="codigo" value="<?php echo $c; ?>"/>
                      <input type="hidden" name="anios" value="<?php echo $anios; ?>"/>
                      <input type="hidden" name="mes" value="<?php echo $mes; ?>"/>
                      <input type="hidden" name="dia" value="<?php echo $dia; ?>"/>
                      <input type="hidden" name="medico" value="<?php echo $m; ?>"/>
                      <input type="hidden" name="tipo" value="<?php echo $t; ?>"/>
                      <input type="submit" class="botones" value="Modificar" title="Modificar" />
                    </form>
                  </td>
                  <td>
                    <form  action="lista_usuarios.php" method="get" target="_blank">
                      <input type="hidden" name="id" value="<?php echo $m; ?>">
                      <input type="submit" class="botones" name="ver_medico" value="Ver medico">
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
              echo' <tr> <td colspan="7"> Sin resultados</td></tr> ';
            }

          ?>



        </table>

        <footer>@Copyrigth</footer>
</div>
      </body>
</html>
