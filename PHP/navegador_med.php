<html lang="es">
  <link rel="stylesheet" type="text/css" href="css/navegador_medic.css" />

  <body>
      <div class="fondo_navegador">
        <div id="navegador">
          <ul class="nav">
            <li><a href="pagina_prin_medico.php">Inicio</a></li>
            <li><a >Pacientes</a>
              <ul>
              <li><a href="crear_pacientes.php"  >Insertar paciente</a></li>
              <li><a href="lista_pacientes_med.php" >Gestionar pacientes</a></li>
            </ul>
             </li>
            <li><a >Citas</a>
              <ul>
                <li><a href="crear_cita_med.php" >Reservar cita</a></li>
                <li><a href="lista_citas.php" >Citas concertadas</a></li>
                <li><a href="citas_disponibles.php">Citas disponibles</a> </li>
              </ul>
            </li>
            <li><a >Calendario</a>
              <ul>
                <li><a href="insertar_festivo.php" >Crear Festivo</a></li>
                <li><a href="lista_festivos.php" >Gestionar festivos</a></li>
                <li><a href="gestionar_calendario.php" >Gestionar calendario medicos</a></li>
              </ul>
            </li>
            <li><a >Usuarios</a>
              <ul>
                <li><a href="insertar_usuario_medico.php" >Insertar usuario</a></li>
                <li><a href="lista_usuarios.php" >Gestionar suarios</a></li>
              </ul>
            </li>
            <?php
            @session_start();
            if($_SESSION["rol"]==1){

              ?>
              <li><a >Informes</a>
                <ul>
                  <li><a href="crear_informes.php" >Crear informe</a></li>
                  <li><a href="lista_informes.php" >Gestionar informes</a></li>
                </ul>
              </li>
              <?php
            }
            ?>

            <li><a >Sesi&oacuten</a>
              <ul>
                <li><a href="PHP/salir.php" >Cerrar sesi&oacuten</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>

    </body>
</html>
