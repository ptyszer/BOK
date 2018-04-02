<?php

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Conversation.php';
require __DIR__ . '/../src/Message.php';
require __DIR__ . '/../src/User.php';

session_start();

$db = Database::getInstance();
$conn = $db->getConnection();

if (!isset($_SESSION['user'])) {
    header("Location: LoginController.php");
    exit();
}

$user = User::loadUserById($conn, $_SESSION['user']);
$conversations = Conversation::loadAllConversationsByClientId($conn, $_SESSION['user']);
$currentConversationId = null;
$currentSubject = null;
$messages = null;
$messageForm = '';

if (!$conversations) {
    $conversationsContent = "<i>brak wątków...</i>";
} else {
    //wczytanie aktualnej konwersacji
    $currentConversationId = isset($_GET['convId']) ? $_GET['convId'] : $conversations[0]->getId();
    $currentConversation = Conversation::loadConversationById($conn, $currentConversationId);
    $currentSubject = $currentConversation->getSubject();

    //wczytywanie wątków użytkownika
    $rowsTemplate = [];
    foreach ($conversations as $conversation) {
        $row = new Template(__DIR__ . '/../templates/conversation_row.tpl');
        $row->add('conversationId', $conversation->getId());
        $row->add('conversationSubject', $conversation->getSubject());
        $rowsTemplate[] = $row;
    }
    //dodanie wątków do szablonu
    $rowsContent = Template::joinTemplates($rowsTemplate);
    $table = new Template(__DIR__ . '/../templates/conversation_table.tpl');
    $table->add('rows', $rowsContent);
    $table->add('currentId', $currentConversationId);
    $conversationsContent = $table->parse();

    //wczytanie wszystkich wiadomości dla aktualnego wątku
    $messages = Message::loadAllMessages($conn, $currentConversationId);

    //dodanie id i tematu konwersacji do formularza nowej wiadomości
    $form = new Template(__DIR__ . '/../templates/message_form.tpl');
    $form->add('conversationId', $currentConversationId);
    $form->add('subject', $currentSubject);
    $messageForm = $form->parse();
}

if (!$messages) {
    $messagesContent = "<i>brak wiadomości...</i>";
} else {
    $rowsTemplate = [];
    foreach ($messages as $message) {
        $row = new Template(__DIR__ . '/../templates/message_row.tpl');
        $sender = User::loadUserById($conn, $message->getSenderId());
        $row->add('messageSender', $sender->getLogin());
        $row->add('messageText', $message->getText());
        $rowsTemplate[] = $row;
    }
    $rowsContent = Template::joinTemplates($rowsTemplate);
    $table = new Template(__DIR__ . '/../templates/message_table.tpl');
    $table->add('rows', $rowsContent);
    $messagesContent = $table->parse();
}

$index = new Template(__DIR__ . '/../templates/index.tpl');
$userNavbar = new Template(__DIR__ . '/../templates/user_navbar.tpl');
$content = new Template(__DIR__ . '/../templates/client_content.tpl');

$content->add('message_form', $messageForm);
$content->add('conversations', $conversationsContent);
$content->add('messages', $messagesContent);

$userNavbar->add('username', $user->getLogin());

$index->add('userNavbar', $userNavbar->parse());
$index->add('content', $content->parse());

echo $index->parse();
