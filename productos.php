<?php include('template/header.php');?>

<?php
include("./admin/config/db.php");

$sentenciaSQL= $conexion->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();   
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>




<?php
foreach($listaLibros as $libro){?>

<div class="card m-3 p-3" style="max-width: 340px;">
    <div class="row g-0">
        <div class="col-md-4">
            <img src='./img/upload/<?php echo $libro['imagen']; ?>' class="img-fluid rounded-start"
                style="max-width: 70px;" alt="<?php echo $libro['imagen']; ?>">
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title"><?php echo $libro['nombre'];?> </h5>
                <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional
                    content. This content is a little bit longer.</p>
            </div>
        </div>
    </div>
</div>



<?php } ?>










<?php include('template/footer.php');?>