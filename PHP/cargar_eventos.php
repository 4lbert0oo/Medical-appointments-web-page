<?php
require_once 'BaseDatos.php';
require_once 'buscar.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
  $db=new BaseDatos();
  $db=$db->conectar();

  $codigo=trim($_GET['codigo']);
  $fecha =trim($_GET['fecha']);
  $medico=trim($_GET['medico']);
  $citas =[];

  $es_festivo=false;
  $es_festivo_m=false;
  $es_festivo_t=false;

  $sql = "SELECT * FROM festivos WHERE (medico = '$medico' or medico='') and fecha='$fecha'";
  $result = $db->query($sql);
  if ($result->num_rows>0) {
    while($row = mysqli_fetch_assoc($result)) {
      switch ($row['tipo']) {
        case 'completo':
          $es_festivo=TRUE;
        break;
        case 'maniana':
          $es_festivo_m=TRUE;
        break;
        case 'tarde':
          $es_festivo_t=TRUE;
        break;

      }

      if($es_festivo_m and $es_festivo_t){
        $es_festivo=true;
      }

    }
  }

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

  if(es_finde_semana($fecha,$medico)){
    $es_festivo=true;
  }

  if($es_festivo==false){
        $sql = "SELECT * FROM cita WHERE medico = '$medico' and fecha='$fecha' and codigo!='$codigo'";

          $result = $db->query($sql);

          $horas_ocupadas= [];
          if ($result->num_rows>0) {
              while($row = mysqli_fetch_assoc($result)) {
                  $duracion=(int)$row['duracion']/30;
                  $horas_ocupadas[]=$row['hora'];

                  $hora=explode(":",$row['hora']);
                  $h=$hora[0];
                  $m=$hora[1];

                  for ($i=0; $i <$duracion ; $i++) {
                    $m=$m+30;
                    if($m==60){
                      $h=$h+1;
                      $m=0;
                    }
                    if($m==0){
                      $m="00";
                    }
                    $H=$h.":".$m;
                    $horas_ocupadas[]=$H;
                  }

              }
          }
        $sql="SELECT * from calendario where medico='$medico'";
        $result = $db->query($sql);
        while($row = mysqli_fetch_assoc($result)) {
//mañana-----------------------------------------------------------
          if($es_festivo_m==false){
              $h_i_m=explode(":",$row['hora_inicio_ma']);
              $h1=$h_i_m[0];
              $m1=$h_i_m[1];

              $h_f_m=explode(":",$row['hora_fin_ma']);
              $h2=$h_f_m[0];
              $m2=$h_f_m[1];

              if(in_array($row['hora_inicio_ma'],$horas_ocupadas)){
                $citas[]=array('hora'=>$row['hora_inicio_ma'],'libre'=>'no');
              }else{
                $citas[]=array('hora'=>$row['hora_inicio_ma'],'libre'=>'si');
              }

                while ($h1<$h2 or $m1<$m2) {
                    $m1=$m1+30;
                    if($m1==60){
                      $h1=$h1+1;
                      $m1=0;
                    }
                    $H=$h1.":".$m1;
                    if($m1==0){ $H=$h1.":"."00"; }

                    if(in_array($H,$horas_ocupadas)){
                      $citas[]=array('hora'=>$H,'libre'=>'no');
                    }else{
                      $citas[]=array('hora'=>$H,'libre'=>'si');
                    }

                }
                if(in_array($row['hora_fin_ma'],$horas_ocupadas)){
                  $citas[]=array('hora'=>$row['hora_fin_ma'],'libre'=>'no');
                }else{
                  $citas[]=array('hora'=>$row['hora_fin_ma'],'libre'=>'si');
                }
          }
//Tarde--------------------------------------------------------------------------------
          if($es_festivo_t==false){
                $h_i_t=explode(":",$row['hora_inicio_tard']);
                $h1=$h_i_t[0];
                $m1=$h_i_t[1];

                $h_f_t=explode(":",$row['hora_fin_tard']);
                $h2=$h_f_t[0];
                $m2=$h_f_t[1];

                if(in_array($row['hora_inicio_tard'],$horas_ocupadas)){
                  $citas[]=array('hora'=>$row['hora_inicio_tard'],'libre'=>'no');
                }else{
                  $citas[]=array('hora'=>$row['hora_inicio_tard'],'libre'=>'si');
                }


                  while ($h1<$h2 or $m1<$m2) {
                      $m1=$m1+30;
                      if($m1==60){
                        $h1=$h1+1;
                        $m1=0;
                      }
                      $H=$h1.":".$m1;
                      if($m1==0){ $H=$h1.":"."00"; }

                      if(in_array($H,$horas_ocupadas)){
                        $citas[]=array('hora'=>$H,'libre'=>'no');
                      }else{
                        $citas[]=array('hora'=>$H,'libre'=>'si');
                      }
                  }
                  if(in_array($row['hora_fin_tard'],$horas_ocupadas)){
                    $citas[]=array('hora'=>$row['hora_fin_tard'],'libre'=>'no');
                  }else{
                    $citas[]=array('hora'=>$row['hora_fin_tard'],'libre'=>'si');
                  }
          }
        }

    }

echo json_encode($citas);
}
?>
