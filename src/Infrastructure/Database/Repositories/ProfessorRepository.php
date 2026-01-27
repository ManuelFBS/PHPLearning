<?php

namespace Infrastructure\Database\Repositories;

use Domain\Entities\Professor;
use Domain\Repositories\ProfessorRepositoryInterface;
use Infrastructure\Database\Connection;
use PDOException;

class ProfessorRepository implements ProfessorRepositoryInterface
{
        private Connection $connection;

        public function __construct(Connection $connection)
        {
                $this->connection = $connection;
        }

        public function save(Professor $professor): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'INSERT INTO professors (
                                dni, 
                                names, 
                                lastNames, 
                                birthDate, 
                                email, 
                                phone, 
                                willTeachSubjects, 
                                createdAt) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())';
                        $stmt = $db->prepare($query);

                        return $stmt->execute([
                                $professor->getDni(),
                                $professor->getNames(),
                                $professor->getLastNames(),
                                $professor->getBirthDate(),
                                $professor->getEmail(),
                                $professor->getPhone(),
                                $professor->getSubjects()
                        ]);
                } catch (PDOException $e) {
                        error_log('Error en UserRepository::save: ' . $e->getMessage());
                        return false;
                }
        }

        public function findAll(): array
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'SELECT 
                                dni, 
                                names, 
                                lastNames, 
                                birthDate, 
                                email, 
                                phone, 
                                willTeachSubjects, 
                                createdAt, 
                                updatedAt
                                FROM professors';
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                        $professors = [];
                        foreach ($results as $data) {
                                $professors[] = $this->mapToEntity($data);
                        }

                        return $professors;
                } catch (\PDOException $e) {
                        error_log('Error en ProfessorRepository::findAll: ' . $e->getMessage());
                        return [];
                }
        }

        public function findByDni(string $dni): ?Professor
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'SELECT 
                                dni, 
                                names, 
                                lastNames, 
                                birthDate, 
                                email, 
                                phone, 
                                willTeachSubjects, 
                                createdAt, 
                                updatedAt 
                          FROM professors WHERE dni = ?';
                        $stmt = $db->prepare($query);
                        $stmt->execute([$dni]);
                        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

                        if ($data === false) {
                                return null;
                        }

                        return $this->mapToEntity($data);
                } catch (PDOException $e) {
                        return null;
                }
        }

        public function update(Professor $professor): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'UPDATE professors 
                                SET names = ?, 
                                        lastNames = ?, 
                                        birthDate = ?, 
                                        email = ?, 
                                        phone = ?, 
                                        willTeachSubjects = ?, 
                                        updatedAt = NOW() 
                                WHERE dni = ?';
                        $stmt = $db->prepare($query);
                        return $stmt->execute([
                                $professor->getDni(),
                                $professor->getNames(),
                                $professor->getLastNames(),
                                $professor->getBirthDate(),
                                $professor->getEmail(),
                                $professor->getPhone(),
                                $professor->getSubjects()
                        ]);
                } catch (\PDOException $e) {
                        error_log('Error en ProfessorRepository::update: ' . $e->getMessage());
                        return false;
                }
        }

        public function delete(string $dni): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'DELETE FROM professors WHERE dni = ?';
                        $stmt = $db->prepare($query);
                        return $stmt->execute([$dni]);
                } catch (\PDOException $e) {
                        error_log('Error en ProfessorRepository::delete: ' . $e->getMessage());
                        return false;
                }
        }

        public function exists(string $dni): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'SELECT id FROM professors WHERE dni = ?';
                        $stmt = $db->prepare($query);
                        $stmt->execute([$dni]);

                        return $stmt->rowCount() > 0;
                } catch (\PDOException $e) {
                        return false;
                }
        }

        public function emailExists(string $email): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'SELECT id FROM professors WHERE email = ?';
                        $stmt = $db->prepare($query);
                        $stmt->execute([$email]);

                        return $stmt->rowCount() > 0;
                } catch (\PDOException $e) {
                        return false;
                }
        }

        // * Mapear datos de la base de datos a la entidad User...
        private function mapToEntity(array $data): Professor
        {
                // $createdAt = $data['createdAt'] ? new \DateTime($data['createdAt']) : null;
                // $updatedAt = $data['updatedAt'] ? new \DateTime($data['updatedAt']) : null;

                // > Convertir birthDate: si viene null, usar una fecha por defecto o lanzar excepción...
                $birthDate = null;
                if (!empty($data['birthDate'])) {
                        $birthDate = new \DateTime($data['birthDate']);
                } else {
                        // throw new \InvalidArgumentException('La fecha de nacimiento es requerida...');
                        $birthDate = new \DateTime('1900-01-01');
                }

                return new Professor(
                        $data['dni'],
                        $data['names'],
                        $data['lastNames'],
                        $birthDate,
                        $data['email'],
                        $data['phone'] ?? null,
                        $data['willTeachSubjects'],
                );
        }
}

?>