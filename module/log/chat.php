<?php
date_default_timezone_set('Asia/Bangkok');
$chatFile = "chatlog.txt";
$visitorFile = "visitorlog.txt";  // เก็บข้อมูลผู้เข้าชม

// เอา IP กลับไปหา JS (ถ้าจะใช้แบบ advanced)
if (isset($_GET['get_ip'])) {
    echo $_SERVER['REMOTE_ADDR'];
    exit;
}

// ส่งข้อความใหม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = strip_tags($_POST['nickname']);
    $ip = $_SERVER['REMOTE_ADDR'];
    $message = strip_tags($_POST['message']);
    $time = date("H:i:s");



    // แสดงชื่อเล่น (IP)
    $displayName = $nickname ?: $ip;
    if ($nickname) {
        $displayName .= " ($ip)";
    }

    file_put_contents($chatFile, "[$time][$displayName]: $message\n", FILE_APPEND);
    exit;
}

// แสดงข้อความทั้งหมด
if (file_exists($chatFile)) {
    echo file_get_contents($chatFile);
}

if (file_exists($visitorFile)) {
    echo file_get_contents($visitorFile);
}
