<?php

abstract class Entite
{
    protected PDO $pdo;
    protected ?int $id;

    // Constructeur
    public function __construct(PDO $pdo, ?int $id = null)
    {
        $this->pdo = $pdo;
        $this->id = $id;
    }

    // Retourne l'identifiant
    public function getId(): ?int
    {
        return $this->id;
    }

    // Chaque classe enfant doit avoir une méthode supprimer
    abstract public function supprimer(int $id): bool;
}