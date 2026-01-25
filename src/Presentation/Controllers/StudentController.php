<?php

namespace Presentation\Controllers;

use Domain\UseCases\Student\CreateStudentUseCase;
use Domain\UseCases\Student\DeleteStudentUseCase;
use Domain\UseCases\Student\GetAllStudentsUseCase;
use Domain\UseCases\Student\GetStudentUseCase;
use Domain\UseCases\Student\UpdateStudentUseCase;

class StudentController
{
        private CreateStudentUseCase $createStudentUseCase;
        private GetAllStudentsUseCase $getAllStudentsUseCase;
        private GetStudentUseCase $getStudentUseCase;
        private UpdateStudentUseCase $updateStudentUseCase;
        private DeleteStudentUseCase $deleteStudentUseCase;

        public function __construct(
                CreateStudentUseCase $createStudentUseCase,
                GetAllStudentsUseCase $getAllStudentsUseCase,
                GetStudentUseCase $getStudentUseCase,
                UpdateStudentUseCase $updateStudentUseCase,
                DeleteStudentUseCase $deleteStudentUseCase
        ) {
                $this->createStudentUseCase = $createStudentUseCase;
                $this->getAllStudentsUseCase = $getAllStudentsUseCase;
                $this->getStudentUseCase = $getStudentUseCase;
                $this->updateStudentUseCase = $updateStudentUseCase;
                $this->deleteStudentUseCase = $deleteStudentUseCase;
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
                $requiredFields = [
                        'dni',
                        'names',
                        'lastNames',
                        'birthDate',
                        'email',
                        'phone',
                        'dateEntry',
                        'subjects',
                        'semester'
                ];
                foreach ($requiredFields as $field) {
                        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                                return [
                                        'status' => 'error',
                                        'message' => "El campo '$field' es obligatorio..."
                                ];
                        }
                }

                // > Llamar al caso de uso...
                $result = $this->createStudentUseCase->execute(
                        trim($_POST['dni']),
                        trim($_POST['names']),
                        trim($_POST['lastNames']),
                        ($_POST['birthDate']),
                        trim($_POST['email']),
                        trim($_POST['phone']),
                        ($_POST['dateEntry']),
                        trim($_POST['subjects']),
                        ($_POST['semester'])
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

                // > Aquí se llamaría a un caso de uso GetAllProfessorsUseCase...
                $result = $this->getAllStudentsUseCase->execute();

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

                $result = $this->getStudentUseCase->execute($dni);

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

                $result = $this->updateStudentUseCase->execute($dni);

                return [
                        'status' => $result['success'] ? 'success' : 'error',
                        'message' => $result['message'],
                        'data' => $result['data']
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

                $result = $this->deleteStudentUseCase->execute($dni);

                return [
                        'status' => $result['success'] ? 'success' : 'error',
                        'message' => $result['message']
                ];
        }
}

?>