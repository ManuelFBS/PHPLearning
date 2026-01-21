<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Infrastructure\Container;
use Presentation\Controllers\AuthController;
use Presentation\Controllers\UserController;

// * Obtener la página solicitada...
$page = $_GET['page'] ?? 'login';

// * Lógica del logout...
if ($page === 'logout') {
        session_destroy();
        header('Location: ?page=login');
}

// * Verificar autenticación...
if (!isset($_SESSION['user_id']) && $page !== 'login') {
        header('Location: ?page=login');
        exit();
}

// * Cargar vistas...
if ($page === 'login') {
        // > Obtener el controlador desde el Container...
        $authController = Container::getAuthController();

        // > Procesar login si es POST...
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = $authController->login(
                        $_POST['user'] ?? '',
                        $_POST['password'] ?? ''
                );
                if ($result['success']) {
                        header('Location: ?page=dashboard');
                        exit();
                } else {
                        $error = $result['message'];
                }
        }

        // include __DIR__."/../"
} else {
        //
}

?>
