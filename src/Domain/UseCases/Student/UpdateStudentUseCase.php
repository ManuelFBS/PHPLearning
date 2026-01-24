<?php

namespace Domain\UseCases\Student;

use Domain\Repositories\StudentRepositoryInterface;

/**
 * ~ Caso de uso: Actualizar un estudiante...
 * ~ Maneja las validaciones de negocio antes de ejecutar los cambios....
 */
class UpdateStudentUseCase
{
        private StudentRepositoryInterface $studentRepository;

        public function __construct(
                StudentRepositoryInterface $studentRepository
        ) {
                $this->studentRepository = $studentRepository;
        }

        public function execute(
                string $dni,
                ?string $names = null,
                ?string $lastNames = null,
                ?\DateTime $birthDate = null,
                ?string $email = null,
                ?string $phone = null,
                ?\DateTime $dateEntry = null,
                ?string $subjects = null,
                ?int $semester = null
        ): array {
                // > 1. Validar formato del DNI...
                if (!preg_match('/^\d{8,10}$/', $dni)) {
                        return [
                                'success' => false,
                                'message' => 'El DNI debe contener entre 8 y 10 dígitos numéricos'
                        ];
                }

                // > 2. Proveer al menos un campo para actualizar...
                if (
                        $names === null &&
                        $lastNames === null &&
                        $birthDate === null &&
                        $email === null &&
                        $phone === null &&
                        $dateEntry === null &&
                        $subjects === null &&
                        $semester === null
                ) {
                        return [
                                'success' => false,
                                'message' => 'Debe proporcionar al menos un campo para actualizar...',
                                'data' => null
                        ];
                }

                // > 3. Buscar la entidad (objeto)...
                $student = $this->studentRepository->findByDni($dni);
                if ($student === null) {
                        return [
                                'success' => false,
                                'message' => 'No se pudo cargar el estudiante para actualizar.',
                                'data' => null,
                        ];
                }

                // > 4. Validaciones y actualizaciones usando las reglas de la entidad...
                try {
                        if ($names !== null) {
                                $student->updateNames($names);
                        }
                        if ($lastNames !== null) {
                                $student->updateLastNames($lastNames);
                        }
                        if ($birthDate !== null) {
                                $student->updateBirthDate($birthDate);
                        }
                        if ($email !== null) {
                                $student->updateEmail($email);
                        }
                        if ($phone !== null) {
                                $student->updatePhone($phone);
                        }
                        if ($dateEntry !== null) {
                                $student->updateDateEntry($dateEntry);
                        }
                        if ($subjects !== null) {
                                $student->updateSubjects($subjects);
                        }
                        if ($semester !== null) {
                                $student->updateSemester($semester);
                        }
                } catch (\InvalidArgumentException $e) {
                        // Muestra los errores de validación con un mensaje amigable...
                        return [
                                'success' => false,
                                'message' => $e->getMessage(),
                                'data' => null,
                        ];
                }

                // > 5. Los cambios persisten...
                $updated = $this->studentRepository->update($student);

                return $updated
                        ? [
                                'success' => true,
                                'message' => 'Estudiante actualizado correctamente...',
                                'data' => $student
                        ]
                        : [
                                'success' => false,
                                'message' => 'No se pudo actualizar el estudiante en la base de datos.',
                                'data' => null
                        ];
        }
}

?>