<?php
// Include config file
session_start();
include_once "dbconfig.php";

// Define variables and initialize with empty values
$username = $_POST["username"];
$password = $_POST["password"];

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 使用预处理语句来防止 SQL 注入
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        // 检查是否找到了用户
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // 直接比对明文密码
            if ($password === $row["password"]) {
                // 密码验证成功，设置会话变量
                $_SESSION["login"] = true; // 表示使用者已登入
				$_SESSION["id"] = $row["id"];
				$_SESSION["username"] = $row["username"];

                
                // 重定向到 main.php
                header("Location: main.php");
                exit;
            } else {
                // 密码不匹配
                function_alert("帳號或密碼錯誤");
            }
        } else {
            // 用户名未找到
            function_alert("帳號或密碼錯誤");
        }
        
        // 关闭预处理语句
        mysqli_stmt_close($stmt);
    } else {
        // SQL 执行错误
        function_alert("數據庫錯誤");
    }
    
    // 关闭数据库连接
    mysqli_close($link);
} else {
    // 表单提交错误
    function_alert("Something went wrong"); 
}

// 显示提示信息的函数
function function_alert($message) { 
    echo "<script>alert('$message');
    window.location.href='index.php'; // 重定向到 index.php
    </script>"; 
    return false;
}
?>
