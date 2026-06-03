<?php

declare(strict_types=1);

final class UserModel
{
    public function findById(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM utilisateur WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function authenticate(string $login, string $password): ?array
    {
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
        return Database::connection()
            ->query('SELECT nom, prenom, role, login, password, solde FROM utilisateur ORDER BY id')
            ->fetchAll();
    }

    public function byRole(string $role): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, nom, prenom, login, solde FROM utilisateur WHERE role = :role ORDER BY nom, prenom'
        );
        $stmt->execute(['role' => $role]);
        return $stmt->fetchAll();
    }

    public function loginExists(string $login): bool
    {
        $stmt = Database::connection()->prepare('SELECT id FROM utilisateur WHERE login = :login LIMIT 1');
        $stmt->execute(['login' => $login]);
        return (bool) $stmt->fetch();
    }

    public function add(string $role, string $nom, string $prenom, string $login, string $password, float $solde): int
    {
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
