<?php

namespace Domain\DTOs\User;

use Domain\DTOs\ValidationResult;

/**
 * ~ DTO para la creación de un usuario...
 * ~ Centraliza los datos de entrada y sus validaciones (formato y reglas básicas).
 */
final class CreateUserDTO
{
        private const ALLOWED_ROLES = ['Admin', 'Professor', 'Student'];
        private const DNILENGTH_MIN = 8;
        private const DNILENGTH_MAX = 10;
        private const USERNAME_LENGTH_MIN = 3;
        private const USERNAME_LENGTH_MAX = 20;
        private const PASSWORD_MIN_LENGTH = 8;

        private string $dni;
        private string $username;
        private string $password;
        private string $role;

        public function __construct(
                string $dni,
                string $username,
                string $password,
                string $role
        ) {
                $this->dni = $dni;
                $this->username = $username;
                $this->password = $password;
                $this->role = $role;
        }

        /**
         * * Crea el DTO desde datos crudos (ej. $_POST) y valida...
         * * Si la validación falla, retorna null y el resultado de validación en $validationResult...
         */
        public static function fromRequest(array $data): array
        {
                $dni = trim($data['dni'] ?? '');
                $username = trim($data['username'] ?? '');
                $password = trim($data['password'] ?? '');
                $role = trim($data['role'] ?? '');

                $errors = [];

                // Campos requeridos
                if ($dni === '') {
                        $errors['dni'] = 'El DNI es obligatorio.';
                }
                if ($username === '') {
                        $errors['user'] = 'El nombre de usuario es obligatorio.';
                }
                if ($password === '') {
                        $errors['password'] = 'La contraseña es obligatoria.';
                }
                if ($role === '') {
                        $errors['role'] = 'El rol es obligatorio.';
                }

                if ($errors !== []) {
                        return [null, ValidationResult::failure($errors)];
                }

                // > Formato DNI: solo números, longitud 8-10...
                if (!preg_match('/^\d{'
                                . self::DNILENGTH_MIN
                                . ','
                                . self::DNILENGTH_MAX
                                . '}$/', $dni)) {
                        $errors['dni'] =
                                'El DNI debe contener entre '
                                . self::DNILENGTH_MIN
                                . 'y '
                                . self::DNILENGTH_MAX . 'caracteres';
                }

                // > Formato username: alfanumérico y guión bajo, 3-20...
                if (!preg_match('/^[a-zA-Z0-9_]{'
                                . self::USERNAME_LENGTH_MIN
                                . ','
                                . self::USERNAME_LENGTH_MAX
                                . '}$/', $username)) {
                        $errors['user'] =
                                'El nombre de usuario debe tener entre '
                                . self::USERNAME_LENGTH_MIN
                                . ' y '
                                . self::USERNAME_LENGTH_MAX
                                . ' caracteres (letras, números o _).';
                }

                // > Contraseña mínima...
                if (strlen($password) < self::PASSWORD_MIN_LENGTH) {
                        $errors['password'] =
                                'La contraseña debe tener al menos '
                                . self::PASSWORD_MIN_LENGTH
                                . ' caracteres.';
                }

                // > Rol permitido...
                if (!in_array($role, self::ALLOWED_ROLES, true)) {
                        $errors['role'] =
                                'Rol no válido. Debe ser: '
                                . implode(', ', self::ALLOWED_ROLES);
                }

                if ($errors !== []) {
                        return [null, ValidationResult::failure($errors)];
                }

                return [new self(
                        $dni,
                        $username,
                        $password,
                        $role
                ), ValidationResult::success()];
        }

        public function getDni(): string
        {
                return $this->dni;
        }

        public function getUsername(): string
        {
                return $this->username;
        }

        public function getPassword(): string
        {
                return $this->password;
        }

        public function getRole(): string
        {
                return $this->role;
        }
}

?>