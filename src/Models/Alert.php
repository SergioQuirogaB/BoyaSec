<?php

namespace Models;

use PDO;

class Alert
{
    private PDO $db;

    public function __construct()
    {
        $this->db = get_pdo();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO alerts (rule_id, ip, event_count, detected_at, details, created_at)
             VALUES (:rule_id, :ip, :event_count, :detected_at, :details, NOW())'
        );

        $stmt->execute([
            'rule_id' => $data['rule_id'],
            'ip' => $data['ip'],
            'event_count' => $data['event_count'],
            'detected_at' => $data['detected_at'],
            'details' => json_encode($data['details']),
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function latest(int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            'SELECT a.*, r.name as rule_name
             FROM alerts a
             LEFT JOIN rules r ON r.id = a.rule_id
             ORDER BY a.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function all(int $limit = 100): array
    {
        $stmt = $this->db->prepare(
            'SELECT a.*, r.name as rule_name
             FROM alerts a
             LEFT JOIN rules r ON r.id = a.rule_id
             ORDER BY a.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

