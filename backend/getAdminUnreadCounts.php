<?php
// Returns unread message count for each user to admin
include '../config.php';

// Get all users except admin
$users = [];
$res = mysqli_query($conn, "SELECT username FROM users WHERE role='user'");
while ($row = mysqli_fetch_assoc($res)) {
    $users[] = $row['username'];
}

// Accept last seen message ids from JS
$lastSeen = [];
if (isset($_POST['lastSeen'])) {
    $lastSeen = json_decode($_POST['lastSeen'], true);
}

$unreadCounts = [];
foreach ($users as $user) {
    $lastId = isset($lastSeen[$user]) ? intval($lastSeen[$user]) : 0;
    $query = "SELECT COUNT(*) as cnt FROM chat_messages WHERE sender=? AND receiver='admin'";
    $params = [$user];
    $types = 's';
    if ($lastId > 0) {
        $query .= " AND id > ?";
        $params[] = $lastId;
        $types .= 'i';
    }
    $stmt = $conn->prepare($query);
    if (count($params) == 2) {
        $stmt->bind_param($types, $params[0], $params[1]);
    } else {
        $stmt->bind_param($types, $params[0]);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $cnt = 0;
    if ($row = $result->fetch_assoc()) {
        $cnt = intval($row['cnt']);
    }
    $unreadCounts[$user] = $cnt;
    $stmt->close();
}

echo json_encode(['success' => true, 'unread' => $unreadCounts]);
