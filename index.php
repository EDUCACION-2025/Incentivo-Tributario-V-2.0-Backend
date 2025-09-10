<?php

/*=================================
=      Mostrar errores  prueba    =
=================================*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*=================================
=            Cabeceras            =
=================================*/

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Authorization");
header("Content-Type: text/html; charset=utf-8");
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

/*=====  End of Cabeceras  ======*/

/*=================================
=        Comprobar errores        =
=================================*/

// Opcional: si el método es OPTIONS, no procesar más
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

session_start();

try {
    require 'src/Routes/index.php';
} catch (Throwable $e) {
    http_response_code(500);
    echo "<pre>";
    echo "ERROR: " . $e->getMessage() . "\n\n";
    echo $e->getTraceAsString();
    echo "</pre>";
    exit;
}
