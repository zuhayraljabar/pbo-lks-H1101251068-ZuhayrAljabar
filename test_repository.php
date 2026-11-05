<?php

require __DIR__ . '/vendor/autoload.php';

use App\Config\Env;
use App\Database\Connection;
use App\Repositories\MySQLBookRepository;

// Load .env
Env::load(__DIR__. '/.env');

try {
    $pdo = Connection::get();   // ← pastikan namanya getInstance()
    $repo = new MySQLBookRepository($pdo);

    $books = $repo->findAll();

    echo "Total buku: " . count($books) . PHP_EOL;

    foreach ($books as $book) {
        echo "- {$book->getJudul()} oleh {$book->getPenulis()}" . PHP_EOL;
    }

} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
    echo "File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
}