<?php
// config.php 파일을 포함하여 데이터베이스 연결
require_once 'config.php';

// 게시물 ID를 URL에서 받음
if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    // 게시물 조회 쿼리
    $query = "SELECT * FROM posts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    // 게시물이 존재하면 출력
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        
        // 게시물 작성자와 로그인한 사용자가 같은지 확인
        // 예시로 세션에 저장된 사용자 ID와 비교
        session_start();
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']) {
            // 게시물 삭제 처리
            if (isset($_POST['delete'])) {
                $queryDelete = "DELETE FROM posts WHERE id = ?";
                $stmtDelete = $conn->prepare($queryDelete);
                $stmtDelete->bind_param("i", $postId);
                if ($stmtDelete->execute()) {
                    echo "게시물이 삭제되었습니다.";
                    header("Location: index.php"); // 목록 페이지로 리다이렉트
                    exit;
                } else {
                    echo "게시물 삭제에 실패했습니다.";
                }
            }
        } else {
            echo "삭제 권한이 없습니다.";
            exit;
        }
    } else {
        echo "해당 게시물을 찾을 수 없습니다.";
        exit;
    }
} else {
    echo "게시물 ID가 지정되지 않았습니다.";
    exit;
}
?>

