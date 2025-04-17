
# 🧵 SimpChat-PHP
## 中文文档在英文后 ##

## English / en ##

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
git clone https://github.com/chrislxn/simpchat-php.git
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


## 中文 / zh-CN ##

# 🧵 SimpChat-PHP

一个基于 PHP 的超轻量聊天室，无需数据库、无需依赖，开箱即用，适合嵌入任何网页！

## ✨ 功能特色

- ✅ 简洁 UI，纯 HTML + PHP 实现
- ✅ 💬 每 1.5 秒自动刷新消息
- ✅ 🔐 URL 传参识别管理员身份
- ✅ 📱 自适应移动端布局
- ✅ 🧼 消息输出安全，支持 emoji
- ✅ ✂️ 每条发言限制 100 个 UTF-8 字符
- ✅ 🧩 无需数据库，使用文件 `chatlog.txt` 存储
- ✅ 🗂 自动保留最近 50 条消息
- ✅ ⏱ 限速机制：每用户每 2 秒仅允许发言一次
- ✅ 📄 支持 cron 定时归档超大日志

## 📦 安装方法

1. 克隆项目或将 `index.php` 放入你的网站目录：

```bash
git clone https://github.com/chrislxn/simpchat-php.git
```

2. 确保服务器已安装 PHP

3. 创建可写的日志文件：

```bash
touch chatlog.txt
chmod 644 chatlog.txt
```

4. 浏览器访问：

```
https://你的域名/chat/
```

## 🔐 管理员进入方式

访问以下 URL（设置自己的密钥）：

```
https://你的域名/chat/?admin=your_secret_key_here
```

管理员发言将带有红色 `[管理员]` 标识。

## 🛠 可选功能：日志归档

若日志超过 500KB，可保留最后 400 行，其余归档：

```bash
0 * * * * tail -n 400 /路径/chatlog.txt > /tmp/chat_tail.txt && ts=$(date +\%Y\%m\%d-\%H%M%S) && mv /路径/chatlog.txt /路径/chatlog-${ts}.log && mv /tmp/chat_tail.txt /路径/chatlog.txt && echo "[$(date +\%H:\%M:\%S)] 系统: 聊天记录已归档，保留最后 400 条。" >> /路径/chatlog.txt
```

## 📜 License

GPL-3.0 license © 2025 Chris Li

