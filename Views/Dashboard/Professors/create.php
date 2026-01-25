<?php

// ~ Cargar el autoload de Composer (si no está ya cargado)...
require_once __DIR__ . '/../../../vendor/autoload.php';

// ~ Importar las clases necesarias usando namespaces...
use Infrastructure\Container;

// ~ Solo el Admin puede estar aquí...
if ($_SESSION['user_role'] !== 'Admin') {
    echo '<div class="alert alert-danger">Acceso restringido. Solo administradores pueden ver esta página.</div>';
    exit();
}

// ~ Obtener el controlador desde el Container (Inyección de Dependencias)...
$controller = Container::getProfessorController();

// ~ Solo procesar el formulario si es POST...
$response = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = $controller->store();
}

?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Registrar Nuevo Profesor</h4>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($response)): ?>
                        <div class="alert alert-<?= $response['status'] === 'success' ? 'success' : 'danger' ?>">
                            <?= $response['message'] ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">DNI</label>
                            <input type="text" name="dni" class="form-control" required placeholder="Ej: 12345678">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" required placeholder="profe@unipro.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombres</label>
                            <input type="text" name="names" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellidos</label>
                            <input type="text" name="lastNames" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="birthDate" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Materias que dictará</label>
                            <input type="text" name="subjects" class="form-control" placeholder="Ej: PHP, MySQL, JavaScript">
                        </div>
                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">Guardar Profesor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>