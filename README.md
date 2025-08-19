# 🤖 IMG TO PDF Bot

Telegram orqali rasmni PDF ga o'tkazish uchun yaratilgan bot. Laravel va DomPDF yordamida qurilgan.

## ✨ Xususiyatlar

- 📸 **Rasm yuklash** - Bir yoki bir necha rasm
- 📄 **PDF yaratish** - Chiroyli va tuzilgan PDF
- 🎯 **Inline Keyboard** - Qulay tugmalar bilan
- 🧹 **Avtomatik tozalash** - Fayllar avtomatik o'chiriladi
- 📱 **Telegram Bot API** - Real-time ishlash
- 🔒 **Xavfsizlik** - HTTPS va webhook

## 🚀 O'rnatish

### Talablar
- PHP 8.2+
- Laravel 12.0+
- Composer
- HTTPS domain (webhook uchun)
- Telegram Bot Token

### 1. Loyihani klonlash
```bash
git clone <repository-url>
cd img-to-pdf/laravel
```

### 2. Dependencelarni o'rnatish
```bash
composer install
npm install
npm run build
```

### 3. .env faylini sozlash
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

### 4. Laravel sozlamalari
```bash
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### 5. Server sozlamalari
```bash
# Nginx yoki Apache sozlang
# HTTPS sertifikat o'rnating
# Webhook URL ni sozlang
```

## 🤖 Telegram Bot yaratish

### 1. @BotFather da bot yarating
```
/newbot
Bot nomi: IMG TO PDF Bot
Bot username: img_to_pdf_bot
```

### 2. Webhook o'rnating
```
https://api.telegram.org/botYOUR_TOKEN/setWebhook?url=https://your-domain.com/api/telegram/webhook
```

## 📱 Bot ishlatish

### Asosiy funksiyalar
1. **Rasm yuklang** - Botga rasm yuboring
2. **Tayyor tugmasini bosing** - PDF yaratish uchun
3. **PDF oling** - Bot sizga PDF fayl yuboradi
4. **Avtomatik tozalash** - Fayllar o'chiriladi

### Tugmalar
- 📖 **Bot haqida** - Bot haqida ma'lumot
- 📋 **Ishlatish tartibi** - Qanday ishlatish
- 🔄 **Yangi rasm** - Yangi rasm yuklash
- ✅ **Tayyor** - PDF yaratish
- 🏠 **Bosh sahifa** - Asosiy menyu

## 🏗️ Loyiha tuzilishi

```
laravel/
├── app/Http/Controllers/
│   └── TelegramBotController.php    # Bot asosiy logikasi
├── resources/views/
│   └── pdf/
│       └── images.blade.php         # PDF template
├── routes/
│   └── web.php                      # Webhook route
├── bootstrap/
│   └── app.php                      # Middleware sozlamalari
└── README.md                         # Ushbu fayl
```

## 🔧 API Endpointlar

- `POST /api/telegram/webhook` - Telegram webhook endpoint

## 🛠️ Texnik ma'lumotlar

### Paketlar
- `barryvdh/laravel-dompdf` - PDF yaratish
- Laravel Storage - Fayllarni boshqarish
- Telegram Bot API - Bot funksiyalari

### Fayl tuzilishi
- Rasm: `storage/app/public/images/{chat_id}/`
- PDF: `storage/app/public/pdfs/{chat_id}/`

### Xavfsizlik
- HTTPS majburiy
- Bot tokenini himoya qiling
- Webhook URL ni faqat o'zingiz bilgan holda saqlang

## 🧪 Test qilish

1. Botga `/start` yuboring
2. Rasm yuklang
3. "Tayyor" tugmasini bosing
4. PDF fayl oling

## 📊 Log va monitoring

Barcha bot faoliyati `storage/logs/laravel.log` faylida saqlanadi:
- Webhook qabul qilindi
- Rasm yuklandi
- PDF yaratildi
- Xatoliklar

## 🐛 Muammolarni hal qilish

### Bot javob bermaydi
- Webhook to'g'ri o'rnatilganini tekshiring
- HTTPS domain ishlatayotganingizni tekshiring
- Bot token to'g'ri ekanligini tekshiring

### PDF yaratilmaydi
- Storage link yaratilganini tekshiring
- DomPDF paketi o'rnatilganini tekshiring
- Rasm fayllar saqlanganini tekshiring

### Fayllar saqlanmaydi
- Storage disk public ga ulanganini tekshiring
- Fayl huquqlarini tekshiring

## 📞 Yordam

Muammolar bo'lsa:
1. Laravel log fayllarini tekshiring
2. Telegram Bot API xatoliklarini tekshiring
3. Server sozlamalarini tekshiring

## 👨‍💻 Muallif

**Azizbek Hakimov** ([@azizbek-web-dev](https://github.com/azizbek-web-dev))

- 🌐 Telegram: [@azizbek_web_dev](https://t.me/azizbek_web_dev)
- 💻 GitHub: [azizbek-web-dev](https://github.com/azizbek-web-dev)
- 📧 Email: azizbek.web.dev@gmail.com

## 📄 Litsenziya

Bu loyiha [MIT License](LICENSE) ostida tarqatiladi.

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

## 🌟 Yordam berish

Agar bu loyiha foydali bo'lsa, ⭐ yulduzcha qo'yishni unutmang!

---

**Eslatma:** Bu bot faqat o'quv maqsadida yaratilgan. Production da ishlatishdan oldin xavfsizlik sozlamalarini tekshiring.
