<?php require __DIR__ . '/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante El Drago - Tenerife</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        :root {
            --primary-color: #005A9C;
            --light-bg: #f0f5fa;
            --dark-text: #333333;
            --light-text: #f8f9fa;
            --card-bg: #ffffff;
            --border-color: #dee2e6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
        }
        
        header {
            background: var(--primary-color);
            color: var(--light-text);
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .info-card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }
        
        .info-card h2 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }
        
        .info-card p {
            color: #555;
            line-height: 1.8;
            margin-bottom: 1rem;
        }
        
        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .contact-item {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }
        
        .contact-item strong {
            color: var(--dark-text);
            display: block;
            margin-bottom: 0.5rem;
        }
        
        #map {
            height: 500px;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 2rem;
            border: 1px solid var(--border-color);
        }
        
        .tech-stack {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-top: 2rem;
            border: 1px solid var(--border-color);
        }
        
        .tech-stack h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .tech-list {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .tech-badge {
            background: var(--primary-color);
            color: var(--light-text);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        footer {
            text-align: center;
            padding: 2rem;
            color: #777;
            margin-top: 3rem;
        }
        
        .coordinates {
            background: var(--light-bg);
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            font-family: 'Courier New', monospace;
            border: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <header>
        <div style="display: flex; align-items: center; justify-content: center; gap: 2rem;">
            <img src="logo.png" alt="Logo Restaurante El Drago" style="width: 220px; height: auto; border-radius: 50%; flex-shrink: 0;">
            <div style="text-align: left;">
                <h1>Restaurante El Drago</h1>
                <p>Auténtica cocina canaria en el corazón de Tenerife</p>
            </div>
        </div>
    </header>
    
    <div class="container">
        <div class="info-card">
            <h2>Bienvenidos a nuestro restaurante</h2>
            <p>
                El Drago es un restaurante familiar ubicado en San Cristóbal de La Laguna, Tenerife, 
                con más de 20 años de tradición en gastronomía canaria. Ofrecemos platos elaborados 
                con productos locales de la más alta calidad.
            </p>
            
            <div class="contact-info">
                <div class="contact-item">
                    <strong>📍 Dirección</strong>
                    Calle La Rosa, 15<br>
                    38201 San Cristóbal de La Laguna<br>
                    Tenerife, Islas Canarias
                </div>
                <div class="contact-item">
                    <strong>📞 Teléfono</strong>
                    +34 922 123 456
                </div>
                <div class="contact-item">
                    <strong>⏰ Horario</strong>
                    Lun-Sáb: 13:00 - 16:00, 20:00 - 23:00<br>
                    Domingo: 13:00 - 16:00
                </div>
                <div class="contact-item">
                    <strong>✉️ Email</strong>
                    info@eldrago.es
                </div>
            </div>
            
            <div class="coordinates">
                <strong>Coordenadas GPS:</strong><br>
                Latitud: <?php echo $latitude; ?><br>
                Longitud: <?php echo $longitude; ?>
            </div>
        </div>
        
        <div class="info-card">
            <h2>📍 Cómo llegar</h2>
            <p>
                Encuentra nuestro restaurante fácilmente con el mapa interactivo. Estamos ubicados 
                en el centro histórico de La Laguna, cerca de la Catedral y a pocos minutos del 
                centro comercial La Villa.
            </p>
            <div id="map"></div>
        </div>
        
        <div class="tech-stack">
            <h3>💻 Tecnologías utilizadas en esta demo</h3>
            <div class="tech-list">
                <span class="tech-badge">PHP <?php echo phpversion(); ?></span>
                <span class="tech-badge">Leaflet.js 1.9.4</span>
                <span class="tech-badge">OpenStreetMap</span>
                <span class="tech-badge">HTML5/CSS3</span>
                <span class="tech-badge">JavaScript ES6</span>
                <span class="tech-badge">Aplicación Web Híbrida</span>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2024 Restaurante El Drago - Demo de geolocalización<br></p>
    </footer>
    
    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        const ROOT_STYLES = getComputedStyle(document.documentElement);
        const PRIMARY_COLOR = ROOT_STYLES.getPropertyValue('--primary-color').trim();

        // Coordenadas del restaurante pasadas desde PHP
        const restaurantLat = <?php echo $latitude; ?>;
        const restaurantLng = <?php echo $longitude; ?>;
        const restaurantName = "<?php echo $name; ?>";
        const restaurantAddress = "<?php echo $address; ?>";
        
        console.log(`Inicializando mapa con coordenadas: ${restaurantLat}, ${restaurantLng}`);
        
        // Inicializar el mapa centrado en el restaurante
        const map = L.map('map').setView([restaurantLat, restaurantLng], 16);
        
        // Añadir capa de tiles de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Icono personalizado para el marcador
        const customIcon = L.icon({
            iconUrl: `data:image/svg+xml;base64,${btoa(`<svg xmlns="http://www.w3.org/2000/svg" width="32" height="42" viewBox="0 0 32 42"><path fill="${PRIMARY_COLOR}" d="M16 0C7.163 0 0 7.163 0 16c0 8.837 16 26 16 26s16-17.163 16-26c0-8.837-7.163-16-16-16zm0 24c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z"/></svg>`)}`,
            iconSize: [32, 42],
            iconAnchor: [16, 42],
            popupAnchor: [0, -42]
        });
        
        // Añadir marcador en la ubicación del restaurante
        const marker = L.marker([restaurantLat, restaurantLng], { icon: customIcon }).addTo(map);
        
        // Popup con información del restaurante
        marker.bindPopup(`
            <div style="text-align: center; font-size: 14px;">
                <h3 style="color: ${PRIMARY_COLOR}; margin-bottom: 10px; font-size: 18px;">${restaurantName}</h3>
                <p style="margin: 5px 0;"><strong>Dirección:</strong><br>${restaurantAddress}</p>
                <p style="margin: 5px 0;"><strong>Teléfono:</strong> +34 922 123 456</p>
                <a href="https://www.google.com/maps/dir//${restaurantLat},${restaurantLng}" 
                   target="_blank" 
                   style="display: inline-block; margin-top: 10px; padding: 8px 15px; background: ${PRIMARY_COLOR}; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
                   Cómo llegar
                </a>
            </div>
        `).openPopup();
        
        // Añadir círculo de área de influencia
        L.circle([restaurantLat, restaurantLng], {
            color: PRIMARY_COLOR,
            fillColor: PRIMARY_COLOR,
            fillOpacity: 0.1,
            radius: 500 // 500 metros de radio
        }).addTo(map);
        
        // Control de escala
        L.control.scale({
            imperial: false,
            metric: true
        }).addTo(map);
    </script>
</body>
</html>
