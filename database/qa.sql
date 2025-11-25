-- Datos de prueba para BoyaSec SIEM Lite
INSERT INTO logs_raw (filename, payload, source)
VALUES
('sample-apache.log', '192.168.1.10 - - [10/Oct/2025:13:55:36 +0000] "GET /admin HTTP/1.1" 401 498 "-" "curl/7.68.0"', 'apache'),
('sample-scanner.log', '10.0.0.9 - - [10/Oct/2025:13:57:36 +0000] "GET /.env HTTP/1.1" 404 312 "-" "Mozilla/5.0"', 'apache');

INSERT INTO logs_normalized (raw_id, event_time, ip, method, path, status_code, user_agent)
VALUES
(1, '2025-10-10 13:55:36', '192.168.1.10', 'GET', '/admin', 401, 'curl/7.68.0'),
(2, '2025-10-10 13:57:36', '10.0.0.9', 'GET', '/.env', 404, 'Mozilla/5.0');

