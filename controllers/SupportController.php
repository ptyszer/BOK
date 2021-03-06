<?php

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Conversation.php';
require __DIR__ . '/../src/Message.php';
require __DIR__ . '/../src/User.php';

session_start();

$conn = Database::getInstance();

if (!isset($_SESSION['user'])) {
    header("Location: LoginController.php");
    exit();
}

$user = User::loadUserById($conn, $_SESSION['user']);
$openConversations = Conversation::loadOpenConversations($conn);
$myConversations = Conversation::loadAllConversationsBySupportId($conn, $_SESSION['user']);
$currentConversationId = null;
$currentSubject = null;
$messages = null;
$messageForm = '';

if (!$myConversations) {
    $myConversationsContent = "<i>brak przypisanych wątków...</i>";
} else {
    //wczytanie aktualnej konwersacji
    $currentConversationId = isset($_GET['convId']) ? $_GET['convId'] : $myConversations[0]->getId();
    $currentConversation = Conversation::loadConversationById($conn, $currentConversationId);
    $currentSubject = $currentConversation->getSubject();

    //wczytywanie wątków użytkownika
    $rowsTemplate = [];
    foreach ($myConversations as $conversation) {
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
    $myConversationsContent = $table->parse();

    //wczytanie wszystkich wiadomości dla aktualnego wątku
    $messages = Message::loadAllMessages($conn, $currentConversationId);

    //dodanie id i tematu konwersacji do formularza nowej wiadomości
    $form = new Template(__DIR__ . '/../templates/message_form.tpl');
    $form->add('conversationId', $currentConversationId);
    $form->add('subject', $currentSubject);
    $messageForm = $form->parse();
}

if (!$openConversations) {
    $openConversationsContent = "<i>brak otwartych wątków...</i>";
} else {
    $rowsTemplate = [];
    foreach ($openConversations as $conversation) {
        $row = new Template(__DIR__ . '/../templates/open_conversation_row.tpl');
        $row->add('conversationId', $conversation->getId());
        $row->add('conversationSubject', $conversation->getSubject());
        $rowsTemplate[] = $row;
    }
    $rowsContent = Template::joinTemplates($rowsTemplate);
    $table = new Template(__DIR__ . '/../templates/open_conversation_table.tpl');
    $table->add('rows', $rowsContent);
    $openConversationsContent = $table->parse();

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
$content = new Template(__DIR__ . '/../templates/support_content.tpl');

$content->add('message_form', $messageForm);
$content->add('conversations', $myConversationsContent);
$content->add('openConversations', $openConversationsContent);
$content->add('messages', $messagesContent);

$userNavbar->add('username', $user->getLogin());

$index->add('userNavbar', $userNavbar->parse());
$index->add('content', $content->parse());

echo $index->parse();



