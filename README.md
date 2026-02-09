# Proyectos de Desarrollo Web en Entorno Servidor (DWS)

Este repositorio agrupa una colección de proyectos y ejercicios prácticos desarrollados para la asignatura de Desarrollo Web en Entorno Servidor. El objetivo principal es demostrar la aplicación de tecnologías del lado del servidor, principalmente PHP, en diferentes contextos y arquitecturas.

## Stack Tecnológico Principal
*   **Lenguaje de Programación:** PHP
*   **Base de Datos:** MySQL
*   **Frontend:** HTML, CSS, JavaScript
*   **Dependencias y Herramientas:** Composer, PHPUnit, PHP-CS-Fixer, PHPStan

---

## Tabla de Contenidos
1.  [Sistema de Gestión de Comunidad](#sistema-de-gestión-de-comunidad)
2.  [Servicio Web SOAP](#servicio-web-soap)
3.  [Aplicación con Estándares Modernos](#aplicación-con-estándares-modernos)
4.  [Ejercicios Prácticos de PHP](#ejercicios-prácticos-de-php)
5.  [Sistema de Registro y Listado](#sistema-de-registro-y-listado)
6.  [Gestión de Preferencias de Usuario](#gestión-de-preferencias-de-usuario)
7.  [Maquetación Web](#maquetación-web)
8.  [Instalación y Uso](#instalación-y-uso)

---

## Proyectos Destacados

### Sistema de Gestión de Comunidad
*   **Carpetas:** `Gestion_Comunidad/`, `code2/`, `code3/`
*   **Descripción:** Una aplicación robusta para la administración de una comunidad de vecinos. El sistema está diseñado para manejar diferentes roles de usuario, permitiendo una gestión centralizada de la información y las finanzas.
*   **Características:**
    *   **Autenticación y Roles:** Sistema de login que diferencia entre `administrador`, `presidente` y `vecino`.
    *   **Gestión de Vecinos:** Funcionalidades CRUD (Crear, Leer, Actualizar, Borrar) para los vecinos de la comunidad.
    *   **Gestión de Cuotas:** Módulo para que el presidente pueda modificar las cuotas de la comunidad.
    *   **Base de Datos:** Utiliza una base de datos MySQL, cuyo script de creación se encuentra en `code3/sql/comunidad.sql`.

### Servicio Web SOAP
*   **Carpeta:** `code6/`
*   **Descripción:** Implementación de un servicio web basado en el protocolo SOAP. Este proyecto demuestra la capacidad de exponer funcionalidades a través de la red y consumirlas desde un cliente.
*   **Componentes:**
    *   `servicio.php`: Punto de entrada (endpoint) del servicio web.
    *   `Operaciones.php` y `DatosTienda.php`: Definen la lógica de negocio y las estructuras de datos del servicio.
    *   `generarWsdl.php`: Script para generar el archivo `servicio.wsdl` utilizando la librería `php2wsdl`.
    *   `cliente.php`: Un ejemplo de cómo consumir las operaciones expuestas por el servicio web.

### Aplicación con Estándares Modernos
*   **Carpeta:** `code/`
*   **Descripción:** Un proyecto PHP que destaca por su configuración moderna y profesional, utilizando herramientas estándar en la industria para garantizar la calidad y mantenibilidad del código.
*   **Puntos Clave:**
    *   **Gestión de Dependencias:** Uso de `Composer` para gestionar las librerías del proyecto.
    *   **Calidad de Código:** Integración de:
        *   `PHP-CS-Fixer`: Para mantener un estilo de código consistente.
        *   `PHPStan`: Para análisis estático y detección de errores.
        *   `PHPUnit`: Para la implementación de pruebas unitarias.
    *   **Configuración:** El archivo `config/restaurant.php` sugiere que la aplicación podría estar relacionada con la gestión de un restaurante.

---

## Otros Ejercicios y Tareas

### Ejercicios Prácticos de PHP
*   **Carpeta:** `Ejercicios/`
*   **Descripción:** Una colección de scripts autocontenidos que resuelven problemas algorítmicos y demuestran el uso de funcionalidades básicas de PHP. Incluye un calculador de factoriales (`factorial.php`).

### Sistema de Registro y Listado
*   **Carpeta:** `Tarea1/`
*   **Descripción:** Aplicación básica que implementa un formulario de registro (`registro.php`) y un listado de los datos introducidos (`lista.php`), cubriendo los fundamentos del manejo de formularios en PHP.

### Gestión de Preferencias de Usuario
*   **Carpeta:** `code4/`
*   **Descripción:** Un pequeño proyecto que muestra cómo gestionar las preferencias de un usuario (`preferencias.php`) y cómo aplicarlas en la visualización (`mostrar.php`). Probablemente utiliza sesiones o cookies de PHP.

### Maquetación Web
*   **Carpeta:** `empresa-tarea-ecs/`
*   **Descripción:** Proyecto enfocado en el desarrollo frontend, utilizando principalmente HTML y CSS para la construcción de una página estática.

---

## Instalación y Uso
La mayoría de los proyectos están diseñados para ser ejecutados en un entorno de servidor web estándar como Apache o Nginx con PHP.
1.  Clona o descarga este repositorio en el directorio raíz de tu servidor web (ej. `/var/www/html/`).
2.  Para el **Sistema de Gestión de Comunidad**, importa el archivo `code3/sql/comunidad.sql` en tu gestor de bases de datos MySQL para crear la estructura de tablas necesaria.
3.  Para los proyectos que usan `Composer` (`code/`, `code6/`), ejecuta `composer install` dentro de sus respectivas carpetas para descargar las dependencias.
4.  Accede a los diferentes proyectos a través de tu navegador web.

---

**Autor:** Eudaldo Alonso (si es correcto, si no, puedes cambiarlo)
