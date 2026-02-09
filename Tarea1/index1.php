<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Correos Electrónicos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 20px;
            text-align: center;
        }
        
        h2 {
            color: #764ba2;
            margin-bottom: 15px;
        }
        
        .intro-text {
            line-height: 1.6;
            color: #555;
            margin-bottom: 20px;
        }
        
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            transition: transform 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }
        
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        
        .alert {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        tr:hover {
            background: #f5f5f5;
        }
        
        .back-link {
            margin-top: 20px;
            display: inline-block;
        }
        
        .features {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .features ul {
            margin-left: 20px;
            line-height: 2;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Configuración de la base de datos (en memoria para esta demostración)
        // En producción, usar MySQL
        session_start();
        
        // Inicializar array de correos si no existe
        if (!isset($_SESSION['correos'])) {
            $_SESSION['correos'] = [];
        }
        
        // Determinar qué página mostrar
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 'inicio';
        $mensaje = '';
        $tipo_mensaje = '';
        
        // Procesar formulario si se envió
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre']) && isset($_POST['email'])) {
            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            
            // Validación en servidor
            if (empty($nombre) || empty($email)) {
                $mensaje = 'Por favor, completa todos los campos.';
                $tipo_mensaje = 'alert';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mensaje = 'El correo electrónico no es válido.';
                $tipo_mensaje = 'alert';
            } else {
                // Verificar duplicados
                $existe = false;
                foreach ($_SESSION['correos'] as $correo) {
                    if (strtolower($correo['email']) === strtolower($email)) {
                        $existe = true;
                        break;
                    }
                }
                
                if ($existe) {
                    $mensaje = 'Este correo electrónico ya está registrado.';
                    $tipo_mensaje = 'alert';
                } else {
                    // Guardar el correo
                    $_SESSION['correos'][] = [
                        'nombre' => htmlspecialchars($nombre),
                        'email' => htmlspecialchars($email),
                        'fecha' => date('Y-m-d H:i:s')
                    ];
                    
                    // Redirigir a la página de visualización
                    header('Location: ?pagina=visualizar');
                    exit;
                }
            }
        }
        
        // PÁGINA DE INICIO
        if ($pagina === 'inicio') {
        ?>
            <h1>📧 Gestión de Correos Electrónicos</h1>
            
            <div class="intro-text">
                <p>Bienvenido a nuestra aplicación de gestión de direcciones de correo electrónico. Esta herramienta te permite mantener un registro organizado de contactos de forma sencilla y eficiente.</p>
            </div>
            
            <div class="features">
                <h2>¿Qué puedes hacer?</h2>
                <ul>
                    <li>✅ Registrar tu nombre y correo electrónico</li>
                    <li>✅ Validación automática del formato de correo</li>
                    <li>✅ Prevención de registros duplicados</li>
                    <li>✅ Visualizar todos los correos registrados</li>
                    <li>✅ Interfaz intuitiva y fácil de usar</li>
                </ul>
            </div>
            
            <div class="intro-text">
                <h2>¿Cómo funciona?</h2>
                <p><strong>Paso 1:</strong> Haz clic en el botón de abajo para acceder al formulario de registro.</p>
                <p><strong>Paso 2:</strong> Introduce tu nombre completo y dirección de correo electrónico.</p>
                <p><strong>Paso 3:</strong> El sistema validará tu correo y verificará que no esté duplicado.</p>
                <p><strong>Paso 4:</strong> Una vez registrado, podrás ver la lista completa de todos los contactos.</p>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="?pagina=registro" class="btn">Comenzar Registro →</a>
            </div>
            
        <?php
        // PÁGINA DE REGISTRO
        } elseif ($pagina === 'registro') {
        ?>
            <h1>📝 Formulario de Registro</h1>
            
            <?php if ($mensaje): ?>
                <div class="<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            
            <p class="intro-text">Por favor, introduce tus datos para registrarte en nuestra lista de correos.</p>
            
            <form method="POST" action="?pagina=registro" id="formularioRegistro" onsubmit="return validarFormulario()">
                <div class="form-group">
                    <label for="nombre">Nombre completo:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Juan Pérez" required>
                    <span class="error" id="errorNombre">Por favor, introduce tu nombre</span>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" id="email" name="email" placeholder="Ej: juan@ejemplo.com" required>
                    <span class="error" id="errorEmail">El correo debe contener una @</span>
                </div>
                
                <button type="submit" class="btn">Registrar y Ver Lista →</button>
                <a href="?pagina=inicio" class="btn" style="background: #6c757d; margin-left: 10px;">← Volver al Inicio</a>
            </form>
            
            <script>
                function validarFormulario() {
                    let valido = true;
                    
                    // Obtener valores
                    const nombre = document.getElementById('nombre').value.trim();
                    const email = document.getElementById('email').value.trim();
                    
                    // Resetear errores
                    document.getElementById('errorNombre').style.display = 'none';
                    document.getElementById('errorEmail').style.display = 'none';
                    
                    // Validar nombre
                    if (nombre === '') {
                        document.getElementById('errorNombre').style.display = 'block';
                        valido = false;
                    }
                    
                    // Validar email (verificar que tenga @)
                    if (email === '' || email.indexOf('@') === -1) {
                        document.getElementById('errorEmail').style.display = 'block';
                        valido = false;
                    }
                    
                    // Validación adicional del formato de email
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        document.getElementById('errorEmail').textContent = 'El formato del correo no es válido';
                        document.getElementById('errorEmail').style.display = 'block';
                        valido = false;
                    }
                    
                    return valido;
                }
                
                // Validación en tiempo real
                document.getElementById('email').addEventListener('blur', function() {
                    if (this.value.indexOf('@') === -1) {
                        document.getElementById('errorEmail').style.display = 'block';
                    } else {
                        document.getElementById('errorEmail').style.display = 'none';
                    }
                });
            </script>
            
        <?php
        // PÁGINA DE VISUALIZACIÓN
        } elseif ($pagina === 'visualizar') {
        ?>
            <h1>📋 Lista de Correos Registrados</h1>
            
            <?php if (count($_SESSION['correos']) > 0): ?>
                <div class="success">
                    ✓ Total de correos registrados: <strong><?php echo count($_SESSION['correos']); ?></strong>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Correo Electrónico</th>
                            <th>Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['correos'] as $index => $correo): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo $correo['nombre']; ?></td>
                            <td><?php echo $correo['email']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($correo['fecha'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert">
                    No hay correos registrados todavía. ¡Sé el primero en registrarte!
                </div>
            <?php endif; ?>
            
            <div class="back-link">
                <a href="?pagina=registro" class="btn">Registrar Otro Correo</a>
                <a href="?pagina=inicio" class="btn" style="background: #6c757d; margin-left: 10px;">← Volver al Inicio</a>
            </div>
        <?php
        }
        ?>
    </div>
</body>
</html>