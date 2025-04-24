<?php
// ฟังก์ชันเพื่อดึง IP ของผู้ใช้งาน
function getUserIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
    return $_SERVER['REMOTE_ADDR'];
}
$myIP = getUserIP();

// ข้อความที่จะแสดงเมื่อมีผู้เข้ามาที่ห้องแชท
$visitorMessage = "👋 IP: $myIP เข้ามาที่ห้องแชท!";
$date = date("Y-m-d H:i:s");
$log = "[$date] $myIP เข้ามาที่หน้า Dashboard\n";
// บันทึกข้อความนี้ลงในไฟล์ visitorlog.txt
$visitorFile = '/module/log/visitorlog.txt';
file_put_contents("./module/log/visitorlog.txt", $log, FILE_APPEND);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Local Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eef1f5;
            padding: 40px;
            margin: 0;
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .card a {
            text-decoration: none;
            color: #34495e;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .emoji {
            font-size: 20px;
        }

        #notification {
            background-color: #f9c74f;
            color: #333;
            padding: 10px;
            border-radius: 8px;
            font-weight: bold;
            margin-bottom: 20px;
            display: none;
        }
    </style>
</head>

<body>
    <h1>🚀 My Local Projects</h1>
    <div class="container">
        <?php
        $allowed = ['Chatlnw', 'Dashboard'];

        foreach ($allowed as $folder) {
            if (is_dir("../$folder")) {
                echo "
                <div class='card'>
                    <a href='../$folder' target='_blank'>
                        <span class='emoji'>📁</span> $folder
                    </a>
                </div>
                ";
            }
        }
        ?>
    </div>

    <h1>🚧 Dev Projects</h1>
    <div class="container">
        <div class="card">
            <a href="#" target="_blank">
                <span class='emoji'>📄</span> warranty: FixBug and Edit Year Format
            </a>
            <ul style="margin: 10px 0 0 30px; padding: 0; font-size: 14px; color: #555;">
                <li>🐞 Bug Fixed</li>
                <li>📅 Year Format updated (Dev)</li>
            </ul>
        </div>

        <div class="card">
            <a href="#" target="_blank">
                <span class='emoji'>🧪</span> QLIS: Test API new Endpoint
            </a>
            <ul style="margin: 10px 0 0 30px; padding: 0; font-size: 14px; color: #555;">
                <li>🕒 Not yet implemented</li>
            </ul>
        </div>
    </div>

    <!-- chat -->
    <div id="notification">👋 มีคนเข้ามาที่ห้องแชท!</div>
    <h1>💬 Chat Room</h1>

    <div id="chat-box" style="height: 300px; overflow-y: auto; background: #fff; border: 1px solid #ccc; padding: 10px; border-radius: 8px; font-family: monospace;"></div>

    <form id="chat-form" style="margin-top: 10px; display: flex; gap: 10px;">
        <input type="text" id="nickname" placeholder="ตั้งชื่อเล่น (ไม่ตั้งจะใช้ IP)" style="width: 200px; padding: 8px; font-size: 16px;">
        <input type="text" id="message" placeholder="พิมพ์ข้อความ..." style="flex: 1; padding: 8px; font-size: 16px;">
        <button type="submit" style="padding: 8px 16px;">ส่ง</button>
    </form>

    <script>
        const chatBox = document.getElementById("chat-box");
        const chatForm = document.getElementById("chat-form");

        function loadChat() {
            fetch("./module/log/chat.php")
                .then((res) => res.text())
                .then((data) => {
                    chatBox.innerText = data;
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }

        setInterval(loadChat, 1000);
        loadChat();

        chatForm.addEventListener("submit", function(e) {
            e.preventDefault();
            const message = document.getElementById("message").value;
            const nickname = document.getElementById("nickname").value || "Guest";
            if (!message.trim()) return;

            const formData = new FormData();
            formData.append("nickname", nickname);
            formData.append("message", message);

            fetch("./module/log/chat.php", {
                method: "POST",
                body: formData
            }).then(() => {
                document.getElementById("message").value = "";
                loadChat();
            });
        });

        // แสดงข้อความแจ้งเตือนเมื่อโหลดหน้าเว็บ
        window.onload = function() {
            var notification = document.getElementById('notification');
            notification.style.display = 'block';
            setTimeout(function() {
                notification.style.display = 'none';
            }, 5000); // ซ่อนหลังจาก 5 วินาที
        }

        // แจ้งเตือนเมื่อผู้ใช้ออกจากหน้า
        window.onbeforeunload = function() {
            var ip = '<?php echo $_SERVER['REMOTE_ADDR']; ?>';
            var message = "IP " + ip + " ออกจากห้องแชท";
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "./module/log/log_out.php", true); // ส่งข้อมูลไปยัง log_out.php เพื่อบันทึกการออก
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("ip=" + ip + "&message=" + encodeURIComponent(message));
        }
    </script>

</body>

</html>
