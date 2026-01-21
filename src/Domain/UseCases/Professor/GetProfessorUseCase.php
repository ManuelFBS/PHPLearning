<?php

namespace Domain\UseCases\Professor;

use Domain\Repositories\ProfessorRepositoryInterface;

class GetProfessorUseCase
{
        private ProfessorRepositoryInterface $professorRepository;

        public function __construct(
                ProfessorRepositoryInterface $professorRepository
        ) {
                $this->professorRepository = $professorRepository;
        }

        public function execute(string $dni): array
        {
                $professor = $this->professorRepository->findByDni($dni);

                if ($professor === null) {
                        return [
                                'success' => false,
                                'message' => 'Profesor no encontrado...',
                                'data' => null
                        ];
                }

                return [
                        'success' => true,
                        'message' => 'Profesor encontrado',
                        'data' => $professor
                ];
        }
}

?>