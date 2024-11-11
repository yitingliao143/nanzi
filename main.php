<?php
session_start();


	

	// Include translations
	include 'translations.php';

	// Database connection
	$servername = "localhost"; 
	$username = "root";    
	$password = "";    
	$dbname = "tgos";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Fetch tags from database
	$tags = [];
	$sql = "SELECT DISTINCT tags FROM tgos WHERE tags IS NOT NULL AND tags != ''";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$tags[] = $row['tags'];
		}
	}

	$conn->close();

	// Default language
	$defaultLanguage = 'zh'; // Change this to your desired default language
	$currentLanguage = isset($_SESSION['language']) ? $_SESSION['language'] : $defaultLanguage;

	// Handle language selection
	if (isset($_POST['language'])) {
		$selectedLanguage = $_POST['language'];
		if (array_key_exists($selectedLanguage, $translations)) {
			$_SESSION['language'] = $selectedLanguage;
			$currentLanguage = $selectedLanguage;
		}
	}

	// Translation function
	function translate($key, $language) {
		global $translations, $defaultLanguage;
		return $translations[$language][$key] ?? $translations[$defaultLanguage][$key] ?? $key;
	}

?>


<!DOCTYPE html>
<html lang="<?php echo $currentLanguage; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo translate('title', $currentLanguage); ?></title>
   
    <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;500;700&display=swap" rel="stylesheet">
   
    <style>
		/* 基本樣式 */
		body { 
			font-family: 'Zen Maru Gothic', sans-serif;
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			display: flex;
			flex-direction: column;
			min-height: 100vh;
			background-color: #546377;
			color: #0d47a1;
		}

		/* 頁首 */
		header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			background-color: #303c4d;
			padding: 15px;
			color: white;
		}

		header h1 {
			margin: 0;
			font-size: 24px;
		}

		.header-options {
			display: flex;
			align-items: center;
		}

		.header-options li {
			list-style-type: none;
			margin: 0 10px;
		}

		.header-options a,
		.header-options button {
			color: white;
			text-decoration: none;
			font-size: 16px;
			background-color: #444;
			padding: 8px 15px;
			border-radius: 5px;
			cursor: pointer;
			transition: background-color 0.3s;
		}

		.header-options a:hover,
		.header-options button:hover {
			background-color: #555;
		}

		/* 主體容器 */
		.container {
			display: flex;
			width: 90%;
			max-width: 1200px;
			margin: 20px auto;
			flex-wrap: wrap;
		}

		/* 左側區域 */
		.left-section {
			flex: 2;
			margin-right: 20px;
			display: flex;
			flex-direction: column;
			width: 50%;
		}

		.news-section {
			background-color: #f7f7f7;
			border-radius: 8px;
			padding: 20px;
			margin-bottom: 20px;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
		}

		.news-section h3 {
			font-size: 1.8em;
			margin-bottom: 10px;
		}

		.news-section li {
			margin: 10px 0;
		}

		/* 地圖區域 */
		#TGMap {
			background-color: #f7f7f7;
			border-radius: 8px;
			padding: 20px;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
			flex: 1;
			text-align: center;
			font-size: 1.2em;
			color: #555;
		}

		/* 右側區域 */
		.right-section {
			flex: 1;
			display: flex;
			flex-direction: column;
		}

		.top-right {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
		}

		/* 搜索區域 */
		.search-container {
			display: flex;
		}

		.search-container input {
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 4px;
			margin-right: 10px;
			flex: 1;
		}

		.search-container button {
			padding: 10px;
			background-color: #007BFF;
			color: white;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			transition: background-color 0.3s;
		}

		.search-container button:hover {
			background-color: #0056b3;
		}

		/* 右側底部 */
		.bottom-right {
			display: flex;
			flex-direction: column;
			gap: 10px;
		}

		/* 全寬按鈕 */
		.full-width-button {
			padding: 10px;
			background-color: #FFA726;
			color: white;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			transition: background-color 0.3s;
			width: 100%;
			text-align: center;
			font-size: 1em;
		}

		.full-width-button:hover {
			background-color: #FB8C00;
		}

		/* 按鈕容器 */
		.button-container a {
			display: block;
			padding: 10px;
			background-color: #007BFF;
			color: white;
			text-decoration: none;
			text-align: center;
			border-radius: 4px;
			transition: background-color 0.3s;
			font-size: 1em;
		}

		.button-container a:hover {
			background-color: #0056b3;
		}

		/* 標籤按鈕 */
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

		/* 下拉選單 */
		.dropdown {
			position: relative;
		}

		.dropdown-content {
			display: none;
			position: absolute;
			background-color: #f9f9f9;
			min-width: 160px;
			box-shadow: 0 8px 16px rgba(0,0,0,0.2);
			z-index: 1;
			border-radius: 4px;
			overflow: hidden;
		}

		.dropdown:hover .dropdown-content {
			display: block;
		}

		.dropdown-content a, .dropdown-content button {
			color: black;
			padding: 12px 16px;
			text-decoration: none;
			display: block;
			width: 100%;
			text-align: left;
			background-color: #f9f9f9;
			border: none;
			cursor: pointer;
			font-size: 1em;
		}

		.dropdown-content a:hover, .dropdown-content button:hover {
			background-color: #f1f1f1;
		}

		/* 標籤區域 */
		.tags-section {
			margin-top: 10px;
			display: flex;
			flex-wrap: wrap;
			gap: 10px;
		}
    




    </style>
</head>

<body>
    <header>
	<?php
       
        if (isset($_SESSION["username"])) {
            echo "歡迎, " . htmlspecialchars($_SESSION["username"]) . "!";
        }
        ?>
        <h1>歡迎成為楠梓人</h1>
		<div class="header">
                <li>
                    <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
                        <form action="logout.php" method="post" style="display:inline;">
                            <button type="submit" style="background: none; border: none; color: white;">登出</button>
                        </form>
                    <?php else: ?>
                        <button><a href="index.php">登入</a></button>
                    <?php endif; ?>
                </li>
                
                <li class="dropdown">
                    <button class="floating-button"><?php echo translate('language', $currentLanguage); ?></button>
                    <div class="dropdown-content dropdown-content-language">
                        <form method="POST">
                            <button name="language" value="zh">中文</button>
                            <button name="language" value="en">English</button>
                        </form>
                    </div>
                </li>
				<li class="dropdown">
                    <button class="floating-button"><?php echo translate('categories', $currentLanguage); ?></button>
                    <div class="dropdown-content dropdown-content-categories">
                        <a href="#"><?php echo translate('food', $currentLanguage); ?></a>
                        <a href="#"><?php echo translate('life', $currentLanguage); ?></a>
                        <a href="#"><?php echo translate('play', $currentLanguage); ?></a>
                        <a href="#"><?php echo translate('fashion', $currentLanguage); ?></a>
                        <a href="#"><?php echo translate('traffic', $currentLanguage); ?></a>
                    </div>
                </li>
        </div>    
    </header>
	</ul>

    <div class="container">
        <div class="left-section">
				<div class="news-section">
				<h3><?php echo translate('latestNews', $currentLanguage); ?></h3>
				<?php
					$news_sources = [
						['name' => 'Yahoo新聞', 'url' => 'https://tw.news.yahoo.com/'],
						['name' => '自由報新聞', 'url' => 'https://www.ltn.com.tw/'],
						['name' => '聯合新聞', 'url' => 'https://udn.com/news/index'],
						['name' => '中國時報', 'url' => 'https://www.chinatimes.com/'],
						['name' => 'BBC中文', 'url' => 'https://www.bbc.com/zhongwen/trad']
					];

					echo '<ul>';
					foreach ($news_sources as $source) {
						echo '<li><span class="tag-btn"><a href="' . htmlspecialchars($source['url']) . '" target="_blank">' . htmlspecialchars($source['name']) . '</a></span></li>';
					}
					echo '';
				?>
					
					
				</div>
				 <div id="TGMap"><h2>楠梓區地圖</h2>
				<img
				  src="nanzi.jpg"
				  width="600"
				  height="400" />
			</div>
			
		</div>

           
    

        <div class="right-section">
            <div class="search-container">
				<form method="GET" action="store.php">
					<input type="search" name="search" placeholder="請輸入搜尋名稱" required>
					<button type="submit">搜尋</button>
				</form>
			</div>

			<!-- Display tags as buttons -->
			<div class="tags-section">
				<?php foreach ($tags as $tag): ?>
					<button class="tag-btn" onclick="window.location.href='store.php?tag=<?php echo urlencode($tag); ?>'">
						<?php echo htmlspecialchars($tag); ?>
					</button>
				<?php endforeach; ?>
			</div>


            <div class="bottom-right">
                
                
                <li class="button-container button-container-add-store">
                    <a href="#" onclick="location.href='add_data.php'"><?php echo translate('addStore', $currentLanguage); ?></a>
                </li>
                <li class="button-container button-container-store-overview">
                    <a href="#" onclick="location.href='store.php'"><?php echo translate('storeOverview', $currentLanguage); ?></a>
                </li>
				<li class="button-container button-container-store-overview">
                    <a href="#" onclick="location.href='introduce.php'"><?php echo translate('storeIntroduce', $currentLanguage); ?></a>
                </li>
            </div>
        </div>
    
	</div>
</body>
</html>
