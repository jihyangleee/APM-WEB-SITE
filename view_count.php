<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__.'/inc/dbcon.php';

// Accept id from POST or GET
$id = 0;
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
} elseif (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "invalid id"]);
    exit;
}

// POST: increment views in posts table
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $mysqli->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["ok" => false, "error" => $mysqli->error]);
        exit;
    }
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(["ok" => false, "error" => $stmt->error]);
        $stmt->close();
        exit;
    }
    $stmt->close();
}

// Fetch current views
$views = null;
$stmt2 = $mysqli->prepare("SELECT views FROM posts WHERE id = ?");
if ($stmt2) {
    $stmt2->bind_param('i', $id);
    if ($stmt2->execute()) {
        $stmt2->bind_result($views);
        $stmt2->fetch();
    }
    $stmt2->close();
}

if ($views === null) {
    http_response_code(404);
    echo json_encode(["ok" => false, "error" => "post not found"]);
    exit;
}

echo json_encode(["ok" => true, "id" => $id, "views" => intval($views)]);
exit;
?>
