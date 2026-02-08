<?php session_start(); 
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

// 검색어 처리
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// 글 목록 조회 (검색어가 있으면 필터링)
$res = [];
if ($search !== '') {
    $searchParam = "%".$search."%";
    $stmt = $mysqli->prepare("SELECT id, userid, title, created_at as regdate FROM posts WHERE userid LIKE ? OR title LIKE ? ORDER BY created_at DESC LIMIT 20");
    $stmt->bind_param("ss", $searchParam, $searchParam);
} else {
    $stmt = $mysqli->prepare("SELECT id, userid, title, created_at as regdate FROM posts ORDER BY created_at DESC LIMIT 20");
}

if ($stmt) {
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_object()) {
    $res[] = $row;
  }
  $stmt->close();
}

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
      <link rel="stylesheet" href="/css/style.css">
    </head>
	<body>
		<h1 class="brand-title"><b>T</b>ime<b>-><b>T</b>ech</h1>
		
<nav class="navbar navbar-expand-lg border-bottom" data-bs-theme="light">
  <div class="container-fluid">
    <a class="navbar-brand" href="/index.php">Main</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">최신</a>
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
            <li><a class="dropdown-item" href="/post.php">등록</a></li>
            <li><a class="dropdown-item" href="/view_post.php">내 글</a></li>
          </ul>
        </li>
      
      <li class="nav-item">
        <a class="nav-link active" aria-current="page"  href="/mypage.php">마이페이지</a>
</li>
      </ul>
    </div>
    </nav>
    <table class="table">
            <thead>
                <tr>
                    <th scope="col">번호</th>
                    <th scope="col">글쓴이</th>
                    <th scope="col">제목</th>
                    <th scope="col">등록일</th>
                </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="4">
                  <form class="d-flex" role="search" method="GET" action="">
                    <input class="form-control me-2" type="search" placeholder="글쓴이 또는 제목 검색" name="q" aria-label="Search" value="<?php echo htmlspecialchars($search); ?>"/>
                    <button class="btn btn-outline-success" type="submit">Search</button>
                  </form>
                </td>
              </tr>
              <?php 
              $i = 1;
              foreach($res as $r): ?>
                <tr>
                    <th scope="row"><?php echo $i++;?></th>
                    <td><?php echo htmlspecialchars($r->userid);?></td>   <!-- 이름 -->
                    <!-- xss 방지 htmlspecialchars  -->
                    <td><a href="/read_post.php?id=<?php echo (int)$r->id;?>"><?php echo htmlspecialchars($r->title);?></a></td> <!--제목-->
                    <td><?php echo $r->regdate;?></td>
                </tr>
              <?php endforeach; ?>
    </tbody>
    </table>
    
    
        <div class="container auth-section">
        <?php 
                if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])){
        echo"<a href=\"login.php\" class=\"login-btn\"><span>로그인</span></a>";}
        else{
            $user_id = $_SESSION['user_id'];
            $user_name= $_SESSION['user_name'];
            echo"<div class=\"user-info\"><strong>$user_name</strong><span class=\"user-id\">$user_id</span>";
            echo"<a href='logout.php' class=\"logout-link\">로그아웃</a></div>";
        }
    ?>
        </div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
	</body>
</html>
<!-- 사람들이 보고 난 조회수 부분 
<button type="button" name="view" id="viewBtn">View</button>
<span>조회수: <strong id="viewCount">0</strong></span>
JS
<script> const postId = 123; // 실제 포스트 id로 교체 const viewCountEl = document.getElementById('viewCount'); document.getElementById('viewBtn').addEventListener('click', async () => { try { const res = await fetch('/view_count.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams({ id: String(postId) }) }); const data = await res.json(); if (data.ok) viewCountEl.textContent = data.views; } catch (e) {} }); // 페이지 진입 시 현재 조회수 표시(선택) (async () => { try { const res = await fetch('/view_count.php?id=' + postId); const data = await res.json(); if (data.ok) viewCountEl.textContent = data.views; } catch (e) {} })();
</script> -->