<?php include('../admin_template/header.php');?>

<?php
$txtID=(isset($_POST['txtID']))? $_POST['txtID'] : '';
$txtNombre=(isset($_POST['txtNombre']))? $_POST['txtNombre'] : '';
$txtImagen=(isset($_FILES['txtImagen']['name']))? $_FILES['txtImagen']['name'] : '';
$accion=(isset($_POST['accion']))? $_POST['accion'] : '';

include("../config/db.php");


switch($accion){
    case "agregar":       
        $sentenciaSQL= $conexion->prepare("INSERT INTO libros (nombre, imagen) VALUES (:nombre,:imagen);");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);

        //Subida de la imagen
        $fecha= new DateTime();
        $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

        $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

        if($tmpImagen!=""){
            move_uploaded_file($tmpImagen,"../../img/upload/".$nombreArchivo);
        }

        $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
        $sentenciaSQL->execute();   
        header("Location:productos.php");     
        break;
    case "modificar":
        // echo $txtNombre;
        // echo $txtID;
        $sentenciaSQL= $conexion->prepare("UPDATE libros SET nombre=:nombre WHERE id =:id");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

        if($txtImagen != ""){
            $fecha= new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";    
            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            move_uploaded_file($tmpImagen,"../../img/upload/".$nombreArchivo);
            $sentenciaSQL= $conexion->prepare("SELECT imagen FROM libros WHERE id = :id");            
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
            $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if(isset($libro["imagen"]) && ($libro["imagen"]!= "imagen.jpg") ){
                if(file_exists("../../img/upload/".$libro["imagen"])){
                    unlink("../../img/upload/".$libro["imagen"]);
                }
            }

            $sentenciaSQL= $conexion->prepare("UPDATE libros SET imagen=:imagen WHERE id =:id");
            $sentenciaSQL->bindParam(':imagen',  $nombreArchivo);
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();

            }
            //Permite que el sitio se refresque y habilite nuevamente los btns
            header("Location:productos.php");
      
            
        break;
    case "cancelar":
        //Permite que el sitio se refresque y habilite nuevamente los btns
        header("Location:productos.php");
        break;   
    case "seleccionar":
        $sentenciaSQL= $conexion->prepare("SELECT * FROM libros WHERE id = :id ");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
        $txtNombre=$libro['nombre'];
        $txtImagen=$libro['imagen'];

        // echo "Presiono el btn seleccionar";
        break;   
    case "borrar":       
        $sentenciaSQL= $conexion->prepare("SELECT imagen FROM libros WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if(isset($libro["imagen"]) && ($libro["imagen"]!= "imagen.jpg") ){
            if(file_exists("../../img/upload/".$libro["imagen"])){
                unlink("../../img/upload/".$libro["imagen"]);
            }
        }


        $sentenciaSQL= $conexion->prepare("DELETE FROM libros WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute(); 
        //Permite que el sitio se refresque y habilite nuevamente los btns
        header("Location:productos.php");
        break;   
}



$sentenciaSQL= $conexion->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();   
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);



?>





<div class="col-md-5">
    <div class="card">
        <div class="card-header">
            Datos de los libros
        </div>
        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label for="txtID">ID:</label>
                    <input type="text" class="form-control" name="txtID" id="txtID" placeholder="ID"
                        value="<?php echo $txtID; ?>" required readonly>
                </div>
                <div class="form-group mb-3">
                    <label for="txtNombre">Libro:</label>
                    <input type="text" class="form-control" name="txtNombre" id="txtNombre"
                        placeholder="Nombre del libro" value="<?php echo $txtNombre; ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="txtImagen">Imagen:</label>
                    <input type="file" class="form-control" name="txtImagen" id="txtImagen"
                        value="<?php echo $txtImagen; ?>">
                </div>



                <div class="d-flex justify-content-center align-items-center mt-5" role="group" aria-label="">
                    <button type="submit" name="accion" value="agregar"
                        <?php echo ($accion == "seleccionar")? "disabled":""?>
                        class="btn btn-success m-2">Agregar</button>

                    <button type="submit" name="accion" value="modificar"
                        <?php echo ($accion != "seleccionar")? "disabled":""?>
                        class="btn btn-warning m-2">Modificar</button>

                    <button type="submit" name="accion" value="cancelar"
                        <?php echo ($accion != "seleccionar")? "disabled":""?>
                        class="btn btn-info m-2">Cancelar</button>
                </div>

            </form>
        </div>

    </div>


</div>
<div class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($listaLibros as $libro){ ?>
            <tr>
                <td><?php echo $libro['id'];  ?></td>
                <td><?php echo $libro['nombre'];  ?></td>
                <td> <img src="../../img/upload/<?php echo $libro['imagen']; ?>" class="img-thumbnail" width="50"
                        alt="<?php echo $libro['imagen']; ?>"> <?php echo $libro['imagen']; ?>
                </td>

                <td>
                    <form method="post">
                        <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id']; ?>">
                        <input type="submit" name="accion" value="seleccionar" class="btn btn-primary" />
                        <input type="submit" name="accion" value="borrar" class="btn btn-danger" />
                    </form>

                </td>

            </tr>
            <?php }?>


        </tbody>
    </table>







</div>




<?php include('../admin_template/footer.php');?>