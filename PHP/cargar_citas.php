<?php
require_once 'BaseDatos.php';
//INCOMPLETO

if (isset($_POST['buscar_citas'])){
  $db=new BaseDatos();
  $db=$db->conectar();



  $medico=trim($_POST['buscar_citas']);



  $horas=array();


  $sql="SELECT * FROM calendario where medico='$medico'";
  $sql2="SELECT * FROM calendario where medico='todos'";

  $result = $db->query($sql);
  $result2 = $db->query($sql2);

  if($result===TRUE){
    if (mysqli_num_rows($result) >0) {

      while($row = mysqli_fetch_assoc($result)) {
        $horas[]=$row['hora_inicio_ma'];
        $horas[]=$row['hora_inicio_tard'];

      }
    }
  }else{
    if (mysqli_num_rows($result2) > 0) {

      while($row = mysqli_fetch_assoc($result2)) {
        $horas[]=$row['hora_inicio_ma'];
        $horas[]=$row['hora_inicio_tard'];
      }
    }
  }


  $json_string = json_encode($horas);
  echo $json_string;
}
