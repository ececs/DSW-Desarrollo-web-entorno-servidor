<?php
session_start();

$msg = "";

// comprobar si se quiere borrar
if (isset($_GET['borrar']) && $_GET['borrar'] == '1') {
    
    if (isset($_SESSION['idioma'])) {
        // eliminar sesión
        session_unset();
        session_destroy();
        
        $msg = "Preferencias Borradas.";
        
    } else {
        $msg = "Debes fijar primero las preferencias.";
    }
}

// obtener valores de sesión
if (isset($_SESSION['idioma'])) {
    $idioma = $_SESSION['idioma'];
} else {
    $idioma = 'No has elegido idioma';
}

if (isset($_SESSION['perfil_publico'])) {
    $perfil = $_SESSION['perfil_publico'];
} else {
    $perfil = 'No has elegido perfil';
}

if (isset($_SESSION['zona_horaria'])) {
    $zona = $_SESSION['zona_horaria'];
} else {
    $zona = 'No has elegido zona horaria';
}

// contador de cambios desde cookie
if (isset($_COOKIE['pref_changes'])) {
    $contador = $_COOKIE['pref_changes'];
} else {
    $contador = 0;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Preferencias</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h1><i class="fas fa-list-check"></i> Tus Preferencias Guardadas</h1>
            </div>
            <div class="card-body">
                <?php 
                    // mostrar mensaje si hay
                    if ($msg != "") {
                        echo "<div class='alert alert-info'>$msg</div>";
                    }
                ?>

                <p><strong>Idioma:</strong> <?php echo $idioma; ?></p>
                <p><strong>Perfil Público:</strong> <?php echo $perfil; ?></p>
                <p><strong>Zona Horaria:</strong> <?php echo $zona; ?></p>

                <hr>

                <div class="alert alert-light">
                    <p class="mb-0">Has cambiado tus preferencias <strong><?php echo $contador; ?></strong> veces.</p>
                </div>

            </div>
            <div class="card-footer">
                <a href="mostrar.php?borrar=1" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Borrar
                </a>
                <a href="preferencias.php" class="btn btn-success">
                    <i class="fas fa-pencil-alt"></i> Establecer
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>