CREATE TABLE blogs (
                           id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                           author VARCHAR(255) NOT NULL,
                           title VARCHAR(255) NOT NULL,
                           body VARCHAR(255) NOT NULL,
                           created_on DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);