<?php session_start(); 
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";
?>
<!DOCTYPE html>
<html>
	<head> 
		
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<title>TechBlogMain</title>
    	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="/assets/css/style.css">
    </head>
	<body>
		<h1 class="brand-title"><b>T</b>ime<b>-><b>T</b>ech</h1>
		
<nav class="navbar navbar-expand-lg border-bottom" data-bs-theme="light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Main</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/recent_blog.php">최신</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">인기</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">질의응답</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            글 등록
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/pages/post/post.php">등록</a></li>
            <li><a class="dropdown-item" href="/pages/post/view_post.php">내 글</a></li>
          </ul>
        </li>
        <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="/pages/user/mypage.php">마이페이지</a>
       </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
    </div>
</nav>
    <div class="container auth-section">
    <?php 
			if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])){
	echo"<a href=\"/pages/auth/login.php\" class=\"login-btn\"><span>로그인</span></a>";}
	else{
		$user_id = $_SESSION['user_id'];
		$user_name= $_SESSION['user_name'];
		echo"<div class=\"user-info\"><strong>$user_name</strong><span class=\"user-id\">$user_id</span>";
		echo"<a href='/pages/auth/logout.php' class=\"logout-link\">로그아웃</a></div>";
	}
?>
    </div>
  
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
	</body>
</html>
