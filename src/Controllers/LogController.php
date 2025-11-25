<?php

namespace Controllers;

use Models\LogRaw;
use Models\LogNormalized;
use Services\LogNormalizer;
use Services\RuleEngine;

class LogController
{
    private LogRaw $rawModel;
    private LogNormalized $normalizedModel;
    private LogNormalizer $normalizer;
    private RuleEngine $ruleEngine;

    public function __construct()
    {
        $this->rawModel = new LogRaw();
        $this->normalizedModel = new LogNormalized();
        $this->normalizer = new LogNormalizer();
        $this->ruleEngine = new RuleEngine();
    }

    public function handleUpload(array $file, string $source): array
    {
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Error al subir el archivo');
        }

        $payload = file_get_contents($file['tmp_name']);
        $rawId = $this->rawModel->create([
            'filename' => $file['name'],
            'payload' => $payload,
            'source' => $source,
        ]);

        $normalizedCount = 0;
        $lines = preg_split('/\r\n|\n|\r/', $payload);

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $normalized = $this->normalizer->normalizeLine($line, $source);
            if ($normalized) {
                $normalized['raw_id'] = $rawId;
                $this->normalizedModel->create($normalized);
                $normalizedCount++;
            }
        }

        $alerts = $this->ruleEngine->run();

        return [
            'raw_id' => $rawId,
            'events_normalized' => $normalizedCount,
            'alerts_generated' => count($alerts),
        ];
    }

    public function latestNormalized(int $limit = 100): array
    {
        return $this->normalizedModel->latest($limit);
    }

    public function getDashboardData(): array
    {
        return [
            'top_ips' => $this->normalizedModel->topIps(),
            'methods' => $this->normalizedModel->countByMethod(),
            'statuses' => $this->normalizedModel->countByStatus(),
        ];
    }
}

