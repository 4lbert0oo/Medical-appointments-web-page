<?php
require_once 'BaseDatos.php';

if(isset($_GET['borrar_paciente'])){
    $db=new BaseDatos();
    $db=$db->conectar();

    $id=$_GET['id'];

    $sql="DELETE from paciente where id='$id'";

    if($db->query($sql)==TRUE){
        echo  "Borrado correctamente ";

        header('Location:../lista_pacientes_med.php');
    }else{
        echo  "Error al borrar ".$db->error;
    }

}

if(isset($_GET['borrar_cita'])){
  $db=new BaseDatos();
  $db=$db->conectar();

  $c=$_GET['codigo'];
  $p=$_GET['procedencia'];

  $sql="DELETE from cita where codigo='$c'";

  if($db->query($sql)==TRUE){
      echo  "Borrado correctamente ";
      echo $sql;

      header('Location:../lista_citas.php');

  }else{
      echo  "Error al borrar ".$db->error;
  }

}

function borrar_cita($codigo){
  $db=new BaseDatos();
  $db=$db->conectar();

  $sql="DELETE from cita where codigo='$c'";
  $db->query($sql);
}

if(isset($_GET['borrar_usuario'])){
  $db=new BaseDatos();
  $db=$db->conectar();

  $id=$_GET['id'];
  $month=date("n");
  $year=date("Y");
  $dia=date("j");

  $fecha=$year."-".$month."-".$dia;
  @session_start();
  if  ($id!=$ID){
    $sql="UPDATE usuario SET fecha_baja='$fecha' where id='$id'";

    if($db->query($sql)==TRUE){
        echo  "Borrado correctamente ";
        echo $sql;

        header('Location:../lista_usuarios.php');

    }else{
        echo  "Error al borrar ".$db->error;
        echo $sql;
    }
  }else{
    echo "No puedes darte de baja con la sesión abierta.";
  }
}

if(isset($_GET['dar_alta'])){
  $db=new BaseDatos();
  $db=$db->conectar();

  $id=$_GET['id'];

    $sql="UPDATE usuario SET fecha_baja='' where id='$id'";

    if($db->query($sql)==TRUE){
        echo  "Borrado correctamente ";
        echo $sql;

        header('Location:../lista_usuarios.php');

    }else{
        echo  "Error al borrar ".$db->error;
        echo $sql;
    }


}

if(isset($_GET['borrar_informe'])){
  $db=new BaseDatos();
  $db=$db->conectar();

  $c=$_GET['codigo'];

  $sql="DELETE from informe where cod_inf='$c'";

  if($db->query($sql)===TRUE){
      echo  "Borrado correctamente ";
      echo $sql;

      header('Location:../lista_informes.php');

  }else{
      echo  "Error al borrar ".$db->error;
      echo $sql;
  }

}

if(isset($_GET['borrar_festivo'])){
  $db=new BaseDatos();
  $db=$db->conectar();

  $c=$_GET['codigo'];

  $sql="DELETE from festivos where codigo='$c'";

  if($db->query($sql)===TRUE){
      echo  "Borrado correctamente ";
      echo $sql;

      header('Location:../lista_festivos.php');

  }else{
      echo  "Error al borrar ".$db->error;
      echo $sql;
  }

}
