<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $conn = mysqli_connect("localhost", "practica", "practica" ,"alberto_garcia_paño");
    if (!$conn) {
        die("Error en la conexion : " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM provincias";

    $result = mysqli_query($conn, $sql);

    $provincias = array();
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {

            $provincia = array('id'=> $row['id_provincia'], 'nombre'=> $row['provincia']);
            $provincias[] = $provincia;
        }
    }


    $json_string = json_encode($provincias);
    echo $json_string;
}
?>
