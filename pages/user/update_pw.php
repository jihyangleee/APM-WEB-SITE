<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

if(!isset($_SESSION['user_id'])){
  echo"<script>alert('권한이 없습니다.');location.href='/index.php';</script>";
  exit;
}

$userid=$_SESSION['user_id'];
$input_userid = isset($_POST['userid']) ? trim($_POST['userid']) : '';
$ex_pw=isset($_POST['ex_pw'])? trim($_POST['ex_pw']) :'';
$new_pw=isset($_POST['new_pw'])? trim($_POST['new_pw']):'';

if($userid != $input_userid){
    echo"<script>alert('아이디가 일치하지 않습니다.');history.back();</script>";
    exit;
}

// 기존 비밀번호 확인
$stmt = $mysqli->prepare("SELECT password FROM users WHERE userid = ?");
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if(!$row || !password_verify($ex_pw, $row['password'])){
    echo"<script>alert('비밀번호가 틀렸습니다.');history.back();</script>";
    exit;
}

// 새 비밀번호 유효성 검사
if(strlen($new_pw) < 4){
    echo"<script>alert('새 비밀번호는 4자 이상이어야 합니다.');history.back();</script>";
    exit;
}

// 새 비밀번호로 업데이트
$hashed_pw = password_hash($new_pw, PASSWORD_DEFAULT);
$stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE userid = ?");
$stmt->bind_param("ss", $hashed_pw, $userid);
$ok = $stmt->execute();
$stmt->close();

if($ok){
    echo"<script>alert('비밀번호가 변경되었습니다.');location.href='/index.php';</script>";
    exit;
} else {
    echo"<script>alert('비밀번호 변경에 실패했습니다.');history.back();</script>";
    exit;
}
?>

