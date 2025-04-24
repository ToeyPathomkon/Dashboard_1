<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip = $_POST['ip'];
    $message = $_POST['message'];
    $time = date("H:i:s");

    $visitorFile = "visitorlog.txt";  // เก็บข้อมูลการเข้าและออกของผู้ใช้

    // บันทึกข้อความการออก
    file_put_contents($visitorFile, "[$time] $message (IP: $ip) \n", FILE_APPEND);
}
?>
