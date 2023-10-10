<?php
require ('../fpdf/fpdf.php');

class PDF extends FPDF
{

  function Header(){
    $this->Image( '../img/Logo.png', 170,8,33);

    $this->SetFont('Arial','B',15);

    $this->Cell( 65);

    $this->Cell( 60,  10, utf8_decode('Informe'),  0,  0, 'C');

    $this->Ln( 20);//salto línea
  }

  function Footer(){

      $this->SetY(-15);
      $this->SetFont('Arial', 'I',  8);
      $this->Cell( 0, 10,   utf8_decode('Pagina').$this->PageNo().'/(nb)', 0, 0, 'C');
  }

}

require ('BaseDatos.php');
  if(isset($_GET['informe'])){

    $titulo=$_GET['titulo'];
    $medico=$_GET['medico'];
    $paciente=$_GET['paciente'];
    $fecha=$_GET['fecha'];
    $hora=$_GET['hora'];
    $contenido=$_GET['contenido'];

    $pdf=new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont( 'Times','', 13);

    $pdf-> Text( 30,  40,  'Fecha: '.$fecha.', '.$hora);
    $pdf-> Text( 30,  50,  'Medico: '.$medico);
    $pdf-> Text( 30,  60,  'Paciente: '.$paciente);
    $pdf-> Text( 30,  90,  ''.$contenido);

    $pdf->Output();
  }

?>
