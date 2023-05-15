<?php

namespace App\Model\Test;

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

use App\Model\BookModel;
use Faker\Factory;

$bookModel = new BookModel();

$numberOfBooks = 50;

for($i=1; $i < $numberOfBooks; $i ++){
    $title = $faker->text();
    $content = $faker->text();
    $userId = $faker->id();

echo sprintf("
title: %s
content: %s
userId: %s
", $title, $content, $userId);
}

//$bookModel->create($title, $content, $userId);






