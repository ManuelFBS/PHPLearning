<?php

$role = $_SESSION['user_role'] ?? 'Student';
$bgClass = '';

switch ($role) {
        case 'Admin':
                $bgClass = 'bg-admin';
                break;
        case 'Professor':
                $bgClass = 'bg-professor';
                break;
        case 'Student':
                $bgClass = 'bg-student';
                break;
}

?>

<div class="sidebar d-flex flex-column p-3 <?php echo $bgClass; ?>">
        <a href="#" class="navbar-brand text-white fw-bold fs-4">Unipro 1.0</a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto h-100">
                <li class="nav-item">
                        <a href="?page=dashboard" class="nav-link text-white">Inicio</a>
                </li>
                
                <?php if ($role === 'Admin'): ?>
                        <li class="nav-item">
                                <a href="?page=Professors/create" class="nav-link text-white">
                                        <i class="bi bi-person-plus"></i> Nuevo Profesor
                                </a>
                        </li>
                        <li class="nav-item">
                                <a href="?page=Students/create" class="nav-link text-white">
                                        <i class="bi bi-person-vcard-fill"></i> Nuevo Estudiante
                                </a>
                        </li>
                        <li class="nav-item">
                                <a href="?page=Users/create" class="nav-link text-white">
                                        <i class="bi bi-person-workspace"></i> Nuevo Usuario
                                </a>
                        </li>
                <?php endif; ?>

                <li><hr class="text-white-70"></li>
                <li>
                        <a href="?page=Professors/list" class="nav-link text-white">
                                <i class="bi bi-people"></i> Ver Profesores
                        </a>
                </li>
                <li>
                        <a href="?page=Students/list" class="nav-link text-white">
                                <i class="bi bi-people"></i> Ver Estudiantes
                        </a>
                </li>
                <li>
                        <a href="?page=Users/list" class="nav-link text-white">
                                <i class="bi bi-people"></i> Ver Usuarios
                        </a>
                </li>

                <li class="mt-auto">
                        <hr class="text-white-70">
                        <a href="?page=logout" class="nav-link text-white mt-5">
                                <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
                        </a>
                </li>
        </ul>
</div>