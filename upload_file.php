// 업로드 파일 처리

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $upload_dir = 'uploads/';

    if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
        echo "파일 업로드 성공!";
    } else {
        echo "파일 업로드 실패!";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>파일 업로드</title>
</head>
<body>
    <h1>파일 업로드</h1>
    <form method="post" enctype="multipart/form-data">
        <label>파일: <input type="file" name="file"></label><br>
        <button type="submit">업로드</button>
    </form>
</body>
</html>

