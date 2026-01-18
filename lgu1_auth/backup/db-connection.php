<?php
// db.php

function loadEnv($path)
{
    if (!file_exists($path)) {
        throw new Exception('.env file not found');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;

        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

try {
    loadEnv(__DIR__ . '/../.env');

    $host    = $_ENV['DB_HOST'];
    $db      = $_ENV['DB_NAME'];
    $user    = $_ENV['DB_USER'];
    $pass    = $_ENV['DB_PASS'];
    $charset = $_ENV['DB_CHARSET'];

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    die("Environment error: " . $e->getMessage());
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
