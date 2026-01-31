<?php

namespace Presentation\Controllers;

use Domain\DTOs\Professor\CreateProfessorDTO;
use Domain\DTOs\Professor\UpdateProfessorDTO;
use Domain\UseCases\Professor\CreateProfessorUseCase;
use Domain\UseCases\Professor\DeleteProfessorUseCase;
use Domain\UseCases\Professor\GetAllProfessorsUseCase;
use Domain\UseCases\Professor\GetProfessorUseCase;
use Domain\UseCases\Professor\UpdateProfessorUseCase;

class ProfessorController
{
        private CreateProfessorUseCase $createProfessorUseCase;
        private GetAllProfessorsUseCase $getAllProfessorsUseCase;
        private GetProfessorUseCase $getProfessorUseCase;
        private UpdateProfessorUseCase $updateProfessorUseCase;
        private DeleteProfessorUseCase $deleteProfessorUseCase;

        public function __construct(
                CreateProfessorUseCase $createProfessorUseCase,
                GetAllProfessorsUseCase $getAllProfessorsUseCase,
                GetProfessorUseCase $getProfessorUseCase,
                UpdateProfessorUseCase $updateProfessorUseCase,
                DeleteProfessorUseCase $deleteProfessorUseCase
        ) {
                $this->createProfessorUseCase = $createProfessorUseCase;
                $this->getAllProfessorsUseCase = $getAllProfessorsUseCase;
                $this->getProfessorUseCase = $getProfessorUseCase;
                $this->updateProfessorUseCase = $updateProfessorUseCase;
                $this->deleteProfessorUseCase = $deleteProfessorUseCase;
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

                // > 3. Efectuar validaciones...
                [$dto, $validationResult] = CreateProfessorDTO::fromRequest($_POST);

                if (!$validationResult->isValid()) {
                        return [
                                'status' => 'error',
                                'message' => $validationResult->getFirstError(),
                                'errors' => $validationResult->getErrors()
                        ];
                }

                // > 4. Llamar al caso de uso...
                $result = $this->createProfessorUseCase->execute($dto);

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

                // > Aquí se llamaría a un caso de uso GetAllProfessorsUseCase...
                $result = $this->getAllProfessorsUseCase->execute();

                return [
                        'status' => $result['success'] ? 'success' : 'error',
                        'message' => $result['message'],
                        'data' => $result['data']
                ];
        }

        public function show(string $dni): array
        {
                if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
                        return [
                                'status' => 'error',
                                'message' => 'No tienes permisos para realizar esta acción'
                        ];
                }

                $result = $this->getProfessorUseCase->execute($dni);

                return [
                        'status' => $result['success'] ? 'success' : 'error',
                        'message' => $result['message'],
                        'data' => $result['data']
                ];
        }

        public function update(string $dni): array
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

                // > Crear y validar el DTO desde la petición...
                [$dto, $validationResult] = UpdateProfessorDTO::fromRequest($_POST);

                if (!$validationResult->isValid()) {
                        return [
                                'status' => 'error',
                                'message' => $validationResult->getFirstError(),
                                'errors' => $validationResult->getErrors()
                        ];
                }

                // > Llamar al caso de uso con los datos ya validados...
                $result = $this->updateProfessorUseCase->execute($dni, $dto);

                return [
                        'status' => $result['success'] ? 'success' : 'error',
                        'message' => $result['message'],
                        'data' => $result['data'] ?? null
                ];
        }

        public function destroy(string $dni): array
        {
                if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
                        return [
                                'status' => 'error',
                                'message' => 'No tienes permisos para realizar esta acción'
                        ];
                }

                $result = $this->deleteProfessorUseCase->execute($dni);

                return [
                        'status' => $result['success'] ? 'success' : 'error',
                        'message' => $result['message']
                ];
        }
}

?>