<?php

namespace Domain\Entities;

/**
 * ~ Entidad User - Representa un usuario en el dominio
 * ~ Contiene solo la lógica de negocio relacionada con el usuario
 */
class User
{
        private string $dni;
        private string $username;
        private string $passwordHash;
        private string $role;
        private ?\DateTime $createdAt;
        private ?\DateTime $updatedAt;

        public function __construct(
                string $dni,
                string $username,
                string $passwordHash,
                string $role,
                ?\DateTime $createdAt,
                ?\DateTime $updatedAt
        ) {
                $this->dni = $dni;
                $this->username = $username;
                $this->passwordHash = $passwordHash;
                $this->role = $role;
                $this->createdAt = $createdAt;
                $this->updatedAt = $updatedAt;
        }

        // * Getters...
        public function getDni(): string
        {
                return $this->dni;
        }

        public function getUsername(): string
        {
                return $this->username;
        }

        public function getPasswordHash(): string
        {
                return $this->passwordHash;
        }

        public function getRole(): string
        {
                return $this->role;
        }

        public function getCreatedAt(): ?\DateTime
        {
                return $this->createdAt;
        }

        public function getUpdatedAt(): ?\DateTime
        {
                return $this->updatedAt;
        }

        /**
         * > Método de negocio: Verificar si la contraseña es correcta...
         */
        public function verifyPassword(string $password): bool
        {
                return password_verify($password, $this->passwordHash);
        }

        /**
         * > Método de negocio: Cambiar la contraseña...
         */
        public function updatePassword(string $newPassword): void
        {
                if (strlen($newPassword) < 8) {
                        throw new \InvalidArgumentException('La contraseña debe tener al menos 8 caracteres...');
                }
                $this->passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
                $this->updatedAt = new \DateTime();
        }

        /**
         * > Método de negocio: Cambiar el rol...
         */
        public function updateRole(string $newRole): void
        {
                $allowedRoles = ['Admin', 'Professor', 'Student'];

                if (!in_array($newRole, $allowedRoles, true)) {
                        throw new \InvalidArgumentException('Rol no válido...');
                }
                $this->role = $newRole;
                $this->updatedAt = new \DateTime();
        }

        /**
         * > Método de negocio: Verificar si es administrador...
         */
        public function isAdmin(): bool
        {
                return $this->role === 'Admin';
        }
}

?>