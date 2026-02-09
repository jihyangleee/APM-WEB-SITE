<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>Normaltic login hw</title>
	<link rel="stylesheet" href="style_login.css">

</head>
<body>
	<div class="container">
	<?php 
	if(!isset($_SESSION["user_id"]) || !isset($_SESSION["user_name"])){?>
	
	<form action ="/pages/auth/login_ok.php" method="post">
	<p class="loginId"> 아이디: <input type="text"name="user_id"></p>
	<p class="loginPw"> 비밀번호: <input type="password"name="user_pw"></p>
	<a href="/pages/auth/register.php">[회원가입]</a>

	<p><input type="submit" value="로그인"></p>
	<p><input type="button" value="취소" onclick="location.href='/index.php'"></p>
	</form>
	<?php } else{
		$user_id = $_SESSION['user_id'];
		$user_name=  $_SESSION['user_name'];
		echo "<p><strong>".htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8')."</strong> (".htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8').")님은 이미 로그인하고 있습니다.</p>";
		echo "<p><a href='/index.php'>[돌아가기]</a> <a href='/pages/auth/logout.php'>[로그아웃]</a></p>";
		}?>
	
	</body>
</html>

	 
