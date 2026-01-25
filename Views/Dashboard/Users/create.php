<?php

require_once __DIR__ . '/../../../App/Controllers/UserController.php';

// ~ Solo el Admin puede estar aquí...
if ($_SESSION['user_role'] !== 'Admin') {
    echo '<div class="alert alert-danger">Acceso restringido. Solo administradores pueden ver esta página.</div>';
    exit();
}

// * Llamar al controlador (método estático)
$response = UserController::store();

?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Registrar Nuevo Usuario</h4>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($response)): ?>
                        <div class="alert alert-<?= $response['status'] === 'success' ? 'success' : 'danger' ?>">
                            <?= htmlspecialchars($response['message']) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">DNI</label>
                            <input type="text" name="dni" class="form-control" required 
                                   placeholder="Ej: 12345678" 
                                   pattern="[0-9]{8,10}" 
                                   title="Solo números, entre 8 y 10 dígitos">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre de Usuario</label>
                            <input type="text" name="user" class="form-control" required 
                                   placeholder="Ej: jperez" 
                                   pattern="[a-zA-Z0-9_]{3,20}" 
                                   title="Solo letras, números y guiones bajos (3-20 caracteres)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required 
                                   minlength="8" 
                                   placeholder="Mínimo 8 caracteres">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rol</label>
                            <select name="role" class="form-control" required>
                                <option value="">Seleccione un rol</option>
                                <option value="Admin">Administrador</option>
                                <option value="Professor">Profesor</option>
                                <option value="Student">Estudiante</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">
                                <strong>Nota:</strong> El DNI debe estar previamente registrado como Profesor o Estudiante.
                            </small>
                        </div>
                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">Guardar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>