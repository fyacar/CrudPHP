<?php 
    require("config/conexion.php");
    require "controllers/Empleados.php";
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud con php y Mysql</title>
      
<link rel ="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/>


<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
</head>

<body>
    <br>
    <h1 style="text-align:center;">CRUD EMPLEADOS</h1>
    <img src="docs/imagenes/banner1.jpg" style="border-radius: 10px; height: 150px; width:700px ; margin-left: auto; margin-right: auto; display: block; border: solid black 3px;">
    <div class="container">
           <form action="" method="post" enctype="multipart/form-data">
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Empleado</h5>
                         <button type="button" onclic="btnCerrar();" class="close" data-dismiss="modal" aria-label="Close"> 
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="form-row">

                                <input type="hidden" required name="txtId" value="<?php echo $id ?>">

                                <div class="form-group col-md-4">
                                    <label for="">Nombre(s):</label>
                                    <input type="text" class="form-control  <?php echo(isset($error['Nombre']))?"is-invalid":""; ?>"  require name="txtNombre" placeholder="" id="txtNombre" require value="<?php echo $nombre ?>">
                                <div class="invalid-feedback">
                                <?php echo(isset($error['Nombre']))?$error['Nombre']:""; ?>
                                </div>  
                                    <br>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Apellido Paterno:</label>
                                    <input type="text" class="form-control <?php echo(isset($error['apellidoPaterno']))?"is-invalid":""; ?>" require  name="txtApellidoPaterno" placeholder="" id="txt1" require value="<?php echo $apellidoPaterno ?>">
                                <div class="invalid-feedback">
                                <?php echo(isset($error['apellidoPaterno']))?$error['apellidoPaterno']:""; ?>
                                </div>  
                                    <br>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Apellido Materno:</label>
                                    <input type="text" class="form-control <?php echo(isset($error['apellidoMaterno']))?"is-invalid":""; ?>"  required name="txtApellidoMaterno" placeholder="" id="txt1" require value="<?php echo $apellidoMaterno ?>">
                                    <div class="invalid-feedback">
                                <?php echo(isset($error['apellidoMaterno']))?$error['apellidoMaterno']:""; ?>
                                </div>  
                                    <br>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="">Correo:</label>
                                    <input type="email" class="form-control <?php echo(isset($error['correo']))?"is-invalid":""; ?>"  required name="txtCorreo" placeholder="" id="txt1" require value="<?php echo $correo ?>">
                                    <div class="invalid-feedback">
                                <?php echo(isset($error['correo']))?$error['correo']:""; ?>
                                </div>
                                    <br>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="">Imagen:</label>

                                    <?php if($imagen!=""){?> 
                                        <br>
                                        <img class="img-thumbnail mx-auto d-block" width="100px" height="50px" src="docs/imagenes/<?php echo $imagen; ?>"> 
                                        <br>
                                    <?php                                       
                                    }?>

                                    <input type="file" class="form-control" accept="image/*" name="txtImagen" placeholder="" id="txt1" value="<?php echo $imagen ?>">
                                    <br>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button value="btnAgregar" <?php echo$accionAgregar?> class="btn btn-success" type="submit" name="accion">Agregar</button>
                            <button value="btnModificar" <?php echo$accionModificar?> class="btn btn-warning" type="submit" name="accion">Modificar</button>
                            <button value="btnEliminar" <?php echo$accionEliminar?> class="btn btn-danger" type="submit" name="accion">Eliminar</button>
                            <button value="btnCancelar" <?php echo$accionCancelar?> onclick="return cerrarModal();" class="btn btn-primary" type="submit" name="accion">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!--trigger modal -->
            <br>
            <button type="button" onClick="limpiar();" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Agregar Empleado
            </button>
            <br>
            <br>

        </form>

        <div class="row">

            <table class="table table-hover table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <?php foreach ($listaEmpleados as $empleado) { ?>
                    <tr>
                        <td> <img class="img-thumbnail" width="100px" height="50px" src="docs/imagenes/<?php echo $empleado['emp_foto']; ?>"> </td>
                        <td><?php echo $empleado['emp_nombre'] . " " .  $empleado['emp_apellido_paterno'] . " " .  $empleado['emp_apellido_materno']; ?> </td>
                        <td><?php echo $empleado['emp_correo']; ?> </td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="txtId" value="<?php echo $empleado['emp_id'] ?>">
                                

                                <input type="submit" value="Seleccionar" name="accion" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">
                                <button value="btnEliminar" onclick="return confirmar('¿Desea Eliminar el regsitro?');" type="submit" name="accion" class="btn btn-danger">Eliminar</button>
                            </form>

                        </td>

                    <?php   } ?>
            </table>

        </div>

    </div>

    <?php if($mostrarModal){?>
   <script>       
        $('#exampleModal').modal('show');       
    </script>
<?php }?>

</body>

<script>
    function limpiar(){
    $('#exampleModal').on('show.bs.modal', function (event) {
    $("#exampleModal input").val("");
    $("#exampleModal textarea").val("");
    $("#exampleModal select").val("");
    $("#exampleModal input[type='checkbox']").prop('checked', false).change();
    $imagen="";
});
    }
   
    function confirmar(Mensaje){
        return (confirm(Mensaje))?true:false;
    }

    function cerrarModal(){
        $('#exampleModal').modal('hide');
        $imagen="";
    }


</script>
</html>