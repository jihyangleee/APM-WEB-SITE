<?php session_start();
include $_SERVER['DOCUMENT_ROOT']."/inc/dbcon.php";

// 로그인 체크
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.');location.href='/login.php';</script>";
    exit;
}

// id 파라미터 확인
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('잘못된 접근입니다.');history.back();</script>";
    exit;
}

$postId = (int)$_GET['id'];
$userId = $_SESSION['user_id'];

// 해당 글에 사용자가 존재하는지 확인
$stmt = $mysqli->prepare("SELECT userid FROM posts WHERE id = ?");
if (!$stmt) {
    die("query prepare error => ".$mysqli->error);
}

$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('존재하지 않는 글입니다.');location.href='/index.php';</script>";
    exit;
}

$row = $result->fetch_object();
$stmt->close();


// 작성자 본인인지 확인
if ($row->userid !== $userId) {
    echo "<script>alert('삭제 권한이 없습니다.');history.back();</script>";
    exit;
}


// 삭제 실행
$stmt = $mysqli->prepare("DELETE FROM posts WHERE id = ? AND userid = ?");
if (!$stmt) {
    die("query prepare error => ".$mysqli->error);
}
$stmt->bind_param("is", $postId, $userId);

if ($stmt->execute()) {
    echo "<script>alert('삭제되었습니다.');location.href='/index.php';</script>";
} else {
    echo "<script>alert('삭제에 실패했습니다.');history.back();</script>";
}
$stmt->close();
$mysqli->close();


