<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class TelegramBotController extends Controller
{
    public function webhook(Request $request)
    {
        $update = $request->all();
        
        if (isset($update['message'])) {
            $message = $update['message'];
            $chatId = $message['chat']['id'];
            $text = $message['text'] ?? '';
            
            if ($text === 'Tayyor') {
                $this->generatePdfFromImages($chatId);
                return response()->json(['status' => 'success']);
            }
            
            if (isset($message['photo'])) {
                $this->saveImage($message['photo'], $chatId);
                return response()->json(['status' => 'success']);
            }
            
            $this->sendMessage($chatId, "Rasm yuklang yoki 'Tayyor' deb yozing");
        }
        
        return response()->json(['status' => 'success']);
    }
    
    private function saveImage($photos, $chatId)
    {
        $photo = end($photos);
        $fileId = $photo['file_id'];
        
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
            
            $this->sendMessage($chatId, "Rasm saqlandi! 'Tayyor' tugmasini bosib PDF yarating");
        }
    }
    
    private function generatePdfFromImages($chatId)
    {
        $imagePath = 'images/' . $chatId;
        $images = Storage::disk('public')->files($imagePath);
        
        if (empty($images)) {
            $this->sendMessage($chatId, "Hech qanday rasm topilmadi!");
            return;
        }
        
        $pdf = PDF::loadView('pdf.images', [
            'images' => $images,
            'chatId' => $chatId
        ]);
        
        $pdfPath = 'pdfs/' . $chatId . '/' . Str::random(10) . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());
        
        $this->sendDocument($chatId, $pdfPath);
        
        Storage::disk('public')->deleteDirectory($imagePath);
        Storage::disk('public')->deleteDirectory('pdfs/' . $chatId);
        
        $this->sendMessage($chatId, "PDF tayyor! Rasm va PDF fayllar tozalandi");
    }
    
    private function sendMessage($chatId, $text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        
        $data = [
            'chat_id' => $chatId,
            'text' => $text
        ];
        
        $this->makeTelegramRequest($url, $data);
    }
    
    private function sendDocument($chatId, $filePath)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$token}/sendDocument";
        
        $fileUrl = Storage::disk('public')->url($filePath);
        
        $data = [
            'chat_id' => $chatId,
            'document' => $fileUrl
        ];
        
        $this->makeTelegramRequest($url, $data);
    }
    
    private function makeTelegramRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }
}
