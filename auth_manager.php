<?php
require_once 'session_manager.php';

class AuthManager
{
    public static function checkAuth()
    {
        return !!SessionManager::get('user_id');
    }

    public static function login($userId)
    {
        SessionManager::set('user_id', $userId);
        SessionManager::remove('username');
    }

    public static function logout()
    {
        SessionManager::remove('user_id');
    }
}
?>
