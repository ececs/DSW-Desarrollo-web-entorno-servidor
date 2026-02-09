CREATE DATABASE IF NOT EXISTS comunidad;

USE comunidad;

-- Se elimina la tabla si ya existe para asegurar que se aplica el nuevo esquema
DROP TABLE IF EXISTS vecinos;

CREATE TABLE vecinos (
    id INT(5) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    dni VARCHAR(9) NOT NULL UNIQUE,
    telefono INT(9),
    email VARCHAR(50),
    fechaAlta DATE,
    piso VARCHAR(2),
    bloque VARCHAR(2),
    letra VARCHAR(2),
    cuotasImpagadas INT(3),
    fechaUltimaCuota DATE,
    nombreUsuario VARCHAR(15) NOT NULL UNIQUE,
    passUsuario VARCHAR(255) NOT NULL, -- Aumentado a 255 para almacenar hashes de contraseña
    rolUsuario ENUM('vecino', 'presidente', 'administrador') NOT NULL
);