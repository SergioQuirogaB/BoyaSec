<?php

$config = require __DIR__ . '/env.php';
$GLOBALS['config'] = $config;
date_default_timezone_set($config['app']['timezone'] ?? 'UTC');

/**
 * Devuelve una única instancia PDO para el resto de la aplicación.
 */
function get_pdo(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $db = $GLOBALS['config']['db'];
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $db['host'],
            $db['port'],
            $db['database'],
            $db['charset']
        );

        $pdo = new PDO($dsn, $db['username'], $db['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    return $pdo;
}

