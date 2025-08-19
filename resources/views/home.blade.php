<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Bot - Rasm to PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
        }
        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        .status {
            background: rgba(255,255,255,0.2);
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .status h3 {
            margin-top: 0;
            color: #4ade80;
        }
        .info {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
        }
        .info h3 {
            color: #60a5fa;
            margin-top: 0;
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
        }
        .webhook-url {
            background: rgba(0,0,0,0.3);
            padding: 15px;
            border-radius: 10px;
            font-family: monospace;
            word-break: break-all;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🤖 Telegram Bot</h1>
            <p>Rasm to PDF konverter</p>
        </div>

        <div class="status">
            <h3>✅ Bot ishlaydi!</h3>
            <p>Webhook endpoint: <code>/api/telegram/webhook</code></p>
        </div>

        <div class="info">
            <h3>📱 Bot ishlash tartibi:</h3>
            <ol class="steps">
                <li>Telegram da botga rasm yuklang</li>
                <li>Bot rasmni saqlaydi</li>
                <li>"Tayyor" deb yozing</li>
                <li>PDF fayl yaratiladi va yuboriladi</li>
                <li>Fayllar avtomatik tozalanadi</li>
            </ol>
        </div>

        <div class="info">
            <h3>🔧 Server sozlamalari:</h3>
            <p><strong>Webhook URL:</strong></p>
            <div class="webhook-url">
                https://{{ $_SERVER['HTTP_HOST'] ?? 'your-domain.com' }}/api/telegram/webhook
            </div>
            <p><strong>Bot token:</strong> .env faylida TELEGRAM_BOT_TOKEN</p>
        </div>

        <div class="info">
            <h3>📋 Kerakli fayllar:</h3>
            <ul class="steps">
                <li>TelegramBotController.php - Bot logikasi</li>
                <li>images.blade.php - PDF template</li>
                <li>web.php - Route lar</li>
                <li>.env - Bot token</li>
            </ul>
        </div>

        <div class="footer">
            <p>Laravel + DomPDF + Telegram Bot API</p>
            <p>© {{ date('Y') }} - Production ready</p>
        </div>
    </div>
</body>
</html>
