<?php
session_start(); 
// 세션이 있는 경우 register_ok.php가 작동되지 않게 하기 위함
if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
	header('Location: index.php');
	exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>회원가입</title>
</head>
<body>
<h1>회원가입이 완료되었습니다.</h1>
<?php
	echo "<a href=\"login.php\">[로그인하기]</a></p>";?>
</body>
</html>
