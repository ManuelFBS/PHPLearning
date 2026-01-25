<?php

namespace Domain\UseCases\Student;

use Domain\Repositories\StudentRepositoryInterface;

/**
 * ~ Caso de uso: Eliminar un estudiante...
 * ~ Contiene toda la lógica de negocio para eliminar estudiante...
 */
class DeleteStudentUseCase
{
        private StudentRepositoryInterface $studentRepository;

        public function __construct(
                StudentRepositoryInterface $studentRepository
        ) {
                $this->studentRepository = $studentRepository;
        }

        /**
         * * Ejecutar el caso de uso de eliminación...
         *
         * @param string dni Representa el DNI (Documento Nacional de Identidad) de un estudiante...
         * @return array ['success' => bool, 'message' => string, 'data' => null]
         */
        public function execute(string $dni): array
        {
                // > 1. Buscar el profesor por su DNI...
                $student = $this->studentRepository->findByDni($dni);

                // > 2. Validar si existe el profesor...
                if ($student === null) {
                        return [
                                'success' => false,
                                'message' => 'Estudiante NO encontrado...',
                                'data' => null
                        ];
                }

                // > 3. Proceder a la eliminación del profesor...
                $deleteStudent = $this->studentRepository->delete($dni);

                // > 4. Verificar si la eliminación fue exitosa...
                if ($deleteStudent) {
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