<?php
require_once 'BaseDatos.php';
include("seguridad.php");



function modificar_paciente($nomb,$ape,$DNI,$tipo,$fecha,$direc,$local,$provin,$pais,$id){

    $db=new BaseDatos();
    $db=$db->conectar();

    $sql="UPDATE paciente set nombre='$nomb', apellidos='$ape', documento_id='$DNI',tipo_doc='$tipo',fecha_nacimiento='$fecha',direccion='$direc',localidad='$local', provincia='$provin', pais='$pais' where id='$id'";

    $resultado=$db->query($sql);

    if($resultado===TRUE){
      return true;
    }else{
      return false;
    }

}



function modificar_usuario($n,$u,$clave,$nueva_clave,$especialidad,$correo,$id){

  $db=new BaseDatos();
  $db=$db->conectar();



  if($nueva_clave===""){
    $nueva_clave=$clave;
  }

    $sql="UPDATE usuario set nombre='$n', usuario='$u',clave='$nueva_clave',especialidad='$especialidad',correo='$correo' where id='$id' ";

    $resultado=$db->query($sql);

    if($resultado===TRUE){
      return true;
    }else{
      return false;
    }



}

function modificar_cita($f, $h, $d,$c,$o){

  $db=new BaseDatos();
  $db=$db->conectar();


  $sql="UPDATE cita set fecha='$f', hora='$h', duracion='$d',observaciones='$o' WHERE codigo='$c' ";
  //echo $sql;
  $resultado=$db->query($sql);

  if($resultado===TRUE){

    return true;

  }else{
    return false;
  }

}

function modificar_informe($titulo, $paciente, $contenido, $codigo){

  $db=new BaseDatos();
  $db=$db->conectar();

  $sql="UPDATE informe set titulo='$titulo', paciente='$paciente', contenido='$contenido' WHERE cod_inf='$codigo'";

  $resultado=$db->query($sql);

  if($resultado===TRUE){

    return true;
  }else{
    return false;
  }

}

function modificar_festivos($codigo,$fecha,$tipo){

  $db=new BaseDatos();
  $db=$db->conectar();


  $sql="UPDATE festivos set fecha='$fecha',tipo='$tipo' WHERE codigo=$codigo";

  if($db->query($sql)===TRUE){//Para ver si se ha insertado
      return true;
  }else{
      return false;
  }

}

function modificar_calendario($id,$hora_i_m,$hora_f_m,$hora_i_t,$hora_f_t,$sabado,$domingo,$duracion){

  $db=new BaseDatos();
  $db=$db->conectar();


  $sql="UPDATE calendario set	hora_inicio_ma='$hora_i_m', hora_fin_ma='$hora_f_m', hora_inicio_tard='$hora_i_t', hora_fin_tard='$hora_f_t', sabado_h='$sabado', domingo_h='$domingo', duracion_cita='$duracion' WHERE id=$id";

  if ($sabado=="" and $domingo!="") {
    $sql="UPDATE calendario set	hora_inicio_ma='$hora_i_m', hora_fin_ma='$hora_f_m', hora_inicio_tard='$hora_i_t', hora_fin_tard='$hora_f_t',  domingo_h='$domingo', duracion_cita='$duracion' WHERE id=$id";
  }else if($sabado!="" and $domingo==""){
    $sql="UPDATE calendario set	hora_inicio_ma='$hora_i_m', hora_fin_ma='$hora_f_m', hora_inicio_tard='$hora_i_t', hora_fin_tard='$hora_f_t', sabado_h='$sabado', duracion_cita='$duracion' WHERE id=$id";
  }else if($sabado=="" and $domingo==""){
    $sql="UPDATE calendario set	hora_inicio_ma='$hora_i_m', hora_fin_ma='$hora_f_m', hora_inicio_tard='$hora_i_t', hora_fin_tard='$hora_f_t', duracion_cita='$duracion' WHERE id=$id";
  }

  if($db->query($sql)===TRUE){//Para ver si se ha insertado
      return true;
  }else{
      return false;
  }

}
