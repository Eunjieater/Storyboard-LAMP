<!-- index.php -->
<?php
session_start();
include('config.php');

// 로그인 여부 확인
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
    echo "<p>안녕하세요, $username 님</p>";
    echo "<a href='logout.php'>로그아웃</a>";
} else {
    echo "<a href='login.php'>로그인</a> | <a href='signup.php'>회원가입</a>";
}

// 게시물 출력 (예시)
$query = "SELECT * FROM posts ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);


// 검색어가 전달되었을 경우
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// 게시물 목록 조회 쿼리 (검색어가 있을 경우에만 조건 추가)
$query = "SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$searchTerm = "%" . $search . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시물 목록</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>게시물 목록</h1>

    <!-- 검색 폼 -->
    <form action="index.php" method="get">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="검색어를 입력하세요">
        <button type="submit">검색</button>
    </form>

    <ul>
        <?php
        // 게시물 목록 출력
        if ($result->num_rows > 0) {
            while ($post = $result->fetch_assoc()) {
                echo "<li><a href='post.php?id=" . $post['id'] . "'>" . htmlspecialchars($post['title']) . "</a></li>";
            }
        } else {
            echo "<li>검색 결과가 없습니다.</li>";
        }
        ?>
    </ul>

    <a href="create_post.php">게시물 작성</a>

    <?php
    // 쿼리 종료
    $stmt->close();

    // 데이터베이스 연결 종료
    $conn->close();
    ?>
</body>
</html>
