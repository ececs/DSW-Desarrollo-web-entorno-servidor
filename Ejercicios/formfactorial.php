<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = isset($_POST['numero']) ? (int)$_POST['numero'] : 0;
    $factorial = 1;

    if ($numero >= 0) {
        for ($i = 1; $i <= $numero; $i++) {
            $factorial *= $i;
        }
        echo "El factorial de $numero es $factorial";
    } else {
        echo "El factorial solo está definido para números enteros mayores o iguales a cero.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcular Factorial</title>
</head>
<body>
    <form method="POST" action="">
        <label for="numero">Introduce un número:</label>
        <input type="number" id="numero" name="numero" required>
        <button type="submit">Calcular Factorial</button>
    </form>
</body>
</html>