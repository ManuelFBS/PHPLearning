<?php

namespace Domain\Repositories;

use Domain\Entities\Professor;

interface ProfessorRepositoryInterface
{
        public function save(Professor $professor): bool;
        public function findByDni(string $dni): ?Professor;
        public function findAll(): array;
        public function update(Professor $professor): bool;
        public function delete(string $dni): bool;
        public function exists(string $dni): bool;
        public function emailExists(string $email): bool;
}

?>