<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $song->title }} - Lyrics</title>
    <link rel="icon" type="image/png" href="{{ public_path('favicon.ico') }}">
    <style>
        @page {
            margin: 40px 50px;
            size: A4;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background-color: #ffffff;
            color: #1a1a1a;
            font-size: 12px;
            line-height: 1.6;
        }

        .header {
            border-bottom: 3px solid #2d5a3d;
            padding-bottom: 15px;
            margin-bottom: 25px;
            text-align: center;
        }

        .album-cover {
            width: 100px;
            height: 100px;
            border: 3px solid #2d5a3d;
            margin-bottom: 15px;
        }

        .album-cover-placeholder {
            width: 100px;
            height: 100px;
            background-color: #2d5a3d;
            text-align: center;
            line-height: 100px;
            font-size: 42px;
            color: #ffffff;
            border: 3px solid #1a3d28;
            margin: 0 auto 15px auto;
        }

        .song-title {
            font-size: 26px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .artist {
            font-size: 16px;
            color: #2d5a3d;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .album-name {
            font-size: 12px;
            color: #666666;
            font-style: italic;
        }

        .lyrics-section {
            margin-top: 20px;
            text-align: center;
        }

        .lyrics-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #2d5a3d;
            margin-bottom: 20px;
            font-weight: bold;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 8px;
        }

        .lyrics-content {
            font-size: 13px;
            line-height: 1.8;
            color: #333333;
            margin: 0 auto;
            text-align: center;
        }

        .verse {
            margin-bottom: 25px;
        }

        .footer {
            background-color: #2d5a3d;
            padding: 10px 25px;
            color: #ffffff;
            margin-top: 40px;
        }

        .footer-table {
            width: 100%;
        }

        .ministry-name {
            font-size: 11px;
            font-weight: bold;
            color: #ffffff;
        }

        .website {
            font-size: 9px;
            color: #c0c0c0;
        }

        .tagline {
            font-size: 9px;
            color: #c0c0c0;
            font-style: italic;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($album->cover_image && file_exists(storage_path('app/public/' . $album->cover_image)))
            <img src="{{ storage_path('app/public/' . $album->cover_image) }}" class="album-cover" alt="{{ $album->title }}">
        @else
            <div class="album-cover-placeholder">&#9835;</div>
        @endif
        <div class="song-title">{{ $song->title }}</div>
        <div class="artist">{{ $album->artist }}</div>
        <div class="album-name">From the album "{{ $album->title }}"</div>
    </div>

    <div class="lyrics-section">
        <div class="lyrics-label">Lyrics</div>
        <div class="lyrics-content">
            @if($song->lyrics)
                @foreach(preg_split('/\n\s*\n/', $song->lyrics) as $verse)
                    <div class="verse">{!! nl2br(e(trim($verse))) !!}</div>
                @endforeach
            @else
                Lyrics not available for this song.
            @endif
        </div>
    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>
                    <div class="ministry-name">Pioneer Missions Africa</div>
                    <div class="website">www.pioneermissionsafrica.co.za</div>
                </td>
                <td>
                    <div class="tagline">"Proclaiming the Everlasting Gospel"</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
