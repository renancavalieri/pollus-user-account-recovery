<?php
class Connection
{
    public static function get() : PDO
    {
        $pdo = new PDO("sqlite::memory:", null, null, [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]);
        $pdo->exec("CREATE TABLE `reset_requests` 
        (
          `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
          `user_id` INTEGER NOT NULL,
          `token` varchar(512) NOT NULL UNIQUE,
          `generated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `valid` bit(1) NOT NULL
        );");
        
        return $pdo;
    }
}