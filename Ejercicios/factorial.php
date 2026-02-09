
<?php
$numero = 5; // Cambia este valor para calcular el factorial de otro número
$factorial = 1;

if ($numero >= 0) {
    for ($i = 1; $i <= $numero; $i++) {
        $factorial *= $i;
    }
    echo "El factorial de $numero es $factorial";
} else {
    echo "El factorial solo está definido para números enteros mayores o iguales a cero.";
}
?>