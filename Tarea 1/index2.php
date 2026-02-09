<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Contactos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c3e50;
            margin: 0;
            padding: 20px;
        }
        
        .contenedor {
            max-width: 900px;
            margin: 0 auto;
            background: #ecf0f1;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        
        h2 {
            color: #34495e;
            margin-top: 25px;
        }
        
        p {
            line-height: 1.8;
            color: #2c3e50;
        }
        
        .boton {
            background-color: #3498db;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 15px;
            margin: 5px;
        }
        
        .boton:hover {
            background-color: #2980b9;
        }
        
        .boton-gris {
            background-color: #95a5a6;
        }
        
        .boton-gris:hover {
            background-color: #7f8c8d;
        }
        
        .campo {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #2c3e50;
            font-weight: bold;
        }
        
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        input:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .mensaje-error {
            color: #c0392b;
            font-size: 13px;
            margin-top: 3px;
            display: none;
        }
        
        .aviso-ok {
            background: #2ecc71;
            color: white;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .aviso-mal {
            background: #e74c3c;
            color: white;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
        }
        
        th {
            background-color: #34495e;
            color: white;
            padding: 10px;
            text-align: left;
        }
        
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .seccion {
            display: none;
        }
        
        .seccion.mostrar {
            display: block;
        }

        .info-box {
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #3498db;
        }

        ul {
            margin-left: 20px;
            line-height: 1.8;
        }

        li {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        
        <div id="inicio" class="seccion mostrar">
            <h1>Gestor de Contactos por Email</h1>
            
            <p>Esta es una aplicación web sencilla que he desarrollado para gestionar una lista de contactos mediante sus direcciones de correo electrónico.</p>
            
            <div class="info-box">
                <h2>Funcionalidades principales:</h2>
                <ul>
                    <li>Registro de contactos con nombre y email</li>
                    <li>Validación automática de direcciones de correo</li>
                    <li>Control de duplicados en la base de datos</li>
                    <li>Listado completo de todos los registros</li>
                </ul>
            </div>
            
            <h2>Como usar la aplicacion</h2>
            <p>Pulsa el botón de abajo para acceder al formulario de registro. Introduce tu nombre y tu dirección de correo. El sistema comprobará que el formato del email es correcto y que no está ya registrado. Después de guardar los datos, podrás ver el listado completo con todos los contactos que se han ido añadiendo.</p>
            
            <button class="boton" onclick="irARegistro()">Ir al formulario</button>
        </div>

        <div id="registro" class="seccion">
            <h1>Añadir nuevo contacto</h1>
            
            <div id="avisoForm"></div>
            
            <p>Rellena los siguientes campos para darte de alta en la lista:</p>
            
            <form id="miFormulario">
                <div class="campo">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" placeholder="Escribe tu nombre completo">
                    <span class="mensaje-error" id="errorNom">El nombre es obligatorio</span>
                </div>
                
                <div class="campo">
                    <label for="email">Email:</label>
                    <input type="text" id="email" placeholder="tu.email@ejemplo.com">
                    <span class="mensaje-error" id="errorMail">Debe ser un email válido con @</span>
                </div>
                
                <button type="button" class="boton" onclick="guardarContacto()">Guardar contacto</button>
                <button type="button" class="boton boton-gris" onclick="volverInicio()">Cancelar</button>
            </form>
        </div>

        <div id="lista" class="seccion">
            <h1>Contactos registrados</h1>
            
            <div id="zonaLista"></div>
            
            <button class="boton" onclick="irARegistro()">Añadir más</button>
            <button class="boton boton-gris" onclick="volverInicio()">Volver</button>
        </div>
    </div>

    <script>
        var listaContactos = [];

        function ocultarTodas() {
            document.getElementById('inicio').className = 'seccion';
            document.getElementById('registro').className = 'seccion';
            document.getElementById('lista').className = 'seccion';
        }

        function volverInicio() {
            ocultarTodas();
            document.getElementById('inicio').className = 'seccion mostrar';
        }

        function irARegistro() {
            ocultarTodas();
            document.getElementById('registro').className = 'seccion mostrar';
            document.getElementById('avisoForm').innerHTML = '';
        }

        function irALista() {
            ocultarTodas();
            document.getElementById('lista').className = 'seccion mostrar';
            actualizarLista();
        }

        function validarDatos() {
            var ok = true;
            var nom = document.getElementById('nombre').value;
            var mail = document.getElementById('email').value;
            
            document.getElementById('errorNom').style.display = 'none';
            document.getElementById('errorMail').style.display = 'none';
            
            if (nom === '') {
                document.getElementById('errorNom').style.display = 'block';
                ok = false;
            }
            
            if (mail === '' || mail.indexOf('@') === -1) {
                document.getElementById('errorMail').innerHTML = 'El email debe contener @';
                document.getElementById('errorMail').style.display = 'block';
                ok = false;
            } else {
                var patron = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!patron.test(mail)) {
                    document.getElementById('errorMail').innerHTML = 'Formato de email incorrecto';
                    document.getElementById('errorMail').style.display = 'block';
                    ok = false;
                }
            }
            
            return ok;
        }

        function guardarContacto() {
            if (!validarDatos()) {
                return;
            }
            
            var nom = document.getElementById('nombre').value;
            var mail = document.getElementById('email').value;
            
            var existe = false;
            for (var i = 0; i < listaContactos.length; i++) {
                if (listaContactos[i].email.toLowerCase() === mail.toLowerCase()) {
                    existe = true;
                    break;
                }
            }
            
            if (existe) {
                document.getElementById('avisoForm').innerHTML = '<div class="aviso-mal">Este email ya está en la lista</div>';
                return;
            }
            
            var contacto = {
                nombre: nom,
                email: mail,
                fecha: new Date()
            };
            
            listaContactos.push(contacto);
            
            document.getElementById('nombre').value = '';
            document.getElementById('email').value = '';
            
            irALista();
        }

        function actualizarLista() {
            var zona = document.getElementById('zonaLista');
            
            if (listaContactos.length === 0) {
                zona.innerHTML = '<div class="aviso-mal">Todavía no hay contactos registrados</div>';
                return;
            }
            
            var html = '<div class="aviso-ok">Total de contactos: ' + listaContactos.length + '</div>';
            html += '<table><thead><tr><th>Nº</th><th>Nombre</th><th>Email</th><th>Fecha</th></tr></thead><tbody>';
            
            for (var i = 0; i < listaContactos.length; i++) {
                var f = listaContactos[i].fecha;
                var dia = f.getDate();
                var mes = f.getMonth() + 1;
                var anio = f.getFullYear();
                var hora = f.getHours();
                var minutos = f.getMinutes();
                if (minutos < 10) minutos = '0' + minutos;
                
                var fechaTxt = dia + '/' + mes + '/' + anio + ' ' + hora + ':' + minutos;
                
                html += '<tr>';
                html += '<td>' + (i + 1) + '</td>';
                html += '<td>' + listaContactos[i].nombre + '</td>';
                html += '<td>' + listaContactos[i].email + '</td>';
                html += '<td>' + fechaTxt + '</td>';
                html += '</tr>';
            }
            
            html += '</tbody></table>';
            zona.innerHTML = html;
        }

        window.onload = function() {
            var campoEmail = document.getElementById('email');
            if (campoEmail) {
                campoEmail.onblur = function() {
                    var valor = this.value;
                    if (valor !== '' && valor.indexOf('@') === -1) {
                        document.getElementById('errorMail').innerHTML = 'El email debe tener @';
                        document.getElementById('errorMail').style.display = 'block';
                    }
                };
            }
        };
    </script>
</body>
</html>