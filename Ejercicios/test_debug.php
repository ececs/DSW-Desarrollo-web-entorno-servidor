<?php

function saludar($nombre) {
    $mensaje = "Hola, " . $nombre;
    return $mensaje;
}

$nombre = "Office";
echo saludar($nombre);