<?php
// getMessages.php: Fetches chat messages between two users
include 'config.php';

$sender = $_GET['sender'] ?? '';
$receiver = $_GET['receiver'] ?? '';

if ($sender && $receiver) {
    $stmt = $conn->prepare("SELECT * FROM chat_messages WHERE (sender=? AND receiver=?) OR (sender=? AND receiver=?) ORDER BY sent_at ASC");
    $stmt->bind_param('ssss', $sender, $receiver, $receiver, $sender);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode(['success' => true, 'messages' => $messages]);
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Missing fields']);
}
