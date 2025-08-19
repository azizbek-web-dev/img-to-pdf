# 🤖 IMG TO PDF Bot

A Telegram bot for converting images to PDF. Built with Laravel and DomPDF.

## ✨ Features

- 📸 **Image Upload** - Single or multiple images
- 📄 **PDF Generation** - Beautiful and organized PDFs
- 🎯 **Inline Keyboard** - Convenient buttons
- 🧹 **Auto Cleanup** - Files automatically deleted
- 📱 **Telegram Bot API** - Real-time operation
- 🔒 **Security** - HTTPS and webhook

## 🚀 Installation

### Requirements
- PHP 8.2+
- Laravel 12.0+
- Composer
- HTTPS domain (for webhook)
- Telegram Bot Token

### 1. Clone the project
```bash
git clone <repository-url>
cd img-to-pdf/laravel
```

### 2. Install dependencies
```bash
composer install
npm install
npm run build
```

### 3. Configure .env file
```env
APP_NAME="IMG TO PDF Bot"
APP_ENV=production
APP_KEY=base64:your_key_here
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

FILESYSTEM_DISK=public

# Telegram Bot
TELEGRAM_BOT_TOKEN=your_bot_token_here
```

### 4. Laravel setup
```bash
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### 5. Server configuration
```bash
# Configure Nginx or Apache
# Install HTTPS certificate
# Set webhook URL
```

## 🤖 Create Telegram Bot

### 1. Create bot with @BotFather
```
/newbot
Bot name: IMG TO PDF Bot
Bot username: img_to_pdf_bot
```

### 2. Set webhook
```
https://api.telegram.org/botYOUR_TOKEN/setWebhook?url=https://your-domain.com/api/telegram/webhook
```

## 📱 Bot Usage

### Main functions
1. **Upload Image** - Send image to bot
2. **Press Ready Button** - To create PDF
3. **Get PDF** - Bot sends you PDF file
4. **Auto Cleanup** - Files are deleted

### Buttons
- 📖 **About Bot** - Bot information
- 📋 **Usage Guide** - How to use
- 🔄 **New Image** - Upload new image
- ✅ **Ready** - Create PDF
- 🏠 **Home** - Main menu

## 🏗️ Project Structure

```
laravel/
├── app/Http/Controllers/
│   └── TelegramBotController.php    # Main bot logic
├── resources/views/
│   ├── home.blade.php               # Home page
│   └── pdf/
│       └── images.blade.php         # PDF template
├── routes/
│   └── web.php                      # Webhook route
├── bootstrap/
│   └── app.php                      # Middleware config
└── README.md                         # This file
```

## 🔧 API Endpoints

- `POST /api/telegram/webhook` - Telegram webhook endpoint
- `GET /` - Home page

## 🛠️ Technical Details

### Packages
- `barryvdh/laravel-dompdf` - PDF generation
- Laravel Storage - File management
- Telegram Bot API - Bot functionality

### File Structure
- Images: `storage/app/public/images/{chat_id}/`
- PDFs: `storage/app/public/pdfs/{chat_id}/`

### Security
- HTTPS required
- Protect bot token
- Keep webhook URL private

## 🧪 Testing

1. Send `/start` to bot
2. Upload image
3. Press "Ready" button
4. Get PDF file

## 📊 Logging and Monitoring

All bot activity is saved in `storage/logs/laravel.log`:
- Webhook received
- Image uploaded
- PDF generated
- Errors

## 🐛 Troubleshooting

### Bot not responding
- Check webhook is set correctly
- Verify HTTPS domain
- Check bot token

### PDF not generating
- Verify storage link created
- Check DomPDF package installed
- Verify image files saved

### Files not saving
- Check storage disk public linked
- Check file permissions

## 📞 Support

If you have issues:
1. Check Laravel log files
2. Check Telegram Bot API errors
3. Check server configuration

## 👨‍💻 Author

**Azizbek Hakimov** ([@azizbek-web-dev](https://github.com/azizbek-web-dev))

- 🌐 Telegram: [@Aziz_codes](https://t.me/Aziz_codes)
- 💻 GitHub: [azizbek-web-dev](https://github.com/azizbek-web-dev)
- 📧 Email: [azizxakimov45@gmail.com](mailto:azizxakimov45@gmail.com)

## 📄 License

This project is licensed under [MIT License](LICENSE).

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

## 🌟 Support the Project

If this project is helpful, don't forget to give it a ⭐ star!

---

**Note:** This bot is created for educational purposes. Check security settings before using in production.
