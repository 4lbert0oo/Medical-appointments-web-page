<?php
require_once 'BaseDatos.php';


    function validaRequerido($valor){
        if(trim($valor) == '')
            return false;
        else
            return true;
    }

    function validaEntero($valor, $opciones=null){
        if(filter_var($valor, FILTER_VALIDATE_INT, $opciones) === FALSE)
            return false;
        else
            return true;
    }

    function validaEmail($valor){
        // eliminamos caracteres no validos
        $valorlimpio = filter_var($valor, FILTER_SANITIZE_EMAIL);

        if ($valorlimpio!=$valor)
            return false;
        else if(filter_var($valor, FILTER_VALIDATE_EMAIL) === FALSE)
            return false;
        else
            return true;
    }

    function validaLongitud($valor,$min,$max){
      if($valor>=$min and $valor<=$max){
        return true;
      }else{
        return false;
      }
    }

    function validar_dni($dni,$id,$tipo){
      	$letra = substr($dni, -1);
      	$numeros = substr($dni, 0, -1);
        if($tipo=="dni"){
            if ( $dni!=""/*substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros%23, 1) == $letra && strlen($letra) == 1 && strlen ($numeros) == 8*/ ){
              $db=new BaseDatos();
              $db=$db->conectar();

              $sql="SELECT * from paciente where documento_id='$dni' and id!='$id'";
              $result = $db->query($sql);
              if (mysqli_num_rows($result) === 0) {
                return true;
              }else{
                return false;
              }

          	}else{
          		return false;
          	}
        }else{
          return validateNie($dni,$id);
        }
    }

    function validateNie($nif,$id){
      if (preg_match('/^[XYZT][0-9][0-9][0-9][0-9][0-9][0-9][0-9][A-Z0-9]/', $nif)) {
        for ($i = 0; $i < 9; $i ++){
          $num[$i] = substr($nif, $i, 1);
        }

        if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X','Y','Z'), array('0','1','2'), $nif), 0, 8) % 23, 1)) {
          $db=new BaseDatos();
          $db=$db->conectar();

          $sql="SELECT * from paciente where documento_id='$nif' and id!='$id'";
          $result = $db->query($sql);
          if (mysqli_num_rows($result) === 0) {
            return true;
          }else{
            return false;
          }
        } else {
          return false;
        }
      }
    }

    function limpia($valor) {
        return trim(htmlspecialchars($valor));
    }
    function validaLetras($valor) {
        if (reg_match("/^[a-zA-Z0-p9]+$/", $valor))
            return true;
        return false;
    }
    function validaFecha($valor) {
        $array = explode("-",$valor);
        $dia = $array[2];
        $mes = $array[1];
        $ano = $array[0];
        if (!is_numeric($dia) || !is_numeric($mes) || !is_numeric($ano))
            return false;
        else
            return checkdate($mes, $dia, $ano);
    }

    function checktime($valor) {
      $array = explode(":",$valor);
      $hour= $array[0];
      $min=$array[1];
       if ($hour < 0 || $hour > 23 || !is_numeric($hour)) {
           return false;
       }
       if ($min < 0 || $min > 59 || !is_numeric($min)) {
           return false;
       }

       return true;
   }

   function combrobar_fecha($fecha,$hoy){
     $f_hoy = explode("-",$hoy);
     $dia = $f_hoy[2];
     $mes = $f_hoy[1];
     $ano = $f_hoy[1];

     $f_f = explode("-",$fecha);
     $dia_f = $f_f[2];
     $mes_f = $f_f[1];
     $ano_f = $f_f[1];
     if($ano_f>=$ano and $mes_f>=$mes and $dia_f>=$dia){
       return true;
     }else{
       return false;
     }

   }

   function comprobar_hora($duracion,$Fecha,$Hora_1,$medico,$codigo){
           $db=new BaseDatos();
           $db=$db->conectar();

           $d=explode(":",$Hora_1);
           $h=$d[0];
           $m=$d[1];
           $h_f=$h;
           $m_f=$m;
           for ($i=0; $i <$duracion/30 ; $i++) {
             $m_f=$m_f+30;
             if($m_f==60){
               $h_f=$h_f+1;
               $m_f=0;
             }
           }

           $sql="SELECT * FROM cita where medico='$medico' and fecha='$Fecha' and codigo!='$codigo'";
           $result = $db->query($sql);
           if (mysqli_num_rows($result) > 0) {
               while($row = mysqli_fetch_assoc($result)) {
                  $Hora_2=explode(":",$row['hora']);
                  $h2=$Hora_2[0];
                  $m2=$Hora_2[1];
                  $h_f2=$h2;
                  $m_f2=$m2;
                  for ($i=0; $i <$row['duracion']/30 ; $i++) {
                    $m_f2=$m_f2+30;
                    if($m_f2==60){
                      $h_f2=$h_f2+1;
                      $m_f2=0;
                    }
                  }
                  if(($h2>$h and $h_f<=$h2) or ($h_f2<=$h and $h_f>$h_f2)){
                    if($h2==$h_f and $m_f>$m2){
                      return false;
                    }
                    if($h==$h_f2 and $m<$m_f2){
                      return false;
                    }

                  }else{
                    return false;
                  }
               }
           }


     return true;

   }

   function comprobar_festivo_hora($duracion,$fecha,$hora,$medico){
     $db=new BaseDatos();
     $db=$db->conectar();

     $HORA=explode(":",$hora);
     $H=$HORA[0];
     $m=$HORA[1];
     $HF=$H;
     $m_f=$m;
     for ($i=0; $i <$duracion/30 ; $i++) {
       $m_f=$m_f+30;
       if($m_f==60){
         $HF=$HF+1;
         $m_f=0;
         if($HF==24){
          $HF=0;
         }
       }
     }

     $sql="SELECT * from calendario where medico='$medico'";

     $r=$db->query($sql);
     if($r->num_rows>0) {
       while($row = mysqli_fetch_assoc($r)) {
         $h_i_m=explode(":",$row['hora_inicio_ma']);
         $h1=$h_i_m[0];
         $m1=$h_i_m[1];

         $h_f_m=explode(":",$row['hora_fin_ma']);
         $h2=$h_f_m[0];
         $m2=$h_f_m[1];

         $h_i_t=explode(":",$row['hora_inicio_tard']);
         $h3=$h_i_t[0];
         $m3=$h_i_t[1];

         $h_f_t=explode(":",$row['hora_fin_tard']);
         $h4=$h_f_t[0];
         $m4=$h_f_t[1];

         $SQL="SELECT * from festivos WHERE (fecha='$fecha' and medico='$medico') OR (fecha='$fecha' and medico='')";
         $resultado=$db->query($SQL);
         if($resultado->num_rows>0) {
           while($fila= mysqli_fetch_assoc($resultado)) {
              $tipo=$fila['tipo'];

              if(($H>=$h1 and $HF>$h1 ) and ($H<$h2 and $HF<=$h2)){
                if(($H==$h1 and $m<$m1) or ($HF==$h2 and $m_f>$m2)){
                  return false;
                }
                if($tipo=="maniana"){
                  return false;
                }
              }else if(($H>=$h3 and $HF>$h3 ) and ($H<$h4 and $HF<=$h4)){
                if(($H==$h3 and $m<$m3) or ($HF==$h4 and $m_f>$m4)){
                  return false;
                }
                if($tipo=="tarde"){
                  return false;
                }
              }else{
                return false;
              }
           }
         }else{
           if(($H>=$h1 and $HF>$h1 ) and ($H<$h2 and $HF<=$h2)){
             if(($H==$h1 and $m<$m1) or ($HF==$h2 and $m_f>$m2)){
               return false;
             }

           }else if(($H>=$h3 and $HF>$h3 ) and ($H<$h4 and $HF<=$h4)){
             if(($H==$h3 and $m<$m3) or ($HF==$h4 and $m_f>$m4)){
               return false;
             }

           }else{
             return false;
           }
         }

       }
     }

     return true;
   }


?>
