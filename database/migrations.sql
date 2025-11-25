CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password)
VALUES ('admin', '$2y$10$vKrldmGoT.rKsgveEalv0OWExOgqgtRH8Ht4VWGxOWfOms/JpWxrq')
ON DUPLICATE KEY UPDATE username = username;

CREATE TABLE IF NOT EXISTS logs_raw (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    source VARCHAR(100) DEFAULT 'manual',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS logs_normalized (
    id INT AUTO_INCREMENT PRIMARY KEY,
    raw_id INT NOT NULL,
    event_time DATETIME NOT NULL,
    ip VARCHAR(45) NOT NULL,
    method VARCHAR(10) NOT NULL,
    path VARCHAR(1024) NOT NULL,
    status_code SMALLINT NOT NULL,
    user_agent VARCHAR(512) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_event_time (event_time),
    INDEX idx_ip (ip),
    CONSTRAINT fk_logs_raw FOREIGN KEY (raw_id) REFERENCES logs_raw(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    threshold INT DEFAULT 5,
    window_seconds INT DEFAULT 300,
    conditions JSON NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_rules_name (name)
);

INSERT INTO rules (name, type, threshold, window_seconds, conditions, is_active)
VALUES
    ('Brute Force HTTP', 'brute_force', 5, 300, JSON_ARRAY(), 1),
    ('Scanner 404', 'scanner', 10, 600, JSON_ARRAY('/.env','/admin','/wp-login'), 1)
ON DUPLICATE KEY UPDATE name = name;

CREATE TABLE IF NOT EXISTS alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rule_id INT NOT NULL,
    ip VARCHAR(45) NOT NULL,
    event_count INT NOT NULL,
    detected_at DATETIME NOT NULL,
    details JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_alert_rule FOREIGN KEY (rule_id) REFERENCES rules(id) ON DELETE CASCADE
);

