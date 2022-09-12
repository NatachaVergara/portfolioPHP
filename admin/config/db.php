<?php
$host="localhost";
$db="sitio";
$usuario="root";
$contrasenia="";


// $host="localhost";
// $db="id19499153_sitio";
// $usuario="id19499153_portfolio";
// $contrasenia="1-h*5L^I!k*DoKzq";


try {
   $conexion=new PDO("mysql:host=$host;dbname=$db", $usuario, $contrasenia);   
    // if($conexion){echo "Conectado ... a sistema";}
} catch (Exception $ex) {
    echo $ex->getMessage();
    
}
?>