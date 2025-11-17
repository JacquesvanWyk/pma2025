<?php

namespace App\Http\Controllers;

use App\Services\SlideExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SlideExportController extends Controller
{
    public function exportPowerPoint(Request $request)
    {
        $cacheKey = $request->get('cacheKey');
        $sermonId = $request->get('sermonId');

        $slides = Cache::get($cacheKey);

        if (! $slides) {
            abort(404, 'Slides not found or expired');
        }

        Cache::forget($cacheKey);

        $service = new SlideExportService;
        $filePath = $service->exportToPowerPoint($slides, $sermonId);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function exportPdf(Request $request)
    {
        $cacheKey = $request->get('cacheKey');
        $sermonId = $request->get('sermonId');

        $slides = Cache::get($cacheKey);

        if (! $slides) {
            abort(404, 'Slides not found or expired');
        }

        Cache::forget($cacheKey);

        $service = new SlideExportService;
        $filePath = $service->exportToPdf($slides, $sermonId);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
