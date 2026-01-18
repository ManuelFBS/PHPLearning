<?php

namespace Presentation\Controllers;

use Domain\UseCases\User\CreateUserUseCase;
use Domain\UseCases\User\DeleteUserUseCase;
use Domain\UseCases\User\GetUserUseCase;
use Domain\UseCases\User\UpdateUserUseCase;

class UserController
{
        private CreateUserUseCase $createUserUseCase;
        private GetUserUseCase $getUserUseCase;
        private UpdateUserUseCase $updateUserUseCase;
        private DeleteUserUseCase $deleteUserUseCase;

        public function __construct(
                CreateUserUseCase $createUserUseCase,
                GetUserUseCase $getUserUseCase,
                UpdateUserUseCase $updateUserUseCase,
                DeleteUserUseCase $deleteUserUseCase
        ) {
                $this->createUserUseCase = $createUserUseCase;
                $this->getUserUseCase = $getUserUseCase;
                $this->updateUserUseCase = $updateUserUseCase;
                $this->deleteUserUseCase = $deleteUserUseCase;
        }

        public function store(): array
        {
                // > Verificar permisos...
                if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
                        return [
                                'status' => 'error',
                                'message' => 'No tienes permisos para realizar esta acción'
                        ];
                }

                // > Verificar método HTTP...
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        return [
                                'status' => 'error',
                                'message' => 'Método HTTP no permitido. Se requiere POST...'
                        ];
                }

                // > Validar campos requeridos...
                $requiredFields = ['dni', 'user', 'password', 'role'];
                foreach ($requiredFields as $field) {
                        if (!isset($_POST["$field"]) || empty(trim($_POST[$field]))) {
                                return [
                                        'status' => 'error',
                                        'message' => "El campo '$field' es obligatorio..."
                                ];
                        }
                }

                // > Llamar al caso de uso...
                $result = $this->createUserUseCase->execute(
                        trim($_POST['dni']),
                        trim($_POST['user']),
                        $_POST['password'],
                        trim($_POST['role']),
                );

                return [
                        'status' => $result['success'] ? 'success' : 'error',
                        'message' => $result['message']
                ];
        }

        public function getAll(): array
        {
                // > Verificar permisos...
                if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
                        return [
                                'status' => 'error',
                                'message' => 'No tienes permisos para realizar esta acción'
                        ];
                }

                // > Aquí se llamaría a un caso de uso GetAllUsersUseCase...
                // Por ahora, se retorna un ejemplo...
                return [
                        'status' => 'success',
                        'message' => 'Listado de usuarios',
                        'data' => []
                ];
        }

        public function show(string $username): array
        {
                if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
                        return [
                                'status' => 'error',
                                'message' => 'No tienes permisos para realizar esta acción'
                        ];
                }

                $result = $this->getUserUseCase->execute($username);

                return [
                        'status' => $result['success'] ? 'success' : 'error',
                        'message' => $result['message'],
                        'data' => $result['data']
                ];
        }

        public function update(string $username): array
        {
                if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
                        return [
                                'status' => 'error',
                                'message' => 'No tienes permisos para realizar esta acción'
                        ];
                }

                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        return [
                                'status' => 'error',
                                'message' => 'Método HTTP no permitido. Se requiere POST...'
                        ];
                }

                $role = trim($_POST['role'] ?? '');
                $password = $_POST['password'] ?? null;

                $result = $this->updateUserUseCase->execute($username, $role, $password);

                return [
                        'status' => $result['success'] ? 'success' : 'error',
                        'message' => $result['message']
                ];
        }

        public function destroy(string $username): array
        {
                if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
                        return [
                                'status' => 'error',
                                'message' => 'No tienes permisos para realizar esta acción'
                        ];
                }

                $result = $this->deleteUserUseCase->execute($username);

                return [
                        'status' => $result['success'] ? 'success' : 'error',
                        'message' => $result['message']
                ];
        }
}

?>