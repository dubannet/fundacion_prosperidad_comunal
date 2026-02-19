<?php
// fundacion_core/test_db.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// 1. Cargamos el bootstrap (él se encarga de cargar el autoload y el .env)
require_once __DIR__ . '/config/bootstrap.php';

use App\Config\Database;
use App\Models\Proyecto;

try {
    echo "<h2>--- Prueba de Conexión ---</h2>";

    // 2. Intentamos obtener la conexión PDO a través del Singleton
    $db = Database::getInstance()->getConnection();
    echo "Conexion a la base de datos establecida exitosamente.<br>";

    // 3. Probamos un Modelo (Proyecto) para verificar que el Namespace y Autoload funcionen
    $proyectoModel = new Proyecto();
    $tabla = $proyectoModel->getTable();
    
    echo "El modelo Proyecto se cargó correctamente (Tabla vinculada: <b>$tabla</b>).<br>";

    // 4. Intentamos una consulta simple
    $stmt = $db->query("SELECT DATABASE() as db");
    $nombreDB = $stmt->fetchColumn();
    echo "Estás conectado a la base de datos: <b>" . $nombreDB . "</b><br>";

} catch (\Exception $e) {
    echo "❌ <b>Error:</b> " . $e->getMessage() . "<br>";
}