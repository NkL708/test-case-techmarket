CREATE TABLE categories(
    id INT PRIMARY KEY,
    name VARCHAR(255),
    parent_id INT,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
);

INSERT INTO categories(name, parent_id) VALUES 
('1', NULL), 
('2', NULL), 
('3', NULL),
('1.1', 1), 
('1.2', 1), 
('1.3', 1);
