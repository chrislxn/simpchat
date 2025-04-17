
# 🧵 SimpChat-PHP

A **lightweight, mobile-friendly, file-based chatroom** written in PHP — no database, no dependencies, just plug-and-chat!

## ✨ Features

- ✅ Simple & minimalist design
- ✅ 💬 Real-time auto-refresh (1.5s)
- ✅ 🔐 Admin mode via URL token
- ✅ 📱 Fully responsive, mobile-ready layout
- ✅ 🧼 HTML-safe output, emoji-safe
- ✅ ✂️ Character limit (100 UTF-8 characters)
- ✅ 🧩 No database, all messages stored in `chatlog.txt`
- ✅ 🗂 Auto truncate to last 50 messages
- ✅ ⏱ Throttle: 1 message / 2 seconds
- ✅ 📄 Optional log archiving (via cron)

## 📦 Installation

1. Clone this repo or copy `index.php` to your PHP server directory:

```bash
git clone https://github.com/yourname/minichat-php.git
```

2. Ensure PHP is installed and running.

3. Create a writable log file:

```bash
touch chatlog.txt
chmod 644 chatlog.txt
```

4. Access the chatroom via browser:

```
https://yourdomain.com/chat/
```

## 🔐 Admin Access

Visit:

```
https://yourdomain.com/chat/?admin=your_secret_key_here
```

Admins will be tagged with `[Admin]` in red.

## 🛠 Optional: Log Archiving

Auto-truncate when `chatlog.txt` exceeds 500KB, keeping only the last ~400 lines:

```bash
0 * * * * tail -n 400 /path/to/chatlog.txt > /tmp/chat_tail.txt && ts=$(date +\%Y\%m\%d-\%H%M%S) && mv /path/to/chatlog.txt /path/to/chatlog-${ts}.log && mv /tmp/chat_tail.txt /path/to/chatlog.txt && echo "[$(date +\%H:\%M:\%S)] System: Chat log archived, keeping last 400 lines." >> /path/to/chatlog.txt
```

## 📜 License

MIT License © 2025 Your Name
