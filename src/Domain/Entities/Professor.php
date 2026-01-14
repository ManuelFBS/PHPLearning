<?php

namespace Domain\Entities;

class Professor
{
        private string $dni;
        private string $names;
        private string $lastNames;
        private \DateTime $birthDate;
        private string $email;
        private ?string $phone;
        private string $subjects;

        public function __construct(
                string $dni,
                string $names,
                string $lastNames,
                \DateTime $birthDate,
                string $email,
                ?string $phone,
                string $subjects
        ) {
                $this->dni = $dni;
                $this->names = $names;
                $this->lastNames = $lastNames;
                $this->birthDate = $birthDate;
                $this->email = $email;
                $this->phone = $phone;
                $this->subjects = $subjects;
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

        public function getSubjects(): string
        {
                return $this->subjects;
        }

        // * Setters de validación...
        public function updateEmail(string $email): void
        {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        throw new \InvalidArgumentException('Email no válido...');
                }
                $this->email = $email;
        }
}

?>