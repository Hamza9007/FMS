<?php
// download.php
session_start();
if (!isset($_SESSION['username'])) {
    die("Unauthorized");
}

$type = $_GET['type'] ?? '';
$file = basename($_GET['file'] ?? ''); // sanitize file name

$baseDirs = [
    'inquiries' => 'E:/uploads/inquiries/',
    'quotations' => 'E:/uploads/quotations/',
    'po' => 'E:/uploads/po/',
    'invoices' => 'E:/uploads/invoices/'
];

if (!isset($baseDirs[$type])) {
    die('Invalid file type.');
}

$filePath = $baseDirs[$type] . $file;
if (!file_exists($filePath)) {
    die('File not found.');
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit;
?>
