<?php

use Alura\Pdo\Infrastructure\Repository\StudentsRepository;

require_once 'vendor/autoload.php';

$estudantes = new StudentsRepository();

$lista = $estudantes->allStudents();

foreach ($lista as $aluno) {
    echo $aluno->name() . PHP_EOL;
}