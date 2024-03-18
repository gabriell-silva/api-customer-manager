USE dbCustomerManager;

CREATE TABLE IF NOT EXISTS users(
    `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `username` varchar(255) DEFAULT NULL,
    `password` varchar(50) DEFAULT NULL,
    `token` TEXT DEFAULT NULL
);


INSERT INTO users(username, password)
VALUES('admin','123456a');