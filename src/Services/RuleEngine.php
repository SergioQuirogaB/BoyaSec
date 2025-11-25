<?php

namespace Services;

use Models\Rule;

/**
 * Motor de correlación básico: ejecuta las reglas activas contra los eventos
 * recientes en la base y crea alertas cuando se cumplen los umbrales.
 */
class RuleEngine
{
    private Rule $ruleModel;
    private AlertService $alertService;

    public function __construct()
    {
        $this->ruleModel = new Rule();
        $this->alertService = new AlertService();
    }

    public function run(): array
    {
        $rules = $this->ruleModel->all();
        $alerts = [];

        foreach ($rules as $rule) {
            if (!(int) $rule['is_active']) {
                continue;
            }

            switch ($rule['type']) {
                case 'brute_force':
                    $alerts = array_merge($alerts, $this->detectBruteForce($rule));
                    break;
                case 'scanner':
                    $alerts = array_merge($alerts, $this->detectScanning($rule));
                    break;
                default:
                    break;
            }
        }

        return $alerts;
    }

    private function detectBruteForce(array $rule): array
    {
        $window = (int) ($rule['window_seconds'] ?: 300);
        $threshold = (int) ($rule['threshold'] ?: 5);
        $startTime = date('Y-m-d H:i:s', time() - $window);

        $pdo = get_pdo();
        $stmt = $pdo->prepare(
            'SELECT ip, COUNT(*) as total
             FROM logs_normalized
             WHERE event_time >= :start
               AND status_code IN (401, 403)
             GROUP BY ip
             HAVING total >= :threshold'
        );
        $stmt->execute([
            'start' => $startTime,
            'threshold' => $threshold,
        ]);

        $alerts = [];
        foreach ($stmt->fetchAll() as $row) {
            $alerts[] = $this->alertService->create([
                'rule_id' => $rule['id'],
                'ip' => $row['ip'],
                'event_count' => $row['total'],
                'detected_at' => date('Y-m-d H:i:s'),
                'details' => [
                    'type' => 'Brute Force',
                    'window_seconds' => $window,
                ],
            ]);
        }

        return $alerts;
    }

    private function detectScanning(array $rule): array
    {
        $window = (int) ($rule['window_seconds'] ?: 600);
        $threshold = (int) ($rule['threshold'] ?: 10);
        $startTime = date('Y-m-d H:i:s', time() - $window);

        $suspicious = ['/admin', '/wp-login', '/.env', '/phpmyadmin'];
        $pdo = get_pdo();

        $placeholders = implode(',', array_fill(0, count($suspicious), '?'));
        $sql = sprintf(
            'SELECT ip, COUNT(*) as total
             FROM logs_normalized
             WHERE event_time >= ?
               AND (status_code = 404 OR path IN (%s))
             GROUP BY ip
             HAVING total >= ?',
            $placeholders
        );

        $params = array_merge([$startTime], $suspicious, [$threshold]);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $alerts = [];
        foreach ($stmt->fetchAll() as $row) {
            $alerts[] = $this->alertService->create([
                'rule_id' => $rule['id'],
                'ip' => $row['ip'],
                'event_count' => $row['total'],
                'detected_at' => date('Y-m-d H:i:s'),
                'details' => [
                    'type' => 'Scanning',
                    'window_seconds' => $window,
                    'suspicious_paths' => $suspicious,
                ],
            ]);
        }

        return $alerts;
    }
}

