<?php

namespace Domain\DTOs\User;

use Domain\DTOs\ValidationResult;

final class UpdateUserDTO
{
        private const ALLOWED_ROLES = ['Admin', 'Professor', 'Student'];
        private const PASSWORD_MIN_LENGTH = 8;

        private ?string $password;
        private ?string $role;

        private function __construct(?string $password, ?string $role)
        {
                $this->password = $password;
                $this->role = $role;
        }

        public static function fromRequest(array $data): array
        {
                $passwordRaw = isset($data['password']) ? trim($data['password']) : '';
                $password = $passwordRaw !== '' ? $passwordRaw : null;
                $roleRaw = isset($data['role']) ? trim($data['role']) : '';
                $role = $roleRaw !== '' ? $roleRaw : null;

                $errors = [];

                if ($password !== null && strlen($password) < self::PASSWORD_MIN_LENGTH) {
                        $errors['password'] = 'La contraseña debe tener al menos ' . self::PASSWORD_MIN_LENGTH . ' caracteres.';
                }
                if ($role !== null && !in_array($role, self::ALLOWED_ROLES, true)) {
                        $errors['role'] = 'Rol no válido. Debe ser: ' . implode(', ', self::ALLOWED_ROLES);
                }

                if ($errors !== []) {
                        return [null, ValidationResult::failure($errors)];
                }

                return [new self($password, $role), ValidationResult::success()];
        }

        public function getPassword(): ?string
        {
                return $this->password;
        }

        public function getRole(): ?string
        {
                return $this->role;
        }
}

?>