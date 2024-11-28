<?php

use Alura\Pdo\Infrastructure\Repository\StudentsRepository;

require_once 'vendor/autoload.php';

$estudantes = new StudentsRepository();

var_dump($estudantes->studentsBirthAt());