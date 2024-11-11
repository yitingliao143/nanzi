
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入介面</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #546377;
            color: #0d47a1;
        }

        .login-container {
            width: 90%;
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        .login-container h1 {
            font-size: 2em;
            color: #303c4d;
            margin-bottom: 20px;
        }

        .login-form {
            display: flex;
            flex-direction: column;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1em;
        }

        .login-form button {
            padding: 10px;
            background-color: #0d47a1;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        .login-form button:hover {
            background-color: #0b3a8a;
        }

        .login-form a {
            margin-top: 15px;
            color: #0d47a1;
            text-decoration: none;
            font-size: 0.9em;
            transition: color 0.3s;
        }

        .login-form a:hover {
            color: #0b3a8a;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>登入</h1>
        <form class="login-form" action="login.php" method="POST">
            <input type="text" name="username" placeholder="使用者名稱" required>
            <input type="password" name="password" placeholder="密碼" required>
            <button type="submit">登入</button>
            <a href="#">忘記密碼？</a>
        </form>
    </div>
</body>
</html>
