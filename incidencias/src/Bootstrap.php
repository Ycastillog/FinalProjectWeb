<?php
namespace App;

class Bootstrap {
    public static function env(string $key, $default = null) {
        static $env = null;
        if ($env === null) {
            $env = [];
            $file = dirname(__DIR__) . '/.env';
            if (is_file($file)) {
                foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                    if (str_starts_with(trim($line), '#')) continue;
                    [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
                    $env[trim($k)] = trim($v);
                }
            }
        }
        return $env[$key] ?? $default;
    }
}

