<?php

namespace Domain\UseCases\User;

use Domain\Entities\User;
use Domain\Repositories\ProfessorRepositoryInterface;
use Domain\Repositories\StudentRepositoryInterface;
use Domain\Repositories\UserRepositoryInterface;

/**
 * ~ Caso de uso: Crear un nuevo usuario...
 * ~ Contiene toda la lógica de negocio para registrar usuarios...
 */
class CreateUserUseCase
{
        private UserRepositoryInterface $userRepository;
        private ProfessorRepositoryInterface $professorRepository;
        private StudentRepositoryInterface $studentRepository;

        public function __construct(
                UserRepositoryInterface $userRepository,
                ProfessorRepositoryInterface $professorRepository,
                StudentRepositoryInterface $studentRepository
        ) {
                $this->userRepository = $userRepository;
                $this->professorRepository = $professorRepository;
                $this->studentRepository = $studentRepository;
        }

        // * Ejecutar el caso de uso...
        public function execute(
                string $dni,
                string $username,
                string $password,
                string $role
        ): array {
                // > 1. Validar DNI...
                if (!preg_match('/^\d{8,10}$/', $dni)) {
                        return [
                                'success' => false,
                                'message' => 'El DNI debe contener entre 8 y 10 dígitos numéricos'
                        ];
                }

                // 2. Validar username...
                if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
                        return [
                                'success' => false,
                                'message' => 'El nombre de usuario debe tener entre 3 y 20 caracteres'
                        ];
                }

                // > 3. Validar password...
                if (strlen($password) < 8) {
                        return [
                                'success' => false,
                                'message' => 'La contraseña debe tener al menos 8 caracteres'
                        ];
                }

                // > 4. Validar rol...
                $allowedRoles = ['Admin', 'Professor', 'Student'];
                if (!in_array($role, $allowedRoles, true)) {
                        return [
                                'success' => false,
                                'message' => 'Rol no válido'
                        ];
                }

                // > 5. Verificar que el DNI exista en profesores o estudiantes...
                $professorExists = $this->professorRepository->exists($dni);
                $studentExists = $this->studentRepository->exists($dni);

                if (!$professorExists && !$studentExists) {
                        return [
                                'success' => false,
                                'message' => 'El DNI no está registrado como profesor o estudiante...'
                        ];
                }

                // > 6. Verificar que no exista ya el usuario...
                if ($this->userRepository->exists($dni, $username)) {
                        return [
                                'success' => false,
                                'message' => 'El nombre de usuario o DNI ya está registrado...'
                        ];
                }

                // > 7. Crear la entidad User...
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                $user = new User(
                        $dni,
                        $username,
                        $passwordHash,
                        $role,
                        new \DateTime(), null
                );

                // > 8. Guardar en el repositorio...
                $result = $this->userRepository->save($user);

                if ($result) {
                        return [
                                'success' => true,
                                'message' => 'Usuario registrado correctamente...'
                        ];
                } else {
                        return [
                                'success' => false,
                                'message' => 'No se pudo registrar el usuario...'
                        ];
                }
        }
}

?>