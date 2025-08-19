# 🚀 Server Deployment

## 1. Fayllarni serverga yuklang
```bash
# Laravel loyihasini serverga yuklang
git clone <repository> /var/www/telegram-bot
cd /var/www/telegram-bot
```

## 2. Dependencelarni o'rnating
```bash
composer install --no-dev --optimize-autoloader
npm install --production
npm run build
```

## 3. .env faylini sozlang
```env
APP_NAME="Telegram Bot"
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

## 4. Laravel sozlamalari
```bash
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

## 5. Nginx konfiguratsiyasi
```nginx
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl;
    server_name your-domain.com;
    
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;
    
    root /var/www/telegram-bot/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 6. Webhook o'rnating
```
https://api.telegram.org/botYOUR_TOKEN/setWebhook?url=https://your-domain.com/api/telegram/webhook
```

## 7. Xavfsizlik
- HTTPS majburiy
- Bot tokenini hech kimga bermang
- Server firewall sozlang
- Regular backup qiling

## 8. Test qilish
Botga rasm yuklang va "Tayyor" deb yozing!
