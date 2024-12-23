<!-- signup.php -->
<?php
session_start(); // 세션 시작
include('config.php'); // 데이터베이스 연결

// 폼이 POST로 전송된 경우
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 입력값 가져오기
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT); // 비밀번호 해시화

    // 이메일 중복 확인
    $check_user_query = "SELECT * FROM users WHERE email = '$email'";
    $check_user_result = mysqli_query($conn, $check_user_query);
    
    if (mysqli_num_rows($check_user_result) > 0) {
        echo "이미 존재하는 이메일입니다.";
    } else {
        // 회원가입 처리 (데이터베이스에 사용자 정보 저장)
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password_hash')";
        if (mysqli_query($conn, $query)) {
            // 회원가입 성공 후 로그인 페이지로 리다이렉트
            header('Location: login.php'); // 로그인 페이지로 리다이렉트
            exit(); // 코드 실행 종료
        } else {
            echo "Error: " . mysqli_error($conn); // 에러 발생 시 출력
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>회원가입</h1>
    </header>
    <main>
        <form action="signup.php" method="POST">
            <label for="username">아이디</label>
            <input type="text" id="username" name="username" required>

            <label for="email">이메일</label>
            <input type="email" id="email" name="email" required>

            <label for="password">비밀번호</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">가입하기</button>
        </form>
    </main>
</body>
</html>

