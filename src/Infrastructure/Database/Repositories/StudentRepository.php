<?php

namespace Infrastructure\Database\Repositories;

use Domain\Entities\Student;
use Domain\Repositories\StudentRepositoryInterface;
use Infrastructure\Database\Connection;
use PDOException;

class StudentRepository implements StudentRepositoryInterface
{
        private Connection $connection;

        public function __construct(Connection $connection)
        {
                $this->connection = $connection;
        }

        public function save(Student $student): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'INSERT INTO students (
                                dni, 
                                names, 
                                lastNames, 
                                birthDate, 
                                email, 
                                phone, 
                                dateEntry, 
                                willTakeSubjects, 
                                semester, 
                                createdAt) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())';
                        $stmt = $db->prepare($query);

                        return $stmt->execute([
                                $student->getDni(),
                                $student->getNames(),
                                $student->getLastNames(),
                                $student->getBirthDate()->format('Y-m-d'),
                                $student->getEmail(),
                                $student->getPhone(),
                                $student->getDateEntry()->format('Y-m-d'),
                                $student->getSubjects(),
                                $student->getSemester()
                        ]);
                } catch (PDOException $e) {
                        error_log('Error en StudentRepository::save: ' . $e->getMessage());
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
                                dateEntry, 
                                willTakeSubjects, 
                                semester, 
                                createdAt 
                                FROM students';
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                        $students = [];
                        foreach ($results as $data) {
                                $students[] = $this->mapToEntity($data);
                        }

                        return $students;
                } catch (PDOException $e) {
                        error_log('Error en StudentRepository::findAll: ' . $e->getMessage());
                        return [];
                }
        }

        public function findByDni(string $dni): ?student
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
                                dateEntry, 
                                willTakeSubjects, 
                                semester, 
                                createdAt, 
                                updatedAt 
                          FROM students WHERE dni = ?';
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

        public function update(student $student): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'UPDATE students 
                                SET names = ?, 
                                        lastNames = ?, 
                                        birthDate = ?, 
                                        email = ?, 
                                        phone = ?, 
                                        dateEntry = ?, 
                                        willTakeSubjects = ?, 
                                        semester = ?, 
                                        updatedAt = NOW() 
                                WHERE dni = ?';
                        $stmt = $db->prepare($query);
                        return $stmt->execute([
                                $student->getNames(),
                                $student->getLastNames(),
                                $student->getBirthDate()->format('Y-m-d'),
                                $student->getEmail(),
                                $student->getPhone(),
                                $student->getDateEntry()->format('Y-m-d'),
                                $student->getSubjects(),
                                $student->getSemester(),
                                $student->getDni()  // dni al final para el WHERE...
                        ]);
                } catch (PDOException $e) {
                        error_log('Error en studentRepository::update: ' . $e->getMessage());
                        return false;
                }
        }

        public function delete(string $dni): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'DELETE FROM students WHERE dni = ?';
                        $stmt = $db->prepare($query);
                        return $stmt->execute([$dni]);
                } catch (PDOException $e) {
                        error_log('Error en studentRepository::delete: ' . $e->getMessage());
                        return false;
                }
        }

        public function exists(string $dni): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'SELECT id FROM students WHERE dni = ?';
                        $stmt = $db->prepare($query);
                        $stmt->execute([$dni]);
                        return $stmt->rowCount() > 0;
                } catch (PDOException $e) {
                        return false;
                }
        }

        public function emailExists(string $email): bool
        {
                try {
                        $db = $this->connection->connect();
                        $query = 'SELECT id FROM students WHERE email = ?';
                        $stmt = $db->prepare($query);
                        $stmt->execute([$email]);

                        return $stmt->rowCount() > 0;
                } catch (\PDOException $e) {
                        return false;
                }
        }

        // * Mapear datos de la base de datos a la entidad Student...
        private function mapToEntity(array $data): student
        {
                // > Convertir birthDate y dateEntry: si vienen null, lanzar excepción...
                $birthDate = null;
                $dateEntry = null;
                if (!empty($data['birthDate']) && !empty($data['dateEntry'])) {
                        $birthDate = new \DateTime($data['birthDate']);
                        $dateEntry = new \DateTime($data['dateEntry']);
                } else {
                        throw new \InvalidArgumentException('La fecha de nacimiento es requerida...');
                        // $birthDate = new \DateTime('1900-01-01');
                }

                return new student(
                        $data['dni'],
                        $data['names'],
                        $data['lastNames'],
                        $birthDate,
                        $data['email'],
                        $data['phone'],
                        $dateEntry,
                        $data['willTakeSubjects'],
                        $data['semester'],
                );
        }
}

?>