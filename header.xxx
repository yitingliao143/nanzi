<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: main.php");
    exit;  //記得要跳出來，不然會重複轉址過多次
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>歡迎成為楠梓人</title>
    <style>
        .custom-button {
            background-color: #4CAF50; /* 綠色背景 */
            border: none; /* 無邊框 */
            color: white; /* 白色文字 */
            padding: 15px 32px; /* 內邊距 */
            text-align: center; /* 文字居中 */
            text-decoration: none; /* 無下劃線 */
            display: inline-block; /* 行內塊 */
            font-size: 16px; /* 字體大小 */
            margin: 4px 2px; /* 外邊距 */
            cursor: pointer; /* 指針樣式 */
            border-radius: 12px; /* 圓角 */
        }
        .custom-button:hover {
            background-color: #45a049; /* 滑鼠懸停時的背景顏色 */
        }
        #header {
            background-color: #f1f1f1;
            padding: 20px;
        }
        #content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 40px; /* 左右內邊距 */
        }
        label {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="header">
        <div id="content">
            <label>歡迎成為楠梓人</label>
            <a href="logout.php">
                <button class="custom-button" type="button">Logout</button>
            </a>
        </div>
    </div>
</body>
</html>
