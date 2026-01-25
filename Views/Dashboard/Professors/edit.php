<?php

// ~ Cargar el autoload de Composer (si no está ya cargado)
require_once __DIR__ . '/../../../vendor/autoload.php';

// ~ Importar las clases necesarias usando namespaces
use Infrastructure\Container;

// * Se obtiene el DNI desde la URL...
$dni = $_GET['dni'] ?? '';

// * Si no hay DNI, mostramos un error...
if (empty($dni)) {
        echo '<div class="alert alert-danger">DNI no proporcionado.</div>';
        exit;
}

// * Obtener el controlador desde el Container (Inyección de Dependencias)...
$controller = Container::getProfessorController();

// * Primero se obtienen los datos actuales del profesor para llenar el formulario
// * (es un método de instancia, no estático)...
$response = $controller->show($dni);
$professor = $response['data'] ?? null;

// * Si no existe el profesor, se muestra un error...
if (!$professor) {
        echo '<div class="alert alert-danger">Profesor no encontrado...</div>';
        exit;
}

// * Se procesa el formulario si se envió por POST...
$updateResponse = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // > Usar el controlador de instancia, no estático...
        $updateResponse = $controller->update($dni);
        // > Si la actualización fue exitosa, se recargan los datos...
        if ($updateResponse['status'] === 'success') {
                $response = $controller->show($dni);
                $professor = $response['data'] ?? null;
        }
}

?>

<div class="container">
        <div class="row justify-content-center">
                <div class="col-md-8">
                        <div class="card shadow">
                                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">Editar Profesor</h4>
                                        <a href="?page=Professors/list" class="btn btn-light btn-sm">
                                                <i class="bi bi-arrow-left"></i> Volver
                                        </a>
                                </div>
                                <div class="card-body">
                                        <?php if ($updateResponse): ?>
                                                <div class="alert alert-<?= $updateResponse['status'] === 'success' ? 'success' : 'danger' ?>">
                                                        <?= htmlspecialchars($updateResponse['message']) ?>
                                                </div>
                                        <?php endif; ?>

                                        <form method="POST" class="row g-3">
                                                <!-- DNI (solo lectura, no se puede editar) -->
                                                <div class="col-md-6">
                                                        <label class="form-label">DNI</label>
                                                        <input type="text" value="<?php echo htmlspecialchars($professor['dni']); ?>" class="form-control" readonly>
                                                        <small class="text-muted">El DNI no se puede modificar.</small>
                                                </div>
                                                <div class="col-md-6">
                                                        <label class="form-label">Correo Electrónico</label>
                                                        <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($professor['email']); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                        <label class="form-label">Nombres</label>
                                                        <input type="text" name="names" class="form-control" required value="<?php echo htmlspecialchars($professor['names']); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                        <label class="form-label">Apellidos</label>
                                                        <input type="text" name="lastNames" class="form-control" required value="<?php echo htmlspecialchars($professor['lastNames']); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                        <label class="form-label">Fecha de Nacimiento</label>
                                                        <input type="date" name="birthDate" class="form-control" required value="<?php echo htmlspecialchars($professor['birthDate']); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                        <label class="form-label">Teléfono</label>
                                                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($professor['phone'] ?? ''); ?>">
                                                </div>
                                                <div class="col-12">
                                                        <label class="form-label">Materias que dictará</label>
                                                        <input type="text" name="subjects" class="form-control" value="<?php echo htmlspecialchars($professor['subjects'] ?? ''); ?>" placeholder="Ej: PHP, MySQL, JavaScript">
                                                </div>
                                                <div class="col-12 text-end mt-4">
                                                        <a href="?page=Professors/list" class="btn btn-secondary">Cancelar</a>
                                                        <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
                                                </div>
                                        </form>
                                </div>
                        </div>
                </div>
        </div>
</div>