<?php

// ~ Cargar el autoload de Composer (si no está ya cargado)
require_once __DIR__ . '/../../../vendor/autoload.php';

// ~ Importar las clases necesarias usando namespaces
use Infrastructure\Container;

// * Se obtiene el DNI desde la URL (ejemplo: ?page=professors_show&dni=12345678)...
$dni = $_GET['dni'] ?? '';

// * Si NO hay DNI, se muestra un error...
if (empty($dni)) {
        echo '<div class="alert alert-danger">DNI no proporcionado...</div>';
        exit;
}

// * Obtener el controlador desde el Container (Inyección de Dependencias)...
$controller = Container::getProfessorController();

// * Se llama al controlador para obtener los datos del profesor (es un método de instancia, no estático)...
$response = $controller->show($dni);

// * Se extrae la información...
$status = $response['status'];
$message = $response['message'];
$professor = $response['data'] ?? null;

?>

<div class="container">
        <div class="row justify-content-center">
                <div class="col-md-8">
                        <div class="card shadow">
                                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                        <h4 class="mb=0">Detalles del Professor</h4>
                                        <a href="?page=Professors/list" class="btn btn-light btn-sm">
                                                <i class="bi bi-arrow-left"></i> Volver al listado
                                        </a>
                                </div>
                                <div class="card-body">
                                        <?php if ($status === 'error'): ?>
                                                <div class="alert alert-danger">
                                                        <?php echo htmlspecialchars($message); ?>
                                                </div>
                                        <?php elseif ($professor): ?>
                                                <!-- Se muestran los datos del profesor en formato de tarjeta -->
                                                <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">DNI:</div>
                                                        <div class="col-md-8"><?php echo htmlspecialchars($professor->getDni()); ?></div>
                                                </div>
                                                <hr>
                                                <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Nombres:</div>
                                                        <div class="col-md-8"><?php echo htmlspecialchars($professor->getNames()); ?></div>
                                                </div>
                                                <hr>
                                                <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Apellidos:</div>
                                                        <div class="col-md-8"><?php echo htmlspecialchars($professor->getLastNames()); ?></div>
                                                </div>
                                                <hr>
                                                <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Fecha de Nacimiento:</div>
                                                        <div class="col-md-8">
                                                                <?php
                                                                // * Formateamos la fecha de yyyy-mm-dd a dd/mm/yyyy...
                                                                $birthDate = $professor->getBirthDate();
                                                                $formattedDate = $birthDate->format('d/m/y');
                                                                echo htmlspecialchars($formattedDate);
                                                                ?>
                                                        </div>
                                                </div>
                                                <hr>
                                                <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Email:</div>
                                                        <div class="col-md-8"><?php echo htmlspecialchars($professor->getEmail()); ?></div>
                                                </div>
                                                <hr>
                                                <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Teléfono:</div>
                                                        <div class="col-md-8"><?php echo htmlspecialchars($professor->getPhone() ?? 'No especificado'); ?></div>
                                                </div>
                                                <hr>
                                                <div class="row mb-3">
                                                        <div class="col-md-4 fw-bold">Materias:</div>
                                                        <div class="col-md-8"><?php echo htmlspecialchars($professor->getSubjects() ?? 'No especificado'); ?></div>
                                                </div>
                                                <hr>
                                                <div class="text-end mt-4">
                                                        <a href="?page=Professors/edit&dni=<?php echo urlencode($professor->getDni()); ?>" class="btn btn-warning">
                                                                <i class="bi bi-pencil"></i> Editar
                                                        </a>
                                                        <a href="?page=Professors/list" class="btn btn-secondary">Volver</a>
                                                </div>
                                        <?php endif; ?>
                                </div>
                        </div>
                </div>
        </div>
</div>