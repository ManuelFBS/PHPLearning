<?php

// ~ Cargar el autoload de Composer (si no está ya cargado)
require_once __DIR__ . '/../../../vendor/autoload.php';

// ~ Importar las clases necesarias usando 'namespaces'...

use Domain\Entities\Student;
use Infrastructure\Container;

// * Se obtiene el DNI desde la URL...
$dni = $_GET['dni'] ?? '';

// * Si no hay DNI, mostramos un error...
if (empty($dni)) {
        echo '<div class="alert alert-danger">DNI no proporcionado.</div>';
        exit;
}

// * Obtener el controlador desde el Container (Inyección de Dependencias)...
$controller = Container::getStudentController();

// * Primero se obtienen los datos actuales del estudiante para llenar el
// * formulario (es un método de instancia, no estático)...
$response = $controller->show($dni);
$student = $response['data'] ?? null;

// * Si no existe el estudiante, se muestra un error...
if (!$student) {
        echo '<div class="alert alert-danger">Estudiante no encontrado...</div>';
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
                $student = $response['data'] ?? null;
        }
}

?>

<div class="container">
        <div class="row justify-content-center">
                <div class="col-md-8">
                        <div class="card shadow">
                                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">Editar Estudiante</h4>
                                        <a href="?page=Students/list" class="btn btn-light btn-sm">
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
                                                        <input type="text" value="<?php echo htmlspecialchars($student->getDni()); ?>" class="form-control" readonly>
                                                        <small class="text-muted">El DNI no se puede modificar.</small>
                                                </div>
                                                <div class="col-md-6">
                                                        <label class="form-label">Correo Electrónico</label>
                                                        <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($student->getEmail()); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                        <label class="form-label">Nombres</label>
                                                        <input type="text" name="names" class="form-control" required value="<?php echo htmlspecialchars($student->getNames()); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                        <label class="form-label">Apellidos</label>
                                                        <input type="text" name="lastNames" class="form-control" required value="<?php echo htmlspecialchars($student->getLastNames()); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                        <label class="form-label">Fecha de Nacimiento</label>
                                                        <?php
                                                        $bd = $student->getBirthDate();
                                                        $birthDateValue = $bd ? $bd->format('Y-m-d') : '';
                                                        ?>
                                                        <input type="date" name="birthDate" class="form-control" 
                                                                required value="<?php echo htmlspecialchars($birthDateValue); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                        <label class="form-label">Teléfono</label>
                                                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($student->getPhone() ?? ''); ?>">
                                                </div>
                                                <div class="col-12">
                                                        <label class="form-label">Fecha de Ingreso</label>
                                                        <?php
                                                        $de = $student->getDateEntry();
                                                        $dateEntryValue = $de ? $de->format('Y-m-d') : '';
                                                        ?>
                                                        <input type="text" name="dateentry" class="form-control" 
                                                                required value="<?php echo htmlspecialchars($dateEntryValue); ?>">
                                                </div>
                                                <div class="col-12">
                                                        <label class="form-label">Materias que cursará</label>
                                                        <input type="text" name="subjects" class="form-control" value="<?php echo htmlspecialchars($student->getSubjects() ?? ''); ?>" placeholder="Ej: PHP, MySQL, JavaScript">
                                                </div>
                                                <div class="col-12">
                                                        <label class="form-label">Semestre</label>
                                                        <input type="number" name="semester" class="form-control" value="<?php echo htmlspecialchars($student->getSemester() ?? ''); ?>">
                                                </div>
                                                <div class="col-12 text-end mt-4">
                                                        <a href="?page=Students/list" class="btn btn-secondary">Cancelar</a>
                                                        <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
                                                </div>
                                        </form>
                                </div>
                        </div>
                </div>
        </div>
</div>