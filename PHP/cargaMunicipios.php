<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = mysqli_connect("localhost", "practica", "practica" ,"alberto_garcia_paño");
    if (!$conn) {
        die("Error en la conexion : " . mysqli_connect_error());
    }

    $id = trim($_POST['provincia']);


    $sql = "SELECT * FROM municipios WHERE id_provincia = '$id'";


    $result = mysqli_query($conn, $sql);

    $municipios = array();
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            //echo "id: " . $row["id_municipio"]. " - Nombre: " . $row["nombre"]. "<br>";
            $municipio = array('id'=> $row['id_municipio'], 'nombre'=> $row['nombre']);
            $municipios[] = $municipio;
        }
    }


    $json_string = json_encode($municipios);
    echo $json_string;
}
?>
