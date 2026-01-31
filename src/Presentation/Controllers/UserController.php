<?php

namespace Presentation\Controllers;

use Domain\DTOs\User\CreateUserDTO;
use Domain\DTOs\User\UpdateUserDTO;
use Domain\DTOs\ValidationResult;
use Domain\UseCases\User\CreateUserUseCase;
use Domain\UseCases\User\DeleteUserUseCase;
use Domain\UseCases\User\GetAllUsersUseCase;
use Domain\UseCases\User\GetUserUseCase;
use Domain\UseCases\User\UpdateUserUseCase;

class UserController
{
        private CreateUserUseCase $createUserUseCase;
        private GetAllUsersUseCase $getAllUsersUseCase;
        private GetUserUseCase $getUserUseCase;
        private UpdateUserUseCase $updateUserUseCase;
        private DeleteUserUseCase $deleteUserUseCase;

        public function __construct(
                CreateUserUseCase $createUserUseCase,
                GetAllUsersUseCase $getAllUsersUseCase,
                GetUserUseCase $getUserUseCase,
                UpdateUserUseCase $updateUserUseCase,
                DeleteUserUseCase $deleteUserUseCase
        ) {
                $this->createUserUseCase = $createUserUseCase;
                $this->getAllUsersUseCase = $getAllUsersUseCase;
                $this->getUserUseCase = $getUserUseCase;
                $this->updateUserUseCase = $updateUserUseCase;
                $this->deleteUserUseCase = $deleteUserUseCase;
        }

        public function store(): array
        {
                // > 1. Verificar permisos...
                if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
                        return [
                                'status' => 'error',
                                'message' => 'No tienes permisos para realizar esta acción'
                        ];
                }

                // > 2. Verificar método HTTP...
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        return [
                                'status' => 'error',
                                'message' => 'Método HTTP no permitido. Se requiere POST...'
                        ];
                }

                // > 3. Crear y validar el DTO desde la petición...
                [$dto, $validationResult] = CreateUserDTO::fromRequest($_POST);

                if (!$validationResult->isValid()) {
                        return [
                                'status' => 'error',
                                'message' => $validationResult->getFirstError(),
                                'errors' => $validationResult->getErrors()
                        ];
                }

                // > 4. Llamar al caso de uso con el DTO ya validado...
                $result = $this->createUserUseCase->execute($dto);

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
                $result = $this->getAllUsersUseCase->execute();

                return [
                        'status' => $result['success'] ? 'success' : 'error',
                        'message' => $result['message'],
                        'data' => $result['data']
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

                // > Crear y validar el DTO desde la petición (password y role opcionales)...
                [$dto, $validationResult] = UpdateUserDTO::fromRequest($_POST);

                if (!$validationResult->isValid()) {
                        return [
                                'status' => 'error',
                                'message' => $validationResult->getFirstError(),
                                'errors' => $validationResult->getErrors()
                        ];
                }

                // > Llamar al caso de uso con los datos ya validados...
                $result = $this->updateUserUseCase->execute($username, $dto);

                return [
                        'status' => $result['success'] ? 'success' : 'error',
                        'message' => $result['message'] ?? ''
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