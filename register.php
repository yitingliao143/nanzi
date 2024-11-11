<?php
// Include config file
include_once "dbconfig.php";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 接收表單數據
    $user_email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm-password"];

    // 檢查是否為空或密碼不一致
    if (empty($username) || empty($password) || empty($user_email)) {
        redirect_with_message("請輸入帳號、密碼和電子郵件地址");
    }
    
    if ($password !== $confirm_password) {
        redirect_with_message("密碼和確認密碼不一致");
    }

    // 查詢用戶是否已存在
    $sql = "SELECT id FROM users WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1) {
            redirect_with_message("此帳號已被註冊");
        } else {
            // 插入新用戶
            $sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "sss", $user_email, $username, $password);
                if (mysqli_stmt_execute($stmt)) {
                    redirect_with_message("註冊成功！", "index.php");
                } else {
                    redirect_with_message("註冊失敗，請重試");
                }
            }
        }
        mysqli_stmt_close($stmt);
    } else {
        redirect_with_message("數據庫錯誤");
    }
    mysqli_close($link);
}

// 重定向並顯示消息
function redirect_with_message($message, $redirect = 'register.php') {
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
    <title>註冊頁面</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="register-container">
        <h2>註冊</h2>
        <form action="register.php" method="post"> <!-- 確保表單提交到正確的 PHP 文件 -->
            <div class="input-group">
                <label for="username">用戶名</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="email">電子郵件地址</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">密碼</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="confirm-password">確認密碼</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <button type="submit">註冊</button>
        </form>
        <?php
        if (isset($_GET['message'])) {
            echo '<p>' . htmlspecialchars($_GET['message']) . '</p>';
        }
        ?>
    </div>
</body>
</html>
