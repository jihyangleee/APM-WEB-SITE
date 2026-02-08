<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

if(!isset($_SESSION['user_id'])){
    echo"<script>alert('로그인이 필요합니다.');location.href='/login.php';</script>";
    exit;
}
$userid = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>비밀번호 변경</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h3>비밀번호 변경</h3>
        <form method="post" action="/update_pw.php">
            <div class="mb-3">
                <label class="form-label">아이디</label>
                <input type="text" name="userid" class="form-control" placeholder="<?php echo htmlspecialchars($userid); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">기존 비밀번호</label>
                <input type="password" name="ex_pw" class="form-control" >
            </div>
            <div class="mb-3">
                <label class="form-label">새로운 비밀번호</label>
                <input type="password" name="new_pw" class="form-control">
            </div>
            <button type="submit" class="btn btn-pastel-green">비밀번호 변경</button>
            <a href="/index.php" class="btn btn-pastel-green">취소</a>
        </form>
</div>
     
</body>
</html>