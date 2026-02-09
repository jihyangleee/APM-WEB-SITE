<?php
session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

	if(!isset($_POST['user_id']) || !isset($_POST['user_pw'])){
		header("Content-Type: text/html; charset=UTF-8");
		echo"<script>alert('아이디 또는 비밀번호가 빠졌거나 잘못된 접근입니다.');";
		echo "window.location.replace('/pages/auth/login.php');</script>";
		exit;

	}
	$user_id = $mysqli->real_escape_string($_POST['user_id']);
	$user_pw = $_POST['user_pw'];
	
	//데이터 베이스 쿼리 
	//db에 저장 및 session 생성
	$sql = "SELECT * FROM users WHERE userid = '$user_id'";
	$result=  $mysqli-> query($sql);

	if($result -> num_rows >0){
		$row = $result->fetch_assoc();
		if(password_verify($user_pw,$row['password'])){
			$_SESSION['user_id'] = $user_id;
			$_SESSION['user_name'] = $row['nickname'];
			
			header("Location: /index.php");
			exit;
		}else{// 인증실패
			
			header("Content-Type: text/html; charset=UTF-8");
			echo"<script>alert('아이디 또는 비밀번호가 잘못되었습니다.');";
			echo"window.location.replace('/pages/auth/login.php');</script>";
			exit;
		}}
	
	else{
		//일치하는 id가 없는 경우
		
		header("Content-Type: text/html; charset=UTF-8");
		echo"<script>alert('아이디 혹은 비밀번호가 잘못되었습니다.');";
		echo"window.location.replace('/pages/auth/login.php');</script>";
		exit;
	}
	$mysqli->close();?>
