<!-- login.php -->
<?php
session_start(); // 세션 시작
include('config.php'); // 데이터베이스 연결

// 로그인 처리
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    // 이메일을 기반으로 사용자 찾기
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    // 비밀번호 확인
    if ($user && password_verify($password, $user['password'])) {
        // 로그인 성공: 세션에 사용자 정보 저장
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // 메인 페이지로 리다이렉트
        header('Location: index.php');
        exit(); // 코드 실행 종료
    } else {
        echo "아이디 또는 비밀번호가 잘못되었습니다.";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>로그인</h1>
    </header>
    <main>
        <form action="login.php" method="POST">
            <label for="email">이메일</label>
            <input type="email" id="email" name="email" required>

            <label for="password">비밀번호</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">로그인</button>
        </form>
    </main>
</body>
</html>

