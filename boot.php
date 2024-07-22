<?php
require_once 'session_manager.php';
require_once 'auth_manager.php';
require_once 'user_manager.php';
require_once 'joke_manager.php';
require_once 'ip_manager.php';

SessionManager::start();

function pdo(): PDO
{
    static $pdo;

    if (!$pdo) {
        if (file_exists(__DIR__ . '/config.php')) {
            $config = include __DIR__.'/config.php';
        } else {
            $msg = 'Создайте и настройте config.php на основе config.sample.php';
            trigger_error($msg, E_USER_ERROR);
        }

        $dsn = 'mysql:dbname='.$config['db_name'].';host='.$config['db_host'];
        $pdo = new PDO($dsn, $config['db_user'], $config['db_pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    return $pdo;
}
?>
