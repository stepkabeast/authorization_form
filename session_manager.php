<?php
class SessionManager
{
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public static function flash($message = null)
    {
        if ($message) {
            $_SESSION['flash'] = $message;
        } else {
            if (!empty($_SESSION['flash'])) {
                echo '<div class="alert alert-danger mb-3">' . $_SESSION['flash'] . '</div>';
            }
            unset($_SESSION['flash']);
        }
    }
}
?>
