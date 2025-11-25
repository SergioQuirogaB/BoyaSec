<?php

namespace Models;

use PDO;

class LogNormalized
{
    private PDO $db;

    public function __construct()
    {
        $this->db = get_pdo();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO logs_normalized (raw_id, event_time, ip, method, path, status_code, user_agent, created_at)
             VALUES (:raw_id, :event_time, :ip, :method, :path, :status_code, :user_agent, NOW())'
        );

        $stmt->execute([
            'raw_id' => $data['raw_id'],
            'event_time' => $data['event_time'],
            'ip' => $data['ip'],
            'method' => $data['method'],
            'path' => $data['path'],
            'status_code' => $data['status_code'],
            'user_agent' => $data['user_agent'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function latest(int $limit = 100): array
    {
        $stmt = $this->db->prepare(
            'SELECT ln.*, lr.filename FROM logs_normalized ln
             LEFT JOIN logs_raw lr ON lr.id = ln.raw_id
             ORDER BY ln.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function topIps(int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            'SELECT ip, COUNT(*) as total
             FROM logs_normalized
             GROUP BY ip
             ORDER BY total DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByMethod(): array
    {
        $stmt = $this->db->query(
            'SELECT method, COUNT(*) as total
             FROM logs_normalized
             GROUP BY method'
        );
        return $stmt->fetchAll();
    }

    public function countByStatus(): array
    {
        $stmt = $this->db->query(
            'SELECT status_code, COUNT(*) as total
             FROM logs_normalized
             GROUP BY status_code'
        );
        return $stmt->fetchAll();
    }
}

