<?php

namespace Domain\DTOs\Professor;

use Domain\DTOs\ValidationResult;

/**
 * * DTO para la actualización de un profesor...
 * Todos los campos son opcionales; se validan solo los enviados...
 */
final class UpdateProfessorDTO
{
        private const NAMES_MIN_LENGTH = 2;
        private const NAMES_MAX_LENGTH = 100;
        private const PHONE_REGEX = '/^[0-9+\-\s]{6,20}$/';

        private ?string $names;
        private ?string $lastNames;
        private ?\DateTime $birthDate;
        private ?string $email;
        private ?string $phone;
        private ?string $subjects;

        private function __construct(
                ?string $names,
                ?string $lastNames,
                ?\DateTime $birthDate,
                ?string $email,
                ?string $phone,
                ?string $subjects
        ) {
                $this->names = $names;
                $this->lastNames = $lastNames;
                $this->birthDate = $birthDate;
                $this->email = $email;
                $this->phone = $phone;
                $this->subjects = $subjects;
        }

        /**
         * > Crea el DTO desde datos crudos (ej. $_POST).
         * > Retorna [UpdateProfessorDTO|null, ValidationResult].
         */
        public static function fromRequest(array $data): array
        {
                $namesRaw = isset($data['names']) ? trim($data['names']) : '';
                $lastNamesRaw = isset($data['lastNames']) ? trim($data['lastNames']) : '';
                $birthDateRaw = isset($data['birthDate']) ? trim($data['birthDate']) : '';
                $emailRaw = isset($data['email']) ? trim($data['email']) : '';
                $phoneRaw = isset($data['phone']) ? trim($data['phone']) : '';
                $subjectsRaw = isset($data['subjects']) ? trim($data['subjects']) : '';

                $names = $namesRaw !== '' ? $namesRaw : null;
                $lastNames = $lastNamesRaw !== '' ? $lastNamesRaw : null;
                $email = $emailRaw !== '' ? $emailRaw : null;
                $phone = $phoneRaw !== '' ? $phoneRaw : null;
                $subjects = $subjectsRaw !== '' ? $subjectsRaw : null;

                $birthDate = null;
                if ($birthDateRaw !== '') {
                        $birthDate = \DateTime::createFromFormat('Y-m-d', $birthDateRaw);
                        if ($birthDate === false) {
                                return [null, ValidationResult::failure([
                                        'birthDate' => 'La fecha de nacimiento no es válida. Use el formato AAAA-MM-DD.'
                                ])];
                        }
                }

                $errors = [];

                if ($names !== null && (strlen($names) < self::NAMES_MIN_LENGTH || strlen($names) > self::NAMES_MAX_LENGTH)) {
                        $errors['names'] = 'Los nombres deben tener entre ' . self::NAMES_MIN_LENGTH . ' y ' . self::NAMES_MAX_LENGTH . ' caracteres.';
                }
                if ($lastNames !== null && (strlen($lastNames) < self::NAMES_MIN_LENGTH || strlen($lastNames) > self::NAMES_MAX_LENGTH)) {
                        $errors['lastNames'] = 'Los apellidos deben tener entre ' . self::NAMES_MIN_LENGTH . ' y ' . self::NAMES_MAX_LENGTH . ' caracteres.';
                }
                if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = 'El correo electrónico no es válido.';
                }
                if ($phone !== null && !preg_match(self::PHONE_REGEX, $phone)) {
                        $errors['phone'] = 'El teléfono debe tener entre 6 y 20 caracteres (números, +, - o espacios).';
                }

                if ($errors !== []) {
                        return [null, ValidationResult::failure($errors)];
                }

                return [
                        new self($names, $lastNames, $birthDate, $email, $phone, $subjects),
                        ValidationResult::success()
                ];
        }

        public function getNames(): ?string
        {
                return $this->names;
        }

        public function getLastNames(): ?string
        {
                return $this->lastNames;
        }

        public function getBirthDate(): ?\DateTime
        {
                return $this->birthDate;
        }

        public function getEmail(): ?string
        {
                return $this->email;
        }

        public function getPhone(): ?string
        {
                return $this->phone;
        }

        public function getSubjects(): ?string
        {
                return $this->subjects;
        }
}

?>