USE dbCustomerManager;

CREATE TABLE IF NOT EXISTS address(
    `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `street` VARCHAR(255) NOT NULL,
    `number_home` VARCHAR(11) NOT NULL
);