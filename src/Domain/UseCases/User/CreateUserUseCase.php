<?php

namespace Domain\UseCases\User;

use Domain\DTOs\User\CreateUserDTO;
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
        public function execute(CreateUserDTO $dto): array
        {
                $dni = $dto->getDni();
                $username = $dto->getUsername();
                $password = $dto->getPassword();
                $role = $dto->getRole();

                // > 1. Verificar que el DNI exista en profesores o estudiantes...
                $professorExists = $this->professorRepository->exists($dni);
                $studentExists = $this->studentRepository->exists($dni);

                if (!$professorExists && !$studentExists) {
                        return [
                                'success' => false,
                                'message' => 'El DNI no está registrado como profesor o estudiante...'
                        ];
                }

                // > 2. Verificar que no exista ya el usuario...
                if ($this->userRepository->exists($dni, $username)) {
                        return [
                                'success' => false,
                                'message' => 'El nombre de usuario o DNI ya está registrado...'
                        ];
                }

                // > 3. Crear la entidad User...
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                $user = new User(
                        $dni,
                        $username,
                        $passwordHash,
                        $role,
                        new \DateTime(),
                        null
                );

                // > 4. Guardar en el repositorio...
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