<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rasmlar PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
        }
        .page {
            page-break-after: always;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        .image-container {
            text-align: center;
            max-width: 95%;
            max-height: 95%;
        }
        .image-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            color: #999;
            font-size: 12px;
            font-style: italic;
        }
    </style>
</head>
<body>
    @foreach($images as $index => $image)
        <div class="page">
            <div class="image-container">
                @if(isset($image['base64']))
                    <img src="data:image/{{ $image['type'] }};base64,{{ $image['base64'] }}" alt="Rasm {{ $index + 1 }}">
                @else
                    <div style="color: red; padding: 50px;">Rasm yuklanmadi</div>
                @endif
            </div>
            
            @if($index === count($images) - 1)
                <div class="footer">
                    Laravel va DomPDF yordamida yaratildi
                </div>
            @endif
        </div>
    @endforeach
</body>
</html>
