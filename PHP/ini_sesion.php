<?php
require_once 'BaseDatos.php';

$db=new BaseDatos();
$db=$db->conectar();



$sql="select * from usuario where usuario =".$_POST["usuario"];

//$resultado=$db->query($sql);

if($resultado->num_rows===1){
  $fila= mysqli_fetch_assoc($resultado);
  $c1=$fila["clave"];
  $c1=trim($c1);
  $c=$_POST["clave"];
  $fecha=$fila["fecha_baja"];
  if($c1===$c){
    session_start();
    $_SESSION["autentica"] = "SIP";
    $_SESSION["usuarioactual"] = $fila["usuario"];
    $_SESSION["id_usuario"]=$fila["id"];
    $_SESSION["rol"]=$fila["rol"];
    $rol=$fila["rol"];
    $rol=trim($rol);

      header('Location:../pagina_prin_medico.php');

  }else{
    echo'<script>alert("Clave incorrecta.");</script>';
    header("Location: ../index.html");
  }

}else{
  echo'<script>alert("El usuario "'.$_POST["usuario"].'" no existe.");</script>';
  header("Location: ../index.html");
}


?>
