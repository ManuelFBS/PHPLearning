<?php

// ~ Se importa el controlador...
require_once __DIR__ . '/../../../App/Controllers/UserController.php';

// * Se obtiene el nombre de usuario desde la URL (ejemplo: ?page=Users/edit&user=usuarioabcd)...
$userName = $_GET['user'] ?? '';

// * Si NO hay nombre de usuario, se muestra un error...
if (empty($userName)) {
        echo '<div class="alert alert-danger">Usuario no proporcionado...</div>';
        exit;
}

// * Primero se obtienen los datos actuales del usuario para llenar el formulario...
$response = UserController::edit($userName);
$user = $response['data'] ?? null;

// * Si no existe el usuario, se muestra un error...
if (!$user) {
        echo '<div class="alert alert-danger">Usuario no encontrado...</div>';
        exit;
}

// * Se procesa el formulario si se envió por POST...
$updateResponse = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $updateResponse = UserController::update($userName);
        // > Si la actualización fue exitosa, se recargan los datos actualizados...
        if ($updateResponse['status'] === 'success') {
                $response = UserController::edit($userName);
                $user = $response['data'] ?? null;
        }
}

?>

<div class="container">
        <div class="row justify-content-center">
                <div class="col-md-8">
                        <div class="card shadow">
                                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">Editar Usuario</h4>
                                        <a href="?page=Users/list" class="btn btn-light btn-sm">
                                                <i class="bi bi-arrow-left"></i> Volver
                                        </a>
                                </div>
                                <div class="card-body">
                                        <?php if ($updateResponse): ?>
                                                <!-- Mensaje de éxito o error después de enviar el formulario -->
                                                <div class="alert alert-<?= $updateResponse['status'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                                                        <?= htmlspecialchars($updateResponse['message']) ?>
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                        <?php endif; ?>

                                        <form method="POST" class="row g-3">
                                                <!-- Campo DNI (solo lectura, no se puede editar) -->
                                                <div class="col-md-6">
                                                        <label class="form-label fw-bold">DNI</label>
                                                        <input type="text" value="<?php echo htmlspecialchars($user['dni']); ?>" class="form-control" readonly>
                                                        <small class="text-muted">El DNI no se puede modificar.</small>
                                                </div>

                                                <!-- Campo Usuario (solo lectura, no se puede editar) -->
                                                <div class="col-md-6">
                                                        <label class="form-label fw-bold">Nombre de Usuario</label>
                                                        <input type="text" value="<?php echo htmlspecialchars($user['user']); ?>" class="form-control" readonly>
                                                        <small class="text-muted">El nombre de usuario no se puede modificar.</small>
                                                </div>

                                                <!-- Campo Rol (se puede editar) -->
                                                <div class="col-md-6">
                                                        <label for="role" class="form-label fw-bold">Rol <span class="text-danger">*</span></label>
                                                        <select name="role" id="role" class="form-select" required>
                                                                <option value="">Seleccione un rol...</option>
                                                                <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                                                                <option value="Professor" <?= $user['role'] === 'Professor' ? 'selected' : '' ?>>Professor</option>
                                                                <option value="Student" <?= $user['role'] === 'Student' ? 'selected' : '' ?>>Student</option>
                                                        </select>
                                                        <small class="text-muted">Seleccione el rol del usuario en el sistema.</small>
                                                </div>

                                                <!-- Campo Password (opcional) -->
                                                <div class="col-md-6">
                                                        <label for="password" class="form-label fw-bold">Nueva Contraseña</label>
                                                        <input type="password" name="password" id="password" class="form-control" placeholder="Dejar vacío para no cambiar">
                                                        <small class="text-muted">
                                                                Deje este campo vacío si no desea cambiar la contraseña. 
                                                                Si desea cambiarla, debe tener al menos 8 caracteres.
                                                        </small>
                                                </div>

                                                <!-- Información adicional (solo lectura) -->
                                                <div class="col-md-6">
                                                        <label class="form-label fw-bold">Fecha de Creación</label>
                                                        <input type="text" value="
                                                        <?php
                                                        $created = $user['createdAt'];
                                                        $formattedDate = date('d/m/Y H:i', strtotime($created));
                                                        echo htmlspecialchars($formattedDate);
                                                        ?>" class="form-control" readonly>
                                                </div>

                                                <div class="col-md-6">
                                                        <label class="form-label fw-bold">Última Actualización</label>
                                                        <input type="text" value=" 
                                                        <?php
                                                        $updated = $user['updatedAt'];
                                                        $formattedDate = date('d/m/Y H:i', strtotime($updated));
                                                        echo htmlspecialchars($formattedDate);
                                                        ?>" class="form-control" readonly>
                                                </div>

                                                <!-- Botones de acción -->
                                                <div class="col-12 text-end mt-4">
                                                        <a href="?page=Users/list" class="btn btn-secondary">Cancelar</a>
                                                        <button type="submit" class="btn btn-primary px-4">
                                                                <i class="bi bi-save"></i> Guardar Cambios
                                                        </button>
                                                </div>
                                        </form>
                                </div>
                        </div>
                </div>
        </div>
</div>