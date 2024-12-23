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

// 게시물 수정 처리
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // 기존 게시물 데이터
    $query = "SELECT file FROM posts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $currentFilePath = $post['file'];  // 기존 파일 경로

    // 파일 업로드 처리
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $fileName = basename($_FILES['file']['name']);
        $filePath = $uploadDir . $fileName;

        // 파일을 지정된 디렉토리로 이동
        if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            echo "파일 업로드 성공! <br>";
        } else {
            echo "파일 업로드 실패! <br>";
        }
    } else {
        $filePath = $currentFilePath;  // 기존 파일 유지
    }

    // 게시물 수정
    $query = "UPDATE posts SET title = ?, content = ?, file = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $title, $content, $filePath, $postId);
    if ($stmt->execute()) {
        echo "게시물이 수정되었습니다.";
        header("Location: index.php");  // 수정 후 게시물 목록으로 리다이렉트
        exit;
    } else {
        echo "게시물 수정에 실패했습니다.";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시물 수정</title>
</head>
<body>
    <h1>게시물 수정</h1>
    <form action="edit_post.php?id=<?php echo $postId; ?>" method="post" enctype="multipart/form-data">
        <label for="title">제목:</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br><br>

        <label for="content">내용:</label>
        <textarea name="content" id="content" required><?php echo htmlspecialchars($post['content']); ?></textarea><br><br>

        <?php if (!empty($post['file'])): ?>
            <p>현재 파일: <a href="<?php echo $post['file']; ?>" download>파일 다운로드</a></p>
        <?php endif; ?>

        <label for="file">새 파일 업로드:</label>
        <input type="file" name="file" id="file"><br><br>

        <button type="submit" name="submit">게시물 수정</button>
    </form>

    <a href="post.php?id=<?php echo $postId; ?>">게시물로 돌아가기</a>
</body>
</html>

