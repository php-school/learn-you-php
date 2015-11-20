<?php

$db = new \PDO($argv[1]);

$users = $db->query('SELECT * FROM users');

foreach ($users as $user) {
    var_dump($user);
    echo "\n";
}
