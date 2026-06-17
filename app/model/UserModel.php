<?php

declare(strict_types=1);

final class UserModel
{
    public function findById(int $id): ?array
    {
        // Utilise par la session pour retrouver l'utilisateur connecte.
        $stmt = Database::connection()->prepare('SELECT * FROM utilisateur WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function authenticate(string $login, string $password): ?array
    {
        // F1 : mots de passe en clair car le SQL fourni fonctionne comme ca.
        $stmt = Database::connection()->prepare(
            'SELECT * FROM utilisateur WHERE login = :login AND password = :password'
        );
        $stmt->execute([
            'login' => $login,
            'password' => $password,
        ]);

        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function all(): array
    {
        // A1 : liste complete des utilisateurs pour l'administrateur.
        return Database::connection()
            ->query('SELECT nom, prenom, role, login, password, solde FROM utilisateur ORDER BY id')
            ->fetchAll();
    }

    public function byRole(string $role): array
    {
        // Sert aux formulaires qui doivent choisir un conducteur/passager.
        $stmt = Database::connection()->prepare(
            'SELECT id, nom, prenom, login, solde FROM utilisateur WHERE role = :role ORDER BY nom, prenom'
        );
        $stmt->execute(['role' => $role]);
        return $stmt->fetchAll();
    }

    public function loginExists(string $login): bool
    {
        // Evite de creer deux comptes avec le meme login.
        $stmt = Database::connection()->prepare('SELECT id FROM utilisateur WHERE login = :login LIMIT 1');
        $stmt->execute(['login' => $login]);
        return (bool) $stmt->fetch();
    }

    public function add(string $role, string $nom, string $prenom, string $login, string $password, float $solde): int
    {
        // A2/A3 : insertion d'un utilisateur avec le role demande.
        $id = Database::nextId('utilisateur');
        $stmt = Database::connection()->prepare(
            'INSERT INTO utilisateur (id, nom, prenom, role, login, password, solde)
             VALUES (:id, :nom, :prenom, :role, :login, :password, :solde)'
        );
        $stmt->execute([
            'id' => $id,
            'nom' => $nom,
            'prenom' => $prenom,
            'role' => $role,
            'login' => $login,
            'password' => $password,
            'solde' => $solde,
        ]);

        return $id;
    }
}
