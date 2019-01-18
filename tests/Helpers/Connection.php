<?php
class Connection
{
    public static function get() : PDO
    {
        $pdo = new PDO("sqlite::memory:", null, null, [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]);
        $pdo->exec("CREATE TABLE `reset_requests` 
        (
          `user_id` INTEGER NOT NULL PRIMARY KEY,
          `token` varchar(512) NOT NULL,
          `generated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
        );");
        
        return $pdo;
    }
}