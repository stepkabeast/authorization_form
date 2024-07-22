<?php
require_once __DIR__.'/boot.php';

SessionManager::start();

if (empty($_POST['username']) || empty($_POST['password'])) {
    SessionManager::flash('Заполните все обязательные поля.');
    header('Location: /auth-test-ex');
    die;
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    SessionManager::flash('Электронная почта введена некорректно.');
    header('Location: /auth-test-ex');
    die;
}

if (!isset($_POST['username']) || strlen($_POST['username']) < 5) {
    SessionManager::flash('Неверный логин. Логин должен быть не менее 5 символов.');
    header('Location: /auth-test-ex');
    die;
}

$password = $_POST['password'];
if (strlen($password) < 8 || !preg_match('/^[a-zA-Z0-9]+$/', $password)) {
    SessionManager::flash('Пароль должен быть не менее 8 символов и состоять только из латинских символов и цифр.');
    header('Location: /auth-test-ex');
    die;
}

$userManager = new UserManager(pdo());

$stmt = pdo()->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $_POST['email']]);
if ($stmt->rowCount() > 0) {
    SessionManager::flash('Эта почта уже занята.');
    header('Location: /auth-test-ex');
    die;
}

$userManager->createUser($_POST['email'], $_POST['username'], $password);

header('Location: login.php');
