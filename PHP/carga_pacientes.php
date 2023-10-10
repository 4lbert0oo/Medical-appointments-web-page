<?php
require_once 'BaseDatos.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {


    $m=$_GET['paciente'];

    $db=new BaseDatos();
    $db=$db->conectar();


    $sql = "SELECT * FROM paciente WHERE nombre LIKE '%$m%' or apellidos LIKE '%$m%' or documento_id LIKE '%$m%' order by nombre";



    $result = $db->query($sql);

      $pacientes=[];
      while($row = mysqli_fetch_assoc($result)) {

          $paciente = array('id'=>$row['id'] ,'nombre'=>$row['nombre'],'apellidos'=>$row['apellidos'],'documento_id'=>$row['documento_id'] );
          $pacientes[] = $paciente;
      }



    $json_string = json_encode($pacientes);
    echo $json_string;
}
?>
