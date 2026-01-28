<?php

namespace Presentation\Controllers;

use Domain\UseCases\Student\CreateStudentUseCase;
use Domain\UseCases\Student\DeleteStudentUseCase;
use Domain\UseCases\Student\GetAllStudentsUseCase;
use Domain\UseCases\Student\GetStudentUseCase;
use Domain\UseCases\Student\UpdateStudentUseCase;
use DateTime;

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

                // > Convertir birthDate (string del formulario) a DateTime...
                $birthDateInput = trim($_POST['birthDate']);
                $dateEntryInput = trim($_POST['dateEntry']);
                $birthDate = DateTime::createFromFormat('Y-m-d', $birthDateInput);
                $dateEntry = DateTime::createFromFormat('Y-m-d', $dateEntryInput);
                if ($birthDate === false || $dateEntry === false) {
                        return [
                                'status' => 'error',
                                'message' => 'La fecha de nacimiento y/o de entrada no es(son) válida(s). Use el formato AAAA-MM-DD...'
                        ];
                }

                // > Llamar al caso de uso...
                $result = $this->createStudentUseCase->execute(
                        trim($_POST['dni']),
                        trim($_POST['names']),
                        trim($_POST['lastNames']),
                        $birthDate,
                        trim($_POST['email']),
                        trim($_POST['phone']),
                        $dateEntry,
                        trim($_POST['subjects']),
                        trim($_POST['semester'])
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

                // > Leer datos del formulario (los envía edit.php con name="...")...
                $names = isset($_POST['names']) ? trim($_POST['names']) : null;
                $lastNames = isset($_POST['lastNames']) ? trim($_POST['lastNames']) : null;
                $email = isset($_POST['email']) ? trim($_POST['email']) : null;
                $phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;
                $subjects = isset($_POST['subjects']) ? trim($_POST['subjects']) : null;
                $semester = isset($_POST['semester']) ? trim($_POST['semester']) : null;

                // > Fecha: el input type="date" envía Y-m-d...
                $birthDate = null;
                $dateEntry = null;
                if (!empty(trim($_POST['birthDate'] ?? ''))) {
                        $birthDate = DateTime::createFromFormat('Y-m-d', trim($_POST['birthDate']));
                        if ($birthDate === false) {
                                return [
                                        'status' => 'error',
                                        'message' => 'La fecha de nacimiento no es válida. Use el formato AAAA-MM-DD.',
                                        'data' => null
                                ];
                        }
                }
                if (!empty(trim($_POST['dateEntry'] ?? ''))) {
                        $dateEntry = DateTime::createFromFormat('Y-m-d', trim($_POST['dateEntry']));
                        if ($dateEntry === false) {
                                return [
                                        'status' => 'error',
                                        'message' => 'La fecha de entrada no es válida. Use el formato AAAA-MM-DD.',
                                        'data' => null
                                ];
                        }
                }

                $result = $this->updateStudentUseCase->execute(
                        $dni,
                        $names,
                        $lastNames,
                        $birthDate,
                        $email,
                        $phone,
                        $dateEntry,
                        $subjects,
                        $semester
                );

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

                $result = $this->deleteStudentUseCase->execute($dni);

                return [
                        'status' => $result['success'] ? 'success' : 'error',
                        'message' => $result['message']
                ];
        }
}

?>