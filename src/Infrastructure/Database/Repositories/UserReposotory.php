<?php

namespace Infrastructure\Database\Repositories;

use Domain\Entities\User;
use Domain\Repositories\UserRepositoryInterface;
use Infrastructure\Database\Connection;
use PDOException;

// ~ Implementación concreta del repositorio de usuarios usando MySQL...
class UserRepository implements UserRepositoryInterface
{
        private Connection $connection;

        public function __construct(Connection $connection)
        {
                $this->connection = $connection;
        }

        public function save(User $user): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'INSERT INTO users (dni, user, password, role, createdAt) 
                                VALUES (?, ?, ?, ?, NOW())';
                        $stmt = $db->prepare($query);

                        return $stmt->execute([
                                $user->getDni(),
                                $user->getUsername(),
                                $user->getPasswordHash(),
                                $user->getRole()
                        ]);
                } catch (PDOException $e) {
                        error_log('Error en UserRepository::save: ' . $e->getMessage());
                        return false;
                }
        }

        public function findByUsername(string $username): ?User
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'SELECT dni, user, password, role, createdAt, updatedAt 
                      FROM users WHERE user = ?';
                        $stmt = $db->prepare($query);
                        $stmt->execute([$username]);
                        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

                        if ($data === false) {
                                return null;
                        }

                        return $this->mapToEntity($data);
                } catch (\PDOException $e) {
                        error_log('Error en UserRepository::findByUsername: ' . $e->getMessage());
                        return null;
                }
        }

        public function findByDni(string $dni): ?User
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'SELECT dni, user, password, role, createdAt, updatedAt 
                          FROM users WHERE dni = ?';
                        $stmt = $db->prepare($query);
                        $stmt->execute([$dni]);
                        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

                        if ($data === false) {
                                return null;
                        }

                        return $this->mapToEntity($data);
                } catch (\PDOException $e) {
                        return null;
                }
        }

        public function findAll(): array
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'SELECT dni, user, password, role, createdAt, updatedAt FROM users';
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                        $users = [];
                        foreach ($results as $data) {
                                $users[] = $this->mapToEntity($data);
                        }

                        return $users;
                } catch (\PDOException $e) {
                        error_log('Error en UserRepository::findAll: ' . $e->getMessage());
                        return [];
                }
        }

        public function update(User $user): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'UPDATE users SET role = ?, password = ?, updatedAt = NOW() 
                      WHERE user = ?';
                        $stmt = $db->prepare($query);
                        return $stmt->execute([
                                $user->getRole(),
                                $user->getPasswordHash(),
                                $user->getUsername()
                        ]);
                } catch (\PDOException $e) {
                        error_log('Error en UserRepository::update: ' . $e->getMessage());
                        return false;
                }
        }

        public function delete(string $username): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'DELETE FROM users WHERE user = ?';
                        $stmt = $db->prepare($query);
                        return $stmt->execute([$username]);
                } catch (\PDOException $e) {
                        error_log('Error en UserRepository::delete: ' . $e->getMessage());
                        return false;
                }
        }

        public function exists(string $dni, string $username): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'SELECT id FROM users WHERE user = ? OR dni = ?';
                        $stmt = $db->prepare($query);
                        $stmt->execute([$username, $dni]);
                        return $stmt->rowCount() > 0;
                } catch (\PDOException $e) {
                        return false;
                }
        }

        // * Mapear datos de la base de datos a la entidad User...
        private function mapToEntity(array $data): User
        {
                $createdAt = $data['createdAt'] ? new \DateTime($data['createdAt']) : null;
                $updatedAt = $data['updatedAt'] ? new \DateTime($data['updatedAt']) : null;

                return new User(
                        $data['dni'],
                        $data['user'],
                        $data['password'],
                        $data['role'],
                        $createdAt,
                        $updatedAt
                );
        }
}

?>