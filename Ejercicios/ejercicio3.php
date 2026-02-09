<?php
// Define cuántos números pares deseas generar
$n = 10;

// Array para almacenar los números pares
$numerosPares = [];

// Generar los primeros n números pares

for ($i = 1; $i <= $n; $i++) {
    $numerosPares[] = $i * 2;
}

// Mostrar el resultado
echo "Los primeros $n números pares son:\n\n";
?>