<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sermon->title }} - Slides</title>
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
            font-family: 'Arial', sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .slide-page {
            width: 297mm;
            height: 210mm;
            page-break-after: always;
            position: relative;
            overflow: hidden;
        }

        .slide-page:last-child {
            page-break-after: auto;
        }

        .slide-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .slide-number {
            position: absolute;
            bottom: 10mm;
            right: 10mm;
            font-size: 12pt;
            opacity: 0.7;
            color: #666;
            z-index: 100;
        }
    </style>
</head>
<body>
    @foreach($slides as $slide)
        <div class="slide-page">
            <div class="slide-content">
                {!! $slide->rendered_html !!}
            </div>
            <div class="slide-number">{{ $slide->slide_number }}</div>
        </div>
    @endforeach
</body>
</html>
