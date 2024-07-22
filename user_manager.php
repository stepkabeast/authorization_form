<?php
class UserManager
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($email, $username, $password)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
        $stmt->execute([
            'email' => $email,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    public function updateUserPassword($username, $newPassword)
    {
        $stmt = $this->pdo->prepare('UPDATE users SET password = :password WHERE username = :username');
        $stmt->execute([
            'username' => $username,
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);
    }
}
?>
