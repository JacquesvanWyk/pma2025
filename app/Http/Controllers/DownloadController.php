<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Models\PictureStudy;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DownloadController extends Controller
{
    public function galleryImage(Gallery $gallery, GalleryImage $image): Response
    {
        if ($image->gallery_id !== $gallery->id) {
            abort(404);
        }

        $image->incrementDownload();

        $path = $image->image_path;
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            abort(404);
        }

        $filename = $image->title
            ? \Illuminate\Support\Str::slug($image->title).'.'.pathinfo($path, PATHINFO_EXTENSION)
            : basename($path);

        return $disk->download($path, $filename);
    }

    public function pictureStudy(PictureStudy $pictureStudy): Response
    {
        $pictureStudy->incrementDownload();

        $path = $pictureStudy->image_path;
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            abort(404);
        }

        $filename = \Illuminate\Support\Str::slug($pictureStudy->title).'.'.pathinfo($path, PATHINFO_EXTENSION);

        return $disk->download($path, $filename);
    }
}
