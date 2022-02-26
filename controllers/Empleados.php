<?php


//capturamos los valores ingresados en el formulario
$id = (isset($_POST['txtId'])) ? $_POST['txtId'] : "";
$nombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
$apellidoPaterno = (isset($_POST['txtApellidoPaterno'])) ? $_POST['txtApellidoPaterno'] : "";
$apellidoMaterno = (isset($_POST['txtApellidoMaterno'])) ? $_POST['txtApellidoMaterno'] : "";
$correo = (isset($_POST['txtCorreo'])) ? $_POST['txtCorreo'] : "";
$imagen = (isset($_FILES['txtImagen']["name"])) ? $_FILES['txtImagen']["name"] : "";

//capturamos el botón que se presionó

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";


$error=array();


$accionAgregar="";
$accionCancelar="";
$accionModificar=$accionEliminar="disabled";
$mostrarModal=false;

switch ($accion) {
    case "btnAgregar":

        if($nombre==""){
            $error['Nombre'] = "Escribe el nombre";
        }
        if($apellidoPaterno==""){
            $error['apellidoPaterno'] = "Escribe el Apellido Paterno";
        }
        if($apellidoMaterno==""){
            $error['apellidoMaterno'] = "Escribe el Apellido Materno";
        }
        if($correo==""){
            $error['correo'] = "Escribe el Correo";
        }
        if(count($error)>0){
            $mostrarModal=true;
            break;
        }


        $sentencia = $pdo->prepare("insert into Empleados (emp_nombre,emp_apellido_paterno,emp_apellido_materno,emp_correo,emp_foto) values(:Nombre,:ApellidoP,:ApellidoM,:Correo,:Imagen)");


        $sentencia->bindParam(':Nombre', $nombre);
        $sentencia->bindParam(':ApellidoP', $apellidoPaterno);
        $sentencia->bindParam(':ApellidoM', $apellidoMaterno);
        $sentencia->bindParam(':Correo', $correo);

        $fecha = new DateTime();
        //capturamos el nombre de la imagen
        $nombreArchivo = ($imagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";
        // el nombre que php devuelve cuando el usuario selecciona la fotografía (capturamos el archivo)
        $tmpFoto = $_FILES["txtImagen"]["tmp_name"];

        if ($tmpFoto != "") {
            //movemos la imagen (el archivo) a la carpeta correspondiente con el nombre que creamos.            
            move_uploaded_file($tmpFoto, "docs/imagenes/" . $nombreArchivo);
        }

        $sentencia->bindParam(':Imagen', $nombreArchivo);
        $sentencia->execute();
        header('Location: index.php');;
        break;

    case "btnModificar":
        $sentencia = $pdo->prepare("update Empleados set emp_nombre = :Nombre,emp_apellido_paterno= :ApellidoP, emp_apellido_materno= :ApellidoM, emp_correo = :Correo where emp_id = :Id");
        $sentencia->bindParam(':Id', $id);
        $sentencia->bindParam(':Nombre', $nombre);
        $sentencia->bindParam(':ApellidoP', $apellidoPaterno);
        $sentencia->bindParam(':ApellidoM', $apellidoMaterno);
        $sentencia->bindParam(':Correo', $correo);
        $sentencia->execute();

        $fecha = new DateTime();
        //capturamos el nombre de la imagen
        $nombreArchivo = ($imagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";
        // el nombre que php devuelve cuando el usuario selecciona la fotografía (capturamos el archivo)
        $tmpFoto = $_FILES["txtImagen"]["tmp_name"];

        if ($tmpFoto != "") {
            //movemos la imagen (el archivo) a la carpeta correspondiente con el nombre que creamos.            
            move_uploaded_file($tmpFoto, "docs/imagenes/" . $nombreArchivo);

            $sentencia = $pdo->prepare("Select emp_foto from Empleados where emp_id = :Id");
            $sentencia->bindParam(':Id', $id);
            $sentencia->execute();
            $fotoEmpleado = $sentencia->fetch(PDO::FETCH_LAZY);

            if (isset($fotoEmpleado["emp_foto"]) && $fotoEmpleado["emp_foto"] != "imagen.jpg") {
                if (FILE_EXISTS("docs/imagenes/" . $fotoEmpleado["emp_foto"])) {
                    unlink("docs/imagenes/" . $fotoEmpleado["emp_foto"]);
                }
            }

            $sentencia2 = $pdo->prepare("update Empleados set emp_foto = :Imagen where emp_id=:Id");
            $sentencia2->bindParam(':Id', $id);
            $sentencia2->bindParam(':Imagen', $nombreArchivo);
            $sentencia2->execute();
        }

        
        
        header('Location: index.php');
        break;

    case "btnEliminar":

        $sentencia = $pdo->prepare("Select emp_foto from Empleados where emp_id = :Id");
        $sentencia->bindParam(':Id', $id);
        $sentencia->execute();
        $fotoEmpleado = $sentencia->fetch(PDO::FETCH_LAZY);

        if (isset($fotoEmpleado["emp_foto"]) && $fotoEmpleado["emp_foto"] != "imagen.jpg") {
            if (FILE_EXISTS("docs/imagenes/" . $fotoEmpleado["emp_foto"])) {
                unlink("docs/imagenes/" . $fotoEmpleado["emp_foto"]);
            }
        }

        $sentencia = $pdo->prepare("Delete from Empleados where emp_id = :Id");
        $sentencia->bindParam(':Id', $id);
        $sentencia->execute();


        header('Location: index.php');
        break;

    case "Seleccionar":
        $accionAgregar="disabled";
        $accionModificar=$accionEliminar=$accionCancelar="";
        $mostrarModal = true;

        $sentencia = $pdo->prepare("Select * from Empleados where emp_id = :Id");
        $sentencia->bindParam(':Id', $id);
        $sentencia->execute();
        $Empleado = $sentencia->fetch(PDO::FETCH_LAZY);
        
        $nombre = $Empleado["emp_nombre"];
        $apellidoPaterno = $Empleado["emp_apellido_paterno"];
        $apellidoMaterno = $Empleado["emp_apellido_materno"];
        $correo = $Empleado["emp_correo"];
        $imagen = $Empleado["emp_foto"];

        break;

    case "btnCancelar":
        header('Location: index.php');
        break;

  
}

$sentencia = $pdo->prepare("select *  from Empleados");
$sentencia->execute();
$listaEmpleados = $sentencia->fetchAll(PDO::FETCH_ASSOC);


?>
