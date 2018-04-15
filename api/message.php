<?php
$conn = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = new Message();
    $message->setConversationId($_POST['conversationId']);
    $message->setSenderId((int)$_SESSION['user']);
    $message->setText($_POST['messageText']);
    $message->setCreationDate(date('Y-m-d H:i:s', time()));
    $message->saveToDB($conn);
    $response = ['success' => [json_decode(json_encode($message), true)]];
} else {
    $response = ['error' => 'Wrong request method'];
}