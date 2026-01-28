<?php

namespace Domain\UseCases\Student;

use Domain\Entities\Student;
use Domain\Repositories\StudentRepositoryInterface;
use DateTime;

/**
 * ~ Caso de uso: Registrar un nuevo estudiante...
 * ~ Contiene toda la lógica de negocio para registrar estudiantes...
 */
class CreateStudentUseCase
{
        private StudentRepositoryInterface $studentRepository;

        public function __construct(
                StudentRepositoryInterface $studentRepository
        ) {
                $this->studentRepository = $studentRepository;
        }

        public function execute(
                string $dni,
                string $names,
                string $lastNames,
                \DateTime $birthDate,
                string $email,
                string $phone,
                \DateTime $dateEntry,
                string $subjects,
                int $semester
        ): array {
                // > 1. Validar DNI...
                if (!preg_match('/^\d{8,10}$/', $dni)) {
                        return [
                                'success' => false,
                                'message' => 'El DNI debe contener entre 8 y 10 dígitos numéricos'
                        ];
                }

                // > 2. Validar email...
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        throw new \InvalidArgumentException('Email NO válido...');
                }

                // > 3. Verificar que el DNI no exista en estudiantes, también
                // > se verifica que el email tampoco exista en estudiantes...
                $studentDniExists = $this->studentRepository->exists($dni);
                $studentEmailExists = $this->studentRepository->emailExists($email);

                if ($studentDniExists) {
                        return [
                                'success' => false,
                                'message' => 'El DNI ya está registrado en la base de datos...'
                        ];
                }
                //
                if ($studentEmailExists) {
                        return [
                                'success' => false,
                                'message' => 'El email ya está registrado en la base de datos...'
                        ];
                }

                // > 4. Crear la entidad Student...
                $student = new Student(
                        $dni,
                        $names,
                        $lastNames,
                        $birthDate,
                        $email,
                        $phone,
                        $dateEntry,
                        $subjects,
                        $semester,
                        new \DateTime(), null
                );

                // > 5. Guardar en el repositorio...
                $result = $this->studentRepository->save($student);

                if ($result) {
                        return [
                                'success' => true,
                                'message' => 'Estudiante registrado correctamente...'
                        ];
                } else {
                        return [
                                'success' => false,
                                'message' => 'No se pudo registrar el estudiante...'
                        ];
                }
        }
}

?>