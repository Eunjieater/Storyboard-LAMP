<?php
// config.php 파일을 포함하여 데이터베이스 연결
require_once 'config.php';

// 게시물 제목과 내용이 전달되었는지 확인
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 제목과 내용이 비어있는지 체크
    if (empty($_POST['title']) || empty($_POST['content'])) {
        echo "제목과 내용을 모두 입력해주세요.";
        exit;
    }

    // 데이터베이스에 저장할 게시물 제목과 내용
    $title = $_POST['title'];
    $content = $_POST['content'];

    // 파일 업로드 처리
    $filePath = '';  // 기본적으로 파일이 없으면 빈 문자열로 설정
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        // 파일 업로드 처리
        $uploadDir = 'uploads/';  // 파일을 저장할 디렉토리
        $fileName = basename($_FILES['file']['name']);
        $filePath = $uploadDir . $fileName;
        // 파일을 지정된 디렉토리로 이동
        if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            echo "파일 업로드 성공!";
        } else {
            echo "파일 업로드 실패!";
        }
    }

    // 게시물 데이터를 데이터베이스에 저장
    $query = "INSERT INTO posts (title, content, file) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $title, $content, $filePath); // 제목, 내용, 파일 경로를 저장

    // 쿼리 실행
    if ($stmt->execute()) {
        echo "게시물이 저장되었습니다.";
        header("Location: index.php");  // 저장 후 게시물 목록으로 리다이렉트
        exit;
    } else {
        echo "게시물 저장에 실패했습니다.";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시물 생성</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>게시물 생성</h1>
    <form action="create_post.php" method="POST">
        <label for="title">제목:</label>
        <input type="text" name="title" id="title" required><br>

        <label for="content">내용:</label>
        <textarea name="content" id="content" required></textarea><br>
	
	<label for="file">파일 업로드:</label>
        <input type="file" name="file" id="file"><br><br>

        <button type="submit">게시물 생성</button>
    </form>
</body>
</html>

