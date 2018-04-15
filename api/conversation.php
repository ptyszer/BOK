<?php
$conn = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conversation = new Conversation();
    $conversation->setClientId((int)$_SESSION['user']);
    $conversation->setSubject($_POST['conversationSubject']);
    $conversation->setCreationDate(date('Y-m-d H:i:s', time()));
    $conversation->saveToDB($conn);
    $response = ['success' => [json_decode(json_encode($conversation), true)]];
} elseif ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    parse_str(file_get_contents("php://input"), $patchVars);
    $conversation = Conversation::loadConversationById($conn, $patchVars['id']);
    $conversation->setSupportId($_SESSION['user']);
    $conversation->update($conn);
    $response = ['success' => [json_decode(json_encode($conversation), true)]];
} else {
    $response = ['error' => 'Wrong request method'];
}