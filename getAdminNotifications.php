<?php
// Returns latest message info from all users to admin
include 'config.php';

// Get all users except admin
$users = [];
$res = mysqli_query($conn, "SELECT username FROM users WHERE role='user'");
while ($row = mysqli_fetch_assoc($res)) {
    $users[] = $row['username'];
}

$latestMessages = [];
foreach ($users as $user) {
    $stmt = $conn->prepare("SELECT * FROM chat_messages WHERE sender=? AND receiver='admin' ORDER BY sent_at DESC LIMIT 1");
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($msg = $result->fetch_assoc()) {
        $latestMessages[$user] = $msg;
    }
    $stmt->close();
}

echo json_encode(['success' => true, 'latest' => $latestMessages]);
