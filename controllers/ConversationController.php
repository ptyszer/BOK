<?php

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Conversation.php';
require __DIR__ . '/../src/User.php';

session_start();

$db = Database::getInstance();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['conversationSubject'])) {
        $subject = ctype_space($_POST['conversationSubject']) || empty($_POST['conversationSubject']) ? null : trim($_POST['conversationSubject']);
        if($subject){
            $conversation = new Conversation();
            if($_SESSION['user_role'] === 'client'){
                $clientId = (int)$_SESSION['user'];
                $conversation->setClientId($clientId);
                $conversation->setSubject($subject);
                $conversation->setCreationDate(date('Y-m-d H:i:s', time()));
                $conversation->saveToDB($conn);
                header("Location: ClientController.php");
            }

        }
    }
}