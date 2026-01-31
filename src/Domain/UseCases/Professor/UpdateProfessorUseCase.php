<?php

namespace Domain\UseCases\Professor;

use Domain\DTOs\Professor\UpdateProfessorDTO;
use Domain\Repositories\ProfessorRepositoryInterface;

/**
 * ~ Caso de uso: Actualizar un profesor...
 * ~ Recibe el DNI (identificador de ruta) y un DTO ya validado con los campos a actualizar...
 */
class UpdateProfessorUseCase
{
        private ProfessorRepositoryInterface $professorRepository;

        public function __construct(
                ProfessorRepositoryInterface $professorRepository
        ) {
                $this->professorRepository = $professorRepository;
        }

        /**
         * * Ejecuta la actualización del profesor.
         *
         * @param string $dni DNI del profesor a actualizar (viene de la ruta).
         * @param UpdateProfessorDTO $dto Datos ya validados; todos los campos son opcionales.
         * @return array ['success' => bool, 'message' => string, 'data' => Professor|null]
         */
        public function execute(
                string $dni,
                UpdateProfessorDTO $dto
        ): array {
                // > 1. Validar formato del DNI...
                if (!preg_match('/^\d{8,10}$/', $dni)) {
                        return [
                                'success' => false,
                                'message' => 'El DNI debe contener entre 8 y 10 dígitos numéricos',
                                'data' => null
                        ];
                }

                // > 2. Comprobar que al menos un campo venga en el DTO para actualizar...
                if (
                        $dto->getNames() === null &&
                        $dto->getLastNames() === null &&
                        $dto->getBirthDate() === null &&
                        $dto->getEmail() === null &&
                        $dto->getPhone() === null &&
                        $dto->getSubjects() === null
                ) {
                        return [
                                'success' => false,
                                'message' => 'Debe proporcionar al menos un campo para actualizar...',
                                'data' => null
                        ];
                }

                // > 3. Buscar la entidad (objeto)...
                $professor = $this->professorRepository->findByDni($dni);
                if ($professor === null) {
                        return [
                                'success' => false,
                                'message' => 'No se pudo cargar el profesor para actualizar.',
                                'data' => null,
                        ];
                }

                // > 4. Validaciones y actualizaciones usando las reglas de la entidad...
                try {
                        if ($dto->getNames() !== null) {
                                $professor->updateNames($dto->getNames());
                        }
                        if ($dto->getLastNames() !== null) {
                                $professor->updateLastNames($dto->getLastNames());
                        }
                        if ($dto->getBirthDate() !== null) {
                                $professor->updateBirthDate($dto->getBirthDate());
                        }
                        if ($dto->getEmail() !== null) {
                                $professor->updateEmail($dto->getEmail());
                        }
                        if ($dto->getPhone() !== null) {
                                $professor->updatePhone($dto->getPhone());
                        }
                        if ($dto->getSubjects() !== null) {
                                $professor->updateSubjects($dto->getSubjects());
                        }
                } catch (\InvalidArgumentException $e) {
                        // > Muestra los errores de validación con un mensaje amigable...
                        return [
                                'success' => false,
                                'message' => $e->getMessage(),
                                'data' => null,
                        ];
                }

                // > 5. Los cambios persisten...
                $updated = $this->professorRepository->update($professor);

                return $updated
                        ? [
                                'success' => true,
                                'message' => 'Profesor actualizado correctamente...',
                                'data' => $professor
                        ]
                        : [
                                'success' => false,
                                'message' => 'No se pudo actualizar el profesor en la base de datos.',
                                'data' => null
                        ];
        }
}

?>