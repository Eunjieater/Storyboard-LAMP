<?php
// config.php

// 데이터베이스 연결 정보
$host = 'localhost'; // 데이터베이스 호스트
$dbname = 'eunjiboard_db'; // 데이터베이스 이름
$username = 'admin'; // 데이터베이스 사용자명
$password = 'tlsdmswl9!'; // 데이터베이스 비밀번호

// MySQL 데이터베이스 연결
$conn = new mysqli($host, $username, $password, $dbname);

// 연결 오류 확인
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}
?>

