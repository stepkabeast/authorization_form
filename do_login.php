<?php
require_once __DIR__.'/boot.php';

SessionManager::start();

$userManager = new UserManager(pdo());
$user = $userManager->getUserByUsername($_POST['username']);

if (!$user) {
    SessionManager::flash('Пользователь с таким именем не зарегистрирован');
    SessionManager::set('username', $_POST['username']);
    header('Location: login.php');
    die;
}

if (password_verify($_POST['password'], $user['password'])) {
    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        $userManager->updateUserPassword($_POST['username'], $_POST['password']);
    }
    AuthManager::login($user['id']);
    header('Location: index.php');
    die;
}

SessionManager::flash('Пароль неверен');
SessionManager::set('username', $_POST['username']);
header('Location: login.php');
