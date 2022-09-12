<?php include('../admin_template/header.php');?>
<?php

$id=(isset($_POST["id"]))?$_POST['id']:"";
$titulo=(isset($_POST["titulo"]))? $_POST['titulo']: "";
$link=(isset($_POST["link"]))? $_POST['link']:"";
$desc=(isset($_POST["desc"]))? $_POST['desc']: "";
$img=(isset($_FILES["img"]["name"]))? $_FILES['img']["name"]: "";
$accion=(isset($_POST["accion"]))? $_POST['accion']: "";

// print_r($id);
// print_r($titulo);
// print_r($link);
// print_r($desc);
// print_r($img);
// print_r($accion);


include("../config/db.php");
switch ($accion) {
    case "add":
        $sentenciaSQL= $conexion->prepare("INSERT INTO proyectos (titulo, link,descripcion,img) VALUES (:titulo,:link, :descripcion, :img);");
        $sentenciaSQL->bindParam(':titulo',$titulo );
        $sentenciaSQL->bindParam(':link',$link );
        $sentenciaSQL->bindParam(':descripcion',$desc );
        
        //Subida de imagenes
        //Creo una fecha y un nombre de archivo para concatenar con el date
        $fecha= new DateTime();
        $nombreArchivo=($img!="")?$fecha->getTimestamp()."_".$_FILES["img"]["name"]:"img.jpg";
        $tempImg=$_FILES["img"]["tmp_name"];

        if($tempImg!=""){
            move_uploaded_file($tempImg, "../../img/upload_proyectos/".$nombreArchivo);
        }

        $sentenciaSQL->bindParam(':img',$nombreArchivo );
        $sentenciaSQL->execute(); 
        //header("Location: http://nvergara-portfolio.000webhostapp.com/sitioweb/admin/seccion/productos.php");
        // header("Location:proyectos.php");
        break;
        
    case "mod":
        $sentenciaSQL= $conexion->prepare(" UPDATE proyectos SET titulo=:titulo, link=:link, descripcion=:descripcion WHERE id=:id");
        $sentenciaSQL->bindParam(':titulo',$titulo );
        $sentenciaSQL->bindParam(':link',$link );
        $sentenciaSQL->bindParam(':descripcion',$desc );
        $sentenciaSQL->bindParam(':id',$id );
        $sentenciaSQL->execute();

        // cambiar la imagen
        // Creo una fecha y un nombre de archivo para concatenar con el date
        if($img!=""){
            $fecha= new DateTime();
            $nombreArchivo=($img!="")?$fecha->getTimestamp()."_".$_FILES["img"]["name"]:"img.jpg";
            $tempImg=$_FILES["img"]["tmp_name"];

            move_uploaded_file($tempImg, "../../img/upload_proyectos/".$nombreArchivo);
            $sentenciaSQL= $conexion->prepare(" SELECT img FROM proyectos  WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$id );
            $sentenciaSQL->execute();
            $proyecto = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if(isset($proyecto["img"]) && ($proyecto["img"]!= "img.jpg")){
                if(file_exists("../../img/upload_proyectos/".$proyecto["img"])){
                    unlink("../../img/upload_proyectos/".$proyecto["img"]);
                }
            }

            $sentenciaSQL= $conexion->prepare("UPDATE proyectos SET img=:img WHERE id =:id");
            $sentenciaSQL->bindParam(':img',  $nombreArchivo);
            $sentenciaSQL->bindParam(':id',$id );
            $sentenciaSQL->execute();
        }
       //header("Location: http://nvergara-portfolio.000webhostapp.com/sitioweb/admin/seccion/productos.php");
        // header("Location:proyectos.php");
        break;
    case "can":
        //header("Location: http://nvergara-portfolio.000webhostapp.com/sitioweb/admin/seccion/productos.php");
        // header("Location:proyectos.php");
        break;
    case "edit":
        $sentenciaSQL= $conexion->prepare("SELECT * FROM proyectos WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $id );
        $sentenciaSQL->execute();
        $proyecto = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        print_r($id);

        $id=$proyecto["id"];
        $titulo=$proyecto["titulo"];
        $link=$proyecto["link"];
        $desc=$proyecto["descripcion"];
        $img=$proyecto["img"];        
       
        break;
    case "borrar":
        $sentenciaSQL= $conexion->prepare("SELECT img FROM proyectos  WHERE id=:id");
        $sentenciaSQL->bindParam(':id',$id );
        $sentenciaSQL->execute();
        $proyecto = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if(isset($proyecto["img"]) && ($proyecto["img"]!= "img.jpg")){
            if(file_exists("../../img/upload_proyectos/".$proyecto["img"])){
                unlink("../../img/upload_proyectos/".$proyecto["img"]);
            }
        }
        $sentenciaSQL= $conexion->prepare("DELETE FROM proyectos WHERE id =:id");
        $sentenciaSQL->bindParam(':id',$id );
        $sentenciaSQL->execute();
        //header("Location: http://nvergara-portfolio.000webhostapp.com/sitioweb/admin/seccion/productos.php");
        // header("Location:proyectos.php");
        break;
}

$sentenciaSQL= $conexion->prepare("SELECT * FROM proyectos");
$sentenciaSQL->execute();   
$listaProyectos = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
//print_r($listaProyectos);



?>

<?php include('../admin_template/footer.php');?>


<div class="d-md-flex justify-content-center ">
    <div class="col-md-3 ">
        <div class="card">
            <div class="card-header">
                PROYECTOS
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">

                    <div class="ms-1">
                        <label for="id" class="form-label">ID</label>
                        <input type="text" name="id" id="id" class="form-control" value="<?php echo $id; ?>">

                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" name="titulo" id="titulo" class="form-control"
                            value="<?php echo $titulo; ?>">

                        <label for="link" class="form-label">Link</label>
                        <input type="text" name="link" id="link" class="form-control" value="<?php echo $link; ?>">

                        <label for="img" class="form-label">Imagen</label>
                        <input type="file" name="img" id="img" class="form-control">

                        <label for="desc" class="form-label">Descripción</label>
                        <textarea id="desc" name="desc" rows="4" cols="35" value=""><?php echo $desc; ?></textarea>


                        <div class="btn-group d-flex" role="group" aria-label="Button group name">
                            <button type="submit" name="accion" <?php echo ($accion == "edit"? "disabled":"")?>
                                value="add" class="btn btn-success">Agregar</button>
                            <button type="submit" name="accion" <?php echo ($accion != "edit"? "disabled":"")?>
                                value="mod" class="btn btn-warning">Modificar</button>
                            <button type="submit" name="accion" <?php echo ($accion != "edit"? "disabled":"")?>
                                value="can" class="btn btn-info">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="col-md-9 container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>TITULO</th>
                    <th>LINK</th>
                    <th>IMAGEN</th>
                    <th>DESCRIPCION</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listaProyectos as $proyecto){ ?>
                <tr>
                    <td><?php echo $proyecto['id'];  ?></td>
                    <td width="150"><?php echo $proyecto['titulo'];  ?></td>
                    <td width="150"><?php echo $proyecto['link'];  ?></td>

                    <td> <img src="../../img/upload_proyectos/<?php echo $proyecto['img'];?>" class="img-thumbnail"
                            width="100" alt="<?php echo $proyecto['img']; ?>">
                    </td>


                    <td width="250"><?php echo $proyecto['descripcion'];  ?></td>
                    <td width="150">
                        <form method="POST">
                            <input type="hidden" name="id" id="id" value="<?php echo $proyecto['id']; ?>" />
                            <input type="submit" name="accion" value="edit" class="btn btn-warning" />
                            <input type="submit" name="accion" value="borrar" class="btn btn-danger" />

                        </form>


                    </td>

                </tr>
                <?php } ?>


            </tbody>
        </table>

    </div>
</div>



<?php include('../admin_template/footer.php');?>