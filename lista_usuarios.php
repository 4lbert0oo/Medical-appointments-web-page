<?php
require_once 'PHP/BaseDatos.php';
require_once 'PHP/buscar.php';
include("PHP/seguridad.php");
 include("PHP/navegador_med.php");
?>

<html lang="es">
<link rel="stylesheet" type="text/css" href="css/lista_usuarios.css" />
    <head><meta charset="utf-8"><title>Usuarios</title>
    </head>
      <body>

<div class="cuerpo">
        <form action="" method="get">
          <table id="Busqueda">
            <tr>
              <td>Buscar:<input type="text" name="n" value=""></td>
              <td> <input type="submit" name="lista_usuarios" class="botones"  value="Buscar"> </td>
            </tr>
            <tr>
              <td><a >Rol</a>
                <select name="r">
                  <option value="0">Administrador</option>
                  <option value="1">Medico</option>
                  <option value="" selected>Todos</option>
                </select>

              </td>
            </tr>
          </table>
        </form>

<table id="tabla">
  <tr>
    <th>Nombre</th>
    <th>Usuario</th>
    <th>Rol</th>
    <th>Especialidad</th>
    <th>Correo</th>
    <th>Fecha&nbsp;de&nbsp;registro</th>
    <th>Fecha&nbsp;de&nbsp;baja</th>
    <th colspan="3" >Acciones</th>
  </tr>
<?php



  $db=new BaseDatos();
  $db=$db->conectar();
  $sql="SELECT * FROM usuario order by nombre";

if(isset($_GET['lista_usuarios'])){

  $N=$_GET['n'];
  $ROL=$_GET['r'];

  if($ROL!=""){
    $sql="SELECT * FROM usuario WHERE (nombre LIKE '%$N%' or usuario LIKE '%$N%' or correo LIKE '%$N%') and rol='$ROL' order by nombre";
  }else{
    $sql="SELECT * FROM usuario WHERE nombre LIKE '%$N%' or usuario LIKE '%$N%' or correo LIKE '%$N%' order by nombre";
  }
}

if(isset($_GET['ver_medico'])){
  $id=$_GET['id'];
  if($id!=""){
    $sql="SELECT * FROM usuario WHERE id='$id' and rol='1'";
  }else{
    $sql="SELECT * FROM usuario WHERE rol='1'";
  }
}
  $resultado=$db->query($sql);

  if($resultado->num_rows>0){//Para ver si se ha insertado
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
        $u=$fila['usuario'];

        $rol=$fila['rol'];

        $esp=$fila['especialidad'];
        $co=$fila['correo'];
        $f_r=$fila['fecha_alta'];
        $f_b=$fila['fecha_baja'];

        if($f_b==="0000-00-00"){
          $f_b="En alta";
        }

        $clave=$fila['clave'];
        $id=$fila['id'];

        echo '<tr style="background-color:'.$color.'">';
        echo    "<td>$n</td>";
        echo    "<td>$u</td>";
        echo    "<td>".buscar_rol($rol)."</td>";
        echo    "<td>$esp</td>";
        echo    "<td>$co</td>";
        echo    "<td>$f_r</td>";
        echo    "<td>$f_b</td>";



          ?>

        <td>
          <form action="PHP/borrar.php" method="GET">

            <input type="hidden" name="id" value="<?php echo $id; ?>  "/>
            <?php
            if($f_b==="En alta"){
            ?>
            <input type="submit" class="botones" value="Dar de baja" name="borrar_usuario" title="Borrar"  onclick="return confirm('¿Quieres dar de baja a este usuario?')"/>

              <?php
            }else{
              ?>
              <input type="submit" class="botones" value="Dar de alta" name="dar_alta" title="Borrar"  onclick="return confirm('¿Quieres dar de alta a este usuario?')"/>
            <?php
          }
            ?>
          </form>
        </td>


        <td>
          <form action="modificar_usuario.php"    method="post">
            <input type="hidden" name="usuario" value="<?php echo $u; ?>  "/>
            <input type="hidden" name="id" value="<?php echo $id; ?>  "/>
            <input type="hidden" name="nombre" value="<?php echo $n; ?>  "/>
            <input type="hidden" name="especialidad" value="<?php echo $esp; ?>  "/>
            <input type="hidden" name="correo" value="<?php echo $co; ?>  "/>
            <input type="hidden" name="clave" value="<?php echo $clave; ?>  "/>
            <input type="submit" class="botones" value="Modificar" name="modificar_usuario" title="Modificar" />
          </form>

        </td>
        <?php
        if ($rol==1) {
        ?>

        <td>
          <form class="" action="gestionar_calendario.php" method="get" target="_blank">
            <input type="hidden" name="medic" value="<?php echo $id; ?> ">
            <input type="submit" class="botones" name="horarios" value="Calendario">
          </form>
        </td>

        <?php
      }else{
        echo "<td></td>";
      }

        echo "</tr>";
      }

  }else if (!$resultado){
      echo '<p> Error</p><br/>';
      echo $sql;
      echo $db->error;
  }else if($resultado->num_rows===0){
    echo' <tr> <td colspan="8"style="background-color: lightblue"> Sin resultados</td></tr> ';
  }



?>

</table>
        <footer>@Copyrigth</footer>
</div>
      </body>
</html>
