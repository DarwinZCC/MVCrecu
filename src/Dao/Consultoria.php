<?php

namespace App\Dao;

use App\Utilities\Database;
use PDO;

class Consultoria
{
    public static function getAll(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM asignaciones_docente ORDER BY fecha_creacion DESC;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById(int $id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM asignaciones_docente WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function insert(string $nombre, ?string $descripcion)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO asignaciones_docente (nombre, descripcion) VALUES (?, ?)");
        $stmt->execute([$nombre, $descripcion]);
        return $stmt->rowCount();
    }

    public static function update(int $id, string $nombre, ?string $descripcion)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE asignaciones_docente SET nombre = ?, descripcion = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $id]);
        return $stmt->rowCount();
    }

    public static function delete(int $id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM asignaciones_docente WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}