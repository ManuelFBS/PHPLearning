<?php

// ~ Cargar el autoload de Composer (si no está ya cargado)
require_once __DIR__ . '/../../../vendor/autoload.php';

// ~ Importar las clases necesarias usando namespaces
use Infrastructure\Container;

// ~ Obtener el controlador desde el Container (Inyección de Dependencias)...
$controller = Container::getProfessorController();

// ~ Se llama el método getAll() del controlador (es un método de instancia, no estático)...
$response = $controller->getAll();

// * Se extrae la información de la respuesta...
$status = $response['status'];
$message = $response['message'];
$professors = $response['data'] ?? [];  // > Si no existe 'data', usamos un array vacío...

// * Se procesala eliminación si se envió por POST...
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
                isset($_POST['action']) &&
                $_POST['action'] === 'delete') {
        $dniToDelete = $_POST['dni'] ?? '';
        if (!empty($dniToDelete)) {
                // > Usar el controlador de instancia, no estático...
                $deleteResponse = $controller->destroy($dniToDelete);
                // > Se recarga la página para mostrar el mensaje
                // > (o se podría usar JavaScript para no recargar)...
                if ($deleteResponse['status'] === 'success') {
                        header('Location: ?page=professors_list&deleted=1');
                        exit;
                }
        }
}

// * Se muestra un mensaje de éxito si se eliminó correctamente...
if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Profesor eliminado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
}

?>

<div class="container">
        <h2 class="mb-4">Listado de Profesores</h2>

        <?php if ($status === 'error'): ?>
                <!-- Si hay error (por permisos o por fallo en BD), se muestra un mensaje -->
                <div class="alert alert-danger">
                        <?php echo htmlspecialchars($message); ?>
                </div>
        <?php else: ?>
                <!-- Si status es success, se muestra el mensaje y la tabla -->
                <!-- El mensaje desaparecerá automáticamente después de 3 segundos -->
                <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php if (empty($professors)): ?>
                         <!-- Si no hay profesores en la base de datos -->
                        <p>No hay profesores registrados todavía.</p>
                <?php else: ?>
                         <!-- Tabla con el listado de profesores -->
                        <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle">
                                        <thead class="table-dark">
                                                <tr>
                                                        <th>DNI</th>
                                                        <th>Nombre</th>
                                                        <th>Apellidos</th>
                                                        <th>Fec. Nacimiento</th>
                                                        <th>Acciones</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                <?php foreach ($professors as $professor): ?>
                                                        <tr>
                                                                <td><?php echo htmlspecialchars($professor['dni']); ?></td>
                                                                <td><?php echo htmlspecialchars($professor['names']); ?></td>
                                                                <td><?php echo htmlspecialchars($professor['lastNames']); ?></td>
                                                                <td>
                                                                        <?php
                                                                        // > EJERCICIO 2: Se formatea la fecha de yyyy-mm-dd a dd/mm/yyyy...
                                                                        $birthDate = $professor['birthDate'];
                                                                        // > strtotime() convierte la fecha a timestamp...
                                                                        // > date() formatea el timestamp al formato deseado...
                                                                        $formattedDate = date('d/m/Y', strtotime($birthDate));
                                                                        echo htmlspecialchars($formattedDate);
                                                                        ?>
                                                                </td>

                                                                <td>
                                                                        <!-- Botones de acción -->
                                                                        <div class="btn-group" role="group">
                                                                                <!-- Botón Ver Detalles -->
                                                                                <a href="?page=Professors/show&dni=<?php echo urlencode($professor['dni']); ?>" 
                                                                                class="btn btn-info btn-sm" 
                                                                                title="Ver Detalles">
                                                                                        <i class="bi bi-eye"></i>
                                                                                </a>
                                                                                <!-- Botón Editar -->
                                                                                <a href="?page=Professors/edit&dni=<?php echo urlencode($professor['dni']); ?>" 
                                                                                class="btn btn-warning btn-sm" 
                                                                                title="Editar">
                                                                                        <i class="bi bi-pencil"></i>
                                                                                </a>
                                                                                <!-- Botón Eliminar -->
                                                                                <form method="POST" style="display: inline;" 
                                                                                        onsubmit="return confirm('¿Estás seguro de que deseas eliminar este profesor?');">
                                                                                        <input type="hidden" name="action" value="delete">
                                                                                        <input type="hidden" name="dni" value="<?php echo htmlspecialchars($professor['dni']); ?>">
                                                                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                                                                <i class="bi bi-trash"></i>
                                                                                        </button>
                                                                                </form>
                                                                        </div>
                                                                </td>
                                                        </tr>
                                                <?php endforeach; ?>
                                        </tbody>
                                </table>
                        </div>

                        <script>
                                // > Script para ocultar el mensaje de éxito después de 3 segundos...
                                document.addEventListener('DOMContentLoaded', function() {
                                        // Buscamos el elemento del mensaje de éxito...
                                        const successMessage = document.getElementById('success-message');
                                        
                                        // Si el mensaje existe, lo ocultamos después de 3000 milisegundos (3 segundos)...
                                        if (successMessage) {
                                                setTimeout(function() {
                                                // Agregamos la clase 'fade' y 'hide' para una animación suave...
                                                successMessage.classList.add('fade');
                                                // Después de la animación, ocultamos completamente el elemento...
                                                setTimeout(function() {
                                                        successMessage.style.display = 'none';
                                                }, 500); // 500ms es el tiempo de la animación fade de Bootstrap...
                                                }, 3000); // 3000ms = 3 segundos...
                                        }
                                });
                        </script>
                <?php endif; ?>
        <?php endif; ?>
</div>