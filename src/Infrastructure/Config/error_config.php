<?php

/**
 * ~ Configuración de errores para desarrollo...
 *
 * ~ Este archivo habilita la visualización de todos los errores PHP
 * ~ durante el desarrollo. NO usar en producción...
 */

// * Definir el entorno (development, production)...
define('APP_ENV', 'development');

// * Habilitar visualización de errores solo en desarrollo...
if (APP_ENV === 'development') {
        // > Mostrar todos los errores...
        error_reporting(E_ALL);

        // > Mostrar errores en pantalla...
        ini_set('display_errors', '1');

        // > Mostrar también errores de inicio (startup errors)...
        ini_set('display_startup_errors', '1');

        // > Formato de errores detallado...
        ini_set('html_errors', '1');

        // > Mostrar errores incluso si hay @ (error suppression)...
        ini_set('ignore_repeated_errors', '0');

        // > Log de errores (opcional, también se guardan en archivo)...
        ini_set('log_errors', '1');

        // > Ruta del archivo de log de errores...
        $logPath = __DIR__ . '/../../../logs/php_errors.log';
        $logDir = dirname($logPath);

        // > Crear directorio de logs si no existe...
        if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
        }

        ini_set('error_log', $logPath);
} else {
        // > En producción: ocultar errores...
        error_reporting(0);
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');
}

?>