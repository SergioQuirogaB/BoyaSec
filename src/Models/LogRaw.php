<?php

namespace Models;

use PDO;

class LogRaw
{
    private PDO $db;

    public function __construct()
    {
        $this->db = get_pdo();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO logs_raw (filename, payload, source, created_at)
             VALUES (:filename, :payload, :source, NOW())'
        );
        $stmt->execute([
            'filename' => $data['filename'],
            'payload' => $data['payload'],
            'source' => $data['source'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function all(int $limit = 100): array
    {
        $stmt = $this->db->prepare('SELECT * FROM logs_raw ORDER BY created_at DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

