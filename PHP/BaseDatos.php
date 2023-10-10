<?php



class BaseDatos {
    private $host ;
    private $username ;
    private $passwd ;
    private $dbname;
    private $conect;

    function conectar(){
        $this->host="localhost";
        $this->username ="practica";
        $this->passwd ="practica";
        $this->dbname ="pw";

        $conect= new mysqli("localhost", "practica", "practica" ,"alberto_garcia_paño");

        if($conect ->connect_errno){
            die("Error".$objetoMysqli->mysqli_connect_errno().", ".$objetoMysqli->mysqli_connect_error());
        }
        return $conect;
    }

    function cerrar_conexion(){
        $this->conect->mysqli_close();
    }
}
