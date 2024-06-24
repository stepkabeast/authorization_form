<?php
require_once __DIR__.'/boot.php';

// Проверка заполненности полей
if (empty($_POST['username']) || empty($_POST['password'])) {
    flash('Заполните все обязательные поля.');
    header('Location: /');
    die;
}

// Проверка корректности почтового адреса
if (!filter_var($_POST['username'], FILTER_VALIDATE_EMAIL)) {
    flash('Электронная почта введена некорректно.');
    header('Location: /');
    die;
}

// Проверка пароля на длину и состав
$password = $_POST['password'];
if (strlen($password) < 8 || !preg_match('/^[a-zA-Z0-9]+$/', $password)) {
    flash('Пароль должен быть не менее 8 символов и состоять только из латинских символов и цифр.');
    header('Location: /');
    die;
}

$stmt = pdo()->prepare("SELECT * FROM `users` WHERE `username` = :username");
$stmt->execute(['username' => $_POST['username']]);
if ($stmt->rowCount() > 0) {
    flash('Этот адрес электронной почты уже занят.');
    header('Location: /');
    die;
}

$stmt = pdo()->prepare("INSERT INTO `users` (`username`, `password`) VALUES (:username, :password)");
$stmt->execute([
    'username' => $_POST['username'],
    'password' => password_hash($password, PASSWORD_DEFAULT),
]);

header('Location: login.php');
