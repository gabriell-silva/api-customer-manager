USE dbCustomerManager;

CREATE TABLE IF NOT EXISTS client_address(
    `client_id` INT(11) NOT NULL,
    `address_id` INT(11) NOT NULL,
    PRIMARY KEY (`client_id`, `address_id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`),
    FOREIGN KEY (`address_id`) REFERENCES `address`(`id`)
);