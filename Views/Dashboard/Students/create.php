<?php

require_once __DIR__ . '/../../../App/Controllers/StudentController.php';

// ~ Solo el Admin puede estar aquí...
if ($_SESSION['user_role'] !== 'Admin') {
    echo '<div class="alert alert-danger">Acceso restringido. Solo administradores pueden ver esta página.</div>';
    exit();
}

$controller = new StudentController();
$response = $controller->store();

?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Registrar Nuevo Estudiante</h4>
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
                            <input type="email" name="email" class="form-control" required placeholder="estudiante@unipro.com">
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
                            <label class="form-label">Fecha de Ingreso</label>
                            <input type="date" name="dateEntry" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Semestre</label>
                            <input type="number" name="semester" class="form-control" required min="1" max="10" placeholder="Ej: 1, 2, 3...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Materias que cursará</label>
                            <input type="text" name="willTakeSubjects" class="form-control" placeholder="Ej: PHP, MySQL, JavaScript">
                        </div>
                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">Guardar Estudiante</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>