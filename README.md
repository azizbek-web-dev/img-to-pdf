# IMG TO PDF Bot

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Telegram](https://img.shields.io/badge/Telegram-2CA5E0?style=for-the-badge&logo=telegram&logoColor=white)

**A powerful Telegram bot for converting images to PDF with multi-language support**

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

</div>

---

## ✨ Features

- **Image Upload** - Single or multiple images support
- **PDF Generation** - Beautiful and organized PDFs using DomPDF
- **Inline Keyboard** - Convenient interactive buttons
- **Auto Cleanup** - Files automatically deleted after processing
- **Multi-language** - English, Russian, and Uzbek support
- **Real-time** - Instant PDF generation and delivery
- **Secure** - HTTPS and webhook protection

## 🚀 Quick Start

### Prerequisites

- PHP 8.2 or higher
- Laravel 12.0 or higher
- Composer
- HTTPS domain (required for webhook)
- Telegram Bot Token

### Installation

```bash
# Clone the repository
git clone <repository-url>
cd img-to-pdf/laravel

# Install dependencies
composer install
npm install && npm run build

# Environment setup
cp .env.example .env
# Edit .env file with your bot token
```

### Configuration

```env
APP_NAME="IMG TO PDF Bot"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

TELEGRAM_BOT_TOKEN=your_bot_token_here
```

### Setup Commands

```bash
# Generate application key
php artisan key:generate

# Create storage link
php artisan storage:link

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🤖 Bot Setup

### 1. Create Telegram Bot

Contact [@BotFather](https://t.me/BotFather) and create a new bot:

```
/newbot
Bot name: IMG TO PDF Bot
Bot username: your_bot_username
```

### 2. Set Webhook

Replace `YOUR_TOKEN` and `your-domain.com` with your actual values:

```
https://api.telegram.org/botYOUR_TOKEN/setWebhook?url=https://your-domain.com/api/telegram/webhook
```

### 3. Test the Bot

Send `/start` to your bot to begin using it.

## 📱 Usage Guide

### Basic Workflow

1. **Start the bot** - Send `/start` command
2. **Upload images** - Send one or more images to the bot
3. **Generate PDF** - Click the "Ready" button
4. **Receive PDF** - Bot sends the generated PDF file
5. **Auto cleanup** - Files are automatically deleted

### Available Commands

- `/start` - Welcome message and main menu
- **About Bot** - Bot information and features
- **Usage Guide** - Step-by-step instructions
- **Language** - Change bot language
- **New Image** - Upload additional images

### Supported Languages

- 🇺🇸 **English** - Default language
- 🇷🇺 **Русский** - Russian language
- 🇺🇿 **O'zbekcha** - Uzbek language

## 🏗️ Project Structure

```
laravel/
├── app/Http/Controllers/
│   └── TelegramBotController.php    # Main bot logic
├── resources/views/
│   ├── home.blade.php               # Multi-language home page
│   └── pdf/
│       └── images.blade.php         # PDF template
├── routes/
│   └── web.php                      # Webhook routes
├── bootstrap/
│   └── app.php                      # Middleware configuration
└── README.md                         # This file
```

## 🔧 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/telegram/webhook` | Telegram webhook endpoint |
| `GET` | `/` | Multi-language home page |

## 🛠️ Technical Details

### Core Packages

- **Laravel Framework** - Web application framework
- **DomPDF** - HTML to PDF conversion
- **Telegram Bot API** - Bot functionality

### File Management

- **Images**: `storage/app/public/images/{chat_id}/`
- **PDFs**: `storage/app/public/pdfs/{chat_id}/`
- **User Settings**: `storage/app/public/users/{chat_id}/`

### Security Features

- HTTPS requirement for webhooks
- CSRF protection (webhook excluded)
- Secure file handling
- Automatic cleanup

## 📊 Logging & Monitoring

All bot activities are logged to `storage/logs/laravel.log`:

- Webhook requests and responses
- Image uploads and processing
- PDF generation status
- Error tracking and debugging
- User interaction logs

## 🐛 Troubleshooting

### Common Issues

| Problem | Solution |
|---------|----------|
| Bot not responding | Check webhook URL and HTTPS |
| PDF not generating | Verify DomPDF installation |
| Files not saving | Check storage permissions |
| Language not changing | Clear browser cache |

### Debug Steps

1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Verify webhook status via Telegram API
3. Test storage permissions and links
4. Confirm environment variables

## 🌟 Features in Detail

### Multi-language Support

The bot automatically detects and remembers user language preferences, providing a localized experience for users worldwide.

### Smart File Handling

- Automatic image optimization
- Efficient PDF generation
- Secure file storage
- Intelligent cleanup system

### User Experience

- Intuitive inline keyboards
- Real-time status updates
- Error handling with helpful messages
- Responsive design

## 📈 Performance

- **Fast Processing** - Optimized image handling
- **Memory Efficient** - Streamlined PDF generation
- **Scalable** - Handles multiple users simultaneously
- **Reliable** - Robust error handling and recovery

## 🤝 Contributing

We welcome contributions! Please feel free to submit issues and pull requests.

### Development Setup

```bash
# Clone and setup
git clone <repository-url>
cd img-to-pdf/laravel
composer install

# Development server
php artisan serve

# Testing
php artisan test
```

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

```
MIT License

Copyright (c) 2025 Azizbek Hakimov

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

## 👨‍💻 Author

<div align="center">

**Azizbek Hakimov**

[![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/azizbek-web-dev)
[![Telegram](https://img.shields.io/badge/Telegram-2CA5E0?style=for-the-badge&logo=telegram&logoColor=white)](https://t.me/Aziz_codes)
[![Email](https://img.shields.io/badge/Email-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:azizxakimov45@gmail.com)

</div>

---

<div align="center">

**If this project helps you, please give it a ⭐ star!**

Made with ❤️ by [Azizbek Hakimov](https://github.com/azizbek-web-dev)

</div>
