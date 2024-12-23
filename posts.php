<?php
// config.php 파일을 포함하여 데이터베이스 연결
require_once 'config.php';

// URL에서 게시물 ID 가져오기
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 게시물 상세 내용 조회 쿼리
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // 게시물이 존재하면 출력
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
    } else {
        echo "게시물을 찾을 수 없습니다.";
        exit();
    }

    // 쿼리 종료
    $stmt->close();
} else {
    echo "게시물 ID가 없습니다.";
    exit();
}

// 데이터베이스 연결 종료
$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시물 상세 보기</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
    <p><small>작성일: <?php echo $post['created_at']; ?></small></p>

    <?php if (!empty($post['file'])): ?>
    <p><a href="<?php echo $post['file']; ?>" download>파일 다운로드</a></p>
    <?php endif; ?>
    <a href="index.php">목록으로 돌아가기</a>
</body>
</html>

