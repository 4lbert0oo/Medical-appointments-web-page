<?php include("PHP/seguridad.php");

 include("PHP/navegador_med.php");
 require_once 'PHP/funciones.php';
 require_once 'PHP/buscar.php';
 require_once 'PHP/BaseDatos.php';

 $id = isset($_POST['id']) ? limpia($_POST['id']) : null;
 $nombre = isset($_POST['nombre']) ? limpia($_POST['nombre']) : null;
 $apellidos = isset($_POST['apellidos']) ? limpia($_POST['apellidos']) : null;
 $documento_id = isset($_POST['DNI']) ? limpia($_POST['DNI']) : null;
 $tipo = isset($_POST['tipo']) ? limpia($_POST['tipo']) : null;
 $direccion = isset($_POST['direccion']) ? limpia($_POST['direccion']) : null;
 $localidad = isset($_POST['localidad']) ? limpia($_POST['localidad']) : null;
 $provincia = isset($_POST['provincia'])? limpia($_POST['provincia']) : null;
 $pais = isset($_POST['pais'])? limpia($_POST['pais']) : null;
 $fecha_n =isset($_POST['fecha_n']) ? limpia($_POST['fecha_n']) : null;

 $db=new BaseDatos();
 $db=$db->conectar();


 ?>
 <!DOCTYPE html>
 <html lang="es">
 <link rel="stylesheet" type="text/css" href="css/ficha_paciente.css" />
     <head>
       <title>Ficha de <?php echo $nombre." ".$apellidos; ?> </title>

       <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
     </head>

       <body>

             <table id="tabla">
               <tr>
                 <td ><a>Nombre:</a></td>
                 <td><?php echo $nombre; ?></td>

               </tr>
               <tr>
                 <td><a>Apellidos:</a></td>
                 <td><?php echo $apellidos; ?></td>

               </tr>
               <tr>
                 <td><a>Fecha de nacimiento:</a></td>
                 <td><?php echo $fecha_n; ?></td>

               </tr>
               <tr>
                 <td><a>Documento ID:</a></td>
                 <td><?php echo $documento_id; ?></td>
               </tr>

               <tr>
                 <td> <a>Tipo de documento:</a> </td>
                 <td><?php echo $tipo; ?></td>
               </tr>

               <tr>
                 <td><a>Direccion:</a></td>
                 <td><?php echo $direccion; ?></td>

               </tr>
               <tr>
                 <td>Pais:</td>
                 <td><?php echo $pais; ?></td>

               </tr>
               <tr>
                 <td><a>Provincia:</a></td>
                 <td><?php echo $provincia; ?></td>
               </tr>
               <tr>
                 <td><a>Localidad:</a></td>
                 <td><?php echo $localidad; ?></td>

               </tr>
             </table>
              <br>

             <ul id="citas">
              <b >Citas:</b>
               <?php
                $sql="SELECT * FROM cita WHERE paciente='$id'";
                $resultado=$db->query($sql);

                if($resultado->num_rows>0){
                  while($fila= mysqli_fetch_assoc($resultado)){
                    $medico=buscar_usuario($fila['medico']);
                    echo "<li>-Cita el ".$fila['fecha']." a las ".$fila['hora']." con ".$medico[0]." : ".$medico[1]."</li>  ";
                  }
                }else{
                  echo "<li>Sin citas</li>";
                }
                ?>
             </ul>
             <br>
             <ul id="informes">
               <b >Informes:</b>
               <?php
                $sql="SELECT * FROM informe WHERE paciente='$id'";
                $resultado=$db->query($sql);

                if($resultado->num_rows>0){
                  while($fila= mysqli_fetch_assoc($resultado)){
                    $medico=buscar_usuario($fila['medico']);
                    echo "<li>- ".$fila['titulo'].": escrito por ".$medico[0]." : ".$medico[1]." el ".$fila['fecha']." a las ".$fila['hora']."</li>  ";
                  }
                }else{
                  echo "<li>Sin informes</li>";
                }
                ?>
             </ul>


         <footer>@Copyrigth</footer>


       </body>
   </html>
