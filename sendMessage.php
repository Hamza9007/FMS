<?php
// sendMessage.php: Receives sender, receiver, and message; inserts into chat_messages
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender = $_POST['sender'] ?? '';
    $receiver = $_POST['receiver'] ?? '';
    $message = $_POST['message'] ?? '';

    if ($sender && $receiver && $message) {
        $stmt = $conn->prepare("INSERT INTO chat_messages (sender, receiver, message) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $sender, $receiver, $message);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing fields']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
