<?php
require_once(__DIR__ . '/../src/Database.php');

$bokArraysSQL = [
    "create table Users(
                        id INT AUTO_INCREMENT NOT NULL,
                        login VARCHAR(255) NOT NULL,
                        hash_pass VARCHAR(255) NOT NULL,
                        role VARCHAR(60) NOT NULL,
                        PRIMARY KEY(id))
     ENGINE=InnoDB, CHARACTER SET=utf8"
    ,
    "create table Conversations(
                        id INT AUTO_INCREMENT NOT NULL,
                        clientId INT,
                        supportId INT,
                        subject VARCHAR(255) NOT NULL,
                        creationDate DATETIME NOT NULL,
                        PRIMARY KEY(id),
                        FOREIGN KEY(clientId) REFERENCES Users(id) ON DELETE CASCADE,
                        FOREIGN KEY(supportId) REFERENCES Users(id) ON DELETE CASCADE)
     ENGINE=InnoDB, CHARACTER SET=utf8"
    ,
    "create table Messages(
                        id INT AUTO_INCREMENT NOT NULL,
                        conversationId INT NOT NULL,
                        senderId INT NOT NULL,
                        text VARCHAR(255) NOT NULL,
                        creationDate DATETIME NOT NULL,
                        PRIMARY KEY(id),
                        FOREIGN KEY(conversationId) REFERENCES Conversations(id) ON DELETE CASCADE,
                        FOREIGN KEY(senderId) REFERENCES Users(id) ON DELETE CASCADE)
     ENGINE=InnoDB, CHARACTER SET=utf8"
    ];

$db = Database::getInstance();
$conn = $db->getConnection();

foreach ($bokArraysSQL as $query) {
    try {
        $result = $conn->query($query);
        echo "Tabela została stworzona poprawnie<br>";
    } catch (PDOException $e) {
        echo "Błąd podczas tworzenia tabeli: " . $e->getMessage() . "<br>";
    }
}

$conn = null;