<?php
	
	include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";
	$username=$password=$password_confirm=$nickname="";
	$wu=$wp=0;

	if($_SERVER['REQUEST_METHOD']=='POST'){
		$username= $_POST['username'];
		$password= $_POST['password'];
		$password_confirm=$_POST['password_confirm'];
		$nickname=$_POST['nickname'];
	if($username != ""){ //사용자가 존재하면
	
		$username=  mysqli_real_escape_string($mysqli,$username);
		$nickname= mysqli_real_escape_string($mysqli,$nickname);

		$jb_sql = "SELECT userid FROM users WHERE userid='$username';";
		$jb_result= mysqli_query($mysqli,$jb_sql);

		if($jb_result && mysqli_fetch_array($jb_result)){
			$wu = 1;//사용자가 이미 존재한다.
		}elseif($password !== $password_confirm){
			$wp = 1; //비밀번호가 일치하지 않는다
		}else{
			$encrypted_password = password_hash($password, PASSWORD_DEFAULT);
			$jb_sql_add_user= "INSERT INTO users(userid,password,nickname) VALUES('$username','$encrypted_password','$nickname');";
			// 등록 시도
			if(!mysqli_query($mysqli, $jb_sql_add_user)){
				die("Error:" .$jb_sql_add_user ."<br>".mysqli_error($mysqli));
			}
			// // 자동 로그인 처리
			// $_SESSION['user_id'] = $username;
			// $_SESSION['user_name'] = $nickname;
			header('Location:/pages/auth/register_ok.php');
			exit;
		}
		mysqli_close($mysqli);
	}}
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<title>회원가입</title>
	</head>
<body>
	<h1>회원가입</h1>
	<form action="/pages/auth/register.php" method="POST">
	<p><input type="text" name="username" placeholder="사용자 ID"required></p>
	<p><input type="password" name="password" placeholder="비밀번호"required></p>
	<p><input type="password" name="password_confirm" placeholder="비밀번호 확인"required></p>
	<p><input type="text" name="nickname" placeholder="닉네임"required></p>
	<p><input type="submit" value="회원가입"></p>
	<?php 
	if($wu===1){
		echo"<p>사용자ID가 중복되었습니다.</p>";
	}
	if($wp===1){
		echo"<p>비밀번호가 일치하지 않습니다.</p>";
	}
	?>
	</form>
	</body>
</html>

		
