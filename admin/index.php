<?php
session_start();

if(isset($_SESSION['usuario'])){            
     header('Location:inicio.php');
}else{
    if($_POST){
        //traigo los valores de los inputs del login
        $email=(isset($_POST['email']))?$_POST['email']:"";
        $contrasena=(isset($_POST['contrasena']))?$_POST['contrasena']:"";
    
        //traigo la conexion a la db
        include("./config/db.php");
        //hago la consulta
        $sentenciaSQL= $conexion->prepare("SELECT email, contrasena, nombre FROM usuarios WHERE email =:email");
        $sentenciaSQL->bindParam(':email', $email);
        $sentenciaSQL->execute();
        $user=$sentenciaSQL->fetch(PDO::FETCH_LAZY);
    
        //  print_r($user);   
    
        if(isset($user['email']) && ($contrasena == $user['contrasena'])){
           $_SESSION['usuario']="ok";
            $_SESSION['nombreUsuario']=$user['nombre'];        
            header('Location:inicio.php');
        }else{
            $mensaje="Error usuario y/o contraseña son incorrectos";
        }   
    }
    
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>

<body>

    <div class="container">
        <div class="row">

            <div class="col-md-4"> </div>
            <div class="col-md-4 mt-5">
                <div class="card">
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body">

                        <?php if(isset($mensaje)){ ?>

                        <div class="alert alert-danger" role="alert">
                            <?php echo $mensaje;  ?>
                        </div>

                        <?php } ?>

                        <form method="post" action="">
                            <fieldset>
                                <div class="form-group">
                                    <label class="form-label mt-4">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label mt-4">Contraseña</label>
                                    <input type="password" name="contrasena" class="form-control"
                                        placeholder="Contraseña" required>
                                </div>

                                <button type="submit" class="btn btn-primary mt-5">Ingresar</button>
                            </fieldset>
                        </form>


                    </div>

                </div>
            </div>

        </div>
    </div>

</body>

</html>