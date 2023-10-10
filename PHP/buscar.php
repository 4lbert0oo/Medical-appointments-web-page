<?php
require_once 'BaseDatos.php';



function buscar_paciente($id){
  $db=new BaseDatos();
  $db=$db->conectar();

  $resultado=array();

  $sql="SELECT * from paciente where id='$id'";
  $r=$db->query($sql);
  if($r->num_rows>0) {
    while($row = mysqli_fetch_assoc($r)) {
      $resultado[]=$row['nombre'];
      $resultado[]=$row['apellidos'];
      $resultado[]=$row['documento_id'];
    }
  }else{
    $resultado[]="ERROR";
  }

  return $resultado;
}

function buscar_provincia($id){
  $db=new BaseDatos();
  $db=$db->conectar();

  $resultado="";

  $sql="SELECT * from provincias where id_provincia='$id'";
  $r=$db->query($sql);
  if($r->num_rows>0) {
    while($row = mysqli_fetch_assoc($r)) {
      $resultado=$row['provincia'];
    }
  }else{
    $resultado="ERROR";
  }

  return $resultado;

}

function buscar_municipio($id){
  $db=new BaseDatos();
  $db=$db->conectar();

  $resultado="";

  $sql="SELECT * from municipios where id_municipio='$id'";
  $r=$db->query($sql);
  if($r->num_rows>0) {
    while($row = mysqli_fetch_assoc($r)) {
      $resultado=$row['nombre'];
    }
  }else{
    $resultado="ERROR";
  }

  return $resultado;

}

function buscar_usuario($id){
  $db=new BaseDatos();
  $db=$db->conectar();

  $resultado=array();

  $sql="SELECT * from usuario where id='$id'";
  $r=$db->query($sql);
  if($r->num_rows>0) {
    while($row = mysqli_fetch_assoc($r)) {
      $resultado[]=$row['nombre'];
      $resultado[]=$row['usuario'];
      $resultado[]=$row['correo'];
      $resultado[]=$row['rol'];
    }
  }else{
    $resultado[]="ERROR";
  }

  return $resultado;

}

function buscar_usuario_user($usuario){
  $db=new BaseDatos();
  $db=$db->conectar();

  $resultado="";

  $sql="SELECT * from usuario where usuario='$usuario'";
  $r=$db->query($sql);
  if($r->num_rows>0) {
    while($row = mysqli_fetch_assoc($r)) {
      $resultado=$row['id'];
    }
  }else{
    $resultado="ERROR";
  }

  return $resultado;
}

function buscar_rol($codigo){
  $db=new BaseDatos();
  $db=$db->conectar();

  $resultado="";
  $sql="SELECT * from rol where codigo='$codigo'";
  $r=$db->query($sql);
  if($r->num_rows>0) {
    while($row = mysqli_fetch_assoc($r)) {
      $resultado=$row['nombre'];
    }
  }else{
    $resultado="ERROR";
  }

  return $resultado;
}

function buscar_festivos($medico){
  $db=new BaseDatos();
  $db=$db->conectar();

  $resultado=array();

  $sql="SELECT * from festivos where tipo='completo' AND medico='$medico'";
  $r=$db->query($sql);
  if($r->num_rows>0) {
    while($row = mysqli_fetch_assoc($r)) {
      $resultado[]=$row['fecha'];
    }
  }

  $sql2="SELECT * from festivos where tipo='completo' AND medico=''";
  $r2=$db->query($sql2);
  if($r2->num_rows>0) {
    while($row = mysqli_fetch_assoc($r2)) {
      $resultado[]=$row['fecha'];
    }
  }

  return $resultado;
}

function buscar_festivos_tarde($medico){
  $db=new BaseDatos();
  $db=$db->conectar();

  $resultado=array();

  $sql="SELECT * from festivos where tipo='tarde' AND medico='$medico'";
  $r=$db->query($sql);
  if($r->num_rows>0) {
    while($row = mysqli_fetch_assoc($r)) {
      $resultado[]=$row['fecha'];
    }
  }

  $sql2="SELECT * from festivos where tipo='tarde' AND medico=''";
  $r2=$db->query($sql2);
  if($r2->num_rows>0) {
    while($row = mysqli_fetch_assoc($r2)) {
      $resultado[]=$row['fecha'];
    }
  }

  return $resultado;
}

function buscar_festivos_maniana($medico){
  $db=new BaseDatos();
  $db=$db->conectar();

  $resultado=array();

  $sql="SELECT * from festivos where tipo='maniana' AND medico='$medico'";
  $r=$db->query($sql);
  if($r->num_rows>0) {
    while($row = mysqli_fetch_assoc($r)) {
      $resultado[]=$row['fecha'];
    }
  }

  $sql2="SELECT * from festivos where tipo='maniana' AND medico=''";
  $r2=$db->query($sql2);
  if($r2->num_rows>0) {
    while($row = mysqli_fetch_assoc($r2)) {
      $resultado[]=$row['fecha'];
    }
  }

  return $resultado;
}

function buscar_habiles($medico){
  $db=new BaseDatos();
  $db=$db->conectar();

  $sabado="";
  $domingo="";
  $sql="SELECT * from calendario where medico='$medico'";
  $r=$db->query($sql);
  if($r->num_rows>0) {
    while($row = mysqli_fetch_assoc($r)) {
      $sabado=$row['sabado_h'];
      $domingo=$row['domingo_h'];
    }
  }

  return $resultado;
}

  function buscar_horario($medico){
    $db=new BaseDatos();
    $db=$db->conectar();

    $sql="SELECT * from calendario where medico='$medico'";

    $resultado=array();
    $r=$db->query($sql);
    if($r->num_rows>0) {
      while($row = mysqli_fetch_assoc($r)) {

        $resultado[]=$row['hora_inicio_ma'];
        $resultado[]=$row['hora_fin_ma'];

        $resultado[]=$row['hora_inicio_tard'];
        $resultado[]=$row['hora_fin_tard'];
      }
    }

    return $resultado;
  }


function es_finde_semana($fecha,$medico) {
  $db=new BaseDatos();
  $db=$db->conectar();

  $sabado="";
  $domingo="";
  $sql="SELECT * from calendario where medico='$medico'";
  $r=$db->query($sql);
  if($r->num_rows>0) {
    while($row = mysqli_fetch_assoc($r)) {
      $sabado=$row['sabado_h'];
      $domingo=$row['domingo_h'];
    }
  }
    $dias = array('', 'Lunes','Martes','Miercoles','Jueves','Viernes','Sabado', 'Domingo');
    $f = $dias[date('N', strtotime($fecha))];
    if(($f=='Domingo' and $domingo=='0') or ($f=='Sabado' and $sabado=='0')){
      return true;
    }else{
      return false;
    }
}
