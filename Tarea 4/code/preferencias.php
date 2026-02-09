<?php
// inicio sesión
session_start();

// cookie para contar cambios
if (!isset($_COOKIE['pref_changes'])) {
    setcookie('pref_changes', 0, time() + (86400 * 30), "/"); 
}

$msg = "";

// procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // guardar datos en sesión
    $_SESSION['idioma'] = $_POST['idioma'];
    $_SESSION['perfil_publico'] = $_POST['perfil_publico'];
    $_SESSION['zona_horaria'] = $_POST['zona_horaria'];

    // incrementar cookie
    $cont = $_COOKIE['pref_changes'];
    $cont++;
    setcookie('pref_changes', $cont, time() + (86400 * 30), "/");

    $msg = "Preferencia de usuario guardadas";
}

// recuperar valores guardados o poner defaults
if(isset($_SESSION['idioma'])){
    $idioma_guardado = $_SESSION['idioma'];
} else {
    $idioma_guardado = 'espanol';
}

if(isset($_SESSION['perfil_publico'])){
    $perfil_guardado = $_SESSION['perfil_publico'];
} else {
    $perfil_guardado = 'si';
}

if(isset($_SESSION['zona_horaria'])){
    $zona_guardada = $_SESSION['zona_horaria'];
} else {
    $zona_guardada = 'GMT';
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preferencias de Usuario</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h1><i class="fas fa-cog"></i> Preferencias de Usuario</h1>
            </div>
            <div class="card-body">
                <?php 
                    // mostrar mensaje si existe
                    if ($msg != ""){
                        echo "<div class='alert alert-success'>$msg</div>";
                    }
                ?>

                <form method="POST" action="preferencias.php">
                    <div class="mb-3">
                        <label for="idioma" class="form-label">Idioma</label>
                        <select name="idioma" id="idioma" class="form-select">
                            <option value="espanol" <?php if($idioma_guardado == 'espanol') echo 'selected'; ?>>Español</option>
                            <option value="ingles" <?php if($idioma_guardado == 'ingles') echo 'selected'; ?>>Inglés</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="perfil_publico" class="form-label">Perfil Público</label>
                        <select name="perfil_publico" id="perfil_publico" class="form-select">
                            <option value="si" <?php if($perfil_guardado == 'si') echo 'selected'; ?>>Sí</option>
                            <option value="no" <?php if($perfil_guardado == 'no') echo 'selected'; ?>>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="zona_horaria" class="form-label">Zona Horaria</label>
                        <select name="zona_horaria" id="zona_horaria" class="form-select">
                            <option value="GMT-2" <?php if($zona_guardada == 'GMT-2') echo 'selected'; ?>>GMT-2</option>
                            <option value="GMT-1" <?php if($zona_guardada == 'GMT-1') echo 'selected'; ?>>GMT-1</option>
                            <option value="GMT" <?php if($zona_guardada == 'GMT') echo 'selected'; ?>>GMT</option>
                            <option value="GMT+1" <?php if($zona_guardada == 'GMT+1') echo 'selected'; ?>>GMT+1</option>
                            <option value="GMT+2" <?php if($zona_guardada == 'GMT+2') echo 'selected'; ?>>GMT+2</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Establecer preferencias
                    </button>
                    <a href="mostrar.php" class="btn btn-secondary">
                        <i class="fas fa-eye"></i> Mostrar preferencias
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>