<?php

function loadEnv($path)
{
        if (!file_exists($path)) {
                return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                        continue;
                }
                [$name, $value] = explode('=', $line, 2);
                $_ENV[trim($name)] = trim($value);
        }
}

loadEnv(__DIR__ . '/../../../Config/.env');

define('BASE_URL', $_ENV['BASE_URL'] ?? 'http://localhost/Projects/PHPLearning_In_CA/PHPLearning/');
define('APP_NAME', $_ENV['APP_NAME'] ?? 'Unipro');
?>