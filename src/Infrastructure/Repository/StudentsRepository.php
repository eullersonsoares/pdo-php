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

    public function allStudents(): array
    {
        $statement = $this->connection->query('SELECT * FROM students;');
        $studentDataList = $statement->fetchAll(PDO::FETCH_ASSOC);
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

    public function studentsBirthAt(DateTimeInterface $birth_date): array
    {
        $studentsList = $this->allStudents();

        $bdStudents = [];

        foreach ($studentsList as $student) {
            $bdStudents[] = $student->birthDate;
        }

        return $bdStudents;
    }

    public function save(Student $student): bool
    {
        return false;
    }

    public function remove(Student $student): bool
    {
        return false;
    }
}