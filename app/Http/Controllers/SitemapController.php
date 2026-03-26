<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = 'https://pioneermissionsafrica.co.za';
        $now = now()->toW3cString();

        $pages = [
            ['loc' => '/', 'priority' => '1.0'],
            ['loc' => '/about', 'priority' => '0.8'],
            ['loc' => '/about/principles', 'priority' => '0.5'],
            ['loc' => '/about/support', 'priority' => '0.5'],
            ['loc' => '/studies', 'priority' => '0.8'],
            ['loc' => '/network', 'priority' => '0.8'],
            ['loc' => '/resources', 'priority' => '0.8'],
            ['loc' => '/resources/tracts', 'priority' => '0.5'],
            ['loc' => '/resources/ebooks', 'priority' => '0.5'],
            ['loc' => '/resources/notes', 'priority' => '0.5'],
            ['loc' => '/resources/picture-studies', 'priority' => '0.5'],
            ['loc' => '/gallery', 'priority' => '0.8'],
            ['loc' => '/music', 'priority' => '0.8'],
            ['loc' => '/sermons', 'priority' => '0.8'],
            ['loc' => '/shorts', 'priority' => '0.8'],
            ['loc' => '/contact', 'priority' => '0.8'],
            ['loc' => '/donate', 'priority' => '0.8'],
            ['loc' => '/prayer-room', 'priority' => '0.8'],
            ['loc' => '/kingdom-kids', 'priority' => '0.8'],
            ['loc' => '/privacy', 'priority' => '0.5'],
            ['loc' => '/terms', 'priority' => '0.5'],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($pages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . $page['loc'] . '</loc>';
            $xml .= '<lastmod>' . $now . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>' . $page['priority'] . '</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
