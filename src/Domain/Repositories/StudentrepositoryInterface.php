<?php

namespace Domain\Repositories;

use Domain\Entities\Student;

interface StudentRepositoryInterface
{
        public function save(Student $student): bool;
        public function findByDni(string $dni): ?Student;
        public function findAll(): array;
        public function update(Student $student): bool;
        public function delete(string $dni): bool;
        public function exists(string $dni): bool;
}

?>