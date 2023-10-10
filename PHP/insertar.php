
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>


<?php
require_once 'BaseDatos.php';
require_once 'buscar.php';

function registrar_paciente($n,$a,$dni,$tipo,$fn,$di,$loc,$pro,$pais){
  $last_id=0;
  $db=new BaseDatos();
  $db=$db->conectar();



  $sql="INSERT INTO paciente (nombre,apellidos,fecha_nacimiento,documento_id,tipo_doc,direccion,localidad,provincia,pais) VALUES ('$n','$a','$fn','$dni','$tipo','$di','$loc','$pro','$pais')";

  if($db->query($sql)===TRUE){//Para ver si se ha insertado
      $last_id=$db->insert_id;
      echo 'Insertado con exito';
      return true;
      //header('Location:/practica/crear_pacientes.php');
  }else{
      echo '<p> Error</p>';
      echo $sql;
      echo $db->error;
      return false;
  }

}

function registrar_cita($f,$h,$paciente,$medico,$d,$obser){

  $db=new BaseDatos();
  $db=$db->conectar();


  $sql="INSERT INTO cita (fecha,hora,paciente,medico,duracion,observaciones) VALUES ('$f','$h','$paciente','$medico','$d','$obser')";

  if($db->query($sql)===TRUE){//Para ver si se ha insertado
      return true;
  }else{
      return false;
  }


}

function registrar_usuario($n,$u,$c,$rol,$esp,$co){

  $db=new BaseDatos();
  $db=$db->conectar();

  $sql="INSERT INTO usuario (nombre,usuario,clave,rol,especialidad,correo) VALUES ('$n','$u','$c','$rol','$esp','$co')";

  if($db->query($sql)===TRUE){//Para ver si se ha insertado
    if($rol==='1'){
      $user=buscar_usuario_user($u);
      registrar_calendario($user);
    }
      return true;
  }else{
      return false;
  }


}

function registrar_calendario($id){
  $db=new BaseDatos();
  $db=$db->conectar();

  $sql="INSERT INTO calendario (medico,duracion_cita) VALUES ('$id','30')";

  if($db->query($sql)===TRUE){//Para ver si se ha insertado
      return true;
  }else{
      return false;
  }

}



function registrar_informe($t,$p,$m,$c){

  $db=new BaseDatos();
  $db=$db->conectar();


  $sql="INSERT INTO informe (titulo,paciente,medico,contenido) VALUES ('$t','$p','$m','$c')";

  if($db->query($sql)===TRUE){//Para ver si se ha insertado
      return true;
  }else{
      return false;
  }

}

function registrar_festivos($fecha,$tipo,$medico){

  $db=new BaseDatos();
  $db=$db->conectar();


  $sql="INSERT INTO festivos (fecha,tipo,medico) VALUES ('$fecha','$tipo','$medico')";

  if($db->query($sql)===TRUE){//Para ver si se ha insertado
      return true;
  }else{
      return false;
  }

}

?>
  </body>
</html>
