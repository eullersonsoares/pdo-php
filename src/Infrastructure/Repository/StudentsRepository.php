<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Model\Repository\StudentRepository;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use DateTimeInterface;
use PDO;

class StudentsRepository implements StudentRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionCreator::createConnection();
    }

    public function hydrateStudentList(\PDOStatement $stmt): array
    {
        $studentDataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $studentList = [];

        foreach ($studentDataList as $studentData) {
            $studentList[] = new Student(
                $studentData['id'],
                $studentData['name'],
                new \DateTimeImmutable($studentData['birth_date'])
            );
        }

        return $studentList;
    }

    public function allStudents(): array
    {
        $selectQuery = 'SELECT * FROM students';
        $stmt = $this->connection->query($selectQuery);

        return $this->hydrateStudentList($stmt);
    }

    public function studentsBirthAt(DateTimeInterface $birthDate): array
    {
        $sqlQuery = "SELECT * FROM students WHERE birth_date = ?;";
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(1, $birthDate->format("Y-m-d"));
        $stmt->execute();

        return $this->hydrateStudentList($stmt);
    }

    public function insert(Student $student): bool
    {
        $insertQuery = 'INSERT INTO students (name, birth_date) VALUES (:name, :birth_date);';
        $stmt = $this->connection->prepare($insertQuery);

        $success = $stmt->execute([
            ":name" => $student->name(),
            ":name" => $student->birthDate()->format('Y-m-d'),
        ]);

        if($success) {
            $student->defineId($this->connection->lastInsertId());
        }

        return $success;
    }

    public function update(Student $student):bool
    {
        $updateQuery = "UPDATE students SET name= :name, birth_date= :birth_date WHERE id = :id;";
        $stmt = $this->connection->prepare($updateQuery);
        $stmt->bindValue(":id", $student->id());
        $stmt->bindValue(":name", $student->name());
        $stmt->bindValue(":birth_date", $student->birthDate()->format("Y-m-d"), PDO::PARAM_INT);
        return $stmt->execute();        
    }

    public function save(Student $student): bool
    {
        if($student->id() === null) {
            return $this->insert($student);
        }

        return $this->update($student);
    }

    public function remove(Student $student): bool
    {
        $stmt = $this->connection->prepare("DELETE FROM students WHERE id=?;");
        $stmt->bindValue(1, $student->id(), PDO::PARAM_INT);
        return $stmt->execute();
    }
}