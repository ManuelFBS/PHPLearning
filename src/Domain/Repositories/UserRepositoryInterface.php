<?php

namespace Domain\Repositories;

use Domain\Entities\User;

/**
 * ~ Interfaz del repositorio de usuarios...
 * ~ Define qué operaciones se pueden hacer con usuarios,
 * ~ sin importar cómo se almacenen...
 */
interface UserRepositoryInterface
{
        // * Guardar nuevo usuario...
        public function save(User $user): user;

        // * Buscar usuario por nombre de usuario...
        public function findByUsername(string $username): ?User;

        // * Buscar usuario por DNI...
        public function findByDni(string $dni): ?User;

        // * Obtener todos los usuarios...
        public function findAll(): array;

        // * Actualizar un usuario existente...
        public function update(User $user): bool;

        // * Eliminar un usuario...
        public function delete(string $username): bool;

        // * Verificar si existe un usuario con ese DNI o username...
        public function exists(string $dni, string $username): bool;
}

?>