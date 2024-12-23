<?php
// config.php 파일을 포함하여 데이터베이스 연결
require_once 'config.php';

// 게시물 ID를 URL에서 받음
if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    // 게시물 조회 쿼리
    $query = "SELECT * FROM posts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postId); // ID는 정수형
    $stmt->execute();
    $result = $stmt->get_result();

    // 게시물이 존재하면 출력
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
    } else {
        echo "해당 게시물을 찾을 수 없습니다.";
        exit;
    }
} else {
    echo "게시물 ID가 지정되지 않았습니다.";
    exit;
}

// 게시물 삭제 처리
if (isset($_POST['delete'])) {
    $deleteQuery = "DELETE FROM posts WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $postId);
    if ($deleteStmt->execute()) {
        echo "게시물이 삭제되었습니다.";
        header("Location: index.php"); // 삭제 후 메인 페이지로 리다이렉트
        exit;
    } else {
        echo "게시물 삭제에 실패했습니다.";
    }
}

// 데이터베이스 연결 종료
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시물 내용</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
    <p><small>작성일: <?php echo $post['created_at']; ?></small></p>

    <a href="index.php">게시물 목록으로 돌아가기</a>

    <br><br>
    <!-- 게시물 수정 버튼 -->
    <a href="edit_post.php?id=<?php echo $post['id']; ?>">게시물 수정</a>

    <!-- 게시물 삭제 버튼 -->
    <form action="post.php?id=<?php echo $post['id']; ?>" method="post" onsubmit="return confirm('정말 삭제하시겠습니까?');">
        <button type="submit" name="delete">게시물 삭제</button>
    </form>

    <!-- 파일 다운로드 링크 추가 -->
    <?php if (!empty($post['file'])): ?>
        <p>첨부 파일: <a href="uploads/<?php echo htmlspecialchars($post['file']); ?>" download>파일 다운로드</a></p>
    <?php else: ?>
        <p>파일이 업로드되지 않았습니다.</p>
    <?php endif; ?>

</body>
</html>

