<?php

namespace Domain\UseCases\Professor;

use Domain\Repositories\ProfessorRepositoryInterface;

/**
 * ~ Caso de uso: Eliminar un profesor...
 * ~ Contiene toda la lógica de negocio para eliminar profesores...
 */
class DeleteProfessorUseCase
{
        private ProfessorRepositoryInterface $professorRepository;

        public function __construct(
                ProfessorRepositoryInterface $professorRepository
        ) {
                $this->professorRepository = $professorRepository;
        }

        public function execute(string $dni): array
        {
                // > 1. Buscar el profesor por su DNI...
                $professor = $this->professorRepository->findByDni($dni);

                // > 2. Validar si existe el profesor...
                if ($professor === null) {
                        return [
                                'success' => false,
                                'message' => 'Profesor NO encontrado...',
                                'data' => null
                        ];
                }

                // > 3. Proceder a la eliminación del profesor...
                $deleteProfessor = $this->professorRepository->delete($dni);

                // > 4. Verificar si la eliminación fue exitosa...
                if ($deleteProfessor) {
                        return [
                                'success' => true,
                                'message' => 'Profesor eliminado correctamente...',
                                'data' => null
                        ];
                } else {
                        return [
                                'success' => false,
                                'message' => 'NO se pudo eliminar el profesor de la base de datos...',
                                'data' => null
                        ];
                }
        }
}

?>