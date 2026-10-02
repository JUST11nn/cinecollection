<?php

class Database
{
    private string $host = "127.0.0.1";
    private string $dbname = "librairie_db";
    private string $username = "root";
    private string $password = "";
    private int $port = 3307;

    // Connexion à la base de données
    public function getConnection(): PDO
    {
        try {
            $pdo = new PDO(
                "mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8mb4",
                $this->username,
                $this->password
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        }
        catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}