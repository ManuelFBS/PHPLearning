<?php

namespace Domain\UseCases\Professor;

use Domain\DTOs\Professor\CreateProfessorDTO;
use Domain\Entities\Professor;
use Domain\Repositories\ProfessorRepositoryInterface;

/**
 * ~ Caso de uso: Registrar un nuevo profesor...
 * ~ Recibe un DTO ya validado; aquí solo se aplican reglas de negocio (unicidad DNI/email)...
 */
class CreateProfessorUseCase
{
        private ProfessorRepositoryInterface $professorRepository;

        public function __construct(
                ProfessorRepositoryInterface $professorRepository
        ) {
                $this->professorRepository = $professorRepository;
        }

        // * Ejecutar el caso de uso...
        public function execute(CreateProfessorDTO $dto): array
        {
                $dni = $dto->getDni();
                $email = $dto->getEmail();

                // > 1. Verificar que el DNI no exista en profesores, también
                // > se verifica que el email tampoco exista en profesores...
                if ($this->professorRepository->exists($dni)) {
                        return [
                                'success' => false,
                                'message' => 'El DNI ya está registrado en la base de datos...'
                        ];
                }
                //
                if ($this->professorRepository->emailExists($email)) {
                        return [
                                'success' => false,
                                'message' => 'El email ya está registrado en la base de datos...'
                        ];
                }

                // > 2. Crear la entidad Professor...
                $professor = new Professor(
                        $dto->getDni(),
                        $dto->getNames(),
                        $dto->getLastNames(),
                        $dto->getBirthDate(),
                        $dto->getEmail(),
                        $dto->getPhone(),
                        $dto->getSubjects()
                );

                // > 3. Guardar en el repositorio...
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