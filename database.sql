-- Create database
CREATE DATABASE IF NOT EXISTS interview_app;
USE interview_app;

-- Create admin table
CREATE TABLE IF NOT EXISTS admin (
    id INT(11) NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create interviewers table
CREATE TABLE IF NOT EXISTS interviewers (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    qualification VARCHAR(255) NOT NULL,
    experience INT(11) NOT NULL,
    resume VARCHAR(255) DEFAULT NULL,
    verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (password: admin)
INSERT INTO admin (email, password) VALUES (
    'admin@gmail.com',
    '$2y$10$YVw6qUufrEvXaW8lVAX6Te71SJNPvdZGLK1p4y4GpkFKyBs.SUJeO'
);

-- Create indexes
CREATE INDEX idx_interviewers_email ON interviewers(email);
CREATE INDEX idx_interviewers_created_at ON interviewers(created_at);
CREATE INDEX idx_interviewers_verified ON interviewers(verified);