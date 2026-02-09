<?php
/**
 * Operaciones.php
 * Clase que contiene las operaciones del servicio web SOAP
 * 
 * IMPORTANTE: Este archivo esta preparado para php2wsdl
 * Cada metodo tiene las anotaciones necesarias (@param, @return, @soap)
 */

/**
 * Clase Operaciones para el servicio web SOAP
 * Gestiona consultas de PVP y stock de productos
 */
class Operaciones
{
    /**
     * Carga los datos de la tienda desde el archivo correspondiente.
     * Este es un método de ayuda privado para evitar el uso de variables globales.
     * 
     * @return array Un array que contiene los datos de 'productos' y 'stock'.
     */
    private static function cargarDatos()
    {
        // Incluimos el archivo que define las variables $productos y $stockTiendas
        require __DIR__ . '/DatosTienda.php';
        return ['productos' => $productos, 'stock' => $stockTiendas];
    }

    /**
     * Obtiene el PVP de un producto dado su codigo
     * 
     * @param string $codigoProducto Codigo del producto a consultar
     * @return float PVP del producto en euros
     * @soap
     */
    public function getPVP($codigoProducto)
    {
        $datos = self::cargarDatos();
        $productos = $datos['productos'];
        
        if (isset($productos[$codigoProducto])) {
            return (float)$productos[$codigoProducto]['pvp'];
        }
        
        return 0.0;
    }
    
    /**
     * Obtiene el stock de un producto en una tienda especifica
     * 
     * @param string $codigoProducto Codigo del producto a consultar
     * @param string $codigoTienda Codigo de la tienda donde consultar
     * @return int Unidades disponibles en stock
     * @soap
     */
    public function getStock($codigoProducto, $codigoTienda)
    {
        $datos = self::cargarDatos();
        $productos = $datos['productos'];
        $stockTiendas = $datos['stock'];
        
        if (!isset($productos[$codigoProducto])) {
            return 0;
        }
        
        if (!isset($stockTiendas[$codigoTienda])) {
            return 0;
        }
        
        if (isset($stockTiendas[$codigoTienda]['stock'][$codigoProducto])) {
            return (int)$stockTiendas[$codigoTienda]['stock'][$codigoProducto];
        }
        
        return 0;
    }
}