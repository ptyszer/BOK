<?php

class User
{
    private $id;
    private $login;
    private $hashPass;
    private $role;

    public function __construct()
    {
        $this->id = -1;
        $this->login = null;
        $this->hashPass = null;
        $this->role = null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getPassword()
    {
        return $this->hashPass;
    }

    public function setPassword($newPassword)
    {
        $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->hashPass = $newHashedPassword;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function saveToDB(PDO $conn)
    {
            $sql = 'INSERT INTO users(login, hash_pass, role) VALUES(:login, :pass, :role)';
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute(['login' => $this->login, 'pass' => $this->hashPass, 'role' => $this->role]);
            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        return false;
    }

    static public function loadUserByLogin(PDO $conn, $login)
    {
        $stmt = $conn->prepare('SELECT * FROM users WHERE login=:login');
        $result = $stmt->execute(['login' => $login]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->login = $row['login'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->role = $row['role'];
            return $loadedUser;
        }
        return null;
    }

    static public function loadUserById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM users WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->login = $row['login'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->role = $row['role'];
            return $loadedUser;
        }
        return null;
    }

    static public function login(PDO $conn, $login, $pass)
    {
        $user = User::loadUserByLogin($conn, $login);

        if (isset($user) && password_verify($pass, $user->getPassword()) == $pass) {
            return $user;
        } else {
            return false;
        }
    }

}