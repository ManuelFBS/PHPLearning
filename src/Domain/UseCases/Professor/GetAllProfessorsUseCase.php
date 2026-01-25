<?php

namespace Domain\UseCases\Professor;

use Domain\Repositories\ProfessorRepositoryInterface;

class GetAllProfessorsUseCase
{
        private ProfessorRepositoryInterface $professorRepository;

        public function __construct(
                ProfessorRepositoryInterface $professorRepository
        ) {
                $this->professorRepository = $professorRepository;
        }

        public function execute(): array
        {
                $professors = $this->professorRepository->findAll();

                return [
                        'success' => true,
                        'message' => 'Listado de profesores',
                        'data' => $professors
                ];
        }
}

?>