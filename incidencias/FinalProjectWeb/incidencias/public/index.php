<?php
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative = substr($class, $len);
    $file = __DIR__ . '/../src/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) require $file;
});

$requested = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath  = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$path = substr($requested, strlen($basePath));
if ($path === false) $path = '/';
$path = '/' . ltrim($path, '/');

require __DIR__ . '/../src/Router.php';
$router = new \App\Router();
$router->run($path);
