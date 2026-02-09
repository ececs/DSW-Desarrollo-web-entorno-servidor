<?php
// Fichero: ver_wsdl.php
// Establece la cabecera para indicar que el contenido es XML
header('Content-Type: text/xml');

// Lee y muestra el contenido del archivo WSDL generado
readfile('servicio.wsdl');
