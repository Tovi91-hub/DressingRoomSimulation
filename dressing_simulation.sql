use dressing_simulation;
CREATE USER IF NOT EXISTS 'dressingroom_user'@'localhost' IDENTIFIED BY 'dress123';

GRANT ALL PRIVILEGES ON dressing_simulation.* TO 'dressingroom_user'@'localhost';
FLUSH PRIVILEGES;
DROP TABLE IF EXISTS simulation_logs;

CREATE TABLE simulation_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    num_customers INT,
    num_rooms INT,
    num_random_items INT,
    total_time FLOAT,
    avg_items FLOAT,
    avg_wait FLOAT,
    avg_usage FLOAT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



DROP DATABASE IF EXISTS clothing_store_sim;
CREATE DATABASE dressing_simulation;
USE dressing_simulation;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password_hash) VALUES
('dressing_admin', '$2y$10$WsY6D.j7Sj.2DzFvp4dB1uPV/HrxbDtr2vm113H3LK3JkX78HVRyG'),
('tester', '$2y$10$8qUIiCCjSls9eN13mUbLYerPZ1pPeD8LrgXVmAX0vvvCTgr08XfB2');
