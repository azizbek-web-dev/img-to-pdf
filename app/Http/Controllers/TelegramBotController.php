<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class TelegramBotController extends Controller
{
    private $languages = ['en', 'ru', 'uz'];
    private $defaultLanguage = 'uz';
    
    // Language messages
    private $messages = [
        'en' => [
            'welcome' => "🤖 *IMG TO PDF Bot* welcomes you!\n\n📸 Upload an image and generate a PDF\n💡 Use the following buttons:",
            'about' => "📖 *About Bot*\n\n🤖 **IMG TO PDF Bot**\n📱 Converts an image to PDF via Telegram\n⚡ Fast and easy to use\n🔒 Secure and reliable\n💾 Auto-deletes files\n\n🛠️ Created with Laravel + DomPDF",
            'usage' => "📋 *Usage Instructions*\n\n1️⃣ **Upload Image** - Send an image to the bot\n2️⃣ **Click the 'Ready' button** - To generate the PDF\n3️⃣ **Get the PDF** - The bot will send you the PDF file\n4️⃣ **Auto-delete** - Files are deleted\n\n💡 You can upload multiple images, and they will be combined into one PDF!",
            'new_image' => "🔄 **Upload New Image**\n\n📸 Now send an image to the bot\n💡 You can upload multiple images\n✅ After uploading an image, the 'Ready' button will appear",
            'default' => "💡 Use the following buttons or upload an image:",
            'image_saved' => "✅ *Image saved!*\n\n📸 Image count: {count}\n💡 Now click the 'Ready' button to generate the PDF or upload another image",
            'pdf_ready' => "🎉 *PDF Ready!*\n\n📄 PDF file sent\n🧹 Files cleaned\n\n🔄 To generate a new PDF, upload an image",
            'no_images' => "❌ No images found!",
            'upload_error' => "❌ Error uploading image",
            'processing_error' => "❌ Error processing image",
            'pdf_error' => "❌ Error generating PDF",
            'language_changed' => "🌍 Language changed to: {language}",
            'select_language' => "🌍 *Select Language*\n\nChoose your preferred language:",
            'current_language' => "🌍 Current language: {language}"
        ],
        'ru' => [
            'welcome' => "🤖 *IMG TO PDF Bot* приветствует вас!\n\n📸 Загрузите изображение и создайте PDF\n💡 Используйте следующие кнопки:",
            'about' => "📖 *О боте*\n\n🤖 **IMG TO PDF Bot**\n📱 Конвертирует изображения в PDF через Telegram\n⚡ Быстро и легко в использовании\n🔒 Безопасно и надежно\n💾 Автоматически удаляет файлы\n\n🛠️ Создан с помощью Laravel + DomPDF",
            'usage' => "📋 *Инструкция по использованию*\n\n1️⃣ **Загрузите изображение** - Отправьте изображение боту\n2️⃣ **Нажмите кнопку 'Готово'** - Для создания PDF\n3️⃣ **Получите PDF** - Бот отправит вам PDF файл\n4️⃣ **Автоудаление** - Файлы удаляются\n\n💡 Вы можете загрузить несколько изображений, и они будут объединены в один PDF!",
            'new_image' => "🔄 **Загрузить новое изображение**\n\n📸 Теперь отправьте изображение боту\n💡 Вы можете загрузить несколько изображений\n✅ После загрузки изображения появится кнопка 'Готово'",
            'default' => "💡 Используйте следующие кнопки или загрузите изображение:",
            'image_saved' => "✅ *Изображение сохранено!*\n\n📸 Количество изображений: {count}\n💡 Теперь нажмите кнопку 'Готово' для создания PDF или загрузите другое изображение",
            'pdf_ready' => "🎉 *PDF готов!*\n\n📄 PDF файл отправлен\n🧹 Файлы очищены\n\n🔄 Для создания нового PDF загрузите изображение",
            'no_images' => "❌ Изображения не найдены!",
            'upload_error' => "❌ Ошибка загрузки изображения",
            'processing_error' => "❌ Ошибка обработки изображения",
            'pdf_error' => "❌ Ошибка создания PDF",
            'language_changed' => "🌍 Язык изменен на: {language}",
            'select_language' => "🌍 *Выберите язык*\n\nВыберите предпочитаемый язык:",
            'current_language' => "🌍 Текущий язык: {language}"
        ],
        'uz' => [
            'welcome' => "🤖 *IMG TO PDF Bot* ga xush kelibsiz!\n\n📸 Rasm yuklang va PDF yarating\n💡 Quyidagi tugmalardan foydalaning:",
            'about' => "📖 *Bot haqida*\n\n🤖 **IMG TO PDF Bot**\n📱 Telegram orqali rasmni PDF ga o'tkazish\n⚡ Tez va qulay ishlash\n🔒 Xavfsiz va ishonchli\n💾 Avtomatik fayllarni tozalash\n\n🛠️ Laravel + DomPDF yordamida yaratildi",
            'usage' => "📋 *Ishlatish tartibi*\n\n1️⃣ **Rasm yuklang** - Botga rasm yuboring\n2️⃣ **'Tayyor' tugmasini bosing** - PDF yaratish uchun\n3️⃣ **PDF oling** - Bot sizga PDF fayl yuboradi\n4️⃣ **Avtomatik tozalash** - Fayllar o'chiriladi\n\n💡 Bir necha rasm yuklab, hammasini bir PDF da olishingiz mumkin!",
            'new_image' => "🔄 **Yangi rasm yuklash**\n\n📸 Endi botga rasm yuboring\n💡 Bir necha rasm yuklashingiz mumkin\n✅ Rasm yuklangandan keyin 'Tayyor' tugmasi paydo bo'ladi",
            'default' => "💡 Quyidagi tugmalardan foydalaning yoki rasm yuklang:",
            'image_saved' => "✅ *Rasm saqlandi!*\n\n📸 Rasm soni: {count}\n💡 Endi 'Tayyor' tugmasini bosib PDF yarating yoki yana rasm yuklang",
            'pdf_ready' => "🎉 *PDF tayyor!*\n\n📄 PDF fayl yuborildi\n🧹 Fayllar tozalandi\n\n🔄 Yangi PDF yaratish uchun rasm yuklang",
            'no_images' => "❌ Hech qanday rasm topilmadi!",
            'upload_error' => "❌ Rasm yuklashda xatolik yuz berdi",
            'processing_error' => "❌ Rasmni qayta ishlashda xatolik yuz berdi",
            'pdf_error' => "❌ PDF yaratishda xatolik yuz berdi",
            'language_changed' => "🌍 Til o'zgartirildi: {language}",
            'select_language' => "🌍 *Tilni tanlang*\n\nO'zingizga yoqan tilni tanlang:",
            'current_language' => "🌍 Joriy til: {language}"
        ]
    ];
    
    // Button texts
    private $buttons = [
        'en' => [
            'about_bot' => '📖 About Bot',
            'usage_instructions' => '📋 Usage Instructions',
            'new_image' => '🔄 New Image',
            'ready' => '✅ Ready',
            'upload_another' => '📸 Upload Another Image',
            'home' => '🏠 Home',
            'select_language' => '🌍 Language',
            'english' => '🇺🇸 English',
            'russian' => '🇷🇺 Русский',
            'uzbek' => '🇺🇿 O\'zbekcha'
        ],
        'ru' => [
            'about_bot' => '📖 О боте',
            'usage_instructions' => '📋 Инструкция',
            'new_image' => '🔄 Новое изображение',
            'ready' => '✅ Готово',
            'upload_another' => '📸 Загрузить еще',
            'home' => '🏠 Главная',
            'select_language' => '🌍 Язык',
            'english' => '🇺🇸 English',
            'russian' => '🇷🇺 Русский',
            'uzbek' => '🇺🇿 O\'zbekcha'
        ],
        'uz' => [
            'about_bot' => '📖 Bot haqida',
            'usage_instructions' => '📋 Ishlatish tartibi',
            'new_image' => '🔄 Yangi rasm',
            'ready' => '✅ Tayyor',
            'upload_another' => '📸 Yana rasm',
            'home' => '🏠 Bosh sahifa',
            'select_language' => '🌍 Til',
            'english' => '🇺🇸 English',
            'russian' => '🇷🇺 Русский',
            'uzbek' => '🇺🇿 O\'zbekcha'
        ]
    ];
    
    private function getUserLanguage($chatId)
    {
        // Get user language from storage or default to Uzbek
        $languageFile = "users/{$chatId}/language.txt";
        if (Storage::disk('public')->exists($languageFile)) {
            $language = trim(Storage::disk('public')->get($languageFile));
            if (in_array($language, $this->languages)) {
                return $language;
            }
        }
        return $this->defaultLanguage;
    }
    
    private function setUserLanguage($chatId, $language)
    {
        if (in_array($language, $this->languages)) {
            $languageFile = "users/{$chatId}/language.txt";
            Storage::disk('public')->put($languageFile, $language);
            return true;
        }
        return false;
    }
    
    private function getMessage($chatId, $key, $replacements = [])
    {
        $language = $this->getUserLanguage($chatId);
        $message = $this->messages[$language][$key] ?? $this->messages[$this->defaultLanguage][$key];
        
        foreach ($replacements as $placeholder => $value) {
            $message = str_replace('{' . $placeholder . '}', $value, $message);
        }
        
        return $message;
    }
    
    private function getButtonText($chatId, $key)
    {
        $language = $this->getUserLanguage($chatId);
        return $this->buttons[$language][$key] ?? $this->buttons[$this->defaultLanguage][$key];
    }
    
    private function getLanguageName($language)
    {
        $names = [
            'en' => 'English',
            'ru' => 'Русский',
            'uz' => 'O\'zbekcha'
        ];
        return $names[$language] ?? $language;
    }

    public function webhook(Request $request)
    {
        try {
            $update = $request->all();
            
            // Log all updates
            Log::info('Telegram Webhook received', [
                'update' => $update,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            // Check for callback query (inline keyboard buttons)
            if (isset($update['callback_query'])) {
                $callbackQuery = $update['callback_query'];
                $chatId = $callbackQuery['message']['chat']['id'];
                $data = $callbackQuery['data'];
                
                Log::info('Callback query received', [
                    'chat_id' => $chatId,
                    'data' => $data
                ]);
                
                if ($data === 'tayyor' || $data === 'ready') {
                    Log::info('Ready button clicked via callback', ['chat_id' => $chatId]);
                    $this->generatePdfFromImages($chatId);
                    return response()->json(['status' => 'success']);
                } elseif ($data === 'yangi_rasm' || $data === 'new_image') {
                    $this->sendNewImageMessage($chatId);
                } elseif ($data === 'bot_haqida' || $data === 'about_bot') {
                    $this->sendAboutMessage($chatId);
                } elseif ($data === 'ishlatish_tartibi' || $data === 'usage_instructions') {
                    $this->sendUsageMessage($chatId);
                } elseif ($data === 'bosh_sahifa' || $data === 'home') {
                    $this->sendWelcomeMessage($chatId);
                } elseif ($data === 'yana_rasm' || $data === 'upload_another') {
                    $this->sendNewImageMessage($chatId);
                } elseif (in_array($data, ['lang_en', 'lang_ru', 'lang_uz'])) {
                    $this->handleLanguageChange($chatId, $data);
                } elseif ($data === 'select_language') {
                    $this->sendLanguageSelection($chatId);
                }
                
                return response()->json(['status' => 'success']);
            }
            
            if (isset($update['message'])) {
                $message = $update['message'];
                $chatId = $message['chat']['id'];
                $text = $message['text'] ?? '';
                $from = $message['from'] ?? [];
                
                // Log user information
                Log::info('Message received', [
                    'chat_id' => $chatId,
                    'text' => $text,
                    'from_user' => $from,
                    'message_type' => isset($message['photo']) ? 'photo' : 'text'
                ]);
                
                if ($text === 'Tayyor' || $text === '✅ Tayyor' || $text === 'Ready' || $text === '✅ Ready' || $text === 'Готово' || $text === '✅ Готово') {
                    Log::info('Generating PDF for user', ['chat_id' => $chatId]);
                    $this->generatePdfFromImages($chatId);
                    return response()->json(['status' => 'success']);
                }
                
                if (isset($message['photo'])) {
                    Log::info('Photo received from user', ['chat_id' => $chatId]);
                    $this->saveImage($message['photo'], $chatId);
                    return response()->json(['status' => 'success']);
                }
                
                // Handle text commands
                if ($text === '/start' || $text === 'start') {
                    $this->sendWelcomeMessage($chatId);
                    Log::info('Welcome message sent', ['chat_id' => $chatId]);
                } elseif (in_array($text, ['📖 Bot haqida', '📖 About Bot', '📖 О боте'])) {
                    $this->sendAboutMessage($chatId);
                    Log::info('About message sent', ['chat_id' => $chatId]);
                } elseif (in_array($text, ['📋 Ishlatish tartibi', '📋 Usage Instructions', '📋 Инструкция'])) {
                    $this->sendUsageMessage($chatId);
                    Log::info('Usage message sent', ['chat_id' => $chatId]);
                } elseif (in_array($text, ['🔄 Yangi rasm', '🔄 New Image', '🔄 Новое изображение'])) {
                    $this->sendNewImageMessage($chatId);
                    Log::info('New image message sent', ['chat_id' => $chatId]);
                } elseif (in_array($text, ['🌍 Language', '🌍 Til', '🌍 Язык'])) {
                    $this->sendLanguageSelection($chatId);
                    Log::info('Language selection sent', ['chat_id' => $chatId]);
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
    
    private function handleLanguageChange($chatId, $data)
    {
        $languageMap = [
            'lang_en' => 'en',
            'lang_ru' => 'ru',
            'lang_uz' => 'uz'
        ];
        
        $newLanguage = $languageMap[$data] ?? 'uz';
        if ($this->setUserLanguage($chatId, $newLanguage)) {
            $languageName = $this->getLanguageName($newLanguage);
            $message = $this->getMessage($chatId, 'language_changed', ['language' => $languageName]);
            $this->sendMessage($chatId, $message);
            
            // Send welcome message in new language
            $this->sendWelcomeMessage($chatId);
        }
    }
    
    private function sendLanguageSelection($chatId)
    {
        $message = $this->getMessage($chatId, 'select_language');
        
        $keyboard = [
            [
                ['text' => $this->getButtonText($chatId, 'english'), 'callback_data' => 'lang_en'],
                ['text' => $this->getButtonText($chatId, 'russian'), 'callback_data' => 'lang_ru']
            ],
            [
                ['text' => $this->getButtonText($chatId, 'uzbek'), 'callback_data' => 'lang_uz']
            ],
            [
                ['text' => $this->getButtonText($chatId, 'home'), 'callback_data' => 'home']
            ]
        ];
        
        $this->sendMessageWithInlineKeyboard($chatId, $message, $keyboard);
    }

    private function sendWelcomeMessage($chatId)
    {
        $message = $this->getMessage($chatId, 'welcome');
        
        $keyboard = [
            [
                ['text' => $this->getButtonText($chatId, 'about_bot'), 'callback_data' => 'about_bot'],
                ['text' => $this->getButtonText($chatId, 'usage_instructions'), 'callback_data' => 'usage_instructions']
            ],
            [
                ['text' => $this->getButtonText($chatId, 'new_image'), 'callback_data' => 'new_image']
            ],
            [
                ['text' => $this->getButtonText($chatId, 'select_language'), 'callback_data' => 'select_language']
            ]
        ];
        
        $this->sendMessageWithInlineKeyboard($chatId, $message, $keyboard);
    }

    private function sendAboutMessage($chatId)
    {
        $message = $this->getMessage($chatId, 'about');
        
        $keyboard = [
            [
                ['text' => $this->getButtonText($chatId, 'usage_instructions'), 'callback_data' => 'usage_instructions'],
                ['text' => $this->getButtonText($chatId, 'new_image'), 'callback_data' => 'new_image']
            ],
            [
                ['text' => $this->getButtonText($chatId, 'home'), 'callback_data' => 'home']
            ]
        ];
        
        $this->sendMessageWithInlineKeyboard($chatId, $message, $keyboard);
    }

    private function sendUsageMessage($chatId)
    {
        $message = $this->getMessage($chatId, 'usage');
        
        $keyboard = [
            [
                ['text' => $this->getButtonText($chatId, 'new_image'), 'callback_data' => 'new_image'],
                ['text' => $this->getButtonText($chatId, 'about_bot'), 'callback_data' => 'about_bot']
            ],
            [
                ['text' => $this->getButtonText($chatId, 'home'), 'callback_data' => 'home']
            ]
        ];
        
        $this->sendMessageWithInlineKeyboard($chatId, $message, $keyboard);
    }

    private function sendNewImageMessage($chatId)
    {
        $message = $this->getMessage($chatId, 'new_image');
        
        $keyboard = [
            [
                ['text' => $this->getButtonText($chatId, 'about_bot'), 'callback_data' => 'about_bot'],
                ['text' => $this->getButtonText($chatId, 'usage_instructions'), 'callback_data' => 'usage_instructions']
            ]
        ];
        
        $this->sendMessageWithInlineKeyboard($chatId, $message, $keyboard);
    }

    private function sendDefaultMessage($chatId)
    {
        $message = $this->getMessage($chatId, 'default');
        
        $keyboard = [
            [
                ['text' => $this->getButtonText($chatId, 'about_bot'), 'callback_data' => 'about_bot'],
                ['text' => $this->getButtonText($chatId, 'usage_instructions'), 'callback_data' => 'usage_instructions']
            ],
            [
                ['text' => $this->getButtonText($chatId, 'new_image'), 'callback_data' => 'new_image']
            ],
            [
                ['text' => $this->getButtonText($chatId, 'select_language'), 'callback_data' => 'select_language']
            ]
        ];
        
        $this->sendMessageWithInlineKeyboard($chatId, $message, $keyboard);
    }

    private function saveImage($photos, $chatId)
    {
        try {
            // Get the highest quality photo
            $photo = end($photos);
            $fileId = $photo['file_id'];
            
            // Get file info from Telegram
            $fileData = $this->makeTelegramRequest('getFile', ['file_id' => $fileId]);
            
            if (isset($fileData['ok']) && $fileData['ok']) {
                $filePath = $fileData['result']['file_path'];
                $fileUrl = "https://api.telegram.org/file/bot" . env('TELEGRAM_BOT_TOKEN') . "/" . $filePath;
                
                // Create directory for user
                $imagePath = "images/{$chatId}";
                Storage::disk('public')->makeDirectory($imagePath);
                
                // Download and save image
                $imageContent = file_get_contents($fileUrl);
                $fileName = uniqid() . '.jpg';
                $fullPath = "{$imagePath}/{$fileName}";
                
                Storage::disk('public')->put($fullPath, $imageContent);
                
                Log::info('Image saved successfully', [
                    'chat_id' => $chatId,
                    'file_name' => $fileName,
                    'file_size' => strlen($imageContent)
                ]);
                
                // Send message with buttons after image is uploaded
                $imageCount = count(Storage::disk('public')->files($imagePath));
                $message = $this->getMessage($chatId, 'image_saved', ['count' => $imageCount]);
                
                $keyboard = [
                    [
                        ['text' => $this->getButtonText($chatId, 'ready'), 'callback_data' => 'ready'],
                        ['text' => $this->getButtonText($chatId, 'upload_another'), 'callback_data' => 'upload_another']
                    ],
                    [
                        ['text' => $this->getButtonText($chatId, 'about_bot'), 'callback_data' => 'about_bot'],
                        ['text' => $this->getButtonText($chatId, 'usage_instructions'), 'callback_data' => 'usage_instructions']
                    ]
                ];
                
                $this->sendMessageWithInlineKeyboard($chatId, $message, $keyboard);
                
            } else {
                Log::error('Failed to get file info from Telegram', [
                    'chat_id' => $chatId,
                    'response' => $fileData
                ]);
                $this->sendMessage($chatId, $this->getMessage($chatId, 'upload_error'));
            }
            
        } catch (\Exception $e) {
            Log::error('Error saving image', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->sendMessage($chatId, $this->getMessage($chatId, 'processing_error'));
        }
    }

    private function generatePdfFromImages($chatId)
    {
        try {
            $imagePath = "images/{$chatId}";
            $images = Storage::disk('public')->files($imagePath);
            
            if (empty($images)) {
                Log::warning('No images found for PDF generation', ['chat_id' => $chatId]);
                $this->sendMessage($chatId, $this->getMessage($chatId, 'no_images'));
                return;
            }
            
            // Prepare image data
            $imageData = [];
            foreach ($images as $image) {
                $imageContent = Storage::disk('public')->get($image);
                $imageType = pathinfo($image, PATHINFO_EXTENSION);
                $base64Image = base64_encode($imageContent);
                
                $imageData[] = [
                    'path' => $image,
                    'base64' => $base64Image,
                    'type' => $imageType
                ];
            }
            
            // Create PDF directory
            $pdfPath = "pdfs/{$chatId}";
            Storage::disk('public')->makeDirectory($pdfPath);
            
            // Generate PDF
            $pdf = PDF::loadView('pdf.images', ['images' => $imageData]);
            $pdfFileName = 'images_' . uniqid() . '.pdf';
            $fullPdfPath = "{$pdfPath}/{$pdfFileName}";
            
            Storage::disk('public')->put($fullPdfPath, $pdf->output());
            
            Log::info('PDF generated successfully', [
                'chat_id' => $chatId,
                'pdf_path' => $fullPdfPath,
                'image_count' => count($images)
            ]);
            
            // Send PDF to user
            $this->sendDocument($chatId, $fullPdfPath);
            
            // Clean up files
            Storage::disk('public')->deleteDirectory($imagePath);
            Storage::disk('public')->deleteDirectory('pdfs/' . $chatId);
            
            Log::info('Files cleaned up', ['chat_id' => $chatId]);
            
            // Send new buttons after PDF is generated
            $message = $this->getMessage($chatId, 'pdf_ready');
            
            $keyboard = [
                [
                    ['text' => $this->getButtonText($chatId, 'new_image'), 'callback_data' => 'new_image'],
                    ['text' => $this->getButtonText($chatId, 'about_bot'), 'callback_data' => 'about_bot']
                ],
                [
                    ['text' => $this->getButtonText($chatId, 'usage_instructions'), 'callback_data' => 'usage_instructions']
                ]
            ];
            
            $this->sendMessageWithInlineKeyboard($chatId, $message, $keyboard);
            
        } catch (\Exception $e) {
            Log::error('Error generating PDF', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->sendMessage($chatId, $this->getMessage($chatId, 'pdf_error'));
        }
    }

    private function sendMessage($chatId, $text)
    {
        $this->makeTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown'
        ]);
    }

    private function sendDocument($chatId, $filePath)
    {
        $fullPath = Storage::disk('public')->path($filePath);
        
        if (file_exists($fullPath)) {
            $this->makeTelegramRequest('sendDocument', [
                'chat_id' => $chatId,
                'document' => new \CURLFile($fullPath)
            ]);
        }
    }

    private function sendMessageWithInlineKeyboard($chatId, $text, $keyboard)
    {
        $this->makeTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => $keyboard
            ])
        ]);
    }

    private function makeTelegramRequest($method, $data)
    {
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/{$method}";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        Log::info('Telegram API request', [
            'method' => $method,
            'data' => $data,
            'response_code' => $httpCode,
            'response' => $response
        ]);
        
        return json_decode($response, true);
    }
}

