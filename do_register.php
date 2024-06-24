<?php
require_once __DIR__.'/boot.php';

// Проверка заполненности полей
if (empty($_POST['username']) || empty($_POST['password'])) {
    flash('Заполните все обязательные поля.');
    header('Location: /auth-test-ex');
    die;
}

// Проверка корректности почты
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    flash('Электронная почта введена некорректно.');
    header('Location: /auth-test-ex');
    die;
}

// Проверка корректности логина
if (!isset($_POST['username']) || strlen($_POST['username']) < 5) {
    flash('Неверный логин. Логин должен быть не менее 5 символов.');
    header('Location: /auth-test-ex');
    die;
}


// Проверка пароля на длину и состав
$password = $_POST['password'];
if (strlen($password) < 8 || !preg_match('/^[a-zA-Z0-9]+$/', $password)) {
    flash('Пароль должен быть не менее 8 символов и состоять только из латинских символов и цифр.');
    header('Location: /auth-test-ex');
    die;
}

$stmt = pdo()->prepare("SELECT * FROM `users` WHERE `email` = :email");
$stmt->execute(['email' => $_POST['email']]);
if ($stmt->rowCount() > 0) {
    flash('Эта почта уже занята.');
    header('Location: /auth-test-ex');
    die;
}

$stmt = pdo()->prepare("INSERT INTO `users` (`email`,`username`, `password`) VALUES (:email, :username, :password)");
$stmt->execute([
    'email' => $_POST['email'],
    'username' => $_POST['username'],
    'password' => password_hash($password, PASSWORD_DEFAULT),
]);

header('Location: login.php');
