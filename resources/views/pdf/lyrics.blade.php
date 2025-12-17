<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $song->title }} - Lyrics</title>
    <style>
        @page {
            margin: 25px 30px;
            size: A4;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background-color: #ffffff;
            color: #1a1a1a;
            font-size: 11px;
            line-height: 1.4;
        }

        .header {
            border-bottom: 3px solid #2d5a3d;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .header-table {
            width: 100%;
        }

        .album-cover {
            width: 80px;
            height: 80px;
            border: 2px solid #2d5a3d;
        }

        .album-cover-placeholder {
            width: 80px;
            height: 80px;
            background-color: #2d5a3d;
            text-align: center;
            line-height: 80px;
            font-size: 32px;
            color: #ffffff;
        }

        .song-info {
            padding-left: 15px;
            vertical-align: middle;
        }

        .song-title {
            font-size: 20px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .artist {
            font-size: 14px;
            color: #2d5a3d;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .album-name {
            font-size: 10px;
            color: #666666;
        }

        .lyrics-section {
            margin-top: 10px;
        }

        .lyrics-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #2d5a3d;
            margin-bottom: 8px;
            font-weight: bold;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 4px;
        }

        .lyrics-content {
            font-size: 10px;
            line-height: 1.5;
            color: #333333;
            white-space: pre-wrap;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #2d5a3d;
            padding: 8px 20px;
            color: #ffffff;
        }

        .footer-table {
            width: 100%;
        }

        .ministry-name {
            font-size: 10px;
            font-weight: bold;
            color: #ffffff;
        }

        .website {
            font-size: 8px;
            color: #c0c0c0;
        }

        .tagline {
            font-size: 8px;
            color: #c0c0c0;
            font-style: italic;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 90px; vertical-align: top;">
                    @if($album->cover_image && file_exists(storage_path('app/public/' . $album->cover_image)))
                        <img src="{{ storage_path('app/public/' . $album->cover_image) }}" class="album-cover" alt="{{ $album->title }}">
                    @else
                        <div class="album-cover-placeholder">&#9835;</div>
                    @endif
                </td>
                <td class="song-info">
                    <div class="song-title">{{ $song->title }}</div>
                    <div class="artist">{{ $album->artist }}</div>
                    <div class="album-name">From the album "{{ $album->title }}"</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="lyrics-section">
        <div class="lyrics-label">Lyrics</div>
        <div class="lyrics-content">{{ $song->lyrics ?? 'Lyrics not available for this song.' }}</div>
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
