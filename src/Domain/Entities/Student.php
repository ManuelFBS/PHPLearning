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
        public function updateNames(string $names): void
        {
                if (trim($names) === '') {
                        throw new \InvalidArgumentException('Los nombres NO pueden estar vacíos...');
                }

                $this->names = $names;
        }

        public function updateLastNames(string $lastNames): void
        {
                if (trim($lastNames) === '') {
                        throw new \InvalidArgumentException('Los apellidos NO pueden estar vacíos...');
                }

                $this->lastNames = $lastNames;
        }

        public function updateBirthDate(\DateTime $birthDate): void
        {
                $this->birthDate = $birthDate;
        }

        public function updateEmail(string $email): void
        {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        throw new \InvalidArgumentException('Email no válido...');
                }

                $this->email = $email;
        }

        public function updatePhone(?string $phone): void
        {
                if ($phone !== null && !preg_match('/^[0-9+\-\s]{6,20}$/', $phone)) {
                        throw new \InvalidArgumentException('Teléfono NO válido...');
                }

                $this->phone = $phone;
        }

        public function updateDateEntry(\DateTime $dateEntry): void
        {
                $this->dateEntry = $dateEntry;
        }

        public function updateSubjects(string $subjects): void
        {
                if (trim($subjects) === '') {
                        throw new \InvalidArgumentException('Las asignaturas NO pueden estar vacías...');
                }

                $this->subjects = $subjects;
        }

        public function updateSemester(int $semester): void
        {
                if ($semester < 1 || $semester > 10) {
                        throw new \InvalidArgumentException('El semestre debe estar entre 1 y 10');
                }
                $this->semester = $semester;
        }
}

?>