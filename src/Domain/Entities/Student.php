<?php

namespace Domain\Entities;

Class Student
{
        private string $dni;
        private string $names;
        private string $lastNames;
        private \DateTime $birthDate;
        private string $email;
        private ?string $phone;
        private \DateTime $dateEntry;
        private ?string $subjects;
        private int $semester;

        public function __construct(
                string $dni,
                string $names,
                string $lastNames,
                \DateTime $birthDate,
                string $email,
                ?string $phone,
                \DateTime $dateEntry,
                ?string $subjects,
                int $semester
        ) {
                $this->dni = $dni;
                $this->names = $names;
                $this->lastNames = $lastNames;
                $this->birthDate = $birthDate;
                $this->email = $email;
                $this->phone = $phone;
                $this->dateEntry = $dateEntry;
                $this->subjects = $subjects;
                $this->semester = $semester;
        }

        // * Getters...

        public function getDni(): string
        {
                return $this->dni;
        }

        public function getNames(): string
        {
                return $this->names;
        }

        public function getLastNames(): string
        {
                return $this->lastNames;
        }

        public function getBirthDate(): \DateTime
        {
                return $this->birthDate;
        }

        public function getEmail(): string
        {
                return $this->email;
        }

        public function getPhone(): ?string
        {
                return $this->phone;
        }

        public function getDateEntry(): \DateTime
        {
                return $this->dateEntry;
        }

        public function getSubjects(): ?string
        {
                return $this->subjects;
        }

        public function getSemester(): int
        {
                return $this->semester;
        }

        // * Métodos de negocio...
        public function updateSemester(int $semester): void
        {
                if ($semester < 1 || $semester > 10) {
                        throw new \InvalidArgumentException('El semestre debe estar entre 1 y 10');
                }
                $this->semester = $semester;
        }
}

?>