<?php

    $dns = 'mysql:host=localhost; dbname=EmpresaPHP; charset=utf8';
    $usr = 'root';
    $pwd = 'sql159456';

try{
    $pdo = new PDO($dns,$usr,$pwd);
    //echo "Conectado correctamente a la BD";
}
catch(PDOException $ex){
    echo "No se pudo realizar la conexión: ". $ex->getMessage();
}

?>




