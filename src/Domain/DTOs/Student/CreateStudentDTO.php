<?php

namespace Domain\DTOs\Student;

use Domain\DTOs\ValidationResult;

/**
 * ~ DTO para la creación de un estudiante...
 * ~ Centraliza los datos de entrada y sus validaciones (formato y reglas básicas).
 */
final class CreateStudentDTO
{
        private const DNI_LENGTH_MIN = 8;
        private const DNI_LENGTH_MAX = 10;
        private const NAMES_LENGTH_MIN = 2;
        private const NAMES_LENGTH_MAX = 100;
        private const PHONE_REGEX = '/^[0-9+\-\s]{6,20}$/';

        private string $dni;
        private string $names;
        private string $lastNames;
        private \DateTime $birthDate;
        private string $email;
        private ?string $phone;
        private \DateTime $dateEntry;
        private ?string $subjects;
        private int $semester;
}

?>