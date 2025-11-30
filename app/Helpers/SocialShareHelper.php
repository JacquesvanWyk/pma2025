<?php

namespace App\Helpers;

class SocialShareHelper
{
    public static function whatsappShareUrl(string $text, ?string $url = null): string
    {
        $message = $url ? "{$text}\n\n{$url}" : $text;

        return 'https://wa.me/?text='.urlencode($message);
    }

    public static function facebookShareUrl(string $url): string
    {
        return 'https://www.facebook.com/sharer/sharer.php?u='.urlencode($url);
    }

    public static function twitterShareUrl(string $text, ?string $url = null): string
    {
        $params = ['text' => $text];
        if ($url) {
            $params['url'] = $url;
        }

        return 'https://twitter.com/intent/tweet?'.http_build_query($params);
    }

    public static function generateCaption(string $title, ?string $description = null, ?string $url = null): string
    {
        $caption = $title;

        if ($description) {
            $caption .= "\n\n{$description}";
        }

        if ($url) {
            $caption .= "\n\n🔗 {$url}";
        }

        return $caption;
    }
}
