<?php

// Crear la conexion con la base de datos
include("conexion/conexion.php");

// Capturamos el id con el método get que proviene desde la url
$id = (isset($_GET['id'])) ? $_GET['id'] : "";

$consultaPersona = $pdo->prepare("SELECT * FROM persona WHERE id=:id");
$consultaPersona->bindParam(':id', $id);
$consultaPersona->execute();

$persona = $consultaPersona->fetch(PDO::FETCH_ASSOC);

// Capturamos si se ha pulsado el botón de eliminar mediante parámetro get
$eliminar = (isset($_GET['del'])) ? $_GET['del'] : "";

// Si se presionó eliminar, caputará el id del elemento que se seleccionó para eliminar
if ($eliminar) {
    $id = $_GET['del'];
    // Creando la sentencia SQL para eliminar los valores en la BD
    // Utilizo pdo para preparar la sentencia
    $sentencia = $pdo->prepare("DELETE FROM persona WHERE id=:id");

    // bindParam será para asignar los valores referenciados anteriormente        
    $sentencia->bindParam(':id', $id);

    // Ejecutar la instrucción de la sentencia
    $sentencia->execute();
    header("index.php");
} else {
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />    
    <meta name="author" content="Abel Consuegra" />
    <title><?php echo $persona['nombres'] ?> <?php echo $persona['apellidos'] ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">    

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <script src="https://kit.fontawesome.com/a018cd853a.js" crossorigin="anonymous"></script>    
    <link href="css/estilos.css" rel="stylesheet" />
    <link rel="icon" href="img/logo_muni.png">
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark  px-2">
        <a href="index.php"> <img class="ml-2" src="img/logo_muni.png" width="50px" alt=""></a>        
        <p class="text-light mt-3 ml-2">Municipalidad de Siguatepeque</p>
        <div class="fecha mr-1 ml-auto text-light">
            <script type="text/javascript">
                var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                var diasSemana = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
                var f = new Date();
                document.write(diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " del " + f.getFullYear());
            </script>
        </div>


    </nav>
    <div id="layoutSidenav_content">
        <main>
            <div class="container mt-4 pt-3 p-4 pb-2 mb-4 px-3" style="-webkit-box-shadow: 0 0 5px 2px rgba(0, 0, 0, .5);
           box-shadow: 0 0 5px 2px rgba(0, 0, 0, .2);">

                <!-- Nombre y Fecha de Registro -->
                <div class="row">
                    <div class="ml-2 row align-items-center col-lg-10">
                        <h2><?php echo $persona['nombres'] ?> <?php echo $persona['apellidos'] ?></h2>
                    </div>
                    <div class="ml-2 row align-items-center col-lg-2">
                    <a href='#' title="Eliminar permanentemente" class="btn btn-danger" onclick="preguntar(<?php echo $persona['id'] ?>)">Eliminar</a>
                    </div>
                </div>
                <!-- Linea horizontal -->
                <hr>
                <div class="ml-3 mt-4 pt-1 row align-items-center col-lg-12">
                    <h6>Número de identidad: </h6>
                    <p class="ml-2 mb-2"> <?php echo $persona['identidad'] ?></p>
                </div>
                <div class="ml-3 mt-4 pt-1 row align-items-center col-lg-12">
                    <h6>Dirección: </h6>
                    <p class="ml-2 mb-2"> <?php echo $persona['direccion'] ?></p>
                </div>

                <div class="ml-3 mt-4 pt-1 row align-items-center col-lg-12">
                    <h6>Teléfono: </h6>
                    <p class="ml-2 mb-2"> <?php echo $persona['telefono'] ?></p>
                </div>

                <div class="ml-3 mt-4 pt-1 row align-items-center col-lg-12">
                    <h6>Correo electrónico: </h6>
                    <?php if ($persona['correo']) { ?>
                        <p class="ml-2 mb-2"> <?php echo $persona['correo'] ?></p>
                    <?php } else { ?>
                        <p class="ml-2 mb-2 text-muted">No disponible</p>
                    <?php } ?>
                </div>
                <div class="ml-3 mt-4 pt-1 row align-items-center col-lg-12">
                    <h6>Estado: </h6>
                    <?php if ($persona['entregado']) { ?>
                        <p class="ml-2 mb-2" style="color: limegreen;"> Entregado</p>
                    <?php } else { ?>
                        <p class="ml-2 mb-2" style="color: red;">No Entregado</p>
                    <?php } ?>
                </div>

                <div class="text-center my-3">
                    <a class="btn btn-primary" href="actualizar.php?id=<?php echo $persona['id']; ?>">Modificar</a>                                        
                    <a class="btn btn-danger" href="index.php">Cancelar</a>
                </div>
            </div>


        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-center small">
                    <div class="text-muted text-center">Copyright &copy; Abel Consuegra 2020</div>

                </div>
            </div>
        </footer>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/eliminar.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

</body>

</html>