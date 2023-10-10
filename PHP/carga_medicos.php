<?php
require_once 'BaseDatos.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {


    $m=$_GET['medico'];

    $db=new BaseDatos();
    $db=$db->conectar();


    $sql = "SELECT * FROM usuario WHERE (nombre LIKE '%$m%' or usuario LIKE '%$m%' or correo LIKE '%$m%') and rol='1' and fecha_baja='0000-00-00' order by nombre";



    $result = $db->query($sql);

      $medicos=[];
      while($row = mysqli_fetch_assoc($result)) {

          $medico = array('id'=>$row['id'] ,'nombre'=>$row['nombre'],'usuario'=>$row['usuario'],'correo'=>$row['correo'] );
          $medicos[] = $medico;
      }



    $json_string = json_encode($medicos);
    echo $json_string;
}
?>
