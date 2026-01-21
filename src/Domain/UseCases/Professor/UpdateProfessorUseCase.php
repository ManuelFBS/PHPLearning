<?php

namespace Domain\UseCases\Professor;

use Domain\Repositories\ProfessorRepositoryInterface;

/**
 * ~ Caso de uso: Actualizar un profesor...
 * ~ Maneja las validaciones de negocio antes de ejecutar los cambios....
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
         * Run the update use case.
         *
         * @param string $dni Es requerido el dni del professor
         * @param string|null $names Opcional
         * @param string|null $lastNames Opcional
         * @param \DateTime|null $birthDate Opcional
         * @param string|null $email Opcional
         * @param string|null $phone Opcional
         * @param string|null $subjects Opcional
         * @return array ['success' => bool, 'message' => string, 'data' => Professor|null]
         */
        public function execute(
                string $dni,
                ?string $names = null,
                ?string $lastNames,
                ?\DateTime $birthDate,
                ?string $email,
                ?string $phone,
                ?string $subjects
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
                        $subjects === null
                ) {
                        return [
                                'success' => false,
                                'message' => 'Debe proporcionar al menos un campo para actualizar (password o rol)...',
                                'data' => null
                        ];
                }

                // > 3. Verificar que el DNI exista en profesores, también...
                $exists = $this->professorRepository->exists($dni);
                if (!$exists) {
                        return [
                                'success' => false,
                                'message' => 'El DNI no se encuentra en la base de datos...',
                                'data' => null
                        ];
                }

                // > 4. Buscar la entidad (objeto)...
                $professor = $this->professorRepository->findByDni($dni);
                if ($professor === null) {
                        return [
                                'success' => false,
                                'message' => 'No se pudo cargar el profesor para actualizar.',
                                'data' => null,
                        ];
                }

                // > 5. Validaciones y actualizaciones usando las reglas de la entidad...
                try {
                        if ($names !== null) {
                                $professor->updateNames($names);
                        }
                        if ($lastNames !== null) {
                                $professor->updateLastNames($lastNames);
                        }
                        if ($birthDate !== null) {
                                $professor->updateBirthDate($birthDate);
                        }
                        if ($email !== null) {
                                $professor->updateEmail($email);
                        }
                        if ($phone !== null) {
                                $professor->updatePhone($phone);
                        }
                        if ($subjects !== null) {
                                $professor->updateSubjects($subjects);
                        }
                } catch (\InvalidArgumentException $e) {
                        // Muestra los errores de validación con un mensaje amigable...
                        return [
                                'success' => false,
                                'message' => $e->getMessage(),
                                'data' => null,
                        ];
                }

                // > 6. Los cambios persisten...
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