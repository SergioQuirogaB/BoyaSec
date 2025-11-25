<?php

namespace Models;

use PDO;

class Rule
{
    private PDO $db;

    public function __construct()
    {
        $this->db = get_pdo();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM rules ORDER BY created_at DESC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM rules WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $rule = $stmt->fetch();
        return $rule ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO rules (name, type, threshold, window_seconds, conditions, is_active, created_at)
             VALUES (:name, :type, :threshold, :window_seconds, :conditions, :is_active, NOW())'
        );

        $stmt->execute([
            'name' => $data['name'],
            'type' => $data['type'],
            'threshold' => $data['threshold'],
            'window_seconds' => $data['window_seconds'],
            'conditions' => json_encode($data['conditions'] ?? []),
            'is_active' => $data['is_active'] ?? 1,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE rules
             SET name = :name,
                 type = :type,
                 threshold = :threshold,
                 window_seconds = :window_seconds,
                 conditions = :conditions,
                 is_active = :is_active
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'type' => $data['type'],
            'threshold' => $data['threshold'],
            'window_seconds' => $data['window_seconds'],
            'conditions' => json_encode($data['conditions'] ?? []),
            'is_active' => $data['is_active'] ?? 1,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM rules WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}

