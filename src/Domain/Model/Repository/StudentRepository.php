<?php

namespace Alura\Pdo\Domain\Model\Repository;

use Alura\Pdo\Domain\Model\Student;

interface StudentRepository {
    public function allStudents():array;
    public function studentsBirthAt(\DateTimeInterface $birth_date): array;
    public function save( Student $student): bool;
    public function remove( Student $student): bool;
}