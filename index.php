<?php
// Por la paginación, detectará si el sitio trae un parámetro get relacionado con la página, de lo contrario lo redirige a la página 1
if (!$_GET) {
    header('location: index.php?pagina=1');
}

// Defino los campos html a utilizar en la programación
$txtId = (isset($_POST['txtId'])) ? $_POST['txtId'] : "";
$txtIdentidad = (isset($_POST['txtIdentidad'])) ? $_POST['txtIdentidad'] : "";
$txtNombres = (isset($_POST['txtNombres'])) ? $_POST['txtNombres'] : "";
$txtApellidos = (isset($_POST['txtApellidos'])) ? $_POST['txtApellidos'] : "";
$txtDireccion = (isset($_POST['txtDireccion'])) ? $_POST['txtDireccion'] : "";
$txtTelefono = (isset($_POST['txtTelefono'])) ? $_POST['txtTelefono'] : "";
// Detecta la acción del botón a clickear
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

// Crear la conexion con la base de datos
include("conexion/conexion.php");
// Variable contador para mostrar el número en la tabla html y siempre sea exacto (sin saltos al eliminar algún campo de la BD)
$c = 0;

// Sentencia de consulta para seleccionar los hoteles
$sentencia = $pdo->prepare("SELECT * FROM persona");
// Ejecuta la sentencia
$sentencia->execute();
// Almacena la información en la lista de hoteles. Fetch_Assoc es la que devuelve la información de la BD
$listaPersonas = $sentencia->fetchAll(PDO::FETCH_ASSOC);


// Para hacer la paginación se necesita calcular cuantos registros por página
$registros_por_pagina = 10;

// Contar las filas de la base de datos
$total_registros = $sentencia->rowCount();
$paginas = $total_registros / $registros_por_pagina;
// Ceil sirve para convertir un float en un entero 
$paginas = ceil($paginas);

switch ($accion) {
    case "btnEstado":

        // Comprueba si el establecimiento está activo o no para luego realizar la actualizacion de estado
        $comprobarActivo = $pdo->prepare("SELECT * FROM persona WHERE id=:id");
        $comprobarActivo->bindParam(':id', $txtId);
        $comprobarActivo->execute();
        $persona = $comprobarActivo->fetch(PDO::FETCH_ASSOC);

        $elementoActivo = $persona['entregado'];

        // Si el establecimiento está inactivo entonces se actualizará como habilitado(1)
        if ($elementoActivo == 0) {
            $sentencia = $pdo->prepare("UPDATE persona SET entregado=1 WHERE id=:id");

            // bindParam será para asignar los valores referenciados anteriormente
            $sentencia->bindParam(':id', $txtId);

            // Ejecutar la instrucción de la sentencia
            $sentencia->execute();
        } else {
            // Caso contrario, si está activo cambiará su estado a inactivo(0)
            $sentencia = $pdo->prepare("UPDATE persona SET entregado=0 WHERE id=:id");

            // bindParam será para asignar los valores referenciados anteriormente
            $sentencia->bindParam(':id', $txtId);

            // Ejecutar la instrucción de la sentencia
            $sentencia->execute();
        }

        header("index.php");
        break;
}

// $eliminar = $_GET['del'] ?? '';
$eliminar = (isset($_GET['del'])) ? $_GET['del'] : "";

if ($eliminar) {
    $id = $_GET['del'];
    // Creando la sentencia SQL para eliminar los valores en la BD
    // Utilizo pdo para preparar la sentencia
    $sentencia = $pdo->prepare("DELETE FROM persona WHERE id=:id");

    // bindParam será para asignar los valores referenciados anteriormente        
    $sentencia->bindParam(':id', $id);

    // Ejecutar la instrucción de la sentencia
    $sentencia->execute();

    // Si la sentencia se ejecutó con éxito mostrará un mensaje y redirigirá a la página de inicio
    if ($sentencia) {
        echo '<script language="javascript">alert("Registro eliminado correctamente");window.location.href="index.php"</script>';
    }    
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
    <title>Bolsas Solidarias</title>
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
        <a class="fecha ml-2" style="text-decoration: none;" href="manual_de_usuario.pdf" target="_blank"><i class="fas fa-question-circle"></i> Ayuda</a>
    </nav>
    <div>
        <main>
            <div class="container container-index mt-1 pt-3 px-2">

                <h3 class="text-center mt-4 mb-3">REGISTRO Y CONTROL DE BOLSAS SOLIDARIAS</h3>

                <div class="row ">
                    <a class="btn btn-success ml-2" href="agregar.php"><i class="fas fa-plus"></i> Agregar registro</a>
                    <div class="row align-items-center ml-auto mr-2" style="display: flex; justify-content: flex-end;">
                        <input data-table="order-table" id="buscar" class="form-control form-control-sm mr-1 w-75 light-table-filter" type="text" placeholder="Buscar" aria-label="Search">
                        <button id="btnBuscar" class="btn"><i class="fas fa-search" aria-hidden="true"></i></button>
                    </div>

                </div>

                <div class="row mt-3 ">
                    <table class="table order-table">
                        <thead class="thead bg-#012a36" style="background-color: #012a36; color:white;">
                            <tr>
                                <th class="text-center">No.</th>
                                <th style="width: 15%;" class="text-center">N. Identidad</th>
                                <th style="width: 20%;" class="text-center">Nombre</th>
                                <th style="width: 35%;" class="text-center">Dirección</th>
                                <th style="width: 10%;" class="text-center">Telefono</th>
                                <th style="width: 10%;" class="text-center">Estado</th>
                                <th style="width: 20%;" class="text-center">Acciones</th>
                            </tr>
                        </thead>

                        <?php
                        if (!$_GET) {
                            header('location: index.php?pagina=1');
                        }
                        if ($_GET['pagina'] > $registros_por_pagina) {
                            header('location: index.php?pagina=1');
                        }

                        // Calculamos el registro con el que iniciará a mostrarse la página
                        $iniciar = ($_GET['pagina'] - 1) * $registros_por_pagina;

                        // Hacemos la consulta de la información de la persona con el límite a calcular desde el registro de inicio y la cantidad de registros a consultar
                        $sentencia_person = $pdo->prepare("SELECT * FROM persona LIMIT :iniciar,:nregistros");

                        $sentencia_person->bindParam(':iniciar', $iniciar, PDO::PARAM_INT);
                        $sentencia_person->bindParam(':nregistros', $registros_por_pagina, PDO::PARAM_INT);
                        // Ejecuta la sentencia
                        $sentencia_person->execute();
                        // Almacena la información en la variable inventario. Fetch_Assoc es la que devuelve la información de la BD
                        $lista_personas_paginado = $sentencia_person->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                        <?php foreach ($lista_personas_paginado as $persona) { 
                            // Mostraremos en la tabla la información de los registros de la base de datos en un rango de 10 por página
                            $c = $c + 1;
                            if ($persona['entregado']) { ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $c; ?></td>
                                    <td class="text-center align-middle"> <a class="text-decoration-none text-dark" title="Ver información" href="ver_registro.php?id=<?php echo $persona['id']; ?>"><?php echo $persona['identidad']; ?></td>
                                    <td class="text-center align-middle"> <a class="text-decoration-none text-dark" title="Ver información" href="ver_registro.php?id=<?php echo $persona['id']; ?>"><?php echo $persona['nombres']; ?> <?php echo $persona['apellidos']; ?></a></td>
                                    <td class="text-center align-middle"><?php echo $persona['direccion']; ?></td>
                                    <td class="text-center align-middle"><?php echo $persona['telefono']; ?></td>
                                    <td class="text-center align-middle" style="color:limegreen;">Entregado</td>
                                    <td class="text-center align-middle">

                                        <form action="" method="post">
                                            <input type="hidden" name="txtId" value="<?php echo $persona['id']; ?>">
                                            <input type="hidden" name="txtIdentidad" value="<?php echo $persona['identidad']; ?>">
                                            <input type="hidden" name="txtNombre" value="<?php echo $persona['nombres']; ?>">
                                            <input type="hidden" name="txtDireccion" value="<?php echo $persona['direccion']; ?>">
                                            <input type="hidden" name="txtTelefono" value="<?php echo $persona['telefono']; ?>">

                                            <a style="font-size: 13px;" class="btn btn-primary text-center m-1" title="Ver información" href="ver_registro.php?id=<?php echo $persona['id']; ?>"><i class="fas fa-eye"></i></a>
                                            <button style="font-size: 13px;" class="btn btn-success m-1" value="btnEstado" title="Quitar entregado" type="submit" name="accion"><i class="fas fa-check-circle"></i></i></button>

                                        </form>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo $c; ?></td>
                                    <td class="text-center align-middle"> <a class="text-decoration-none text-dark" title="Ver información" href="ver_registro.php?id=<?php echo $persona['id']; ?>"> <?php echo $persona['identidad']; ?></td>
                                    <td class="text-center align-middle" style="width: 20%;"> <a class="text-decoration-none text-dark" title="Ver información" href="ver_registro.php?id=<?php echo $persona['id']; ?>"><?php echo $persona['nombres']; ?> <?php echo $persona['apellidos']; ?></a></td>
                                    <td class="text-center align-middle"><?php echo $persona['direccion']; ?></td>
                                    <td class="text-center align-middle"><?php echo $persona['telefono']; ?></td>
                                    <td class="text-center align-middle" style="color: red;">No Entregado</td>
                                    <td class="text-center align-middle">

                                        <form action="" method="post">
                                            <input type="hidden" name="txtId" value="<?php echo $persona['id']; ?>">
                                            <input type="hidden" name="txtNombre" value="<?php echo $persona['nombre']; ?>">
                                            <input type="hidden" name="txtDireccion" value="<?php echo $persona['direccion']; ?>">
                                            <input type="hidden" name="txtTelefono" value="<?php echo $persona['telefono']; ?>">

                                            <a style="font-size: 13px;" class="btn btn-primary text-center m-1" title="Ver información" href="ver_registro.php?id=<?php echo $persona['id']; ?>"><i class="fas fa-eye"></i></a>
                                            <button style="font-size: 13px;" class="btn btn-danger m-1" value="btnEstado" title="Establecer entregado" type="submit" name="accion"><i class="fas fa-times-circle"></i></i></button>

                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>

                        <?php } ?>
                    </table>
                </div>
                <div class="mt-3" style="display: flex; justify-content:center;">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item <?php echo $_GET['pagina'] <= 1 ? 'disabled' : ''; ?> "><a class="page-link" href="<?php echo 'index.php?pagina=' . $_GET['pagina'] - 1; ?>">Anterior</a></li>

                            <?php for ($i = 0; $i < $paginas; $i++) : ?>

                                <li class="page-item <?php echo $_GET['pagina'] == $i + 1 ? 'active' : ''; ?>"><a class="page-link" href="index.php?pagina=<?php echo $i + 1; ?>">
                                        <?php echo $i + 1; ?>
                                    </a></li>


                            <?php endfor ?>

                            <li class="page-item <?php echo $_GET['pagina'] >= $paginas ? 'disabled' : ''; ?>" "><a class=" page-link" href="<?php echo 'index.php?pagina=' . $_GET['pagina'] + 1; ?>">Siguiente</a></li>
                        </ul>
                    </nav>
                </div>
            </div>


        </main>
        <!-- <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-center small">
                    <div class="text-muted text-center">Copyright &copy; Abel Consuegra 2020</div>

                </div>
            </div>
        </footer> -->
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/eliminar.js" type="text/javascript"></script>
    <script src="js/buscar.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

</body>

</html>