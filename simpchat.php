<?php
session_start();

function clean($msg) {
    return substr(strip_tags(trim($msg)), 0, 200);
}

// 设置昵称与管理员状态
if (isset($_POST['setname'])) {
    $nickname = clean($_POST['nickname']);
    $is_admin = isset($_GET['admin']) && $_GET['admin'] === 'woshiadmin';
    $_SESSION['is_admin'] = $is_admin;

    $_SESSION['nickname'] = $nickname;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// 发消息
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['msg']) && isset($_SESSION['nickname'])) {
    if (!isset($_SESSION['last_post'])) $_SESSION['last_post'] = 0;
    if (time() - $_SESSION['last_post'] < 2) {
        http_response_code(429);
        echo "请稍等 2 秒再发送。";
        exit;
    }

    $raw_msg = trim($_POST['msg'] ?? '');

    if (mb_strlen($raw_msg, 'UTF-8') > 100) {
        http_response_code(413);
        echo "消息不能超过 100 个字符！";
        exit;
    }

    $msg = clean($raw_msg);
    if ($msg === '') {
        http_response_code(400);
        echo "不能发送空消息。";
        exit;
    }

    $_SESSION['last_post'] = time();
    $prefix = $_SESSION['is_admin'] ? "[管理员] " : "";
    $entry = "[" . date('H:i:s') . "] " . $prefix . $_SESSION['nickname'] . ": " . $msg . "\n";
    file_put_contents('chatlog.txt', $entry, FILE_APPEND | LOCK_EX);
    exit;
}

// 获取消息
if (isset($_GET['get']) && $_GET['get'] === '1') {
    $lines = file_exists('chatlog.txt') ? file('chatlog.txt') : [];
    $max_lines = 50;
    if (count($lines) > $max_lines) {
        $lines = array_slice($lines, -$max_lines);
        file_put_contents('chatlog.txt', implode('', $lines));
    }

    foreach ($lines as $line) {
        $safe = htmlspecialchars($line);
        $safe = preg_replace_callback('/^(\[\d{2}:\d{2}:\d{2}\])\s(\[管理员\]\s)?(.*?):/', function ($m) {
            $time = '<span style="color:gray;">' . $m[1] . '</span>';
            $admin = isset($m[2]) && $m[2] ? '<span style="color:red;">[管理员]</span> ' : '';
            $name = '<b>' . htmlspecialchars($m[3]) . '</b>';
            return "$time $admin$name:";
        }, $safe);
        echo $safe . "<br>";
    }
    exit;
}
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>MiniChat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; height: 100%; font-family: sans-serif; background: #f0f0f0; }
    #wrapper { display: flex; flex-direction: column; height: 100%; }
    #chatbox { flex: 1; overflow-y: auto; padding: 10px; background: #fff; font-size: 0.95em; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; }
    #chat-form { display: flex; padding: 10px; background: #fff; border-top: 1px solid #ccc; }
    #chat-form input[type="text"] { flex: 1; padding: 10px; font-size: 1em; border: 1px solid #ccc; border-radius: 4px; }
    #chat-form input[type="submit"] { padding: 10px 15px; margin-left: 8px; font-size: 1em; background: #2d8cf0; color: white; border: none; border-radius: 4px; }
    #char-count { font-size: 0.9em; color: gray; margin-top: 5px; }
    #notice { padding: 6px 12px; font-size: 0.9em; color: red; background: #fff8f8; border-top: 1px solid #f0c0c0; }
    form[name="join"] { padding: 20px; }
  </style>
</head>
<body>

<?php if (!isset($_SESSION['nickname'])): ?>
  <form method="post" name="join">
    <?php if (isset($_SESSION['error'])): ?>
      <p style="color:red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <label>请输入你的昵称：</label><br><br>
    <input type="text" name="nickname" style="width: 80%; padding: 10px;" required>
    <input type="submit" name="setname" value="加入聊天室" style="padding: 10px;">
  </form>
<?php else: ?>
  <div id="wrapper">
    <div id="chatbox">Loading...</div>
    <form id="chat-form">
      <input type="text" name="msg" id="msg" placeholder="输入消息..." required>
      <input type="submit" value="发送">
    </form>
    <div id="char-count"></div>
    <div id="notice"></div>
  </div>
  <script>
    const msgInput = document.getElementById('msg');
    const charCount = document.getElementById('char-count');
    const notice = document.getElementById('notice');

    msgInput.addEventListener('input', function () {
      const len = [...msgInput.value].length;
      charCount.textContent = `已输入 ${len} / 100 字符`;
      charCount.style.color = len > 100 ? 'red' : 'gray';
    });

    function loadMessages() {
      fetch('?get=1')
        .then(res => res.text())
        .then(html => {
          const box = document.getElementById('chatbox');
          box.innerHTML = html;
          box.scrollTop = box.scrollHeight;
        });
    }

    setInterval(loadMessages, 1500);
    loadMessages();

    document.getElementById('chat-form').addEventListener('submit', function (e) {
      e.preventDefault();
      const msg = msgInput.value.trim();
      const len = [...msg].length;

      if (msg === '') {
        notice.textContent = "不能发送空消息。";
        return;
      }
      if (len > 100) {
        notice.textContent = "消息不能超过 100 个字符！";
        return;
      }

      fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'msg=' + encodeURIComponent(msg)
      }).then(res => {
        if (!res.ok) return res.text().then(err => { throw new Error(err); });
        msgInput.value = "";
        loadMessages();
        notice.textContent = "";
        charCount.textContent = "";
      }).catch(err => {
        notice.textContent = err.message;
      });

      setTimeout(() => { notice.textContent = ""; }, 3000);
    });
  </script>
<?php endif; ?>

</body>
</html>