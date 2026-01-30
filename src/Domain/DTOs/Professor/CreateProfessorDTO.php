<?php

namespace Domain\DTOs\Professor;

use Domain\DTOs\ValidationResult;

/**
 * * DTO para la creación de un profesor...
 * * Centraliza los datos de entrada y sus validaciones (formato y reglas básicas).
 */
final class CreateProfessorDTO
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
        private string $subjects;

        private function __construct(
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

        /**
         * * Crea el DTO desde datos crudos (ej. $_POST) y valida...
         * * Si la validación falla, retorna null y el resultado de validación
         * * en $validationResult...
         */
        public static function fromRequest(array $data): array
        {
                $dni = trim($data['dni'] ?? '');
                $names = trim($data['names'] ?? '');
                $lastNames = trim($data['lastNames'] ?? '');
                $birthDateRaw = trim($data['birthDate'] ?? '');
                $email = trim($data['email'] ?? '');
                $phoneRaw = trim($data['phone'] ?? '');
                $subjects = trim($data['subjects'] ?? '');

                $errors = [];

                // > Campos requeridos...
                if ($dni === '') {
                        $errors['dni'] = 'El DNI es obligatorio.';
                }
                if ($names === '') {
                        $errors['names'] = 'Los nombres son obligatorios.';
                }
                if ($lastNames === '') {
                        $errors['lastNames'] = 'Los apellidos son obligatorios.';
                }
                if ($birthDateRaw === '') {
                        $errors['birthDate'] = 'La fecha de nacimiento es obligatoria.';
                }
                if ($email === '') {
                        $errors['email'] = 'El correo electrónico es obligatorio.';
                }
                if ($subjects === '') {
                        $errors['subjects'] = 'Las asignaturas son obligatorias.';
                }

                if ($errors !== []) {
                        return [null, ValidationResult::failure($errors)];
                }

                // > Formato DNI: solo números, longitud 8-10...
                if (!preg_match('/^\d{'
                                . self::DNI_LENGTH_MIN
                                . ','
                                . self::DNI_LENGTH_MAX
                                . '}$/', $dni)) {
                        $errors['dni'] =
                                'El DNI debe contener entre '
                                . self::DNI_LENGTH_MIN
                                . ' y '
                                . self::DNI_LENGTH_MAX
                                . ' dígitos numéricos.';
                }

                // > Nombres y apellidos: longitud...
                if (strlen($names) <
                        self::NAMES_LENGTH_MIN ||
                        strlen($names) >
                                self::NAMES_LENGTH_MAX) {
                        $errors['names'] =
                                'Los apellidos deben tener entre '
                                . self::NAMES_LENGTH_MIN
                                . ' y '
                                . self::NAMES_LENGTH_MAX
                                . ' caracteres';
                }
                if (strlen($lastNames) <
                        self::NAMES_LENGTH_MIN ||
                        strlen($lastNames) >
                                self::NAMES_LENGTH_MAX) {
                        $errors['lastNames'] =
                                'Los apellidos deben tener entre '
                                . self::NAMES_LENGTH_MIN
                                . ' y '
                                . self::NAMES_LENGTH_MAX
                                . ' caracteres.';
                }

                // > Fecha de nacimiento: formato Y-m-d...
                $birthDate = \DateTime::createFromFormat('Y-m-d', $birthDateRaw);
                if ($birthDate === false) {
                        $errors['birthDate'] =
                                'La fecha de nacimiento no es válida. Use el formato AAAA-MM-DD.';
                }

                // > Email...
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = 'El correo electrónico no es válido.';
                }

                // > Teléfono: opcional; si viene algo, debe cumplir formato...
                $phone = $phoneRaw === '' ? null : $phoneRaw;
                if ($phone !== null && !preg_match(self::PHONE_REGEX, $phone)) {
                        $errors['phone'] =
                                'El teléfono debe tener entre 6 y 20 caracteres (números, +, - o espacios).';
                }

                if ($errors !== []) {
                        return [null, ValidationResult::failure($errors)];
                }

                return [
                        new self(
                                $dni,
                                $names,
                                $lastNames,
                                $birthDate,
                                $email,
                                $phone,
                                $subjects,
                                ValidationResult::success()
                        )
                ];
        }

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
}

?>