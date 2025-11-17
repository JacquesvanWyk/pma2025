<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Presentation Slides</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .slide {
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 120px;
            page-break-after: always;
            color: #ffffff;
        }

        .slide:last-child {
            page-break-after: auto;
        }

        .slide-content {
            width: 100%;
            max-width: 100%;
        }

        h1 {
            font-size: 64px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 40px;
        }

        h2 {
            font-size: 48px;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 30px;
        }

        p {
            font-size: 28px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        ul {
            font-size: 28px;
            line-height: 1.8;
            padding-left: 50px;
        }

        li {
            margin-bottom: 15px;
        }

        blockquote {
            font-size: 36px;
            font-style: italic;
            line-height: 1.5;
            text-align: center;
        }

        cite {
            font-size: 24px;
            display: block;
            margin-top: 30px;
            opacity: 0.9;
            text-align: right;
        }
    </style>
</head>
<body>
    @foreach($slides as $slide)
        <div class="slide" style="
            @if(($slide['background_type'] ?? 'gradient') === 'gradient')
                background: {{ $slide['background_value'] ?? 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)' }};
            @elseif(($slide['background_type'] ?? 'gradient') === 'image')
                background: url('{{ $slide['background_value'] }}') center/cover no-repeat;
            @else
                background: {{ $slide['background_value'] ?? '#ffffff' }};
            @endif
        ">
            <div class="slide-content">
                {!! $slide['html_content'] ?? '' !!}
            </div>
        </div>
    @endforeach
</body>
</html>
