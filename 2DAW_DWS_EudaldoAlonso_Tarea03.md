
# Portada

**Módulo:** Desarrollo Web en Entorno Servidor (DAW_DSW)

**Tarea:** UT03 - Desarrollo de una pequeña aplicación web en PHP usando Bases de Datos

**Alumno:** Eudaldo Alonso

**Curso:** 2º DAW Semipresencial

---

<!-- Salto de página para el conversor a PDF -->

## Cabecera (para todas las páginas excepto portada e índice)

*Eudaldo Alonso*

---

# Índice

1.  [Introducción](#introducción)
2.  [Estructura de Ficheros](#estructura-de-ficheros)
3.  [Creación de la Base de Datos](#creación-de-la-base-de-datos)
4.  [Análisis del Código y Funcionalidades](#análisis-del-código-y-funcionalidades)
    *   [4.1. Conexión a la Base de Datos (db.php)](#41-conexión-a-la-base-de-datos-dbphp)
    *   [4.2. Sistema de Login y Control de Sesiones (login.php, logout.php)](#42-sistema-de-login-y-control-de-sesiones-loginphp-logoutphp)
    *   [4.3. Panel Principal (dashboard.php)](#43-panel-principal-dashboardphp)
    *   [4.4. Rol Vecino](#44-rol-vecino)
    *   [4.5. Rol Presidente](#45-rol-presidente)
    *   [4.6. Rol Administrador](#46-rol-administrador)
5.  [Capturas de Pantalla Obligatorias](#capturas-de-pantalla-obligatorias)
    *   [5.1. Creación de la Base de Datos](#51-creación-de-la-base-de-datos)
    *   [5.2. Acceso y Vista de cada Rol](#52-acceso-y-vista-de-cada-rol)
    *   [5.3. Operaciones CRUD](#53-operaciones-crud)
6.  [Conclusión](#conclusión)
7.  [Bibliografía](#bibliografía)

---

<!-- Salto de página para el conversor a PDF -->

# 1. Introducción

El presente documento detalla el desarrollo de una aplicación web en PHP para la gestión de una comunidad de vecinos. El objetivo principal es reemplazar un sistema obsoleto basado en hojas de cálculo por una solución centralizada, segura y eficiente que permita gestionar la información de los vecinos y el estado de sus cuotas.

La aplicación implementa un sistema de control de acceso basado en tres roles (vecino, presidente y administrador), cada uno con permisos específicos que garantizan la integridad y la confidencialidad de los datos. Se ha utilizado PHP como lenguaje de backend, PDO para la interacción segura con la base de datos MySQL y Bootstrap para asegurar un diseño responsive y amigable.

---

# 2. Estructura de Ficheros

El proyecto se ha organizado en una estructura de carpetas clara y modular para facilitar el mantenimiento y la escalabilidad.

```
/code3/
├── admin/
│   ├── alta_vecino.php       # Formulario y lógica para crear vecinos.
│   ├── baja_vecino.php       # Lógica para eliminar vecinos.
│   └── modificar_vecino.php  # Formulario y lógica para editar vecinos.
├── css/
│   └── estilo.css            # Hoja de estilos personalizada.
├── data/
│   └── vecinos.dat           # (No utilizado en la versión con BBDD, remanente de versión anterior).
├── includes/
│   ├── config.php            # Variables de configuración y ajustes.
│   ├── db.php                # Lógica para la conexión PDO a la BBDD.
│   ├── funciones.php         # Funciones auxiliares y reutilizables.
│   └── validaciones.php      # Funciones para validar datos de formularios.
├── presidente/
│   └── modificar_cuotas.php  # Interfaz para que el presidente edite cuotas.
├── sql/
│   └── comunidad.sql         # Script SQL para crear la BBDD y la tabla.
├── cambiar_password.php      # Funcionalidad para cambiar la contraseña.
├── dashboard.php             # Panel principal que se muestra tras el login.
├── generar_datos.php         # Script para poblar la BBDD con datos de ejemplo.
├── index.php                 # Punto de entrada, redirige al login.
├── login.php                 # Formulario de inicio de sesión y autenticación.
└── logout.php                # Script para cerrar la sesión del usuario.
```

---

# 3. Creación de la Base de Datos

La base de datos, denominada `comunidad`, es el núcleo de la aplicación. Se ha definido un script `comunidad.sql` que contiene las sentencias necesarias para crear la tabla `vecinos` y su estructura.

La tabla `vecinos` almacena toda la información relevante: datos personales, de contacto, vivienda, estado de las cuotas y credenciales de acceso. El campo `rolUsuario` es un ENUM que define los permisos del usuario dentro de la aplicación.

**Fragmento de `comunidad.sql`:**
```sql
CREATE TABLE `vecinos` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `telefono` int(9) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `fechaAlta` date NOT NULL,
  `piso` varchar(2) NOT NULL,
  `bloque` varchar(2) NOT NULL,
  `letra` varchar(2) NOT NULL,
  `cuotasImpagadas` int(3) DEFAULT 0,
  `fechaUltimaCuota` date DEFAULT NULL,
  `nombreUsuario` varchar(15) NOT NULL,
  `passUsuario` varchar(255) NOT NULL, -- Se recomienda varchar(255) para contraseñas hasheadas
  `rolUsuario` enum('vecino','presidente','administrador') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dni` (`dni`),
  UNIQUE KEY `nombreUsuario` (`nombreUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

---

# 4. Análisis del Código y Funcionalidades

### 4.1. Conexión a la Base de Datos (db.php)

Para la conexión con MySQL se ha utilizado la extensión **PDO (PHP Data Objects)**, tal como se solicita en los requisitos. Este método ofrece una capa de abstracción que facilita la portabilidad a otras bases de datos y, fundamentalmente, previene ataques de inyección SQL mediante el uso de consultas preparadas.

El fichero `includes/db.php` contiene una función que lee los parámetros de conexión (host, usuario, contraseña, nombre de la BBDD) y devuelve un objeto PDO. Se ha implementado un bloque `try-catch` para gestionar posibles errores de conexión de forma controlada.

### 4.2. Sistema de Login y Control de Sesiones (login.php, logout.php)

El acceso a la aplicación está protegido por un sistema de autenticación.

*   **`login.php`**: Presenta el formulario de acceso. Al enviarse, comprueba las credenciales contra la base de datos. Si son correctas, inicia una sesión (`session_start()`) y almacena en variables `$_SESSION` el nombre de usuario, el rol y el ID. Después, redirige al `dashboard.php`. Se hashea la contraseña para la comparación.
*   **`logout.php`**: Cierra la sesión del usuario destruyendo todas las variables de sesión y redirigiendo de vuelta a la página de login.
*   **Control de acceso**: En la cabecera de cada página restringida, se comprueba si existe la variable de sesión del usuario. Si no existe, se le redirige inmediatamente al `login.php`, impidiendo el acceso no autorizado.

### 4.3. Panel Principal (dashboard.php)

Es la página de bienvenida. Muestra un mensaje personalizado y, utilizando la información de la sesión, carga dinámicamente el contenido correspondiente a cada rol. Un `switch` basado en `$_SESSION['rolUsuario']` determina qué vista (vecino, presidente o administrador) se debe mostrar, cargando los datos pertinentes de la base de datos.

### 4.4. Rol Vecino

El vecino tiene los permisos más restringidos. El `dashboard.php` detecta su rol y ejecuta una consulta SQL que selecciona únicamente la fila de la tabla `vecinos` correspondiente a su `id` de usuario. De esta forma, solo puede visualizar su propia información y no tiene acceso a los datos del resto de vecinos.

### 4.5. Rol Presidente

El presidente puede ver los datos de todos los vecinos y modificar las cuotas.
*   **Vista de vecinos**: El `dashboard.php` muestra una tabla con todos los vecinos.
*   **Modificación de cuotas**: En `presidente/modificar_cuotas.php`, el presidente puede actualizar los campos `cuotasImpagadas` y `fechaUltimaCuota` para un vecino específico, identificado por su `id` pasado por `$_GET`.

### 4.6. Rol Administrador

El administrador tiene control total (CRUD).
*   **Crear (Create)**: `admin/alta_vecino.php` contiene el formulario para añadir un nuevo vecino. Los datos se validan y se insertan en la BBDD mediante una consulta `INSERT`.
*   **Leer (Read)**: Al igual que el presidente, ve una tabla con todos los vecinos en el `dashboard.php`.
*   **Actualizar (Update)**: `admin/modificar_vecino.php` permite editar todos los campos de un vecino. Utiliza una consulta `UPDATE` para guardar los cambios.
*   **Borrar (Delete)**: `admin/baja_vecino.php` recibe un `id` por `$_GET` y ejecuta una consulta `DELETE` para eliminar al vecino de la base de datos.

---

# 5. Capturas de Pantalla Obligatorias

En esta sección se deben adjuntar las capturas de pantalla que evidencian el funcionamiento de la aplicación. **Recuerda que cada captura debe incluir la fecha/hora del sistema y tu nombre de usuario del campus.**

### 5.1. Creación de la Base de Datos

> **[INSERTA AQUÍ LA CAPTURA DE PANTALLA DE PHPMYADMIN MOSTRANDO LA ESTRUCTURA DE LA TABLA `vecinos` CREADA A PARTIR DEL SCRIPT `comunidad.sql`.]**
>
> *Descripción: Muestra de la estructura final de la tabla `vecinos` en phpMyAdmin después de ejecutar el script SQL.*

### 5.2. Acceso y Vista de cada Rol

> **[INSERTA AQUÍ LA CAPTURA DE PANTALLA DEL LOGIN DE UN USUARIO `vecino` Y SU VISTA DEL DASHBOARD.]**
>
> *Descripción: El vecino "Juan Pérez" inicia sesión y solo puede ver sus propios datos en el panel.*

> **[INSERTA AQUÍ LA CAPTURA DE PANTALLA DEL LOGIN DE UN USUARIO `presidente` Y SU VISTA DEL DASHBOARD.]**
>
> *Descripción: El presidente "Ana García" inicia sesión y ve la tabla con todos los vecinos.*

> **[INSERTA AQUÍ LA CAPTURA DE PANTALLA DEL LOGIN DE UN USUARIO `administrador` Y SU VISTA DEL DASHBOARD.]**
>
> *Descripción: El administrador "Gestoría SL" inicia sesión y ve la tabla completa con opciones CRUD.*

### 5.3. Operaciones CRUD

> **[INSERTA AQUÍ 2 CAPTURAS: ANTES Y DESPUÉS DE LA MODIFICACIÓN DE CUOTA POR EL PRESIDENTE.]**
>
> *Descripción: Muestra del cambio en las cuotas impagadas de un vecino realizado por el presidente.*

> **[INSERTA AQUÍ 2 CAPTURAS: VISTA INICIAL Y VISTA FINAL TRAS DAR DE ALTA UN VECINO POR EL ADMINISTRADOR.]**
>
> *Descripción: El administrador añade un nuevo vecino a través del formulario y este aparece en la lista.*

> **[INSERTA AQUÍ 2 CAPTURAS: VISTA INICIAL Y VISTA FINAL TRAS DAR DE BAJA UN VECINO POR EL ADMINISTRADOR.]**
>
> *Descripción: El administrador elimina a un vecino de la lista.*

> **[INSERTA AQUÍ 2 CAPTURAS: ANTES Y DESPUÉS DE LA MODIFICACIÓN DE DATOS DE UN VECINO POR EL ADMINISTRADOR.]**
>
> *Descripción: El administrador cambia el teléfono y el email de un vecino.*

---

# 6. Conclusión

El desarrollo de esta aplicación ha permitido poner en práctica los conocimientos fundamentales del desarrollo web en entorno servidor con PHP y MySQL. La utilización de PDO ha sido clave para garantizar la seguridad y la integridad de los datos, mientras que la estructura de roles y sesiones asegura un acceso controlado y personalizado a la información.

El resultado es una herramienta funcional, robusta y escalable que soluciona de manera efectiva el problema planteado, demostrando la viabilidad de sustituir procesos manuales por aplicaciones web dinámicas y seguras.

---

# 7. Bibliografía

*   Documentación oficial de PHP: [https://www.php.net/manual/es/](https://www.php.net/manual/es/)
*   Documentación sobre PDO: [https://www.php.net/manual/es/book.pdo.php](https://www.php.net/manual/es/book.pdo.php)
*   Tutoriales y referencias de W3Schools: [https://www.w3schools.com/php/](https://www.w3schools.com/php/)
*   Documentación oficial de Bootstrap: [https://getbootstrap.com/docs/](https://getbootstrap.com/docs/)
*   Consultas y resolución de dudas en Stack Overflow: [https://stackoverflow.com/](https://stackoverflow.com/)

---

## Pie de página (para todas las páginas excepto portada)

*Página [NÚMERO DE PÁGINA]*
