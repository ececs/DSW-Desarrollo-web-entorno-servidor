const fs = require('fs');
const { Document, Packer, Paragraph, TextRun, Table, TableRow, TableCell, HeadingLevel, 
        AlignmentType, BorderStyle, WidthType, ShadingType, LevelFormat, PageBreak, 
        Header, Footer, PageNumber, ExternalHyperlink } = require('docx');

// Configuración de bordes para tablas
const tableBorder = { style: BorderStyle.SINGLE, size: 1, color: "CCCCCC" };
const cellBorders = { top: tableBorder, bottom: tableBorder, left: tableBorder, right: tableBorder };

// Crear el documento
const doc = new Document({
  styles: {
    default: {
      document: { run: { font: "Arial", size: 24 } } // 12pt default
    },
    paragraphStyles: [
      // Estilos de títulos
      { id: "Title", name: "Title", basedOn: "Normal",
        run: { size: 56, bold: true, color: "000000", font: "Arial" },
        paragraph: { spacing: { before: 240, after: 240 }, alignment: AlignmentType.CENTER } },
      { id: "Heading1", name: "Heading 1", basedOn: "Normal", next: "Normal", quickFormat: true,
        run: { size: 32, bold: true, color: "000000", font: "Arial" },
        paragraph: { spacing: { before: 360, after: 180 }, outlineLevel: 0 } },
      { id: "Heading2", name: "Heading 2", basedOn: "Normal", next: "Normal", quickFormat: true,
        run: { size: 28, bold: true, color: "000000", font: "Arial" },
        paragraph: { spacing: { before: 240, after: 120 }, outlineLevel: 1 } },
      { id: "Heading3", name: "Heading 3", basedOn: "Normal", next: "Normal", quickFormat: true,
        run: { size: 26, bold: true, color: "000000", font: "Arial" },
        paragraph: { spacing: { before: 180, after: 120 }, outlineLevel: 2 } }
    ]
  },
  numbering: {
    config: [
      { reference: "bullet-list",
        levels: [{ level: 0, format: LevelFormat.BULLET, text: "•", alignment: AlignmentType.LEFT,
          style: { paragraph: { indent: { left: 720, hanging: 360 } } } }] },
      { reference: "numbered-list-1",
        levels: [{ level: 0, format: LevelFormat.DECIMAL, text: "%1.", alignment: AlignmentType.LEFT,
          style: { paragraph: { indent: { left: 720, hanging: 360 } } } }] }
    ]
  },
  sections: [{
    properties: {
      page: {
        margin: { top: 1440, right: 1440, bottom: 1440, left: 1440 }
      }
    },
    headers: {
      default: new Header({ children: [
        new Paragraph({ 
          alignment: AlignmentType.RIGHT,
          children: [new TextRun({ text: "Daldo", size: 20, color: "666666" })]
        })
      ]})
    },
    footers: {
      default: new Footer({ children: [
        new Paragraph({ 
          alignment: AlignmentType.CENTER,
          children: [
            new TextRun({ text: "Página ", size: 20 }), 
            new TextRun({ children: [PageNumber.CURRENT], size: 20 })
          ]
        })
      ]})
    },
    children: [
      // PORTADA
      new Paragraph({ 
        heading: HeadingLevel.TITLE, 
        children: [new TextRun("")]
      }),
      new Paragraph({ 
        spacing: { before: 2880 },
        alignment: AlignmentType.CENTER, 
        children: [new TextRun({ text: "Daldo", size: 32, bold: true })]
      }),
      new Paragraph({ 
        spacing: { before: 240, after: 240 },
        alignment: AlignmentType.CENTER, 
        children: [new TextRun({ text: "UT08. Aplicaciones Web Híbridas", size: 28, bold: true })]
      }),
      new Paragraph({ 
        spacing: { after: 240 },
        alignment: AlignmentType.CENTER, 
        children: [new TextRun({ text: "Gestión de Geolocalización y Reservas para Restaurante", size: 24 })]
      }),
      new Paragraph({ 
        spacing: { before: 1440 },
        alignment: AlignmentType.CENTER, 
        children: [new TextRun({ text: "2º DAW - Desarrollo de Aplicaciones Web", size: 24 })]
      }),
      new Paragraph({ 
        spacing: { before: 120 },
        alignment: AlignmentType.CENTER, 
        children: [new TextRun({ text: "Desarrollo Web en Entorno Servidor", size: 22, italics: true })]
      }),
      new Paragraph({ 
        spacing: { before: 720 },
        alignment: AlignmentType.CENTER, 
        children: [new TextRun({ text: "Diciembre 2024", size: 22 })]
      }),
      
      // SALTO DE PÁGINA
      new Paragraph({ children: [new PageBreak()] }),
      
      // ÍNDICE
      new Paragraph({ 
        heading: HeadingLevel.HEADING_1, 
        children: [new TextRun("Índice")]
      }),
      new Paragraph({ 
        spacing: { before: 120, after: 60 },
        children: [new TextRun("1. Introducción")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        children: [new TextRun("2. Estudio de Sistemas de Geolocalización y Mapas Web")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        indent: { left: 360 },
        children: [new TextRun("2.1. Tecnologías Disponibles")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        indent: { left: 360 },
        children: [new TextRun("2.2. Google Maps API")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        indent: { left: 360 },
        children: [new TextRun("2.3. Mapbox")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        indent: { left: 360 },
        children: [new TextRun("2.4. Leaflet.js con OpenStreetMap")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        indent: { left: 360 },
        children: [new TextRun("2.5. Comparativa y Conclusiones")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        children: [new TextRun("3. Estudio de Sistemas de Gestión de Reservas")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        indent: { left: 360 },
        children: [new TextRun("3.1. Tecnologías Disponibles")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        indent: { left: 360 },
        children: [new TextRun("3.2. OpenTable")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        indent: { left: 360 },
        children: [new TextRun("3.3. TheFork/ElTenedor")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        indent: { left: 360 },
        children: [new TextRun("3.4. Sistemas Propios")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        indent: { left: 360 },
        children: [new TextRun("3.5. Comparativa y Conclusiones")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        children: [new TextRun("4. Ventajas de Utilizar APIs y Servicios en Aplicaciones Web")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        children: [new TextRun("5. Tecnologías Actuales para Aplicaciones Web Híbridas")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        children: [new TextRun("6. Mejoras Propuestas para la Aplicación Híbrida del Restaurante")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        children: [new TextRun("7. Desarrollo Teórico: Sistema de Gestión de Reservas")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        children: [new TextRun("8. Conclusión")]
      }),
      new Paragraph({ 
        spacing: { after: 60 },
        children: [new TextRun("9. Bibliografía")]
      }),
      
      // SALTO DE PÁGINA
      new Paragraph({ children: [new PageBreak()] }),
      
      // 1. INTRODUCCIÓN
      new Paragraph({ 
        heading: HeadingLevel.HEADING_1, 
        children: [new TextRun("1. Introducción")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("El objetivo de esta tarea es desarrollar una aplicación web híbrida que integre servicios externos para mejorar la experiencia del usuario en el contexto de un restaurante. En particular, se han estudiado dos casos de uso principales: la geolocalización mediante mapas interactivos y la gestión de reservas online.")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Las aplicaciones web híbridas combinan lo mejor de las tecnologías web (HTML5, CSS3, JavaScript) con la capacidad de acceder a funcionalidades nativas de los dispositivos. En el caso de un restaurante, esto permite ofrecer servicios como visualización de mapas interactivos, cálculo de rutas y gestión de reservas de forma integrada y multiplataforma.")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Este informe presenta un análisis exhaustivo de las tecnologías disponibles, sus ventajas e inconvenientes, junto con una demo funcional de geolocalización implementada con PHP y Leaflet.js. Además, se incluye un desarrollo teórico detallado de cómo implementar un sistema de gestión de reservas.")]
      }),
      
      // SALTO DE PÁGINA
      new Paragraph({ children: [new PageBreak()] }),
      
      // 2. ESTUDIO DE GEOLOCALIZACIÓN
      new Paragraph({ 
        heading: HeadingLevel.HEADING_1, 
        children: [new TextRun("2. Estudio de Sistemas de Geolocalización y Mapas Web")]
      }),
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("2.1. Tecnologías Disponibles")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("En el mercado actual existen diversas soluciones para integrar mapas interactivos en aplicaciones web. Las tres opciones más relevantes son Google Maps API, Mapbox y Leaflet.js con OpenStreetMap. Cada una ofrece diferentes características, modelos de precios y niveles de complejidad.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("2.2. Google Maps API")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Google Maps es la plataforma de mapas más utilizada a nivel mundial, con una cobertura del 99% y datos extremadamente precisos. Ofrece características avanzadas como Street View, datos de tráfico en tiempo real y una API muy completa.")]
      }),
      new Paragraph({ 
        spacing: { before: 120, after: 60 },
        children: [new TextRun({ text: "Ventajas:", bold: true })]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Cobertura global del 99% con datos muy precisos")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Street View integrado para visualización a nivel de calle")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Datos de tráfico en tiempo real")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Documentación exhaustiva y comunidad muy amplia")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Integración perfecta con otros servicios de Google")]
      }),
      new Paragraph({ 
        spacing: { before: 180, after: 60 },
        children: [new TextRun({ text: "Inconvenientes:", bold: true })]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Costes elevados: 7 USD por cada 1.000 visualizaciones después del tier gratuito")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Tier gratuito limitado: 28.500 visualizaciones mensuales (200 USD de crédito)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Requiere API Key y configuración de facturación obligatoria")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Menor flexibilidad en personalización visual")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Cambios frecuentes en precios y políticas")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("2.3. Mapbox")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Mapbox surgió como alternativa a Google Maps tras la subida de precios de 2018. Utiliza datos de OpenStreetMap y ofrece una personalización superior. Es utilizado por empresas como Uber, Airbnb y Foursquare.")]
      }),
      new Paragraph({ 
        spacing: { before: 120, after: 60 },
        children: [new TextRun({ text: "Ventajas:", bold: true })]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Altísimo nivel de personalización de estilos y diseño")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Precio más competitivo: 5 USD por cada 1.000 visualizaciones")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Tier gratuito generoso: 50.000 visualizaciones mensuales")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Rendimiento optimizado con tecnología WebGL")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("SDKs nativos para iOS y Android")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Soporte offline para mapas")]
      }),
      new Paragraph({ 
        spacing: { before: 180, after: 60 },
        children: [new TextRun({ text: "Inconvenientes:", bold: true })]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Curva de aprendizaje más pronunciada")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Requiere token de acceso y configuración")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Cobertura inconsistente en algunos países (India, Israel, China)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sin Street View")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Menor reconocimiento de marca que Google Maps")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("2.4. Leaflet.js con OpenStreetMap")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Leaflet.js es una biblioteca JavaScript de código abierto para mapas interactivos. Es extremadamente ligera (42KB comprimida) y se puede combinar con diferentes proveedores de tiles, siendo OpenStreetMap el más popular por ser completamente gratuito.")]
      }),
      new Paragraph({ 
        spacing: { before: 120, after: 60 },
        children: [new TextRun({ text: "Ventajas:", bold: true })]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Completamente gratuito sin límites de uso")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("No requiere API Key ni configuración de facturación")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Biblioteca muy ligera y rápida")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Open source con comunidad activa")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Fácil de implementar y aprender")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Compatible con múltiples proveedores de tiles")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Amplio ecosistema de plugins")]
      }),
      new Paragraph({ 
        spacing: { before: 180, after: 60 },
        children: [new TextRun({ text: "Inconvenientes:", bold: true })]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sin Street View")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sin datos de tráfico en tiempo real")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Requiere más configuración manual para funcionalidades avanzadas")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Calidad de datos variable según región (depende de OpenStreetMap)")]
      }),
      
      // SALTO DE PÁGINA
      new Paragraph({ children: [new PageBreak()] }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("2.5. Comparativa y Conclusiones")]
      }),
      new Paragraph({ 
        spacing: { after: 180 },
        children: [new TextRun("A continuación se presenta una tabla comparativa de las tres tecnologías analizadas:")]
      }),
      
      // Tabla comparativa
      new Table({
        columnWidths: [3120, 3120, 3120],
        margins: { top: 100, bottom: 100, left: 180, right: 180 },
        rows: [
          new TableRow({
            tableHeader: true,
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                shading: { fill: "667eea", type: ShadingType.CLEAR },
                children: [new Paragraph({ 
                  alignment: AlignmentType.CENTER,
                  children: [new TextRun({ text: "Característica", bold: true, size: 22, color: "FFFFFF" })]
                })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                shading: { fill: "667eea", type: ShadingType.CLEAR },
                children: [new Paragraph({ 
                  alignment: AlignmentType.CENTER,
                  children: [new TextRun({ text: "Google Maps", bold: true, size: 22, color: "FFFFFF" })]
                })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                shading: { fill: "667eea", type: ShadingType.CLEAR },
                children: [new Paragraph({ 
                  alignment: AlignmentType.CENTER,
                  children: [new TextRun({ text: "Mapbox", bold: true, size: 22, color: "FFFFFF" })]
                })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                shading: { fill: "667eea", type: ShadingType.CLEAR },
                children: [new Paragraph({ 
                  alignment: AlignmentType.CENTER,
                  children: [new TextRun({ text: "Leaflet + OSM", bold: true, size: 22, color: "FFFFFF" })]
                })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("28.500/mes ($200)")]	})]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("50.000/mes")] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun({ text: "Tier Gratuito", bold: true })] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Ilimitado")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("$7/1000")] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun({ text: "Coste por 1000", bold: true })] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("$5/1000")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Gratis")] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun({ text: "API Key", bold: true })] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Sí (obligatorio)")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Sí (token)")] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("No")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun({ text: "Personalización", bold: true })] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Media")] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Muy alta")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Alta")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun({ text: "Street View", bold: true })] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Sí")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("No")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("No")] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun({ text: "Complejidad", bold: true })] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Media")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Media-Alta")] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Baja")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun({ text: "Peso librería", bold: true })] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("~250KB")] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("~180KB")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("42KB")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("")]	})]
              })
            ]
          })
        ]
      }),
      
      new Paragraph({ 
        spacing: { before: 360, after: 120 },
        children: [new TextRun({ text: "Conclusión del Estudio:", bold: true })]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Para el caso de uso de un restaurante con tráfico moderado y presupuesto limitado, Leaflet.js con OpenStreetMap es la opción más adecuada. Ofrece todas las funcionalidades necesarias sin costes asociados y con una implementación sencilla. Esta fue la tecnología seleccionada para desarrollar la demo funcional.")]
      }),
      
      // SALTO DE PÁGINA
      new Paragraph({ children: [new PageBreak()] }),
      
      // 3. ESTUDIO DE RESERVAS
      new Paragraph({ 
        heading: HeadingLevel.HEADING_1, 
        children: [new TextRun("3. Estudio de Sistemas de Gestión de Reservas")]
      }),
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("3.1. Tecnologías Disponibles")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Los sistemas de gestión de reservas para restaurantes se dividen en dos categorías principales: plataformas de terceros con APIs y sistemas propios desarrollados a medida. Cada opción presenta diferentes ventajas según el tamaño del negocio y los requisitos específicos.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("3.2. OpenTable")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("OpenTable es líder indiscutible en reservas online, con más de un 21% del mercado global. Fundado en 1998, opera en más de 80 países y procesa más de 1.000 millones de comensales anuales.")]
      }),
      new Paragraph({ 
        spacing: { before: 120, after: 60 },
        children: [new TextRun({ text: "Ventajas:", bold: true })]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Red de 55.000 restaurantes y 31 millones de comensales")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sistema de gestión de mesas completo y maduro")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Herramientas de marketing y email integradas")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Integración con sistemas POS principales")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sistema de puntos y fidelización para clientes")]
      }),
      new Paragraph({ 
        spacing: { before: 180, after: 60 },
        children: [new TextRun({ text: "Inconvenientes:", bold: true })]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("API no abierta (requiere aprobación del programa de afiliados)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Costes elevados: entre 49 y 449 USD mensuales más comisión por reserva")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Comisión adicional de 2 USD por reserva a través de la red")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Principalmente orientado al mercado norteamericano")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("3.3. TheFork/ElTenedor")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("TheFork (conocido como ElTenedor en España) es líder europeo con 12.7% de cuota de mercado. Propiedad de TripAdvisor desde 2014, opera en 11 países con más de 55.000 restaurantes.")]
      }),
      new Paragraph({ 
        spacing: { before: 120, after: 60 },
        children: [new TextRun({ text: "Ventajas:", bold: true })]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Líder en el mercado europeo")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Integración estrecha con TripAdvisor")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sistema de descuentos y programa de fidelización YUMS")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Plan gratuito disponible con funcionalidades básicas")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Más de 20 millones de visitas mensuales")]
      }),
      new Paragraph({ 
        spacing: { before: 180, after: 60 },
        children: [new TextRun({ text: "Inconvenientes:", bold: true })]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Plan Pro+ cuesta 139 GBP mensuales")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Comisión sobre el precio medio del menú por reservas de la red")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("API no documentada públicamente")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Menos presencia fuera de Europa")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("3.4. Sistemas Propios")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Desarrollar un sistema propio de gestión de reservas ofrece máximo control y personalización. Se puede implementar con tecnologías como PHP + MySQL o utilizando frameworks modernos.")]
      }),
      new Paragraph({ 
        spacing: { before: 120, after: 60 },
        children: [new TextRun({ text: "Ventajas:", bold: true })]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sin comisiones por reserva")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Control total sobre datos y funcionalidades")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Personalización completa según necesidades específicas")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Integración directa con sistema interno del restaurante")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Costes predecibles (solo hosting y mantenimiento)")]
      }),
      new Paragraph({ 
        spacing: { before: 180, after: 60 },
        children: [new TextRun({ text: "Inconvenientes:", bold: true })]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Requiere inversión inicial en desarrollo")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Necesita mantenimiento técnico continuo")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sin acceso a la red de clientes de plataformas establecidas")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Responsabilidad completa sobre seguridad y disponibilidad")]
      }),
      
      // SALTO DE PÁGINA
      new Paragraph({ children: [new PageBreak()] }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("3.5. Comparativa y Conclusiones")]
      }),
      new Paragraph({ 
        spacing: { after: 180 },
        children: [new TextRun("Comparativa de sistemas de gestión de reservas:")]
      }),
      
      // Tabla comparativa de reservas
      new Table({
        columnWidths: [3120, 3120, 3120],
        margins: { top: 100, bottom: 100, left: 180, right: 180 },
        rows: [
          new TableRow({
            tableHeader: true,
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                shading: { fill: "4a90e2", type: ShadingType.CLEAR },
                children: [new Paragraph({ 
                  alignment: AlignmentType.CENTER,
                  children: [new TextRun({ text: "Característica", bold: true, size: 22, color: "FFFFFF" })]
                })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                shading: { fill: "4a90e2", type: ShadingType.CLEAR },
                children: [new Paragraph({ 
                  alignment: AlignmentType.CENTER,
                  children: [new TextRun({ text: "OpenTable", bold: true, size: 22, color: "FFFFFF" })]
                })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                shading: { fill: "4a90e2", type: ShadingType.CLEAR },
                children: [new Paragraph({ 
                  alignment: AlignmentType.CENTER,
                  children: [new TextRun({ text: "TheFork", bold: true, size: 22, color: "FFFFFF" })]
                })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                shading: { fill: "4a90e2", type: ShadingType.CLEAR },
                children: [new Paragraph({ 
                  alignment: AlignmentType.CENTER,
                  children: [new TextRun({ text: "Sistema Propio", bold: true, size: 22, color: "FFFFFF" })]
                })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("$49-449/mes + comisión")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Gratis - $139/mes + %")] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun({ text: "Coste mensual", bold: true })] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Solo hosting")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("$2/reserva")] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun({ text: "Comisión", bold: true })] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("% precio medio")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Sin comisión")] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("31M comensales")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun({ text: "Red clientes", bold: true })] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("20M visitas/mes")] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Ninguna")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Privada (afiliados)")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun({ text: "API disponible", bold: true })] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("No documentada")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Desarrollo propio")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Media")] })]
              })
            ]
          }),
          new TableRow({
            children: [
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun({ text: "Complejidad", bold: true })] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Media")] })]
              }),
              new TableCell({
                borders: cellBorders,
                width: { size: 3120, type: WidthType.DXA },
                children: [new Paragraph({ children: [new TextRun("Alta")] })]
              })
            ]
          })
        ]
      }),
      
      new Paragraph({ 
        spacing: { before: 360, after: 120 },
        children: [new TextRun({ text: "Conclusión del Estudio:", bold: true })]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Para un restaurante de tamaño pequeño a mediano, lo más recomendable es comenzar con un sistema propio básico para evitar comisiones. Conforme crece el negocio, se puede evaluar la integración con TheFork (en Europa) u OpenTable (internacional) para acceder a su red de clientes.")]
      }),
      
      // SALTO DE PÁGINA
      new Paragraph({ children: [new PageBreak()] }),
      
      // 4. VENTAJAS DE APIS
      new Paragraph({ 
        heading: HeadingLevel.HEADING_1, 
        children: [new TextRun("4. Ventajas de Utilizar APIs y Servicios en Aplicaciones Web")]
      }),
      new Paragraph({ 
        spacing: { after: 180 },
        children: [new TextRun("La integración de APIs y servicios externos en aplicaciones web ofrece múltiples ventajas que transforman el desarrollo moderno de software:")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("4.1. Reducción de Tiempo de Desarrollo")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("En lugar de desarrollar funcionalidades complejas desde cero, las APIs permiten integrar servicios probados y maduros en cuestión de horas o días. Por ejemplo, integrar un mapa interactivo con Leaflet.js requiere menos de 50 líneas de código, mientras que desarrollar un sistema de mapas propio podría llevar meses.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("4.2. Reducción de Costes")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Utilizar servicios existentes elimina la necesidad de contratar equipos especializados para desarrollar y mantener funcionalidades complejas. Las opciones gratuitas como Leaflet.js con OpenStreetMap permiten ofrecer características avanzadas sin inversión económica.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("4.3. Escalabilidad y Fiabilidad")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Los proveedores de APIs mantienen infraestructuras robustas con alta disponibilidad y capacidad de escalar automáticamente. Google Maps, por ejemplo, maneja miles de millones de solicitudes diarias sin problemas de rendimiento.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("4.4. Actualizaciones y Mantenimiento")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Los proveedores actualizan constantemente sus servicios con nuevas funcionalidades, correcciones de seguridad y mejoras de rendimiento. Esto significa que las aplicaciones se benefician automáticamente de estas mejoras sin esfuerzo adicional de desarrollo.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("4.5. Especialización")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Las empresas proveedoras de APIs se especializan en sus servicios. OpenStreetMap cuenta con millones de contribuidores actualizando mapas continuamente. Esta especialización garantiza calidad superior a lo que podría desarrollar un equipo interno.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("4.6. Interoperabilidad")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Las APIs facilitan la integración entre diferentes sistemas y plataformas. Una aplicación web puede combinar mapas de Leaflet.js, pagos de Stripe, autenticación de Auth0 y almacenamiento de AWS de forma cohesiva.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("4.7. Enfoque en el Core Business")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Utilizar APIs permite a los desarrolladores concentrarse en la lógica de negocio específica de la aplicación en lugar de reimplementar funcionalidades genéricas. Un restaurante puede enfocarse en optimizar sus procesos internos en vez de desarrollar sistemas de mapas.")]
      }),
      
      // SALTO DE PÁGINA
      new Paragraph({ children: [new PageBreak()] }),
      
      // 5. TECNOLOGÍAS ACTUALES
      new Paragraph({ 
        heading: HeadingLevel.HEADING_1, 
        children: [new TextRun("5. Tecnologías Actuales para Aplicaciones Web Híbridas")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Las aplicaciones web híbridas combinan tecnologías web estándar (HTML5, CSS3, JavaScript) con capacidades de acceso a funciones nativas de dispositivos móviles y de escritorio. En 2024, el ecosistema de desarrollo híbrido está más maduro que nunca.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("5.1. Stack Tecnológico Backend")]
      }),
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("PHP")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("PHP sigue siendo altamente relevante en 2024, especialmente para aplicaciones web. PHP 8.3 introduce mejoras significativas de rendimiento y características modernas como enums, attributes y readonly properties. Es el lenguaje detrás de WordPress, Laravel y Symfony, que juntos alimentan millones de sitios web.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("Node.js")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Node.js permite usar JavaScript en el backend, facilitando el desarrollo full-stack con un único lenguaje. Es ideal para aplicaciones en tiempo real, APIs REST y microservicios. Frameworks como Express, NestJS y Fastify ofrecen soluciones robustas y escalables.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("Python")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Python con frameworks como Django y Flask es excelente para desarrollo web rápido. Su sintaxis clara y bibliotecas extensas lo hacen ideal para proyectos que requieren análisis de datos o machine learning integrado. FastAPI es especialmente popular para APIs modernas.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("5.2. Stack Tecnológico Frontend")]
      }),
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("JavaScript/TypeScript")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("JavaScript sigue dominando el frontend, con TypeScript ganando terreno por su sistema de tipos. TypeScript mejora la mantenibilidad del código y reduce errores en tiempo de desarrollo, siendo especialmente valioso en proyectos grandes.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("Frameworks Principales")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("React: Biblioteca más popular, con ecosistema masivo. Next.js añade renderizado del lado del servidor (SSR) y generación estática (SSG).")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Vue.js: Framework progresivo con curva de aprendizaje suave. Nuxt.js proporciona funcionalidades avanzadas similares a Next.js.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Angular: Framework completo de Google, ideal para aplicaciones empresariales complejas con arquitectura bien definida.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Svelte: Compila componentes en JavaScript vanilla eficiente, resultando en aplicaciones muy rápidas y con poco overhead.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("5.3. Frameworks para Aplicaciones Híbridas Móviles")]
      }),
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("Ionic")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Ionic permite crear aplicaciones móviles con HTML, CSS y JavaScript. Se integra perfectamente con Angular, React o Vue.js. Incluye componentes UI preconstruidos y acceso a funcionalidades nativas mediante Capacitor o Cordova.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("React Native")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Desarrollado por Facebook, permite crear aplicaciones móviles realmente nativas usando React. Compila a componentes nativos iOS y Android, ofreciendo rendimiento superior a las soluciones basadas en WebView. Usado por Instagram, Facebook y Uber.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("Flutter")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Framework de Google usando lenguaje Dart. Destaca por su sistema de widgets y hot reload que acelera el desarrollo. Compila a código nativo en iOS, Android, Web y Desktop. Rendimiento excepcional y UI consistente multiplataforma.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("5.4. Bases de Datos")]
      }),
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("SQL")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("MySQL/MariaDB: Bases de datos relacionales más populares, ideales para aplicaciones web tradicionales.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("PostgreSQL: Base de datos relacional avanzada con soporte JSON y características enterprise-grade.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("NoSQL")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("MongoDB: Base de datos de documentos flexible, excelente para datos no estructurados.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Redis: Base de datos en memoria ultra-rápida, perfecta para caché y sesiones.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Firebase: Plataforma BaaS de Google con base de datos en tiempo real y autenticación integrada.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("5.5. Herramientas y Ecosistema")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Composer (PHP): Gestor de dependencias estándar para PHP.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("npm/yarn: Gestores de paquetes para JavaScript/Node.js.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Webpack/Vite: Bundlers para optimizar y empaquetar aplicaciones JavaScript.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Docker: Contenerización para entornos consistentes de desarrollo y producción.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Git: Control de versiones indispensable para cualquier proyecto moderno.")]
      }),
      
      // SALTO DE PÁGINA
      new Paragraph({ children: [new PageBreak()] }),
      
      // 6. MEJORAS PROPUESTAS
      new Paragraph({ 
        heading: HeadingLevel.HEADING_1, 
        children: [new TextRun("6. Mejoras Propuestas para la Aplicación Híbrida del Restaurante")]
      }),
      new Paragraph({ 
        spacing: { after: 180 },
        children: [new TextRun("Basándome en el análisis realizado y en las necesidades típicas de un restaurante moderno, propongo las siguientes mejoras para la aplicación web híbrida:")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("6.1. Sistema de Reservas Integrado")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Implementar sistema propio PHP + MySQL para gestión de reservas sin comisiones.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Calendario interactivo mostrando disponibilidad en tiempo real.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Notificaciones automáticas por email y SMS.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Panel administrativo para gestionar reservas y mesas.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Integración con Google Calendar para sincronización.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("6.2. Menú Digital Interactivo")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Catálogo de platos con imágenes de alta calidad y descripciones detalladas.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Filtros por alérgenos, tipo de dieta (vegetariano, vegano, sin gluten).")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Información nutricional de cada plato.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Posibilidad de previsualizar platos en AR usando cámara del dispositivo.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("6.3. Sistema de Pedidos Online")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Pedidos para recoger en local con cálculo de tiempo de preparación.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sistema de delivery con cálculo automático de zonas y costes.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Seguimiento en tiempo real del estado del pedido.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Integración con pasarelas de pago (Stripe, PayPal, Redsys).")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("6.4. Programa de Fidelización")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sistema de puntos por cada visita o pedido.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Descuentos y promociones personalizadas.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Notificaciones push con ofertas especiales.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Tarjeta de fidelidad digital en la app.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("6.5. Integración con Redes Sociales")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Login con Google, Facebook, Apple.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Compartir platos y experiencias en redes sociales.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Feed con fotos de clientes (Instagram-like).")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sistema de reviews y valoraciones.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("6.6. Mejoras en Geolocalización")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Cálculo automático de rutas desde la ubicación del usuario.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Estimación de tiempo de llegada en coche, transporte público y a pie.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Integración con apps de transporte (Uber, Cabify, taxis locales).")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Mapa mostrando parking cercano y paradas de transporte público.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("6.7. Panel de Administración")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Dashboard con estadísticas de reservas, pedidos y visitas.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Gestión de menú con actualización en tiempo real.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sistema de notificaciones para nuevas reservas y pedidos.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Reportes de ventas y análisis de comportamiento de clientes.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Integración con sistema POS del restaurante.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("6.8. Progressive Web App (PWA)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Convertir la aplicación en PWA para funcionar offline.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Instalable en dispositivos móviles sin pasar por App Store.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Notificaciones push nativas.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Caché inteligente para carga rápida.")]
      }),
      
      // SALTO DE PÁGINA
      new Paragraph({ children: [new PageBreak()] }),
      
      // 7. DESARROLLO TEÓRICO DE RESERVAS
      new Paragraph({ 
        heading: HeadingLevel.HEADING_1, 
        children: [new TextRun("7. Desarrollo Teórico: Sistema de Gestión de Reservas")]
      }),
      new Paragraph({ 
        spacing: { after: 180 },
        children: [new TextRun("A continuación se describe cómo implementaría el sistema de gestión de reservas que no fue seleccionado para la demo práctica. Este desarrollo teórico incluye arquitectura, base de datos, flujo de trabajo y código de ejemplo.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("7.1. Arquitectura del Sistema")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("El sistema seguiría una arquitectura MVC (Modelo-Vista-Controlador) implementada en PHP, con las siguientes capas:")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Frontend: HTML5, CSS3, JavaScript (Vue.js o React para interactividad)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Backend: PHP 8.1+ con arquitectura MVC")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Base de datos: MySQL/MariaDB")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Servicios externos: PHPMailer para emails, Twilio para SMS")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("7.2. Diseño de Base de Datos")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("El sistema requeriría las siguientes tablas principales:")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("Tabla: reservations")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("id (INT, PRIMARY KEY, AUTO_INCREMENT)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("customer_name (VARCHAR(100))")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("customer_email (VARCHAR(150))")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("customer_phone (VARCHAR(20))")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("reservation_date (DATE)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("reservation_time (TIME)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("number_of_guests (INT)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("table_id (INT, FOREIGN KEY)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("status (ENUM: 'pending', 'confirmed', 'cancelled', 'completed')")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("special_requests (TEXT)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("created_at (TIMESTAMP)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("updated_at (TIMESTAMP)")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("Tabla: tables")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("id (INT, PRIMARY KEY, AUTO_INCREMENT)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("table_number (VARCHAR(10))")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("capacity (INT)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("location (VARCHAR(50)) -- ej: 'interior', 'terraza', 'ventana'")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("is_available (BOOLEAN)")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("Tabla: time_slots")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("id (INT, PRIMARY KEY, AUTO_INCREMENT)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("date (DATE)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("time (TIME)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("available_tables (INT)")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("is_blocked (BOOLEAN)")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("7.3. Flujo de Trabajo del Sistema")]
      }),
      new Paragraph({ 
        spacing: { before: 120, after: 60 },
        children: [new TextRun({ text: "Proceso de Reserva:", bold: true })]
      }),
      new Paragraph({ 
        numbering: { reference: "numbered-list-1", level: 0 },
        children: [new TextRun("Cliente accede al formulario de reservas en la web")]
      }),
      new Paragraph({ 
        numbering: { reference: "numbered-list-1", level: 0 },
        children: [new TextRun("Selecciona fecha, hora y número de comensales")]
      }),
      new Paragraph({ 
        numbering: { reference: "numbered-list-1", level: 0 },
        children: [new TextRun("Sistema verifica disponibilidad en tiempo real")]
      }),
      new Paragraph({ 
        numbering: { reference: "numbered-list-1", level: 0 },
        children: [new TextRun("Cliente completa datos personales y peticiones especiales")]
      }),
      new Paragraph({ 
        numbering: { reference: "numbered-list-1", level: 0 },
        children: [new TextRun("Sistema valida datos y evita reservas duplicadas")]
      }),
      new Paragraph({ 
        numbering: { reference: "numbered-list-1", level: 0 },
        children: [new TextRun("Se guarda reserva en BD con estado 'pending'")]
      }),
      new Paragraph({ 
        numbering: { reference: "numbered-list-1", level: 0 },
        children: [new TextRun("Se envía email de confirmación al cliente")]
      }),
      new Paragraph({ 
        numbering: { reference: "numbered-list-1", level: 0 },
        children: [new TextRun("Se notifica al restaurante (email/SMS/panel admin)")]
      }),
      new Paragraph({ 
        numbering: { reference: "numbered-list-1", level: 0 },
        children: [new TextRun("Restaurante confirma reserva (estado cambia a 'confirmed')")]
      }),
      new Paragraph({ 
        numbering: { reference: "numbered-list-1", level: 0 },
        children: [new TextRun("Sistema envía recordatorio 24h antes de la reserva")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("7.4. Implementación de Funcionalidades Clave")]
      }),
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("Verificación de Disponibilidad")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("El sistema debe verificar que no existan conflictos de horarios. Se implementaría una función PHP que consulta reservas existentes para la fecha y hora solicitada, considerando el tiempo promedio de ocupación de mesas (aproximadamente 2 horas).")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("Sistema de Notificaciones")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Se utilizaría PHPMailer para enviar emails automáticos en los siguientes eventos: confirmación de reserva, modificación de reserva, cancelación, recordatorio 24h antes. Opcionalmente, se podría integrar Twilio para SMS.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("Calendario Interactivo")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("El frontend mostraría un calendario usando una librería JavaScript como FullCalendar.js, que permite visualizar la disponibilidad y gestionar reservas de forma visual. Las celdas se colorearían según disponibilidad: verde (disponible), amarillo (pocas mesas), rojo (completo).")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_3, 
        children: [new TextRun("Panel de Administración")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Un panel backend permitiría al personal del restaurante: ver todas las reservas del día/semana/mes, confirmar o cancelar reservas, modificar detalles, bloquear horarios específicos, ver estadísticas de ocupación, exportar datos a Excel/PDF.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("7.5. Consideraciones de Seguridad")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Validación de entradas: Sanitizar todos los datos del formulario con filter_var() y htmlspecialchars()")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Prepared Statements: Usar PDO con prepared statements para evitar inyecciones SQL")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("CSRF Protection: Implementar tokens CSRF en formularios")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Rate Limiting: Limitar número de reservas por IP para evitar spam")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("HTTPS: Forzar conexiones seguras para proteger datos personales")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Autenticación: Sistema de login seguro para panel de administración")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("7.6. Mejoras Futuras")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Integración con Google Calendar para sincronización automática")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sistema de lista de espera cuando no hay disponibilidad")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Recordatorios automáticos por WhatsApp usando API de Twilio")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Sistema de pre-pedido: permitir solicitar platos al hacer la reserva")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Analytics: estadísticas de ocupación y patrones de reserva")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("API REST para integración con aplicaciones móviles")]
      }),
      
      // SALTO DE PÁGINA
      new Paragraph({ children: [new PageBreak()] }),
      
      // 8. CONCLUSIÓN
      new Paragraph({ 
        heading: HeadingLevel.HEADING_1, 
        children: [new TextRun("8. Conclusión")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Con el desarrollo de esta tarea he aprendido las ventajas y características de las aplicaciones web híbridas, así como la importancia de seleccionar las tecnologías adecuadas según las necesidades específicas del proyecto y el presupuesto disponible.")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("La implementación práctica de la demo de geolocalización con Leaflet.js y OpenStreetMap ha demostrado que es posible crear soluciones profesionales y funcionales sin incurrir en costes de APIs comerciales. La demo desarrollada ofrece todas las funcionalidades esenciales que un restaurante necesita para mostrar su ubicación de forma atractiva e interactiva.")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("El estudio comparativo realizado ha revelado que, si bien Google Maps y Mapbox ofrecen características más avanzadas, para casos de uso como el de un restaurante pequeño o mediano, las soluciones open source como Leaflet.js son más que suficientes y eliminan la dependencia de servicios de pago.")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("En cuanto a los sistemas de gestión de reservas, el análisis ha mostrado que las plataformas existentes como OpenTable y TheFork son excelentes para acceder a una red amplia de clientes potenciales, pero implican costes recurrentes significativos. Para restaurantes que están comenzando o que operan con márgenes ajustados, desarrollar un sistema propio puede ser más rentable a largo plazo.")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun({ text: "Mi opinión personal", bold: true, italics: true }), new TextRun({ text: " es que las aplicaciones web híbridas representan el futuro del desarrollo web, especialmente considerando el auge de los dispositivos móviles y la necesidad de ofrecer experiencias multiplataforma consistentes. La capacidad de reutilizar código y integrar servicios externos mediante APIs permite a los desarrolladores crear aplicaciones sofisticadas en menos tiempo y con menos recursos.", italics: true })]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Esta tarea ha consolidado mi comprensión sobre arquitecturas de aplicaciones web modernas, el modelo MVC, la integración de APIs RESTful, y la importancia de elegir el stack tecnológico apropiado. Los conocimientos adquiridos son directamente aplicables en proyectos reales y me preparan mejor para el desarrollo profesional de aplicaciones web.")]
      }),
      new Paragraph({ 
        spacing: { after: 120 },
        children: [new TextRun("Finalmente, destaco la importancia de mantener un equilibrio entre funcionalidad, coste y complejidad. No siempre la solución más cara o compleja es la mejor; a menudo, soluciones más simples y económicas pueden satisfacer perfectamente las necesidades del cliente manteniendo la calidad y el rendimiento.")]
      }),
      
      // SALTO DE PÁGINA
      new Paragraph({ children: [new PageBreak()] }),
      
      // 9. BIBLIOGRAFÍA
      new Paragraph({ 
        heading: HeadingLevel.HEADING_1, 
        children: [new TextRun("9. Bibliografía")]
      }),
      new Paragraph({ 
        spacing: { after: 180 },
        children: [new TextRun("Fuentes consultadas para la realización de esta tarea:")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("Documentación Oficial")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Leaflet.js Documentation. (2024). Disponible en: https://leafletjs.com/")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("OpenStreetMap Wiki. (2024). Disponible en: https://wiki.openstreetmap.org/")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Google Maps Platform Documentation. (2024). Disponible en: https://developers.google.com/maps")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Mapbox Documentation. (2024). Disponible en: https://docs.mapbox.com/")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("PHP Manual. (2024). Disponible en: https://www.php.net/manual/es/")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("Artículos y Recursos Web")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("SoftKraft. (2025). Mapbox vs Google Maps — What are the differences? Disponible en: https://www.softkraft.co/mapbox-vs-google-maps/")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Relevant Software. (2025). Mapbox Vs Google Maps VS OpenStreetMap APIs. Disponible en: https://relevant.software/blog/choosing-a-map-amapbox-google-maps-openstreetmap/")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("AltexSoft. (2022). Online Restaurant Reservation Systems: APIs for Location Discovery. Disponible en: https://www.altexsoft.com/blog/online-restaurant-reservation-landscape/")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Italia Delight. (2024). Top 10 restaurant reservation software in 2024.")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("LogRocket. (2024). 5 JavaScript mapping APIs compared.")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("Recursos Académicos")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Apuntes de la Unidad UT08 - Aplicaciones Web Híbridas en el aula virtual de Campus")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Presentación de clase: Plataformas de programación web en entorno servidor")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Material didáctico del módulo Desarrollo Web en Entorno Servidor (DWS)")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("Sitios Web Consultados")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("OpenTable Official Website. https://www.opentable.com/")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("TheFork Manager. https://www.theforkmanager.com/")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Mapbox Pricing. https://www.mapbox.com/pricing")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Google Maps Platform Pricing. https://mapsplatform.google.com/pricing/")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Restaurantes en Tenerife. https://www.webtenerife.com/que-hacer/comer-y-beber/restaurantes/")]
      }),
      
      new Paragraph({ 
        heading: HeadingLevel.HEADING_2, 
        children: [new TextRun("Bibliotecas y Frameworks")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Composer - Dependency Manager for PHP. https://getcomposer.org/")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("Leaflet.js - Open-source JavaScript library for mobile-friendly interactive maps. https://leafletjs.com/")]
      }),
      new Paragraph({ 
        numbering: { reference: "bullet-list", level: 0 },
        children: [new TextRun("PHP Geocoder Library by willdurand. https://github.com/geocoder-php/Geocoder")]
      }),
      
      new Paragraph({ 
        spacing: { before: 360 },
        alignment: AlignmentType.CENTER,
        children: [new TextRun({ text: "* * *", size: 28 })]
      }),
      new Paragraph({ 
        spacing: { before: 120 },
        alignment: AlignmentType.CENTER,
        children: [new TextRun({ text: "Fin del Informe", size: 22, italics: true })]
      })
    ]
  }]
});

// Guardar el documento
Packer.toBuffer(doc).then(buffer => {
  fs.writeFileSync("/home/claude/restaurante-demo/2DAW_DWS_Daldo_UT08_Tarea01.docx", buffer);
  console.log("Documento creado exitosamente");
});
