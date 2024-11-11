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

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

// 獲取店家詳細資訊
$id = $_GET['id'] ?? 1;
$sql = "SELECT pic, name, map, phone, time, tags, description, announcement FROM tgos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$store = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($store['name']); ?> - 店家介紹</title>
    <style>
        /* CSS 樣式 */
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
        
        .container {
            width: 100%;
            max-width: 1200px;
            padding: 50px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            transition: margin-left 0.3s;
        }

        .left {
            flex: 1;
            padding: 20px;
            margin-right: 20px;
            background-color: #f7f7f7;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .right {
            flex: 2;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2em;
            color: #0d47a1;
        }

        .image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .info {
            margin: 15px 0;
        }

        .tags {
            display: flex;
			flex-wrap: wrap;
        }

        .tag {
            background-color: #58c9b9;
			border: none;
			color: white;
			padding: 8px 15px;
			margin: 5px;
			border-radius: 20px;
			cursor: pointer;
			transition: background-color 0.3s ease;
        }

        .tag:hover {
            background-color: #4cafaf;
        }

        .editable, .comments-section, .announcement {
            margin-top: 20px;
        }

        .editable textarea, .announcement textarea, .comments-section textarea {
            width: 100%;
            height: 100px;
            margin-top: 5px;
            padding: 10px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ccc;
            resize: vertical;
        }

        .submit-btn {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .comment-actions button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            margin-left: 5px;
            cursor: pointer;
            border-radius: 4px;
        }
        .comment-actions button:hover {
            background-color: #0056b3;
        }
        .comment-actions .delete {
            background-color: #dc3545;
        }
        .comment-actions .delete:hover {
            background-color: #c82333;
        }
        .editable {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 4px;
        }
		.section {
            margin-bottom: 20px;
        }
        .comments {
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .comment {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
        }
        .comment p {
            margin: 5px 0;
        }
        .form-section {
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        form input, form textarea {
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            padding: 10px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="left">
        <h1><?php echo htmlspecialchars($store['name']); ?></h1>

        <!-- 店家圖片 -->
        <?php if ($store['pic']): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($store['pic']); ?>" alt="Store Image" class="image">
        <?php endif; ?>

        <!-- 店家基本資料 -->
        <div class="info">
            <h3>聯絡資訊</h3>
            <p><strong>地址：</strong><?php echo htmlspecialchars($store['map']); ?></p>
            <p><strong>聯絡電話：</strong><?php echo htmlspecialchars($store['phone']); ?></p>
            <p><strong>營業時間：</strong><?php echo htmlspecialchars($store['time']); ?></p>
        </div>

        <!-- 標籤區塊 -->
        <div class="tags">
            <h3>標籤</h3>
            <?php 
            $tags = explode(',', $store['tags']); // 將標籤字串轉換為陣列
            foreach ($tags as $tag): 
                $tag = trim($tag); // 去除多餘的空格
            ?>
                <span class="tag"><?php echo htmlspecialchars($tag); ?></span>
            <?php endforeach; ?>
        </div>

        <!-- 可編輯的店家介紹區塊 -->
        <div class="editable">
			<h3>店家介紹</h3>
			<?php if (isset($_SESSION['main_logged_in']) && $_SESSION['store_logged_in'] === true): ?>
				<form method="post" action="update_intro.php?id=<?php echo $id; ?>">
					<textarea name="introduction"><?php echo htmlspecialchars($store['description'] ?? ''); ?></textarea>
					<button type="submit" class="submit-btn">儲存介紹</button>
				</form>
			<?php else: ?>
				<p><?php echo htmlspecialchars($store['description'] ?? ''); ?></p> <!-- 未登入時顯示內容 -->
			<?php endif; ?>
		</div>


        <button><a href="main.php">回到首頁</a></button>
    </div>

    <!-- 留言區 -->
    <div class="right">
    <div class="announcement">
        <h3>公佈欄</h3>
        <?php if (isset($_SESSION['store_logged_in']) && $_SESSION['store_logged_in'] === true): ?>
            <form method="post" action="update_announcement.php?id=<?php echo $id; ?>">
                <textarea name="announcement"><?php echo htmlspecialchars($store['announcement'] ?? ''); ?></textarea>
                <button type="submit" class="submit-btn">儲存公佈</button>
            </form>
        <?php else: ?>
            <p><?php echo htmlspecialchars($store['announcement'] ?? ''); ?></p>
        <?php endif; ?>
    </div>

    <!-- 顧客評論區 -->
    <div class="section" id="reviews">
        <h2>顧客評論</h2>
        <div class="comments" id="comment-list">
            <!-- 顧客評論列表將在這裡生成 -->
        </div>
    </div>

    <!-- 新增評論表單 -->
    <div class="section form-section">
        <h2>新增評論</h2>
        <form id="comment-form">
            <input type="text" id="name" name="name" placeholder="你的名字" required>
            <input type="number" id="rating" name="rating" placeholder="評分（1-5）" min="1" max="5" required>
            <textarea id="comment" name="comment" rows="4" placeholder="你的評論" required></textarea>
            <button type="submit">提交評論</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('comment-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const name = document.getElementById('name').value;
        const rating = document.getElementById('rating').value;
        const comment = document.getElementById('comment').value;

        const commentSection = document.getElementById('comment-list');

        const newComment = document.createElement('div');
        newComment.classList.add('comment');

        const commentContent = document.createElement('div');

        const commentHeader = document.createElement('h3');
        commentHeader.textContent = name;
        commentContent.appendChild(commentHeader);

        const commentRating = document.createElement('p');
        commentRating.textContent = '評分: ' + '★'.repeat(rating) + '☆'.repeat(5 - rating);
        commentContent.appendChild(commentRating);

        const commentText = document.createElement('p');
        commentText.textContent = comment;
        commentContent.appendChild(commentText);

        newComment.appendChild(commentContent);

        const commentActions = document.createElement('div');
        commentActions.classList.add('comment-actions');

        const editButton = document.createElement('button');
        editButton.classList.add('edit');
        editButton.textContent = '編輯';
        editButton.addEventListener('click', function() {
            const newName = prompt('修改名字:', name);
            const newRating = prompt('修改評分 (1-5):', rating);
            const newCommentText = prompt('修改評論:', comment);
            if (newName && newRating && newCommentText) {
                commentHeader.textContent = newName;
                commentRating.textContent = '評分: ' + '★'.repeat(newRating) + '☆'.repeat(5 - newRating);
                commentText.textContent = newCommentText;
            }
        });

        const deleteButton = document.createElement('button');
        deleteButton.classList.add('delete');
        deleteButton.textContent = '刪除';
        deleteButton.addEventListener('click', function() {
            commentSection.removeChild(newComment);
        });

        commentActions.appendChild(editButton);
        commentActions.appendChild(deleteButton);
        newComment.appendChild(commentActions);

        commentSection.appendChild(newComment);

        document.getElementById('comment-form').reset();
    });
</script>
</div>

</body>
</html>

<?php $conn->close(); ?>
