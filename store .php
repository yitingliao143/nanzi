<?php
session_start();

// 檢查使用者是否已登入
$is_logged_in = isset($_SESSION["login"]) && $_SESSION["login"] === true;

// 您可以根據 $is_logged_in 變數顯示不同的內容
if ($is_logged_in) {
    echo "歡迎, " . htmlspecialchars($_SESSION["username"]) . "!";
} else {
    echo "您尚未登入。";
}



		$servername = "localhost"; 
		$username = "root";    
		$password = "";    
		$dbname = "tgos";        

		
		$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}


$sql = "SELECT id, pic, name,  map, phone, time,tags FROM tgos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>店家介紹</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style> 
		body {
			font-family: 'Roboto', sans-serif;
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			display: flex;
			min-height: 100vh;
			background-color: #546377;
			color: #0d47a1;
		}
		
		#sideMenuSwitch:checked ~ .container {
			margin-left: 250px; /* 側邊選單開啟時向右移動主表格 */
		}

		.container {
			width: 90%;
			max-width: 1200px;
			padding: 20px;
			background-color: #ffffff;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			border-radius: 8px;
			margin-top: 20px;
			margin-left: auto;
			margin-right: auto;
			transition: margin-left 0.3s; /* 添加平滑過渡 */
		}
	   
		h1 {
			text-align: center;
			margin-bottom: 20px;
			font-size: 2em;
			color: #fff;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			background-color: #fff;
			border-radius: 10px;
			overflow: hidden;
		}

		table thead {
			background-color: #303c4d;
			color: #fff;
		}

		table th, table td {
			padding: 15px;
			text-align: left;
			border-bottom: 1px solid #ddd;
		}

		table th {
			font-weight: 700;
		}

		table tr:hover {
			background-color: #f1f1f1;
		}

		table img {
			width: 100px;
			height: auto;
			cursor: pointer;
		}

		.modal {
			display: none;
			position: fixed;
			z-index: 1;
			padding-top: 60px;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			overflow: auto;
			background-color: rgb(0, 0, 0);
			background-color: rgba(0, 0, 0, 0.9);
		}

		.modal-content {
			margin: auto;
			display: block;
			width: 80%;
			max-width: 700px;
			max-height: 80%;
			object-fit: contain;
		}

		.modal-content, #caption {
			animation-name: zoom;
			animation-duration: 0.6s;
		}

		@keyframes zoom {
			from {transform: scale(0)} 
			to {transform: scale(1)}
		}

		.close {
			position: absolute;
			top: 15px;
			right: 35px;
			color: #f1f1f1;
			font-size: 40px;
			font-weight: bold;
			transition: 0.3s;
		}

		.close:hover,
		.close:focus {
			color: #bbb;
			text-decoration: none;
			cursor: pointer;
		}

		.sideMenu {
			position: fixed;
			width: 250px;
			height: 100%;
			background-color: #ff7575;
			display: flex;
			flex-direction: column;
			padding: 50px 0;
			box-shadow: 5px 0 5px rgba(48, 60, 77, 0.6);
			transform: translateX(-100%);
			transition: 0.3s;
		}

		.sideMenu form {
			display: flex;
			margin: 0 10px 50px;
			border-radius: 100px;
			border: 1px solid #fff;
		}

		.sideMenu form input {
			width: 85%;
			border: none;
			padding: 5px 10px;
			background-color: transparent;
			color: #fff;
		}

		.sideMenu form button {
			width: 15%;
			border: none;
			padding: 5px 10px;
			background-color: transparent;
			color: #fff;
		}

		.side-menu-switch {
			position: absolute;
			height: 80px;
			width: 40px;
			background-color: #303c4d;
			color: #ffffff;
			right: -40px;
			top: 0;
			bottom: 0;
			margin: auto;
			line-height: 80px;
			text-align: center;
			font-size: 30px;
			border-radius: 0 10px 10px 0;
			cursor: pointer;
		}

		#sideMenuSwitch:checked + .sideMenu {
			transform: translateX(0);
		}
		
		.tags-container {
			display: flex;
			flex-wrap: wrap;
		}

		.tag-btn {
			background-color: #58c9b9;
			border: none;
			color: white;
			padding: 8px 15px;
			margin: 5px;
			border-radius: 20px;
			cursor: pointer;
			transition: background-color 0.3s ease;
		}

		.tag-btn:hover {
			background-color: #4cafaf;
		}
		/* 側邊選單內文字的樣式 */
		.sideMenu a {
			display: block;
			padding: 10px 20px;
			font-size: 18px;
			color: #fff;
			text-decoration: none; /* 移除下劃線 */
			font-weight: bold;
			border-radius: 8px;
			margin: 5px 10px;
			background-color: rgba(255, 255, 255, 0.2);
			transition: background-color 0.3s, color 0.3s, transform 0.3s;
			text-align: center;
		}

		/* 滑鼠懸停效果 */
		.sideMenu a:hover {
			background-color: rgba(255, 255, 255, 0.4);
			color: #303c4d;
			transform: scale(1.05); /* 略微放大 */
		}

	</style>

</head>
<body>
    <input type="checkbox" id="sideMenuSwitch">

    <div class="sideMenu">
        <form method="GET" action="">
            <input type="search" name="search" placeholder="請輸入搜尋名稱">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
        <ul class="nav">
           <li><a href="index.php"><i class="fas fa-chalkboard"></i>ALL</a></li>
		   <li>
                <a href="food.php"><i class="fas fa-sitemap"></i>各式美食</a>
            
            </li>
            <li><a href="bus.php"><i class="fas fa-chalkboard"></i>大眾運輸</a></li>
            <li><a href="store.php"><i class="fas fa-book-reader"></i>便利商店</a></li>
            <li><a href="life.php"><i class="fas fa-user-graduate"></i>生活用品</a></li>
            <li><a href="scot.php"><i class="fas fa-trophy"></i>機車相關</a></li>
			<li><a href="main.php"><i class="fas fa-arrow-left"></i>回到首頁</a></li>
		</ul>
        <label for="sideMenuSwitch" class="side-menu-switch">
            <i class="fa fa-angle-right"></i>
        </label>
    </div>
    <div class="container">
        <h2>楠梓店家總覽</h2>
        <table>
			<thead>
            <tr>
                <th>圖片</th>
                <th>名稱</th>
                <th>地址</th>
                <th>電話</th>
                <th>營業時間</th>
				<th>標籤</th>
            </tr>
            </thead>
            <tbody>
			<?php 
				$servername = "localhost";
				$username = "root";
				$password = "";
				$dbname = "tgos";

				$conn = new mysqli($servername, $username, $password, $dbname);

				if ($conn->connect_error) {
					die("連接失敗: " . $conn->connect_error);
				}

				// 檢查是否有 search 或 tag 搜尋參數
				$search = isset($_GET['search']) ? $_GET['search'] : '';
				$tagFilter = isset($_GET['tag']) ? $_GET['tag'] : '';

				// 基礎 SQL 查詢語句
				$sql = "SELECT id, pic, name, map, phone, time, tags FROM tgos";

				// 根據搜尋條件組合 WHERE 子句
				$conditions = [];
				if ($search) {
					$conditions[] = "(name LIKE '%" . $conn->real_escape_string($search) . "%' OR tags LIKE '%" . $conn->real_escape_string($search) . "%')";
				}
				if ($tagFilter) {
					$conditions[] = "tags LIKE '%" . $conn->real_escape_string($tagFilter) . "%'";
				}

				// 如果有條件，將條件添加到 SQL 查詢語句中
				if (!empty($conditions)) {
					$sql .= " WHERE " . implode(" AND ", $conditions);
				}

				// 執行查詢
				$result = $conn->query($sql);

				// 顯示查詢結果
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						echo "<tr>";
						echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['pic']) . "' alt='" . htmlspecialchars($row['name']) . "'></td>";
						// 新增連結至 introduce.php，並傳遞店家 id
						echo "<td data-label='name'><a href='introduce.php?id=" . $row["id"] . "'>" . htmlspecialchars($row["name"]) . "</a></td>";
						echo "<td data-label='map'><a href='https://www.google.com/maps/search/?api=1&query=" . urlencode($row["map"]) . "' target='_blank'>" . $row["map"] . "</a></td>";
						echo "<td data-label='phone'>" . $row["phone"] . "</td>";
						echo "<td data-label='time'>" . $row["time"] . "</td>";
						echo "<td><div class='tags-container'>";
						$tags = explode(',', $row['tags']);
						foreach ($tags as $tag) {
							echo "<button class='tag-btn' onclick=\"window.location.href='store.php?tag=" . urlencode(trim($tag)) . "'\">" . htmlspecialchars(trim($tag)) . "</button>";
						}
						echo "</div></td>";
						echo "</tr>";
					}
				} else {
					echo "<tr><td colspan='6'>沒有資料</td></tr>";
				}

				$conn->close();
				?>


			</tbody>
		</table>
    </div>
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>

    <script>
        var modal = document.getElementById("myModal");

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var imgs = document.querySelectorAll("table img");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        imgs.forEach(img => {
            img.onclick = function () {
                modal.style.display = "block";
                modalImg.src = this.src;
                captionText.innerHTML = this.alt;
            }
        });

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }
    </script>
</body>
</html>