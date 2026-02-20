<?php
// /fundacion_core/config/bootstrap.php

// 1. Definir la ruta raíz física del proyecto (donde está el núcleo)
define('BASE_PATH', dirname(__DIR__)); 

// 2. Cargar el Autoloader de Composer (Carga PHPMailer, Dotenv y tus clases)
require_once BASE_PATH . '/vendor/autoload.php';

// 3. Cargar variables de entorno desde el archivo .env
if (file_exists(BASE_PATH . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
}

// 4. Configuración dinámica de la URL
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];

// Detectar si estamos en localhost para incluir la subcarpeta
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$publicPath = '/';

if ($host === 'localhost') {
    // Si estás en localhost, extrae la ruta hasta public_html
    // Esto detectará "/subdominio/public_html" automáticamente
    $pos = strpos($scriptName, '/public_html/');
    if ($pos !== false) {
        $publicPath = substr($scriptName, 0, $pos + 13); // +13 para incluir 'public_html/'
    }
}

define('URL_BASE', rtrim($protocol . $host . $publicPath, '/'));

// 5. Configuración de zona horaria
date_default_timezone_set('America/Bogota'); // Ajusta a tu ciudad

// 6. Constantes para subida de archivos
define('UPLOAD_DIR_PATH', BASE_PATH . '/shared_uploads/');

// Cambia 'fundacion.org' por tu dominio real o 'localhost/subdominio/public_html'
//define('URL_UPLOADS', 'http://fundacion.local/uploads/');

// Cambia 'fundacion.org' por tu dominio real o 'localhost/subdominio/public_html'
//define('URL_UPLOADS', 'localhost/subdominio/public_html/uploads/');
define('URL_UPLOADS', URL_BASE . '/uploads/');