<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class TelegramBotController extends Controller
{
    public function webhook(Request $request)
    {
        try {
            $update = $request->all();
            
            // Barcha update ni log ga yozamiz
            Log::info('Telegram Webhook received', [
                'update' => $update,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            if (isset($update['message'])) {
                $message = $update['message'];
                $chatId = $message['chat']['id'];
                $text = $message['text'] ?? '';
                $from = $message['from'] ?? [];
                
                // Foydalanuvchi ma'lumotlarini log ga yozamiz
                Log::info('Message received', [
                    'chat_id' => $chatId,
                    'text' => $text,
                    'from_user' => $from,
                    'message_type' => isset($message['photo']) ? 'photo' : 'text'
                ]);
                
                if ($text === 'Tayyor') {
                    Log::info('Generating PDF for user', ['chat_id' => $chatId]);
                    $this->generatePdfFromImages($chatId);
                    return response()->json(['status' => 'success']);
                }
                
                if (isset($message['photo'])) {
                    Log::info('Photo received from user', ['chat_id' => $chatId]);
                    $this->saveImage($message['photo'], $chatId);
                    return response()->json(['status' => 'success']);
                }
                
                // Start yoki boshqa xabar
                if ($text === '/start' || $text === 'start') {
                    $this->sendWelcomeMessage($chatId);
                    Log::info('Welcome message sent', ['chat_id' => $chatId]);
                } elseif ($text === '📖 Bot haqida') {
                    $this->sendAboutMessage($chatId);
                    Log::info('About message sent', ['chat_id' => $chatId]);
                } elseif ($text === '📋 Ishlatish tartibi') {
                    $this->sendUsageMessage($chatId);
                    Log::info('Usage message sent', ['chat_id' => $chatId]);
                } elseif ($text === '🔄 Yangi rasm') {
                    $this->sendNewImageMessage($chatId);
                    Log::info('New image message sent', ['chat_id' => $chatId]);
                } else {
                    $this->sendDefaultMessage($chatId);
                    Log::info('Default message sent', ['chat_id' => $chatId]);
                }
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Telegram Webhook Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    private function sendWelcomeMessage($chatId)
    {
        $message = "🤖 *IMG TO PDF Bot* ga xush kelibsiz!\n\n";
        $message .= "📸 Rasm yuklang va PDF yarating\n";
        $message .= "💡 Quyidagi tugmalardan foydalaning:";
        
        $keyboard = [
            ['📖 Bot haqida', '📋 Ishlatish tartibi'],
            ['🔄 Yangi rasm']
        ];
        
        $this->sendMessageWithKeyboard($chatId, $message, $keyboard);
    }
    
    private function sendAboutMessage($chatId)
    {
        $message = "📖 *Bot haqida*\n\n";
        $message .= "🤖 **IMG TO PDF Bot**\n";
        $message .= "📱 Telegram orqali rasmni PDF ga o'tkazish\n";
        $message .= "⚡ Tez va qulay ishlash\n";
        $message .= "🔒 Xavfsiz va ishonchli\n";
        $message .= "💾 Avtomatik fayllarni tozalash\n\n";
        $message .= "🛠️ Laravel + DomPDF yordamida yaratildi";
        
        $keyboard = [
            ['📋 Ishlatish tartibi', '🔄 Yangi rasm'],
            ['🏠 Bosh sahifa']
        ];
        
        $this->sendMessageWithKeyboard($chatId, $message, $keyboard);
    }
    
    private function sendUsageMessage($chatId)
    {
        $message = "📋 *Ishlatish tartibi*\n\n";
        $message .= "1️⃣ **Rasm yuklang** - Botga rasm yuboring\n";
        $message .= "2️⃣ **Tayyor tugmasini bosing** - PDF yaratish uchun\n";
        $message .= "3️⃣ **PDF oling** - Bot sizga PDF fayl yuboradi\n";
        $message .= "4️⃣ **Avtomatik tozalash** - Fayllar o'chiriladi\n\n";
        $message .= "💡 Bir necha rasm yuklab, hammasini bir PDF da olishingiz mumkin!";
        
        $keyboard = [
            ['🔄 Yangi rasm', '📖 Bot haqida'],
            ['🏠 Bosh sahifa']
        ];
        
        $this->sendMessageWithKeyboard($chatId, $message, $keyboard);
    }
    
    private function sendNewImageMessage($chatId)
    {
        $message = "🔄 **Yangi rasm yuklash**\n\n";
        $message .= "📸 Endi botga rasm yuboring\n";
        $message .= "💡 Bir necha rasm yuklashingiz mumkin\n";
        $message .= "✅ Rasm yuklangandan keyin 'Tayyor' tugmasi paydo bo'ladi";
        
        $keyboard = [
            ['📖 Bot haqida', '📋 Ishlatish tartibi']
        ];
        
        $this->sendMessageWithKeyboard($chatId, $message, $keyboard);
    }
    
    private function sendDefaultMessage($chatId)
    {
        $message = "💡 Quyidagi tugmalardan foydalaning yoki rasm yuklang:";
        
        $keyboard = [
            ['📖 Bot haqida', '📋 Ishlatish tartibi'],
            ['🔄 Yangi rasm']
        ];
        
        $this->sendMessageWithKeyboard($chatId, $message, $keyboard);
    }
    
    private function saveImage($photos, $chatId)
    {
        try {
            $photo = end($photos);
            $fileId = $photo['file_id'];
            
            Log::info('Processing image', [
                'chat_id' => $chatId,
                'file_id' => $fileId,
                'photo_sizes' => count($photos)
            ]);
            
            $token = env('TELEGRAM_BOT_TOKEN');
            $fileUrl = "https://api.telegram.org/bot{$token}/getFile?file_id={$fileId}";
            $response = file_get_contents($fileUrl);
            $fileData = json_decode($response, true);
            
            if ($fileData['ok']) {
                $filePath = $fileData['result']['file_path'];
                $downloadUrl = "https://api.telegram.org/file/bot{$token}/{$filePath}";
                
                $imageContent = file_get_contents($downloadUrl);
                $fileName = 'images/' . $chatId . '/' . Str::random(10) . '.jpg';
                
                Storage::disk('public')->put($fileName, $imageContent);
                
                Log::info('Image saved successfully', [
                    'chat_id' => $chatId,
                    'file_name' => $fileName,
                    'file_size' => strlen($imageContent)
                ]);
                
                // Rasm yuklangandan keyin tugmalar bilan xabar yuboramiz
                $message = "✅ *Rasm saqlandi!*\n\n";
                $message .= "📸 Rasm soni: " . count(Storage::disk('public')->files('images/' . $chatId)) . "\n";
                $message .= "💡 Endi 'Tayyor' tugmasini bosib PDF yarating yoki yana rasm yuklang";
                
                $keyboard = [
                    ['✅ Tayyor', '📸 Yana rasm'],
                    ['📖 Bot haqida', '📋 Ishlatish tartibi']
                ];
                
                $this->sendMessageWithKeyboard($chatId, $message, $keyboard);
                
            } else {
                Log::error('Failed to get file info', [
                    'chat_id' => $chatId,
                    'response' => $fileData
                ]);
                $this->sendMessage($chatId, "❌ Rasm yuklashda xatolik yuz berdi");
            }
            
        } catch (\Exception $e) {
            Log::error('Image processing error', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            $this->sendMessage($chatId, "❌ Rasmni qayta ishlashda xatolik yuz berdi");
        }
    }
    
    private function generatePdfFromImages($chatId)
    {
        try {
            $imagePath = 'images/' . $chatId;
            $images = Storage::disk('public')->files($imagePath);
            
            Log::info('Generating PDF', [
                'chat_id' => $chatId,
                'image_count' => count($images),
                'image_paths' => $images
            ]);
            
            if (empty($images)) {
                Log::warning('No images found for PDF generation', ['chat_id' => $chatId]);
                $this->sendMessage($chatId, "❌ Hech qanday rasm topilmadi!");
                return;
            }
            
            // Rasm ma'lumotlarini tayyorlaymiz
            $imageData = [];
            foreach ($images as $image) {
                $fullPath = storage_path('app/public/' . $image);
                if (file_exists($fullPath)) {
                    $imageData[] = [
                        'path' => $image,
                        'base64' => base64_encode(file_get_contents($fullPath)),
                        'type' => pathinfo($fullPath, PATHINFO_EXTENSION)
                    ];
                }
            }
            
            $pdf = PDF::loadView('pdf.images', [
                'images' => $imageData,
                'chatId' => $chatId
            ]);
            
            $pdfPath = 'pdfs/' . $chatId . '/' . Str::random(10) . '.pdf';
            Storage::disk('public')->put($pdfPath, $pdf->output());
            
            Log::info('PDF generated successfully', [
                'chat_id' => $chatId,
                'pdf_path' => $pdfPath,
                'pdf_size' => Storage::disk('public')->size($pdfPath)
            ]);
            
            $this->sendDocument($chatId, $pdfPath);
            
            // Fayllarni tozalash
            Storage::disk('public')->deleteDirectory($imagePath);
            Storage::disk('public')->deleteDirectory('pdfs/' . $chatId);
            
            Log::info('Files cleaned up', ['chat_id' => $chatId]);
            
            // PDF yaratilgandan keyin yangi tugmalar
            $message = "🎉 *PDF tayyor!*\n\n";
            $message .= "📄 PDF fayl yuborildi\n";
            $message .= "🧹 Fayllar tozalandi\n\n";
            $message .= "🔄 Yangi PDF yaratish uchun rasm yuklang";
            
            $keyboard = [
                ['🔄 Yangi rasm', '📖 Bot haqida'],
                ['📋 Ishlatish tartibi']
            ];
            
            $this->sendMessageWithKeyboard($chatId, $message, $keyboard);
            
        } catch (\Exception $e) {
            Log::error('PDF generation error', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            $this->sendMessage($chatId, "❌ PDF yaratishda xatolik yuz berdi");
        }
    }
    
    private function sendMessageWithKeyboard($chatId, $text, $keyboard)
    {
        try {
            $token = env('TELEGRAM_BOT_TOKEN');
            $url = "https://api.telegram.org/bot{$token}/sendMessage";
            
            $data = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'keyboard' => $keyboard,
                    'resize_keyboard' => true,
                    'one_time_keyboard' => false
                ])
            ];
            
            $response = $this->makeTelegramRequest($url, $data);
            $responseData = json_decode($response, true);
            
            if ($responseData['ok']) {
                Log::info('Message with keyboard sent successfully', [
                    'chat_id' => $chatId,
                    'message_id' => $responseData['result']['message_id'] ?? null
                ]);
            } else {
                Log::error('Failed to send message with keyboard', [
                    'chat_id' => $chatId,
                    'response' => $responseData
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Send message with keyboard error', [
                'chat_id' => $chatId,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function sendMessage($chatId, $text)
    {
        try {
            $token = env('TELEGRAM_BOT_TOKEN');
            $url = "https://api.telegram.org/bot{$token}/sendMessage";
            
            $data = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown'
            ];
            
            $response = $this->makeTelegramRequest($url, $data);
            $responseData = json_decode($response, true);
            
            if ($responseData['ok']) {
                Log::info('Message sent successfully', [
                    'chat_id' => $chatId,
                    'message_id' => $responseData['result']['message_id'] ?? null
                ]);
            } else {
                Log::error('Failed to send message', [
                    'chat_id' => $chatId,
                    'response' => $responseData
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Send message error', [
                'chat_id' => $chatId,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function sendDocument($chatId, $filePath)
    {
        try {
            $token = env('TELEGRAM_BOT_TOKEN');
            $url = "https://api.telegram.org/bot{$token}/sendDocument";
            
            $fileUrl = Storage::disk('public')->url($filePath);
            
            $data = [
                'chat_id' => $chatId,
                'document' => $fileUrl
            ];
            
            $response = $this->makeTelegramRequest($url, $data);
            $responseData = json_decode($response, true);
            
            if ($responseData['ok']) {
                Log::info('Document sent successfully', [
                    'chat_id' => $chatId,
                    'file_path' => $filePath,
                    'message_id' => $responseData['result']['message_id'] ?? null
                ]);
            } else {
                Log::error('Failed to send document', [
                    'chat_id' => $chatId,
                    'file_path' => $filePath,
                    'response' => $responseData
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Send document error', [
                'chat_id' => $chatId,
                'file_path' => $filePath,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function makeTelegramRequest($url, $data)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                Log::error('CURL error', [
                    'url' => $url,
                    'error' => $error
                ]);
                throw new \Exception("CURL Error: " . $error);
            }
            
            if ($httpCode !== 200) {
                Log::error('HTTP error', [
                    'url' => $url,
                    'http_code' => $httpCode,
                    'response' => $response
                ]);
                throw new \Exception("HTTP Error: " . $httpCode);
            }
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error('Telegram request error', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
