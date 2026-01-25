<?php

namespace Domain\UseCases\Student;

use Domain\Repositories\StudentRepositoryInterface;

class GetAllStudentsUseCase
{
        private StudentRepositoryInterface $studentRepository;

        public function __construct(
                StudentRepositoryInterface $studentRepository
        ) {
                $this->studentRepository = $studentRepository;
        }

        public function execute(): array
        {
                $students = $this->studentRepository->findAll();

                return [
                        'success' => true,
                        'message' => 'Listado de estudiantes',
                        'data' => $students
                ];
        }
}

?>
