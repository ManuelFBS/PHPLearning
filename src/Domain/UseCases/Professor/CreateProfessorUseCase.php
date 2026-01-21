<?php

namespace Domain\UseCases\Professor;

use Domain\Entities\Professor;
use Domain\Repositories\ProfessorRepositoryInterface;

/**
 * ~ Caso de uso: Registrar un nuevo profesor...
 * ~ Contiene toda la lógica de negocio para registrar profesores...
 */
class CreateProfessorUseCase
{
        private ProfessorRepositoryInterface $professorRepository;

        public function __construct(ProfessorRepositoryInterface $professorRepository)
        {
                $this->professorRepository = $professorRepository;
        }

        // * Ejecutar el caso de uso...
        public function execute(
                string $dni,
                string $names,
                string $lastNames,
                \DateTime $birthDate,
                string $email,
                string $phone,
                string $subjects
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

                // > 3. Verificar que el DNI no exista en profesores, también
                // > se verifica que el email tampoco exista en profesores...
                $professorDniExists = $this->professorRepository->exists($dni);
                $professorEmailExists = $this->professorRepository->emailExists($email);

                if ($professorDniExists) {
                        return [
                                'success' => false,
                                'message' => 'El DNI ya está registrado en la base de datos...'
                        ];
                }
                //
                if ($professorEmailExists) {
                        return [
                                'success' => false,
                                'message' => 'El email ya está registrado en la base de datos...'
                        ];
                }

                // > 4. Crear la entidad Professor...
                $professor = new Professor(
                        $dni,
                        $names,
                        $lastNames,
                        $birthDate,
                        $email,
                        $phone,
                        $subjects,
                        new \DateTime(), null
                );

                // > 5. Guardar en el repositorio...
                $result = $this->professorRepository->save($professor);

                if ($result) {
                        return [
                                'success' => true,
                                'message' => 'Professor registrado correctamente...'
                        ];
                } else {
                        return [
                                'success' => false,
                                'message' => 'No se pudo registrar el profesor...'
                        ];
                }
        }
}

?>