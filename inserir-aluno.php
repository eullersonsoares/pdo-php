<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$pdo = \Alura\Pdo\Infrastructure\Persistence\ConnectionCreator::createConnection();

$student = new Student(
    null,
    "Patricia Freitas",
    new \DateTimeImmutable('1986-10-25')
);

$name = $student->name();
$birth_date = $student->birthDate()->format('Y-m-d');

$sqlInsert = "INSERT INTO students (name, birth_date) VALUES (:name, :birth_date);";
$statement = $pdo->prepare($sqlInsert);
$statement->bindParam(':name', $name);
$statement->bindValue(':birth_date', $birth_date);

if ($statement->execute()) {
    echo "Aluno incluído";
}
