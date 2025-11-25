<?php

/**
 * Archivo de configuración sencillo para centralizar las variables
 * sensibles del entorno. En producción se recomienda cargar estos datos
 * desde variables de entorno del sistema operativo.
 */
return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3309,
        'database' => 'qaa',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'name' => 'BoyaSec SIEM Lite',
        'timezone' => 'UTC',
    ],
];

