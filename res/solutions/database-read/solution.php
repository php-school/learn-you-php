<?php

$db = new \PDO($argv[1]);
$users = $db->query('SELECT * FROM users WHERE age > 30');
foreach ($users as $user) {
    echo "User: {$user['name']} Age: {$user['age']} Sex: {$user['gender']}\n";
}
$nameToUpdate = $argv[2];
$stmt = $db->prepare('UPDATE users SET name = :newName WHERE name = :oldName');
$stmt->execute([':newName' => 'David Attenborough', ':oldName' => $nameToUpdate]);