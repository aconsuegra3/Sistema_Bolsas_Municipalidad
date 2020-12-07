<?php

// Crear la conexion con la base de datos
include("conexion/conexion.php");

// $id = $_GET['id'] ?? '';
$id = (isset($_GET['id'])) ? $_GET['id'] : "";

$consultaPersona = $pdo->prepare("SELECT * FROM persona WHERE id=:id");
$consultaPersona->bindParam(':id', $id);
$consultaPersona->execute();

$persona = $consultaPersona->fetch(PDO::FETCH_ASSOC);


// Defino los campos enviados desde el post para poder utilizarlos en la programación
// $txtId=(isset($_POST['txtId']))?$_POST['txtId']:"";
$txtIdentidad = (isset($_POST['txtIdentidad'])) ? $_POST['txtIdentidad'] : "";
$txtNombres = (isset($_POST['txtNombres'])) ? $_POST['txtNombres'] : "";
$txtApellidos = (isset($_POST['txtApellidos'])) ? $_POST['txtApellidos'] : "";
$txtDireccion = (isset($_POST['txtDireccion'])) ? $_POST['txtDireccion'] : "";
$txtTelefono = (isset($_POST['txtTelefono'])) ? $_POST['txtTelefono'] : "";
$txtCorreo = (isset($_POST['txtCorreo'])) ? $_POST['txtCorreo'] : "";
$txtObservaciones = (isset($_POST['txtObservaciones'])) ? $_POST['txtObservaciones'] : "";

// Detecta la acción del botón a clickear
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

// Verificar qué botón presionó el usuario
switch ($accion) {
    case "btnModificar":
        $sentencia = $pdo->prepare("UPDATE persona SET identidad=:identidad, nombres=:nombres, apellidos=:apellidos, direccion=:direccion, telefono=:telefono, correo=:correo, observaciones=:observaciones WHERE id=:id");

        $sentencia->bindParam(':identidad', $txtIdentidad);
        $sentencia->bindParam(':nombres', $txtNombres);
        $sentencia->bindParam(':apellidos', $txtApellidos);
        $sentencia->bindParam(':direccion', $txtDireccion);
        $sentencia->bindParam(':telefono', $txtTelefono);
        $sentencia->bindParam(':correo', $txtCorreo);    
        $sentencia->bindParam(':observaciones', $txtObservaciones);

        $sentencia->bindParam(':id', $id);
        // Ejecutar la instrucción de la sentencia
        $sentencia->execute();


        if ($sentencia) {
            echo '<script language="javascript">alert("Registro actualizado correctamente");window.location.href="index.php"</script>';
        }
        break;
    case "btnCancelar":        
        break;
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistema para registrar los lugares turisticos de Siguatepeque" />
    <meta name="author" content="Abel Consuegra" />
    <title>Actualizar alojamiento</title>
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

        <!-- <a class="ml-2" href="index.php"></a> -->
        <a href="index.php"> <img class="ml-2" src="img/logo_muni.png" width="50px" alt=""></a>

        <!-- <a class="m-auto" href="index.php"><img src="img/logo_muni.png" width="40px" alt=""></a> -->
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
            <div class="container mt-1 pt-3 px-2">

                <form class="form-group" action="" method="post" enctype="multipart/form-data">

                    <h3 class="mt-4 mb-4 text-center text-uppercase">Actualizar información de <?php echo $persona['nombres'] ?> <?php echo $persona['apellidos'] ?></h3>
                    <div class="row mt-4">
                        <div class="col-lg-4">
                            <label for="">Identidad: </label>
                            <input class="form-control " maxlength="15" type="text" name="txtIdentidad" value="<?php echo $persona['identidad'] ?>" placeholder="" id="txtIdentidad" require="" required>
                        </div>

                        <div class="col-lg-4">
                            <label for="">Nombres: </label>
                            <input class="form-control " maxlength="255" type="text" name="txtNombres" value="<?php echo $persona['nombres'] ?>" placeholder="" id="txtNombres" require="" required>
                        </div>
                        <br>
                        <div class="col-lg-4">
                            <label for="">Apellidos: </label>
                            <input class="form-control " maxlength="255" type="text" name="txtApellidos" value="<?php echo $persona['apellidos'] ?>" placeholder="" id="txtApellidos" require="" required>
                        </div>
                        <br>


                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-8">
                            <label for="">Dirección: </label>
                            <input class="form-control" maxlength="1000" type="text" name="txtDireccion" value="<?php echo $persona['direccion'] ?>" placeholder="" id="txtDireccion" require="" required>
                            <br>
                        </div>
                        <div class="col-lg-4">
                            <label for="">Teléfono: </label>
                            <input class="form-control" maxlength="15" type="text" name="txtTelefono" value="<?php echo $persona['telefono'] ?>" placeholder="" id="txtTelefono" require="" required>
                            <br>
                        </div>


                    </div>


                    <div class="row">
                        <div class="col-lg-4">
                            <?php if ($persona['correo']) { ?>
                                <label for="">Correo electrónico: </label>
                                <input class="form-control" maxlength="100" type="text" name="txtCorreo" value="<?php echo $persona['correo'] ?>" placeholder="" id="txtCorreo" require="">
                            <?php } else { ?>
                                <label for="">Correo electrónico: </label>
                                <input class="form-control" maxlength="100" type="text" name="txtCorreo" value="" placeholder="" id="txtCorreo" require="">
                            <?php } ?>

                        </div>
                        <div class="col-lg-8">
                            <label for="">Observaciones: </label>
                            <input class="form-control" maxlength="255" type="text" name="txtObservaciones" value="<?php echo $persona['observaciones'] ?>" placeholder="" id="txtObservaciones" require="">
                            <br>
                        </div>


                    </div>

                    <br>


                    <div class="text-center mt-3">
                        <button class="btn btn-success" value="btnModificar" type="submit" name="accion">Guardar cambios</button>
                        <a href="ver_registro.php?id=<?php echo $persona['id']; ?>" class="btn btn-danger" value="btnCancelar" type="submit" name="accion">Cancelar</a><br>
                    </div>
                </form>
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
    <script src="js/actualizar.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

</body>

</html>