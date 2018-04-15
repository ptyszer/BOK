<?php

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/User.php';

session_start();

$conn = Database::getInstance();
$info = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET) and $_GET['action'] == 'logout') {
        unset($_SESSION['user']);
    }
}

if (isset($_SESSION['user']) && isset($_SESSION['user_role'])) {
    if($_SESSION['user_role'] == 'client'){
        header("Location: ClientController.php");
    }
    if($_SESSION['user_role'] == 'support'){
        header("Location: SupportController.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login']) && isset($_POST['password'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $user = User::login($conn, $login, $password);

        if ($user){
            $_SESSION['user'] = $user->getId();
            $_SESSION['user_role'] = $user->getRole();
            if($user->getRole() == 'client'){
                header("Location: ClientController.php");
            }
            if($user->getRole() == 'support'){
                header("Location: SupportController.php");
            }
        } else {
            $info = 'Nieprawidłowy login lub hasło.';
        }

    }
}

$index = new Template(__DIR__ . '/../templates/index.tpl');
$content = new Template(__DIR__ . '/../templates/login.tpl');
$content->add('info', $info);
$index->add('userNavbar', '');
$index->add('content', $content->parse());

echo $index->parse();