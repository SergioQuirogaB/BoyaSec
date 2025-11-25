<?php

namespace Services;

/**
 * Normaliza distintos formatos de logs en un esquema común
 * (timestamp, ip, method, path, status, user_agent). La implementación
 * cubre Apache/Nginx (combined) y un fallback para logs simples CSV.
 */
class LogNormalizer
{
    public function normalizeLine(string $line, string $source): ?array
    {
        $line = trim($line);

        if ($line === '') {
            return null;
        }

        // Apache/Nginx combined log format.
        $apacheRegex = '/^(?P<ip>\\S+) \\S+ \\S+ \\[(?P<time>[^\\]]+)\\] "(?P<method>\\S+) (?P<path>[^" ]+) [^"]+" (?P<status>\\d{3}) \\S+ "(?:[^"]*)" "(?P<agent>[^"]*)"/';
        if (preg_match($apacheRegex, $line, $matches)) {
            return [
                'event_time' => $this->parseTimestamp($matches['time']),
                'ip' => $matches['ip'],
                'method' => strtoupper($matches['method']),
                'path' => $matches['path'],
                'status_code' => (int) $matches['status'],
                'user_agent' => $matches['agent'],
            ];
        }

        // CSV fallback: timestamp,ip,method,path,status,user_agent
        if (str_contains($line, ',')) {
            $parts = str_getcsv($line);
            if (count($parts) >= 6) {
                return [
                    'event_time' => $this->parseTimestamp($parts[0]),
                    'ip' => $parts[1],
                    'method' => strtoupper($parts[2]),
                    'path' => $parts[3],
                    'status_code' => (int) $parts[4],
                    'user_agent' => $parts[5],
                ];
            }
        }

        // Simple space separated: ip method path status
        $simple = preg_split('/\\s+/', $line);
        if (count($simple) >= 4) {
            return [
                'event_time' => date('Y-m-d H:i:s'),
                'ip' => $simple[0],
                'method' => strtoupper($simple[1]),
                'path' => $simple[2],
                'status_code' => (int) $simple[3],
                'user_agent' => $simple[4] ?? $source,
            ];
        }

        return null;
    }

    private function parseTimestamp(string $value): string
    {
        // Apache: 10/Oct/2000:13:55:36 -0700
        $dt = \DateTime::createFromFormat('d/M/Y:H:i:s O', $value);
        if ($dt) {
            return $dt->format('Y-m-d H:i:s');
        }

        $timestamp = strtotime($value);
        return $timestamp ? date('Y-m-d H:i:s', $timestamp) : date('Y-m-d H:i:s');
    }
}

