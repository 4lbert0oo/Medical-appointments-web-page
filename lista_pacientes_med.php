<?php
require_once 'PHP/BaseDatos.php';
require_once 'PHP/buscar.php';
include("PHP/seguridad.php");

?>
<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" type="text/css" href="css/lista_pacientess.css" />
    <head><meta charset="utf-8"><title>Listado de pacientes</title>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script type="text/javascript">

      </script>
    </head>
      <body>

        <?php include("PHP/navegador_med.php"); ?>

<div class="cuerpo">
        <form action="" method="get">
          <table id="Busqueda">
            <tr>
              <td>Buscar: <input type="text" name="n" value=""></td>
              <td> <input type="submit" class="botones" name="lista_pacientes" value="Buscar"> </td>
            </tr>
          </table>
        </form>

            <table id="tabla">
              <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Documento id</th>
                <th>Tipo de documento</th>
                <th>Fecha de nacimiento</th>
                <th>Direccion</th>
                <th>Localidad</th>
                <th>Provincia</th>
                <th>Pais</th>
                <th colspan="4" >Acciones</th>
              </tr>
            <?php
            $db=new BaseDatos();
            $db=$db->conectar();


            $sql="SELECT * FROM paciente order by id desc";

            if(isset($_GET['lista_pacientes'])){
              $N=$_GET['n'];
              $sql="SELECT * FROM paciente WHERE nombre LIKE '%$N%' or apellidos LIKE '%$N%' or documento_id LIKE '%$N%' order by nombre";
            }
            if(isset($_GET['ver_paciente'])){
              $id=$_GET['id'];
              $sql="SELECT * FROM paciente WHERE id='$id'";
            }
              $resultado=$db->query($sql);

              if($resultado->num_rows>0){
                  $color="lightblue";
                  $i=2;
                  while($fila= mysqli_fetch_assoc($resultado)){
                    if($i%2===0){
                      $color="#59C5F3";
                    }else{
                      $color="#9DDEFA";
                    }
                    $i=$i+1;
                    $n=$fila['nombre'];
                    $a=$fila['apellidos'];
                    $f_n=$fila['fecha_nacimiento'];
                    $dni=$fila['documento_id'];
                    $tipo=$fila['tipo_doc'];
                    $di=$fila['direccion'];
                    $loc=$fila['localidad'];
                    $pro=$fila['provincia'];
                    $pais=$fila['pais'];
                    $id=$fila['id'];

                    $arrray=explode("-",$f_n);
                    $anios=$arrray[0];
                    $mes=$arrray[1];
                    $dia=$arrray[2];

                    echo '<tr style="background-color:'.$color.'">';
                    echo    "<td>$n</td>";
                    echo    "<td>$a</td>";
                    echo    "<td>$dni</td>";
                    echo    "<td>$tipo</td>";
                    echo    "<td>$f_n</td>";
                    echo    "<td>$di</td>";
                    if(is_numeric($loc)){
                      echo    "<td>".buscar_municipio($loc)."</td>";
                    }else{
                      echo    "<td>".$loc."</td>";
                    }
                    if(is_numeric($pro)){
                      echo    "<td>".buscar_municipio($pro)."</td>";
                    }else{
                      echo    "<td>".$pro."</td>";
                    }
                    echo    "<td>$pais</td>";

                    ?>
                    <td>
                      <?php

                      $sql="SELECT * FROM cita WHere paciente='$id'";

                      if($db->query($sql)->num_rows==0){
                       ?>
                      <form action="PHP/borrar.php"  method="GET">

                        <input type="hidden" name="id" value="<?php echo $id; ?>  "/>

                        <input type="submit" class="botones" value="Borrar" name="borrar_paciente" title="Borrar"  onclick="return confirm('¿Quieres borrar a este paciente?')"/>
                      </form>
                      <?php

                      }
                     ?>
                    </td>

                    <td>
                      <form action="modificar_paciente.php"    method="POST">

                        <input type="hidden" name="nombre" value="<?php echo $n; ?>"/>
                        <input type="hidden" name="apellidos" value="<?php echo $a; ?>"/>
                        <input type="hidden" name="DNI" value="<?php echo $dni; ?>"/>
                        <input type="hidden" name="tipo" value="<?php echo $tipo; ?>"/>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="hidden" name="anios" value="<?php echo $anios; ?>"/>
                        <input type="hidden" name="mes" value="<?php echo $mes; ?>"/>
                        <input type="hidden" name="dia" value="<?php echo $dia; ?>"/>
                        <input type="hidden" name="direccion" value="<?php echo $di; ?>"/>
                        <input type="hidden" name="localidad" value="<?php echo $loc; ?>"/>
                        <input type="hidden" name="provincia" value="<?php echo $pro; ?>"/>
                        <input type="hidden" name="pais" value="<?php echo $pais; ?>"/>

                        <input type="submit" class="botones" value="Modificar" title="Modificar" />
                      </form>
                    </td>
                    <?php
                    @session_start();
                    if($_SESSION["rol"]==1){
                     ?>
                    <td>
                      <form class="" action="crear_informes.php" method="post">
                        <input type="hidden" name="paciente" value="<?php echo $id; ?>"/>
                        <input type="submit" class="botones" value="Crear informe" title="Crea un informe de este pacientes" />
                      </form>
                    </td>
                    <?php
                  }
                     ?>

                    <td>
                      <form class="" action="ficha_paciente.php" method="post" target="_blank">
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="hidden" name="nombre" value="<?php echo $n; ?>"/>
                        <input type="hidden" name="apellidos" value="<?php echo $a; ?>"/>
                        <input type="hidden" name="DNI" value="<?php echo $dni; ?>"/>
                        <input type="hidden" name="tipo" value="<?php echo $tipo; ?>"/>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="hidden" name="anios" value="<?php echo $anios; ?>"/>
                        <input type="hidden" name="mes" value="<?php echo $mes; ?>"/>
                        <input type="hidden" name="dia" value="<?php echo $dia; ?>"/>
                        <input type="hidden" name="direccion" value="<?php echo $di; ?>"/>
                        <input type="hidden" name="localidad" value="<?php echo $loc; ?>"/>
                        <input type="hidden" name="provincia" value="<?php echo $pro; ?>"/>
                        <input type="hidden" name="pais" value="<?php echo $pais; ?>"/>

                        <input type="submit" class="botones" value="Ficha" title="Muestra los datos del paciente, sus informes y citas" />

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
                echo' <tr> <td colspan="10" style="background-color: lightblue"> Sin resultados</td></tr> ';

              }

            ?>
          </table>
          <footer>@Copyrigth</footer>
      </div>
      </body>
</html>
