USE dbCustomerManager;

CREATE TABLE IF NOT EXISTS clients(
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `date_birth` DATE NOT NULL,
    `document_cpf` VARCHAR(11) UNIQUE NOT NULL,
    `document_rg` VARCHAR(11) UNIQUE NOT NULL,
    `phone_number` VARCHAR(255)
);