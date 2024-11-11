<?php
session_start();
include_once "dbconfig.php";

// 確保用戶已登入
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 接收表單數據
    $current_password = $_POST["current-password"];
    $new_password = $_POST["new-password"];
    $confirm_password = $_POST["confirm-password"];
    $username = $_SESSION['username'];

    // 檢查是否為空
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        redirect_with_message("請輸入所有必填項目", "change-password.php");
    }

    if ($new_password !== $confirm_password) {
        redirect_with_message("新密碼和確認密碼不一致", "change-password.php");
    }

    // 查詢當前密碼
    $sql = "SELECT password FROM login WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $hashed_password);
                mysqli_stmt_fetch($stmt);
                if (password_verify($current_password, $hashed_password)) {
                    // 更新密碼
                    $sql = "UPDATE login SET password = ? WHERE username = ?";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt, "ss", $new_password_hash, $username);
                        if (mysqli_stmt_execute($stmt)) {
                            header("Location: login.html");
                            exit;
                        } else {
                            redirect_with_message("密碼更新失敗，請重試", "change-password.php");
                        }
                    }
                } else {
                    redirect_with_message("當前密碼不正確", "change-password.php");
                }
            } else {
                redirect_with_message("用戶不存在", "change-password.php");
            }
        } else {
            redirect_with_message("數據庫錯誤", "change-password.php");
        }
        mysqli_stmt_close($stmt);
    } else {
        redirect_with_message("數據庫錯誤", "change-password.php");
    }
    mysqli_close($link);
}

// 重定向函數
function redirect_with_message($message, $redirect = 'change-password.php') {
    $message = urlencode($message);
    header("Location: $redirect?message=$message");
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改密碼</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="change-password-container">
        <h2>修改密碼</h2>
        <form action="change-password.php" method="post">
            <div class="input-group">
                <label for="current-password">當前密碼</label>
                <input type="password" id="current-password" name="current-password" required>
            </div>
            <div class="input-group">
                <label for="new-password">新密碼</label>
                <input type="password" id="new-password" name="new-password" required>
            </div>
            <div class="input-group">
                <label for="confirm-password">確認新密碼</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <button type="submit">更新密碼</button>
        </form>
    </div>
</body>
</html>
