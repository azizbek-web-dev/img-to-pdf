<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMG TO PDF Bot</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255,255,255,0.1);
            padding: 40px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 3em;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .header p {
            font-size: 1.2em;
            opacity: 0.9;
            margin-top: 10px;
        }
        .language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }
        .lang-btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        .lang-btn:hover, .lang-btn.active {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            transform: translateY(-2px);
        }
        .status {
            background: rgba(255,255,255,0.2);
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            text-align: center;
            border: 2px solid rgba(255,255,255,0.3);
        }
        .status h3 {
            margin-top: 0;
            color: #4ade80;
            font-size: 1.5em;
        }
        .info {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            border-left: 4px solid #60a5fa;
        }
        .info h3 {
            color: #60a5fa;
            margin-top: 0;
            font-size: 1.3em;
        }
        .steps {
            list-style: none;
            padding: 0;
        }
        .steps li {
            background: rgba(255,255,255,0.1);
            margin: 10px 0;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #60a5fa;
            transition: all 0.3s ease;
        }
        .steps li:hover {
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }
        .webhook-url {
            background: rgba(0,0,0,0.3);
            padding: 15px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
            margin: 20px 0;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            opacity: 0.8;
            padding: 20px;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
        }
        .btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            margin: 10px 5px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .highlight {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 0.9em;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="language-switcher">
        <button class="lang-btn active" onclick="changeLanguage('en')">🇺🇸 EN</button>
        <button class="lang-btn" onclick="changeLanguage('ru')">🇷🇺 RU</button>
        <button class="lang-btn" onclick="changeLanguage('uz')">🇺🇿 UZ</button>
    </div>

    <div class="container">
        <!-- English Content -->
        <div id="content-en" class="content">
            <div class="header">
                <h1>IMG TO PDF Bot</h1>
                <p>Convert images to PDF with ease</p>
            </div>

            <div class="status">
                <h3>Bot is Running!</h3>
                <p>Webhook endpoint: <code>/api/telegram/webhook</code></p>
            </div>

            <div class="info">
                <h3>How it Works:</h3>
                <ol class="steps">
                    <li>Upload image to the bot</li>
                    <li>Bot saves the image</li>
                    <li>Press "Ready" button</li>
                    <li>PDF is generated and sent</li>
                    <li>Files are automatically cleaned</li>
                </ol>
            </div>

            <div class="info">
                <h3>Server Configuration:</h3>
                <p><strong>Webhook URL:</strong></p>
                <div class="webhook-url">
                    https://{{ $_SERVER['HTTP_HOST'] ?? 'your-domain.com' }}/api/telegram/webhook
                </div>
                <p><strong>Bot Token:</strong> Set in .env file as TELEGRAM_BOT_TOKEN</p>
            </div>

            <div class="info">
                <h3>Required Files:</h3>
                <ul class="steps">
                    <li>TelegramBotController.php - Bot logic</li>
                    <li>images.blade.php - PDF template</li>
                    <li>web.php - Routes</li>
                    <li>.env - Bot token</li>
                </ul>
            </div>

            <div class="footer">
                <p>Built with <span class="highlight">Laravel</span> + <span class="highlight">DomPDF</span> + <span class="highlight">Telegram Bot API</span></p>
                <p>&copy; {{ date('Y') }} - Production Ready</p>
            </div>
        </div>

        <!-- Russian Content -->
        <div id="content-ru" class="content hidden">
            <div class="header">
                <h1>IMG TO PDF Bot</h1>
                <p>Конвертируйте изображения в PDF легко</p>
            </div>

            <div class="status">
                <h3>Бот работает!</h3>
                <p>Webhook endpoint: <code>/api/telegram/webhook</code></p>
            </div>

            <div class="info">
                <h3>Как это работает:</h3>
                <ol class="steps">
                    <li>Загрузите изображение боту</li>
                    <li>Бот сохраняет изображение</li>
                    <li>Нажмите кнопку "Готово"</li>
                    <li>PDF создается и отправляется</li>
                    <li>Файлы автоматически удаляются</li>
                </ol>
            </div>

            <div class="info">
                <h3>Конфигурация сервера:</h3>
                <p><strong>Webhook URL:</strong></p>
                <div class="webhook-url">
                    https://{{ $_SERVER['HTTP_HOST'] ?? 'your-domain.com' }}/api/telegram/webhook
                </div>
                <p><strong>Bot Token:</strong> Установите в .env файле как TELEGRAM_BOT_TOKEN</p>
            </div>

            <div class="info">
                <h3>Необходимые файлы:</h3>
                <ul class="steps">
                    <li>TelegramBotController.php - Логика бота</li>
                    <li>images.blade.php - PDF шаблон</li>
                    <li>web.php - Маршруты</li>
                    <li>.env - Токен бота</li>
                </ul>
            </div>

            <div class="footer">
                <p>Создано с <span class="highlight">Laravel</span> + <span class="highlight">DomPDF</span> + <span class="highlight">Telegram Bot API</span></p>
                <p>&copy; {{ date('Y') }} - Готово к продакшену</p>
            </div>
        </div>

        <!-- Uzbek Content -->
        <div id="content-uz" class="content hidden">
            <div class="header">
                <h1>IMG TO PDF Bot</h1>
                <p>Rasmlarni PDF ga osonlik bilan o'tkazing</p>
            </div>

            <div class="status">
                <h3>Bot ishlayapti!</h3>
                <p>Webhook endpoint: <code>/api/telegram/webhook</code></p>
            </div>

            <div class="info">
                <h3>Qanday ishlaydi:</h3>
                <ol class="steps">
                    <li>Botga rasm yuklang</li>
                    <li>Bot rasmni saqlaydi</li>
                    <li>"Tayyor" tugmasini bosing</li>
                    <li>PDF yaratiladi va yuboriladi</li>
                    <li>Fayllar avtomatik tozalanadi</li>
                </ol>
            </div>

            <div class="info">
                <h3>Server sozlamalari:</h3>
                <p><strong>Webhook URL:</strong></p>
                <div class="webhook-url">
                    https://{{ $_SERVER['HTTP_HOST'] ?? 'your-domain.com' }}/api/telegram/webhook
                </div>
                <p><strong>Bot Token:</strong> .env faylida TELEGRAM_BOT_TOKEN sifatida o'rnating</p>
            </div>

            <div class="info">
                <h3>Kerakli fayllar:</h3>
                <ul class="steps">
                    <li>TelegramBotController.php - Bot logikasi</li>
                    <li>images.blade.php - PDF shabloni</li>
                    <li>web.php - Yo'nalishlar</li>
                    <li>.env - Bot tokeni</li>
                </ul>
            </div>

            <div class="footer">
                <p><span class="highlight">Laravel</span> + <span class="highlight">DomPDF</span> + <span class="highlight">Telegram Bot API</span> yordamida yaratildi</p>
                <p>&copy; {{ date('Y') }} - Production tayyor</p>
            </div>
        </div>
    </div>

    <script>
        function changeLanguage(lang) {
            // Hide all content
            document.querySelectorAll('.content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show selected language content
            document.getElementById('content-' + lang).classList.remove('hidden');
            
            // Update active button
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Store language preference
            localStorage.setItem('preferred-language', lang);
        }
        
        // Load preferred language on page load
        document.addEventListener('DOMContentLoaded', function() {
            const preferredLang = localStorage.getItem('preferred-language') || 'en';
            changeLanguage(preferredLang);
        });
    </script>
</body>
</html>
