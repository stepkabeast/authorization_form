<?php

require_once __DIR__.'/boot.php';

// Проверяем наличие пользователя с указанным юзернеймом
$stmt = pdo()->prepare("SELECT * FROM `users` WHERE `username` = :username");
$stmt->execute(['username' => $_POST['username']]);
if (!$stmt->rowCount()) {
    flash('Пользователь с таким именем не зарегистрирован');
    header('Location: login.php');
    die;
}
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверяем пароль
if (password_verify($_POST['password'], $user['password'])) {
    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        $newHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = pdo()->prepare('UPDATE `users` SET `password` = :password WHERE `username` = :username');
        $stmt->execute([
            'username' => $_POST['username'],
            'password' => $newHash,
        ]);
    }
    $_SESSION['user_id'] = $user['id'];
    header('Location: index.php');
    die;
}

flash('Пароль неверен');
header('Location: login.php');
