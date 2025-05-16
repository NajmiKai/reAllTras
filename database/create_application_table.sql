-- Create application_form table
CREATE TABLE IF NOT EXISTS application_form (
    id INT PRIMARY KEY AUTO_INCREMENT,
    -- Add your columns below in the following format:
    -- column_name DATA_TYPE(size) [constraints],
    -- For example:
    -- first_name VARCHAR(50) NOT NULL,
    -- email VARCHAR(100) NOT NULL UNIQUE,
    -- phone VARCHAR(15),
    -- birth_date DATE,
    -- status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
); 