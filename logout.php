<?php
session_start();

// 清除會話變數
$_SESSION = array();

// 如果使用的是 cookie，則清除 cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 終止會話
session_destroy();

// 重定向回首頁或登入頁面
header('Location: index.php');
exit;
?>
