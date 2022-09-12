<?php include('./admin_template/header.php');?>


<div class="col-md-12">
    <div class="p-5 bg-light">
        <div class="container">
            <h1 class="display-3">Bienvenido/a <?php echo $nombreUsuario;?></h1>
            <p class="lead">Vamos a administrar nuestros proyectos personales</p>
            <hr class="my-2">

            <p class="lead">
                <a class="btn btn-danger btn-lg" href="./seccion/proyectos.php" role="button">Administrar tus proyectos</a>
            </p>
        </div>
    </div>
</div>


<?php include('./admin_template/footer.php');?>