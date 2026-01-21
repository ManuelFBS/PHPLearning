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

        include __DIR__ . '/../Views/Auth/login.php';
} else {
        // > Cargar el layout del dashboard...
        include __DIR__ . '/../Views/Layouts/header.php';
        include __DIR__ . '/../Views/Layouts/sidebar.php';
        echo '<div class="content p-4">';

        // > Buscar la vista...
        $possiblePaths = [
                __DIR__ . "/../Views/Dashboard/Professors/{$page}.php",
                __DIR__ . "/../Views/Dashboard/Students/{$page}.php",
                __DIR__ . "/../Views/Dashboard/Users/{$page}.php",
                __DIR__ . "/../Views/Dashboard/{$page}.php",
        ];

        $viewPath = null;
        foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                        $viewPath = $path;
                        break;
                }
        }

        if ($viewPath) {
                // > Inyectar controladores en las vistas si es necesario...
                $userController = Container::getUserController();
                include $viewPath;
        } else {
                echo '<h2>Bienvenido al sistema Unipro 1.0</h2><p>Selecciona una opción del menú.</p>';
        }

        echo '</div>';

        include __DIR__ . '/../Views/Layouts/footer.php';
}

?>
