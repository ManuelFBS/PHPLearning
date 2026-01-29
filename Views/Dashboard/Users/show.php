<?php

// ~ Cargar el autoload de Composer (si no está ya cargado)...
require_once __DIR__ . '/../../../vendor/autoload.php';

// ~ Importar las clases necesarias usando namespaces...
use Infrastructure\Container;

// * Se obtiene el user desde la URL (ejemplo: ?page=users_show&userName=usuarioabcd)...
$userName = $_GET['user'] ?? '';

// * Si NO hay user, se muestra un error...
if (empty($userName)) {
        echo '<div class="alert alert-danger">Usuario no proporcionado...</div>';
        exit;
}

// * Obtener el controlador desde el Container (Inyección de Dependencias)...
$controller = Container::getUserController();

// * Se llama al controlador para obtener los datos del profesor (es un método
// * de instancia, no estático)...
$response = $controller->show($userName);

// * Se extrae la información...
$status = $response['status'];
$message = $response['message'];
$user = $response['data'] ?? null;

?>

<div  class="container">
        <div class="row justify-content-center">
                <div class="col-md-8">
                        <div class="card shadow">
                                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                        <h4 class="mb=0">Detalles del Usuario</h4>
                                        <a href="?page=Users/list" class="btn btn-light btn-sm">
                                                <i class="bi bi-arrow-left"></i> Volver al listado
                                        </a>
                                </div>
                                <div class="card-body">
                                        <?php if ($status === 'error'): ?>
                                                <div class="alert alert-danger">
                                                        <?php echo htmlspecialchars($message); ?>
                                                </div>
                                        <?php elseif ($user): ?>
                                                <!-- Se muestran los datos del usuario en formato de tarjeta -->
                                                <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">DNI:</div>
                                                        <div class="col-md-8"><?php echo htmlspecialchars($user->getDni()); ?></div>
                                                </div>
                                                <hr>
                                                <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Usuario:</div>
                                                        <div class="col-md-8"><?php echo htmlspecialchars($user->getUsername()); ?></div>
                                                </div>
                                                <hr>
                                                <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Rol:</div>
                                                        <div class="col-md-8"><?php echo htmlspecialchars($user->getRole()); ?></div>
                                                </div>
                                                <hr>
                                                <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Creado:</div>
                                                        <div class="col-md-8">
                                                                <?php
                                                                // * Formateamos la fecha de yyyy-mm-dd a dd/mm/YY...
                                                                $created = $user->getCreatedAt();
                                                                $formattedDate = $created->format('d/m/Y');
                                                                echo htmlspecialchars($formattedDate);
                                                                ?>
                                                        </div>
                                                </div>
                                                <hr>
                                                <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Actualizado:</div>
                                                        <div class="col-md-8">
                                                                <?php
                                                                // * Formateamos la fecha de yyyy-mm-dd a dd/mm/YY...
                                                                $updated = $user->getUpdatedAt();
                                                                $formattedDate = $updated->format('d/m/Y');
                                                                echo htmlspecialchars($formattedDate);
                                                                ?>
                                                        </div>
                                                </div>
                                                <hr>
                                                <div class="text-end mt-4">
                                                        <a href="?page=Users/edit&user=<?php echo urlencode($user->getUsername()); ?>" class="btn btn-warning">
                                                                <i class="bi bi-pencil"></i> Editar
                                                        </a>
                                                        <a href="?page=Users/list" class="btn btn-secondary">Volver</a>
                                                </div>
                                        <?php endif; ?>
                                </div>
                        </div>
                </div>
        </div>
</div>