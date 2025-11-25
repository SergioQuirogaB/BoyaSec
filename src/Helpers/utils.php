<?php

session_start();

function base_path(string $path = ''): string
{
    $root = dirname(__DIR__, 2);
    return $path ? $root . '/' . ltrim($path, '/') : $root;
}

require_once base_path('config/database.php');

spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../';
    $classPath = str_replace('\\', '/', $class);
    $file = $baseDir . $classPath . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

function is_authenticated(): bool
{
    return isset($_SESSION['user_id']);
}

function ensure_auth(): void
{
    if (!is_authenticated()) {
        header('Location: /index.php');
        exit;
    }
}

function flash(string $key, ?string $message = null)
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return;
    }

    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

