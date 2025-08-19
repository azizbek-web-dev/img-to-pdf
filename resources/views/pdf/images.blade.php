<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rasmlar PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .image-container {
            margin-bottom: 30px;
            text-align: center;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
        }
        .image-number {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rasmlar PDF</h1>
        <p>Jami: {{ count($images) }} ta rasm</p>
        <p>Vaqt: {{ now()->format('d.m.Y H:i:s') }}</p>
    </div>

    @foreach($images as $index => $image)
        <div class="image-container">
            <div class="image-number">Rasm {{ $index + 1 }}</div>
            <img src="{{ Storage::disk('public')->url($image) }}" alt="Rasm {{ $index + 1 }}">
        </div>
    @endforeach

    <div class="footer">
        <p>Laravel va DomPDF yordamida yaratildi</p>
    </div>
</body>
</html>
