<?php

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Message.php';
require __DIR__ . '/../src/User.php';

session_start();

$db = Database::getInstance();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['messageText']) && isset($_POST['conversationId'])) {
        if (empty($_POST['conversationId'])) {
            die('nie wybrano żadnego wątku...');
        }
        if (ctype_space($_POST['messageText']) || empty($_POST['messageText'])) {
            die('wiadomość jest pusta...');
        }

        $convId = $_POST['conversationId'];
        $text = trim($_POST['messageText']);

        $message = new Message();
        $senderId = (int)$_SESSION['user'];
        $message->setConversationId($convId);
        $message->setSenderId($senderId);
        $message->setText($text);
        $message->setCreationDate(date('Y-m-d H:i:s', time()));
        $message->saveToDB($conn);

        $location = ucfirst($_SESSION['user_role']) . "Controller.php";
        header("Location: $location?convId=$convId");

    }
}