<?php
	include_once 'dbconfig.php';
	if(isset($_POST['btn-save']))
	{
		// Check if all form fields are set
		if(isset($_POST['name']) && isset($_POST['map']) && isset($_POST['phone']) && isset($_POST['time']))
		{
			// Variables for input data
			$name = $_POST['name'];
			$map = $_POST['map'];
			$phone = $_POST['phone'];
			$time = $_POST['time'];
			
			// SQL query for inserting data into tgos database
			$sql_query = "INSERT INTO tgos(name, map, phone, time) VALUES('$name','$map','$phone','$time')";
			mysqli_query($link, $sql_query);
		}
		else
		{
			echo "All fields are required!";
		}
	}
?>


<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增店家</title>
    <style>
        /* 基本重置 */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #FFF8DC; /* 柔和的黃色背景 */
            color: #333; /* 字體顏色為深灰色，適合閱讀 */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h1, h2 {
            color: #8B8000; /* 深黃色標題 */
        }

        h2 p {
            color: #666;
            font-size: 1rem;
        }

        #body {
            background-color: #F5DEB3; /* 小麥色背景，柔和不刺眼 */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* 微陰影增加層次感 */
            max-width: 500px;
            width: 100%;
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        td {
            padding: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #D2B48C; /* 淺黃色邊框 */
            border-radius: 5px;
            font-size: 1rem;
            background-color: #FFFACD; /* 柔和的奶油黃色背景 */
            color: #333;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #C5B358; /* 聚焦時的深黃色邊框 */
        }

        button[type="submit"], input[type="reset"] {
            background-color: #FFD700; /* 金黃色按鈕 */
            color: #333;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button[type="submit"]:hover, input[type="reset"]:hover {
            background-color: #FFC107; /* 深一點的黃色按鈕 hover 效果 */
        }

        a {
            color: #8B8000;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            #body {
                padding: 20px;
            }

            table {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <h1>新增店家</h1>
    <h2>
        <p>如果需要新增店家，請新增以下資料。</p>
    </h2>
    <div id="body">
        <div id="content">
            <form method="post">
                <table align="center">
                    <tr>
                        <td align="center"><a href="main.php">回到首頁</a></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="name" placeholder="店家名字" required /></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="map" placeholder="店家地址" required /></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="phone" placeholder="連絡方式" required /></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="time" placeholder="營業時間" required /></td>
                    </tr>
                    <tr>
                        <td><button type="submit" name="btn-save"><strong>SAVE</strong></button></td>
                    </tr>
                    <tr>
                        <td><input type="reset" value="清除表單"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>
</html>


