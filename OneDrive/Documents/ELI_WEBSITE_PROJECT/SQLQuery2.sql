CREATE TABLE clients (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    report_date DATE NOT NULL,
    details TEXT
);

CREATE TABLE reports (
    id INT IDENTITY(1,1) PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    report_file VARCHAR(255) NOT NULL
);
