<?php

require __DIR__ . '/vendor/autoload.php';

$settings = require __DIR__ . '/app/settings.php';

$host     = 'localhost';
$dbname   = 'slim-task';
$username = 'root';
$password = 'root';

$postsJson     = 'posts.json';
$postsJsonData = file_get_contents($postsJson);
$usersJson     = 'users.json';
$usersJsonData = file_get_contents($usersJson);

try {
    $posts = json_decode($postsJsonData, true, 512, JSON_THROW_ON_ERROR);
    $users = json_decode($usersJsonData, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    echo $e->getMessage();
}

try {
    $pdo = new PDO("mysql:host=$host;", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    $pdo->exec("USE `$dbname`");

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        username VARCHAR(255) NOT NULL
    )'
    );

    $usersTable = $pdo->prepare(
        'INSERT INTO users (name,username) VALUES (:name, :username)'
    );

    foreach ($users as $user) {
        $usersTable->execute([
            ':name'     => $user['name'],
            ':username' => $user['username'],
        ]);
    }

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        body TEXT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )'
    );

    $postsTable = $pdo->prepare(
        'INSERT INTO posts (user_id, title, body) VALUES (:user_id, :title, :body)'
    );

    foreach ($posts as $post) {
        $postsTable->execute([
            ':user_id' => $post['userId'],
            ':title'   => $post['title'],
            ':body'    => $post['body'],
        ]);
    }

    echo 'Data has been imported successfully!';
} catch (PDOException $e) {
    echo $e->getMessage();
}
