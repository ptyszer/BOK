<?php

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/User.php';

session_start();

$db = Database::getInstance();
$conn = $db->getConnection();
$info = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['role'])) {
        $login = ctype_space($_POST['login']) || empty($_POST['login']) ? null : trim($_POST['login']);
        $password = ctype_space($_POST['password']) || empty($_POST['password']) ? null : trim($_POST['password']);
        $role = $_POST['role'] == 'client' || $_POST['role'] == 'support' ? $_POST['role'] : null;

        $user = User::loadUserByLogin($conn, $login);

        if($user){
            $info = "Użytkownik o podanej nazwie już istnieje.";
        } elseif (!$login || !$password || !$role) {
            $info = 'Błędne dane.';
        } else {
            $user = new User();

            $user->setLogin($login);
            $user->setPassword($password);
            $user->setRole($role);

            $user->saveToDB($conn);

            header("Location: loginController.php");
        }

    }
}

$index = new Template(__DIR__ . '/../templates/index.tpl');
$content = new Template(__DIR__ . '/../templates/register.tpl');
$content->add('info', $info);
$index->add('userNavbar', '');
$index->add('content', $content->parse());

echo $index->parse();
