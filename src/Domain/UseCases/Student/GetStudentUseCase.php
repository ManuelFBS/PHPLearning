<?php

namespace Domain\UseCases\Student;

use Domain\Repositories\StudentRepositoryInterface;

class GetStudentUseCase
{
        private StudentRepositoryInterface $studentRepository;

        public function __construct(
                StudentRepositoryInterface $studentRepository
        ) {
                $this->studentRepository = $studentRepository;
        }

        public function execute(string $dni): array
        {
                $student = $this->studentRepository->findByDni($dni);

                if ($student === null) {
                        return [
                                'success' => false,
                                'message' => 'Estudiante no encontrado...',
                                'data' => null
                        ];
                }

                return [
                        'success' => true,
                        'message' => 'Estudiante encontrado',
                        'data' => $student
                ];
        }
}

?>